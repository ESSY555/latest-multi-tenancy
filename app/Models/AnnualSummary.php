<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnualSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'number_of_times_school_opened',
        'form_teacher_comment',
        'school_head_comment',
        'form_teacher_signature',
        'school_head_signature',
        'promotion_status',
        'pass_status',
        'date',
        'is_approved',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public static function principalCommentFromScore(float|int|null $score): string
    {
        $score = (float) ($score ?? 0);

        if ($score >= 70) {
            return 'Excellent work. Keep it up.';
        }

        if ($score >= 60) {
            return 'Very good. Stay consistent.';
        }

        if ($score >= 50) {
            return 'Good effort. Improve with more revision.';
        }

        if ($score >= 45) {
            return 'Credit. Work harder for better results.';
        }

        return 'Below standard. Needs serious improvement.';
    }

    public static function determinePromotionOutcome(bool $passedMathematics, bool $passedEnglish, float|int|null $averageScore): array
    {
        $averageScore = (float) ($averageScore ?? 0);
        $passedCoreSubjects = $passedMathematics && $passedEnglish;

        if ($averageScore < 45) {
            return [
                'promotion_status' => 'not_promoted',
                'pass_status' => 'fail',
            ];
        }

        if (!$passedCoreSubjects) {
            return [
                'promotion_status' => 'resit',
                'pass_status' => 'pending',
            ];
        }

        return [
            'promotion_status' => 'promoted',
            'pass_status' => 'pass',
        ];
    }

    public static function resolvePromotionOutcomeFromAverages(float|int|null $averageScore, array $subjectAverages): array
    {
        $averageScore = (float) ($averageScore ?? 0);
        $mathAverage = 0.0;
        $englishAverage = 0.0;

        foreach ($subjectAverages as $subjectAverage) {
            $subjectName = strtolower($subjectAverage['subject'] ?? '');

            if (str_contains($subjectName, 'mathematics')) {
                $mathAverage = (float) ($subjectAverage['average'] ?? 0);
            } elseif (str_contains($subjectName, 'english')) {
                $englishAverage = (float) ($subjectAverage['average'] ?? 0);
            }
        }

        return self::determinePromotionOutcome($mathAverage >= 45, $englishAverage >= 45, $averageScore);
    }

    public static function resolveDisplayOutcome(?self $annualSummary, array $computedOutcome): array
    {
        if ($annualSummary) {
            $promotionStatus = $annualSummary->promotion_status;
            $passStatus = $annualSummary->pass_status;

            if (!empty($promotionStatus)) {
                return [
                    'promotion_status' => $promotionStatus,
                    'pass_status' => $passStatus ?: $computedOutcome['pass_status'] ?? 'pending',
                ];
            }
        }

        return $computedOutcome;
    }

    public static function summarizeClassPerformance(array $rows): array
    {
        $rowsCollection = collect($rows);
        $promotedCount = $rowsCollection->filter(fn ($row) => in_array(($row['promotion_status'] ?? ''), ['promoted', 'promoted_by_trial'], true))->count();
        $failedCount = $rowsCollection->filter(function ($row) {
            $promotionStatus = (string) ($row['promotion_status'] ?? '');
            $averageScore = (float) ($row['average_score'] ?? 0);

            return $promotionStatus === 'failed' || ($promotionStatus === 'not_promoted' && $averageScore < 45);
        })->count();
        $notPromotedCount = $rowsCollection->filter(function ($row) {
            $promotionStatus = (string) ($row['promotion_status'] ?? '');
            $averageScore = (float) ($row['average_score'] ?? 0);

            return in_array($promotionStatus, ['not_promoted', 'resit', 'pending'], true) || ($promotionStatus === '' && $averageScore >= 45);
        })->count();
        $best = $rowsCollection
            ->filter(fn ($row) => is_numeric($row['average_score'] ?? null))
            ->sortByDesc(fn ($row) => (float) $row['average_score'])
            ->first();

        return [
            'promoted_count' => $promotedCount,
            'promoted_by_trial_count' => $rowsCollection->filter(fn ($row) => ($row['promotion_status'] ?? '') === 'promoted_by_trial')->count(),
            'not_promoted_count' => $notPromotedCount,
            'failed_count' => $failedCount,
            'best_average' => isset($best['average_score']) ? (float) $best['average_score'] : 0.0,
            'best_student_name' => $best['student_name'] ?? null,
            'best_class_name' => $best['class_name'] ?? null,
        ];
    }
}
