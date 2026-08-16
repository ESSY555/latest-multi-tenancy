<?php

$models = [
    'AcademicYear',
    'AdmissionApplication',
    'Book',
    'ClassAnnouncement',
    'Contact',
    'FormTeacher',
    'Gallery',
    'GradeScale',
    'LessonPlan',
    'MockExam',
    'MockResult',
    'Notification',
    'Result',
    'Result/Result',
    'SchoolClass',
    'SchoolNews',
    'StudentProfile',
    'StudyMaterial',
    'Subject',
    'Syllabus',
    'TeacherAttendance',
    'TeacherReport'
];

foreach ($models as $m) {
    $path = "c:/Users/DELL/Desktop/room-color/latest-school-multi-tenancy/latest-multi-tenancy/app/Models/{$m}.php";
    if (!file_exists($path)) {
        echo "File not found: {$path}\n";
        continue;
    }
    
    $content = file_get_contents($path);
    
    if (strpos($content, 'App\Traits\BelongsToBranch') !== false) {
        continue;
    }
    
    $namespacePattern = '/(namespace\s+App\\\\Models(?:\\\\[a-zA-Z0-9_]+)*;)/';
    $content = preg_replace($namespacePattern, "$1\n\nuse App\\Traits\\BelongsToBranch;", $content);
    
    $classPattern = '/(class\s+[a-zA-Z0-9_]+\s*(?:extends\s+[a-zA-Z0-9_\\\\]+\s*)?(?:implements\s+[a-zA-Z0-9_\\\\\s,]+)?\s*\{)/';
    $content = preg_replace($classPattern, "$1\n    use BelongsToBranch;\n", $content);
    
    file_put_contents($path, $content);
    echo "Updated {$m}\n";
}
