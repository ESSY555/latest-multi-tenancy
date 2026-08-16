<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentResultsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $student;
    protected $results;
    protected $user;

    public function __construct($student, $results, $user)
    {
        $this->student = $student;
        $this->results = $results;
        $this->user = $user;
    }

    public function collection()
    {
        $data = [];
        
        // Add header info
        $data[] = ['STUDENT RESULTS REPORT'];
        $data[] = [];
        $data[] = ['Student Name:', $this->user->name];
        $data[] = ['Admission Number:', $this->student->admission_number];
        $data[] = ['Date:', date('M j, Y')];
        $data[] = [];
        $data[] = [];

        // Add results header
        $data[] = ['Subject', 'CAT1', 'NPW', 'CAT2', 'EXAM', 'TOTAL', 'GRADE'];

        // Add results data
        foreach ($this->results as $result) {
            $data[] = [
                $result->subject,
                $result->ca1,
                $result->ca2,
                $result->ca3,
                $result->exam,
                $result->total,
                $result->grade,
            ];
        }

        // Add summary
        $data[] = [];
        $data[] = ['Summary Statistics'];
        $data[] = ['Total Subjects', $this->results->count()];
        $data[] = ['Average Score', round($this->results->avg('total'), 2)];
        $data[] = ['Highest Score', $this->results->max('total')];
        $data[] = ['Lowest Score', $this->results->min('total')];

        return collect($data);
    }

    public function headings(): array
    {
        return ['Subject', 'CAT1', 'NPW', 'CAT2', 'EXAM', 'TOTAL', 'GRADE'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row
            7 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0070C0']]],
            
            // Title styling
            1 => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'center']],
        ];
    }
}
