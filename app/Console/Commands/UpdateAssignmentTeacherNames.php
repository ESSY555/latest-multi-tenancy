<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateAssignmentTeacherNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assignments:update-teacher-names';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update teacher names for existing assignments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating teacher names for existing assignments...');
        
        $assignments = \App\Models\Assignment::whereNull('teacher_name')->get();
        $updated = 0;
        
        foreach ($assignments as $assignment) {
            if ($assignment->teacher) {
                $assignment->update(['teacher_name' => $assignment->teacher->name]);
                $updated++;
            }
        }
        
        $this->info("Updated {$updated} assignments with teacher names.");
    }
}
