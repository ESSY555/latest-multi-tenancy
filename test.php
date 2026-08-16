<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $q = \App\Models\Result::select('results.student_id', 'results.school_class_id', 'users.name as student_name', 'school_classes.name as class_name', \DB::raw('AVG(results.total) as average_score'))
        ->join('users', 'users.id', '=', 'results.student_id')
        ->join('school_classes', 'school_classes.id', '=', 'results.school_class_id')
        ->whereHas('academicTerm', function ($query) {
            $query->where('academic_year_id', 1);
        })
        ->whereNotNull('results.school_class_id')
        ->groupBy('results.student_id', 'results.school_class_id', 'users.name', 'school_classes.name');
        
    print_r($q->first());
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
