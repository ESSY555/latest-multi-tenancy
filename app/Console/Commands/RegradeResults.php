<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Result\Result;
use App\Models\SchoolClass;

class RegradeResults extends Command
{
    protected $signature   = 'results:regrade {--dry-run : Preview changes without saving}';
    protected $description = 'Re-apply grade and remark to all existing results based on the current grading rules (SS classes use A1–F9, others use A–F).';

    public function handle(): int
    {
        $isDry = $this->option('dry-run');

        if ($isDry) {
            $this->warn('DRY-RUN mode — no changes will be saved.');
        }

        // Load all class names once to avoid N+1 queries
        $classNames = SchoolClass::pluck('name', 'id');

        $this->info('Loading results...');
        $results = Result::all();
        $total   = $results->count();

        if ($total === 0) {
            $this->info('No results found.');
            return self::SUCCESS;
        }

        $this->info("Found {$total} results. Processing...");

        $bar     = $this->output->createProgressBar($total);
        $changed = 0;
        $dummy   = new Result(); // Use model methods without persisting

        foreach ($results as $result) {
            $className = $classNames[$result->school_class_id] ?? null;
            $classType = Result::resolveClassType($className);

            $newGrade  = $dummy->determineGrade($result->total, $classType);
            $newRemark = $dummy->determineRemark($newGrade);

            if ($result->grade !== $newGrade || $result->remark !== $newRemark) {
                $changed++;
                if (!$isDry) {
                    // Use DB update to bypass the boot saving hook (avoids recalculating total)
                    \Illuminate\Support\Facades\DB::table('results')
                        ->where('id', $result->id)
                        ->update([
                            'grade'  => $newGrade,
                            'remark' => $newRemark,
                        ]);
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info("Done. {$changed} of {$total} results " . ($isDry ? 'would be' : 'were') . ' updated.');

        return self::SUCCESS;
    }
}
