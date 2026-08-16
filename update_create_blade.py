import sys

filepath = r"c:\Users\USER\Desktop\room-color\benzalee-school\resources\views\result\create.blade.php"

with open(filepath, "r", encoding="utf-8") as f:
    content = f.read()

# Replace action route
content = content.replace('action="{{ route(\'result.store\') }}"', 'action="{{ route(\'result.bulk-store\') }}"')

# Remove Student Selection completely
student_selection_start = content.find('<!-- Student Selection -->')
student_selection_end = content.find('<!-- Subject Selection -->')
if student_selection_start != -1 and student_selection_end != -1:
    content = content[:student_selection_start] + content[student_selection_end:]

# Replace Assessment Scores with Table Container
assessment_scores_start = content.find('<!-- Assessment Scores Section -->')
assessment_scores_end = content.find('{{-- Attendance Section')
if assessment_scores_start != -1 and assessment_scores_end != -1:
    new_table_html = """<!-- Students Table Section -->
                    <div id="students-table-container" class="mt-8 hidden">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 flex-grow">Bulk Result Entry</h3>
                        </div>
                        <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adm No</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" id="th-ca1">CA1 (0-10)</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" id="th-ca2">CA2 (0-10)</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" id="th-ca3">CA3 (0-10)</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" id="th-exam">Exam (0-70)</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="students-table-body" class="bg-white divide-y divide-gray-200">
                                    <!-- Dynamic Rows -->
                                </tbody>
                            </table>
                        </div>
                        <p id="no-students-msg" class="text-gray-500 mt-4 hidden">No students found for this selection.</p>
                    </div>
                    
                    """
    content = content[:assessment_scores_start] + new_table_html + content[assessment_scores_end:]

# Update JS block
js_start = content.find('<script>')
js_end = content.find('</script>', js_start)

if js_start != -1 and js_end != -1:
    new_js = """<script>
    let signaturePad;

    function calcRowTotal(studentId) {
        const ca1 = parseFloat(document.getElementById(`ca1_${studentId}`)?.value) || 0;
        const ca2 = parseFloat(document.getElementById(`ca2_${studentId}`)?.value) || 0;
        const ca3 = parseFloat(document.getElementById(`ca3_${studentId}`)?.value) || 0;
        const exam = parseFloat(document.getElementById(`exam_${studentId}`)?.value) || 0;
        
        const total = ca1 + ca2 + ca3 + exam;
        const totalEl = document.getElementById(`total_${studentId}`);
        if(totalEl) totalEl.textContent = total;
    }

    function checkAndLoadData() {
        const classId = document.getElementById('class_id').value;
        const subjectId = document.getElementById('subject_id').value;
        const resultType = document.getElementById('result_type').value;
        const termId = document.getElementById('term_id').value;
        const mockExamId = document.getElementById('mock_exam_id').value;

        const tableContainer = document.getElementById('students-table-container');
        const tbody = document.getElementById('students-table-body');
        const noStudentsMsg = document.getElementById('no-students-msg');
        
        // Reset
        tableContainer.classList.add('hidden');
        noStudentsMsg.classList.add('hidden');
        tbody.innerHTML = '';

        if (!classId || !subjectId || (resultType === 'termly' && !termId) || (resultType === 'mock' && !mockExamId)) {
            return;
        }

        // Show loading state
        tableContainer.classList.remove('hidden');
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4">⏳ Loading students and existing results...</td></tr>';

        const url = new URL('/api/bulk-entry-data', window.location.origin);
        url.searchParams.append('class_id', classId);
        url.searchParams.append('subject_id', subjectId);
        url.searchParams.append('result_type', resultType);
        if (resultType === 'termly') url.searchParams.append('term_id', termId);
        if (resultType === 'mock') url.searchParams.append('mock_exam_id', mockExamId);

        fetch(url)
            .then(res => res.json())
            .then(res => {
                tbody.innerHTML = '';
                if (res.success && res.data.length > 0) {
                    res.data.forEach(student => {
                        const ca1 = student.ca1 !== null ? student.ca1 : '';
                        const ca2 = student.ca2 !== null ? student.ca2 : '';
                        const ca3 = student.ca3 !== null ? student.ca3 : '';
                        const exam = student.exam !== null ? student.exam : '';
                        
                        const isExisting = student.has_result;
                        const readOnlyAttr = (isExisting && !res.isAdmin) ? 'readonly class="bg-gray-100 px-2 py-1 w-16 border border-gray-300 rounded"' : 'class="px-2 py-1 w-16 border border-gray-300 rounded focus:ring-green-500"';
                        const disabledAttr = (isExisting && !res.isAdmin) ? 'disabled' : '';
                        
                        const isMock = resultType === 'mock';
                        const ca3Input = isMock ? 
                            `<input type="hidden" name="results[${student.student_id}][ca3]" value="0"> <span class="text-gray-400">-</span>` :
                            `<input type="number" step="0.1" id="ca3_${student.student_id}" name="results[${student.student_id}][ca3]" value="${ca3}" ${readOnlyAttr} ${disabledAttr} oninput="calcRowTotal(${student.student_id})">`;

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td class="px-3 py-3 text-sm font-medium text-gray-900">
                                <input type="hidden" name="results[${student.student_id}][student_id]" value="${student.student_id}">
                                ${student.name}
                                ${isExisting && !res.isAdmin ? '<span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Locked</span>' : ''}
                            </td>
                            <td class="px-3 py-3 text-sm text-gray-500">${student.admission_number}</td>
                            <td class="px-3 py-3 text-sm text-gray-500"><input type="number" step="0.1" id="ca1_${student.student_id}" name="results[${student.student_id}][ca1]" value="${ca1}" ${readOnlyAttr} ${disabledAttr} oninput="calcRowTotal(${student.student_id})"></td>
                            <td class="px-3 py-3 text-sm text-gray-500"><input type="number" step="0.1" id="ca2_${student.student_id}" name="results[${student.student_id}][ca2]" value="${ca2}" ${readOnlyAttr} ${disabledAttr} oninput="calcRowTotal(${student.student_id})"></td>
                            <td class="px-3 py-3 text-sm text-gray-500">${ca3Input}</td>
                            <td class="px-3 py-3 text-sm text-gray-500"><input type="number" step="0.1" id="exam_${student.student_id}" name="results[${student.student_id}][exam]" value="${exam}" ${readOnlyAttr} ${disabledAttr} oninput="calcRowTotal(${student.student_id})"></td>
                            <td class="px-3 py-3 text-sm font-bold text-green-600" id="total_${student.student_id}">0</td>
                        `;
                        tbody.appendChild(tr);
                        calcRowTotal(student.student_id);
                    });
                } else {
                    noStudentsMsg.classList.remove('hidden');
                    tableContainer.classList.add('hidden');
                }
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-red-500 py-4">❌ Failed to load students</td></tr>';
            });
    }

    // Replace loadClassData
    function loadClassData() {
        checkAndLoadData();
    }

    // Attach listeners to other selects
    document.addEventListener('DOMContentLoaded', function() {
        toggleResultType();

        document.getElementById('subject_id').addEventListener('change', checkAndLoadData);
        document.getElementById('term_id').addEventListener('change', checkAndLoadData);
        document.getElementById('mock_exam_id').addEventListener('change', checkAndLoadData);

        // Initialize Signature Pad
        const canvas = document.getElementById('teacher-signature-pad');
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)'
        });

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }

        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        window.clearSignature = () => signaturePad.clear();

        // Handle form submission
        const resultForm = document.getElementById('result-create-form');
        resultForm.addEventListener('submit', function(e) {
            if (signaturePad.isEmpty()) {
                e.preventDefault();
                alert('Please provide your signature before submitting.');
                return;
            }
            document.getElementById('form_teacher_signature_input').value = signaturePad.toDataURL();
            
            // Re-enable disabled inputs so they get submitted (only if admin editing, but here we disabled for non-admins to prevent submit)
            // Wait, if they are disabled, they won't submit. That's fine, non-admins shouldn't overwrite.
        });
    });

    function toggleResultType() {
        const resultType = document.getElementById('result_type')?.value || 'termly';
        const termWrap = document.getElementById('term_wrap');
        const termSelect = document.getElementById('term_id');
        const mockWrap = document.getElementById('mock_exam_wrap');
        const mockSelect = document.getElementById('mock_exam_id');
        const mockHelp = document.getElementById('mock_exam_help');

        const thCa1 = document.getElementById('th-ca1');
        const thCa2 = document.getElementById('th-ca2');
        const thCa3 = document.getElementById('th-ca3');
        const thExam = document.getElementById('th-exam');

        const isMock = resultType === 'mock';

        if (termWrap) termWrap.classList.toggle('hidden', isMock);
        if (mockWrap) mockWrap.classList.toggle('hidden', !isMock);

        if (isMock) {
            if (thCa1) thCa1.textContent = 'TEST (0-10)';
            if (thCa2) thCa2.textContent = 'PRACTICAL (0-20)';
            if (thCa3) thCa3.textContent = 'CA3';
            if (thExam) thExam.textContent = 'EXAM (0-90)';
        } else {
            if (thCa1) thCa1.textContent = 'CA1 (0-10)';
            if (thCa2) thCa2.textContent = 'CA2 (0-10)';
            if (thCa3) thCa3.textContent = 'CA3 (0-10)';
            if (thExam) thExam.textContent = 'Exam (0-70)';
        }

        if (termSelect) {
            termSelect.required = !isMock;
            termSelect.disabled = isMock;
        }
        if (mockSelect) {
            const hasRealMockOptions = mockSelect.options.length > 1;
            if (isMock && hasRealMockOptions && !mockSelect.value) {
                mockSelect.selectedIndex = 1;
            }
            mockSelect.required = isMock && hasRealMockOptions;
            mockSelect.disabled = !isMock;
            if (mockHelp) {
                mockHelp.classList.toggle('hidden', !isMock || hasRealMockOptions);
            }
        }

        checkAndLoadData();
    }
    """
    content = content[:js_start] + new_js + content[js_end:]

# remove onchange="loadClassData()" from class_id since we bind listeners?
# Actually loadClassData() just calls checkAndLoadData() now.

with open(filepath, "w", encoding="utf-8") as f:
    f.write(content)

print("Done")
