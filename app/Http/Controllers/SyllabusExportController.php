<?php

namespace App\Http\Controllers;

use App\Models\Syllabus;
use App\Models\Branch;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class SyllabusExportController extends Controller
{
    /**
     * Export syllabus to PDF
     */
    public function exportPdf(Request $request)
    {
        $branchId = session('selected_branch_id');
        
        if (!$branchId) {
            $firstBranch = Branch::first();
            $branchId = $firstBranch ? $firstBranch->id : null;
        }
        
        $syllabi = $branchId ? Syllabus::where('branch_id', $branchId)->get() : collect();
        $branch = Branch::find($branchId);
        
        $pdf = PDF::loadView('exports.syllabus-pdf', compact('syllabi', 'branch'));
        
        return $pdf->download('syllabus-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export syllabus to Excel (CSV format)
     */
    public function exportExcel(Request $request)
    {
        $branchId = session('selected_branch_id');
        
        if (!$branchId) {
            $firstBranch = Branch::first();
            $branchId = $firstBranch ? $firstBranch->id : null;
        }
        
        $syllabi = $branchId ? Syllabus::where('branch_id', $branchId)->get() : collect();
        
        $filename = 'syllabus-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($syllabi) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['Class', 'Subject', 'Term', 'Topics', 'Duration', 'Learning Objectives', 'Created Date']);
            
            // Add data
            foreach ($syllabi as $syllabus) {
                fputcsv($file, [
                    $syllabus->class,
                    $syllabus->subject,
                    $syllabus->term,
                    $syllabus->topics,
                    $syllabus->duration,
                    $syllabus->objectives ?? 'N/A',
                    $syllabus->created_at->format('M d, Y')
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}


