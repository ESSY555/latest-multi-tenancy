<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradeScale;
use App\Models\Branch;

class GradeScaleSeeder extends Seeder
{
    public function run(): void
    {
        // Get all branches
        $branches = Branch::all();

        foreach ($branches as $branch) {
            // Create default grade scale for each branch
            GradeScale::create([
                'branch_id' => $branch->id,
                'name' => 'Standard 4.0 Scale',
                'description' => 'Standard grading scale with A=4.0, B=3.0, C=2.0, D=1.0, F=0.0',
                'is_default' => true,
                'grade_data' => [
                    [
                        'letter' => 'A',
                        'min_score' => 90,
                        'max_score' => 100,
                        'points' => 4.0,
                        'description' => 'Excellent'
                    ],
                    [
                        'letter' => 'B',
                        'min_score' => 80,
                        'max_score' => 89,
                        'points' => 3.0,
                        'description' => 'Good'
                    ],
                    [
                        'letter' => 'C',
                        'min_score' => 70,
                        'max_score' => 79,
                        'points' => 2.0,
                        'description' => 'Average'
                    ],
                    [
                        'letter' => 'D',
                        'min_score' => 60,
                        'max_score' => 69,
                        'points' => 1.0,
                        'description' => 'Below Average'
                    ],
                    [
                        'letter' => 'F',
                        'min_score' => 0,
                        'max_score' => 59,
                        'points' => 0.0,
                        'description' => 'Failing'
                    ]
                ]
            ]);

            // Create alternative grade scale (Percentage-based)
            GradeScale::create([
                'branch_id' => $branch->id,
                'name' => 'Percentage Scale',
                'description' => 'Percentage-based grading system',
                'is_default' => false,
                'grade_data' => [
                    [
                        'letter' => 'A+',
                        'min_score' => 95,
                        'max_score' => 100,
                        'points' => 4.0,
                        'description' => 'Outstanding'
                    ],
                    [
                        'letter' => 'A',
                        'min_score' => 90,
                        'max_score' => 94,
                        'points' => 4.0,
                        'description' => 'Excellent'
                    ],
                    [
                        'letter' => 'B+',
                        'min_score' => 85,
                        'max_score' => 89,
                        'points' => 3.3,
                        'description' => 'Very Good'
                    ],
                    [
                        'letter' => 'B',
                        'min_score' => 80,
                        'max_score' => 84,
                        'points' => 3.0,
                        'description' => 'Good'
                    ],
                    [
                        'letter' => 'C+',
                        'min_score' => 75,
                        'max_score' => 79,
                        'points' => 2.3,
                        'description' => 'Above Average'
                    ],
                    [
                        'letter' => 'C',
                        'min_score' => 70,
                        'max_score' => 74,
                        'points' => 2.0,
                        'description' => 'Average'
                    ],
                    [
                        'letter' => 'D+',
                        'min_score' => 65,
                        'max_score' => 69,
                        'points' => 1.3,
                        'description' => 'Below Average'
                    ],
                    [
                        'letter' => 'D',
                        'min_score' => 60,
                        'max_score' => 64,
                        'points' => 1.0,
                        'description' => 'Below Average'
                    ],
                    [
                        'letter' => 'F',
                        'min_score' => 0,
                        'max_score' => 59,
                        'points' => 0.0,
                        'description' => 'Failing'
                    ]
                ]
            ]);
        }
    }
}
