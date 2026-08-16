<?php

namespace Tests\Unit;

use App\Models\AnnualSummary;
use PHPUnit\Framework\TestCase;

class AnnualSummaryTest extends TestCase
{
    public function test_it_summarizes_promoted_not_promoted_and_best_result_per_class(): void
    {
        $stats = AnnualSummary::summarizeClassPerformance([
            ['student_name' => 'Alice', 'promotion_status' => 'promoted', 'average_score' => 78.5],
            ['student_name' => 'Bob', 'promotion_status' => 'not_promoted', 'average_score' => 41.2],
            ['student_name' => 'Cynthia', 'promotion_status' => 'promoted', 'average_score' => 84.0],
            ['student_name' => 'Dayo', 'promotion_status' => 'resit', 'average_score' => 45.5],
        ]);

        $this->assertSame(2, $stats['promoted_count']);
        $this->assertSame(2, $stats['not_promoted_count']);
        $this->assertSame(1, $stats['failed_count']);
        $this->assertSame(84.0, $stats['best_average']);
        $this->assertSame('Cynthia', $stats['best_student_name']);
    }

    public function test_it_counts_promoted_by_trial_separately_in_summary(): void
    {
        $stats = AnnualSummary::summarizeClassPerformance([
            ['student_name' => 'Alice', 'promotion_status' => 'promoted', 'average_score' => 78.5],
            ['student_name' => 'Bob', 'promotion_status' => 'promoted_by_trial', 'average_score' => 65.0],
            ['student_name' => 'Cynthia', 'promotion_status' => 'not_promoted', 'average_score' => 41.2],
        ]);

        $this->assertSame(2, $stats['promoted_count']);
        $this->assertSame(1, $stats['promoted_by_trial_count']);
    }

    public function test_equal_average_scores_with_same_subject_criteria_return_same_promotion_outcome(): void
    {
        $firstOutcome = AnnualSummary::resolvePromotionOutcomeFromAverages(44.5, [
            ['subject' => 'Mathematics', 'average' => 44.5],
            ['subject' => 'English', 'average' => 44.5],
        ]);

        $secondOutcome = AnnualSummary::resolvePromotionOutcomeFromAverages(44.5, [
            ['subject' => 'Mathematics', 'average' => 44.5],
            ['subject' => 'English', 'average' => 44.5],
        ]);

        $this->assertSame($firstOutcome, $secondOutcome);
        $this->assertSame('not_promoted', $firstOutcome['promotion_status']);
        $this->assertSame('fail', $firstOutcome['pass_status']);
    }

    public function test_saved_summary_override_is_used_instead_of_recomputed_value(): void
    {
        $summary = new AnnualSummary([
            'promotion_status' => 'promoted',
            'pass_status' => 'pass',
        ]);

        $displayOutcome = AnnualSummary::resolveDisplayOutcome($summary, [
            'promotion_status' => 'not_promoted',
            'pass_status' => 'fail',
        ]);

        $this->assertSame('promoted', $displayOutcome['promotion_status']);
        $this->assertSame('pass', $displayOutcome['pass_status']);
    }
}
