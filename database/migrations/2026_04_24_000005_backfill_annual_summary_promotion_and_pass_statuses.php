<?php

use App\Models\AnnualSummary;
use App\Models\Result;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        AnnualSummary::query()->chunkById(100, function ($summaries): void {
            foreach ($summaries as $summary) {
                $subjectAverages = Result::where('student_id', $summary->student_id)
                    ->whereHas('academicTerm', function ($query) use ($summary) {
                        $query->where('academic_year_id', $summary->academic_year_id);
                    })
                    ->with('subject')
                    ->get()
                    ->groupBy('subject_id')
                    ->map(function ($group) {
                        return [
                            'subject' => $group->first()?->subject?->name ?? '',
                            'average' => (float) $group->avg('total'),
                        ];
                    })
                    ->values();

                if ($subjectAverages->isEmpty()) {
                    continue;
                }

                $mathematicsAverage = $subjectAverages->first(function ($result) {
                    return str_contains(strtolower($result['subject']), 'mathematics');
                })['average'] ?? 0;

                $englishAverage = $subjectAverages->first(function ($result) {
                    return str_contains(strtolower($result['subject']), 'english');
                })['average'] ?? 0;

                $totalAverage = (float) $subjectAverages->avg('average');

                $outcome = AnnualSummary::determinePromotionOutcome(
                    $mathematicsAverage >= 45,
                    $englishAverage >= 45,
                    $totalAverage
                );

                $summary->update([
                    'promotion_status' => $outcome['promotion_status'],
                    'pass_status' => $outcome['pass_status'],
                ]);
            }
        });
    }

    public function down(): void
    {
        // Keep existing values; no-op rollback.
    }
};
