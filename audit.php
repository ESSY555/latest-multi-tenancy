<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$models = [];
foreach (File::allFiles(app_path('Models')) as $file) {
    $class = 'App\\Models\\' . str_replace('/', '\\', $file->getRelativePathname());
    $class = str_replace('.php', '', $class);
    
    if (class_exists($class)) {
        try {
            $reflection = new ReflectionClass($class);
            if ($reflection->isAbstract()) continue;

            $model = new $class;
            $table = $model->getTable();
            $hasBranchId = Schema::hasColumn($table, 'branch_id');
            
            $models[] = [
                'Model' => $class,
                'Table' => $table,
                'Has_branch_id' => $hasBranchId ? 'Yes' : 'No',
            ];
        } catch (\Exception $e) {
            // Ignore errors for non-models
        }
    }
}

echo json_encode($models, JSON_PRETTY_PRINT);
