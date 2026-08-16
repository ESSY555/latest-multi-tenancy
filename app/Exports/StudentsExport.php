<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $students;

    public function __construct($students)
    {
        $this->students = $students;
    }

    public function collection()
    {
        $data = [];
        
        foreach ($this->students as $student) {
            $enrollment = $student->enrollments->sortByDesc('created_at')->first();
            $className = $enrollment && $enrollment->schoolClass ? $enrollment->schoolClass->name : 'Not assigned';
            $admissionNo = $student->studentProfile ? $student->studentProfile->admission_number : 'N/A';
            
            $data[] = [
                $student->name,
                $className,
                $admissionNo
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return ['Student Name', 'Class', 'Admission Number'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0070C0']]],
        ];
    }
}
