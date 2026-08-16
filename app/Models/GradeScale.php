<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeScale extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'description',
        'is_default',
        'grade_data'
    ];

    protected $casts = [
        'grade_data' => 'array',
        'is_default' => 'boolean'
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Get grade points for a given score
    public function getGradePoints($score)
    {
        if (!$this->grade_data) {
            return $this->getDefaultGradePoints($score);
        }

        foreach ($this->grade_data as $grade) {
            if ($score >= $grade['min_score'] && $score <= $grade['max_score']) {
                return $grade['points'];
            }
        }

        return 0.0;
    }

    // Get letter grade for a given score
    public function getLetterGrade($score)
    {
        if (!$this->grade_data) {
            return $this->getDefaultLetterGrade($score);
        }

        foreach ($this->grade_data as $grade) {
            if ($score >= $grade['min_score'] && $score <= $grade['max_score']) {
                return $grade['letter'];
            }
        }

        return 'F';
    }

    // Default grading system (A=90-100, B=80-89, C=70-79, D=60-69, F=0-59)
    private function getDefaultGradePoints($score)
    {
        if ($score >= 90) return 4.0;
        if ($score >= 80) return 3.0;
        if ($score >= 70) return 2.0;
        if ($score >= 60) return 1.0;
        return 0.0;
    }

    private function getDefaultLetterGrade($score)
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    }

    // Get grade description
    public function getGradeDescription($grade)
    {
        $descriptions = [
            'A' => 'Excellent',
            'B' => 'Good',
            'C' => 'Average',
            'D' => 'Below Average',
            'F' => 'Failing'
        ];

        return $descriptions[$grade] ?? 'Unknown';
    }

    // Calculate GPA from multiple scores
    public function calculateGPA($scores)
    {
        if (empty($scores)) {
            return 0.0;
        }

        $totalPoints = 0;
        $totalCredits = count($scores);

        foreach ($scores as $score) {
            $totalPoints += $this->getGradePoints($score);
        }

        return $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0.0;
    }
}
