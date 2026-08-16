0) Confirm approach (before coding)

 Use separate Mock module (not mixed into termly results).

 Keep AnnualResultController and termly calculations untouched.

 Teacher entry can choose result_type = termly or mock.
1) Database migrations
Create mock exam master (recommended)

 Add migration:
database/migrations/2026_04_20_140000_create_mock_exams_table.php

 Table: mock_exams
id
name (e.g. MOCK 2026)
academic_year_id (fk)
branch_id (fk)
is_active (bool)
start_date, end_date (nullable)
timestamps
Create mock results table

 Add migration:
database/migrations/2026_04_20_140100_create_mock_results_table.php

 Table: mock_results
keys: student_id, school_class_id, subject_id, branch_id, academic_year_id, mock_exam_id
scores: ca1, ca2, ca3, exam, total, grade, remark, position
sheet fields: form_teacher_comment, school_head_comment, form_teacher_signature, school_head_signature
extras: psychomotor (json), affective (json), next_term_begins, next_term_fees
approval: is_approved, approved_by, approved_at
timestamps

 Add unique key:
unique(student_id, subject_id, mock_exam_id, branch_id)
2) Models
New model files

 Create app/Models/MockExam.php

 Create app/Models/MockResult.php
Relationship updates

 Update app/Models/User.php
add mockResults() relation

 (Optional) Update app/Models/SchoolClass.php
add mockResults() relation

 (Optional) Update app/Models/Subject.php
add mockResults() relation
3) Controller
New controller

 Create app/Http/Controllers/Result/MockResultController.php
Methods to add

 index(Request $request) (filters by class/year/mock exam)

 scoreSheet(Request $request) (teacher entry page for mock)

 saveScores(Request $request) (save subject scores)

 studentSheet($studentId, $mockExamId) (single-student mock sheet)

 addComment(MockResult $mockResult, Request $request) (comments/signatures/fees/etc)

 approveSheet($studentId, $mockExamId)

 disapproveSheet($studentId, $mockExamId)

 bulkApproveSheets(Request $request)

 bulkDisapproveSheets(Request $request)
Permission rule to enforce

 Same as current:
edit by form_teacher/admin/superadmin
after approval, only superadmin can edit
4) Routes (routes/web.php)
Use same style as existing result. group.


 Add new group (inside authenticated area), e.g.:
prefix: mock-result
name: mock-result.
Suggested route names/paths

 GET /mock-result → MockResultController@index → mock-result.index

 GET /mock-result/score-sheet → scoreSheet → mock-result.score-sheet

 POST /mock-result/score-sheet → saveScores → mock-result.save-scores

 GET /mock-result/student/{studentId}/exam/{mockExamId} → studentSheet → mock-result.student-sheet

 POST /mock-result/{mockResult}/comment → addComment → mock-result.add-comment

 POST /mock-result/student/{studentId}/exam/{mockExamId}/approve → approveSheet → mock-result.approve-sheet

 POST /mock-result/student/{studentId}/exam/{mockExamId}/disapprove → disapproveSheet → mock-result.disapprove-sheet

 POST /mock-result/bulk/approve → bulkApproveSheets → mock-result.bulk-approve-sheets

 POST /mock-result/bulk/disapprove → bulkDisapproveSheets → mock-result.bulk-disapprove-sheets
5) Views
New/repurpose mock views

 Use existing file as base: resources/views/result/mock-result.blade.php
make this the mock student sheet template

 Add list page:
resources/views/result/mock-index.blade.php

 Add teacher score entry page:
resources/views/result/mock-score-sheet.blade.php
Existing view integration

 Update resources/views/teacher/score-sheet.blade.php
add result_type selector (termly / mock)
if mock, show mock exam selector

 Update resources/views/result/index.blade.php and/or resources/views/result/teacher-index.blade.php
add links to mock module
6) “Teacher selects mock” integration point
Two clean ways; choose one:

A) Keep current termly controller unchanged (recommended)

 In teacher/score-sheet add button/link:
“Switch to Mock Entry” → mock-result.score-sheet

 Keep separate submission targets for termly vs mock
B) Single score-sheet form with selector

 Form submits to new dispatch endpoint

 Backend routes internally to termly save or mock save
(For your codebase today, A is safer and quicker.)

7) Sidebar / navigation

 Add “Mock Results” link for staff in:
resources/views/components/admin/branch-admin-sidebar.blade.php
resources/views/components/admin/super-admin-sidebar.blade.php
(and any teacher/form-teacher sidebars you use)
8) Guardrails (must-have)

 Ensure mock queries never touch results table

 Ensure annual report (AnnualResultController) only reads termly results

 Ensure student-facing mock view only shows approved data
9) Verification checklist

 Teacher can enter mock scores by class/subject

 Form teacher/admin/superadmin can add mock comments/signatures/fees

 Admin/superadmin can approve/disapprove mock sheets

 After approval, non-superadmin edit blocked

 Mock results appear in mock pages only (not termly/annual)
If you want, next I can convert this into exact migration/model/controller stubs (copy-ready skeletons) without touching your files yet.