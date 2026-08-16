@php
    $isCreate = ($mode ?? 'create') === 'create';
    $lt = old('lesson_title', $isCreate ? '' : $lessonPlan->lesson_title);
    $initialSubjectEcho = '—';
    $initialClassEcho = '—';
    if ($isCreate) {
        if (old('subject_id') && isset($subjects)) {
            $pickedSubject = $subjects->firstWhere('id', (int) old('subject_id'));
            if ($pickedSubject) {
                $initialSubjectEcho = $pickedSubject->name;
            }
        }
        if (old('class_id') && isset($classes)) {
            $pickedClass = $classes->firstWhere('id', (int) old('class_id'));
            if ($pickedClass) {
                $initialClassEcho = trim($pickedClass->name.' — '.$pickedClass->grade_level);
            }
        }
    }
@endphp

@if ($errors->any())
    <div class="mb-4 rounded-md border border-red-300 bg-red-50 px-3 py-2 text-sm text-red-800 no-print" role="alert">
        <p class="font-semibold">Please fix the errors below and submit again.</p>
        <ul class="mt-2 list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="official-lesson-format text-gray-900 text-sm leading-relaxed max-w-5xl mx-auto">
    <table class="lp-doc mb-2">
        <tbody>
            <tr>
                <td class="lp-lbl">Teacher</td>
                <td class="lp-val">
                    <input type="text" name="teacher_name" id="teacher_name" required
                        value="{{ old('teacher_name', $isCreate ? auth()->user()->name : $lessonPlan->teacher_name) }}"
                        class="lp-input @error('teacher_name') lp-field-invalid @enderror">
                    @error('teacher_name')<p class="mt-1 text-xs text-red-600 no-print">{{ $message }}</p>@enderror
                </td>
                <td class="lp-lbl">Date</td>
                <td class="lp-val">
                    <input type="date" name="lesson_date" id="lesson_date" required
                        value="{{ old('lesson_date', $isCreate ? now()->toDateString() : optional($lessonPlan->lesson_date)->format('Y-m-d')) }}"
                        class="lp-input @error('lesson_date') lp-field-invalid @enderror">
                    @error('lesson_date')<p class="mt-1 text-xs text-red-600 no-print">{{ $message }}</p>@enderror
                </td>
            </tr>
        </tbody>
    </table>

    <table class="lp-doc mb-2">
        <tbody>
            <tr>
                <td class="lp-val" style="width: 58%; vertical-align: top;">
                    <div class="lp-header-school" style="margin-bottom: 4px;">Subject &amp; class</div>
                    <div class="lp-header-meta mt-2 space-y-2">
                        @if ($isCreate)
                            <div>
                                <span class="text-xs text-slate-500 block mb-0.5">Subject</span>
                                <select name="subject_id" id="subject_id" required class="lp-select border border-slate-200 rounded px-1 py-1 @error('subject_id') lp-field-invalid @enderror">
                                    <option value="">Select subject</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error('subject_id')<p class="mt-1 text-xs text-red-600 no-print">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block mb-0.5">Curriculum topic</span>
                                <input type="text" name="topic" id="topic" value="{{ old('topic') }}" required placeholder="e.g. Fractions"
                                    class="lp-input border border-slate-200 rounded px-1 py-1 @error('topic') lp-field-invalid @enderror">
                                @error('topic')<p class="mt-1 text-xs text-red-600 no-print">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block mb-0.5">Class (optional)</span>
                                <select name="class_id" id="class_id" class="lp-select border border-slate-200 rounded px-1 py-1">
                                    <option value="">Select a class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" @selected(old('class_id') == $class->id)>{{ $class->name }} — {{ $class->grade_level }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')<p class="mt-1 text-xs text-red-600 no-print">{{ $message }}</p>@enderror
                            </div>
                        @else
                            <div>
                                <span class="text-xs text-slate-500 block mb-0.5">Subject / curriculum topic</span>
                                <input type="text" name="subject_topic" id="subject_topic" required
                                    value="{{ old('subject_topic', $lessonPlan->subject_topic) }}"
                                    class="lp-input border border-slate-200 rounded px-1 py-1 @error('subject_topic') lp-field-invalid @enderror">
                                @error('subject_topic')<p class="mt-1 text-xs text-red-600 no-print">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block mb-0.5">Class / grade level</span>
                                <input type="text" name="class_grade_level" id="class_grade_level" required
                                    value="{{ old('class_grade_level', $lessonPlan->class_grade_level) }}"
                                    class="lp-input border border-slate-200 rounded px-1 py-1 @error('class_grade_level') lp-field-invalid @enderror">
                                @error('class_grade_level')<p class="mt-1 text-xs text-red-600 no-print">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block mb-0.5">Class link (optional)</span>
                                <select name="class_id" id="class_id" class="lp-select border border-slate-200 rounded px-1 py-1">
                                    <option value="">Select a class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" @selected(old('class_id', $lessonPlan->class_id) == $class->id)>{{ $class->name }} — {{ $class->grade_level }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')<p class="mt-1 text-xs text-red-600 no-print">{{ $message }}</p>@enderror
                            </div>
                        @endif
                    </div>
                </td>
                <td class="lp-val" style="width: 42%; vertical-align: top; text-align: right;">
                    <div class="lp-header-topic text-left sm:text-right">
                        <span style="font-weight: 600;">Topic (lesson title)</span>
                        <div id="lpCoverTopicEcho" class="mt-1 text-right break-words">{{ $lt }}</div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="lp-doc mb-6">
        <tbody>
            <tr>
                <td class="lp-hdr-cell">
                    <span class="align-middle">LESSON PLAN —</span>
                    <input type="text" name="lesson_title" id="lesson_title" required
                        value="{{ $lt }}"
                        oninput="(function(v){var e=document.getElementById('lpCoverTopicEcho');if(e)e.textContent=v;})(this.value)"
                        class="lp-input inline-block align-middle text-center font-extrabold tracking-wide @error('lesson_title') lp-field-invalid @enderror"
                        style="width: 70%; max-width: 100%; color: #fff !important; margin-left: 6px;">
                </td>
            </tr>
        </tbody>
    </table>
    @error('lesson_title')<p class="mb-2 text-xs text-red-600 no-print">{{ $message }}</p>@enderror

    <table class="lp-doc mb-6">
        <tbody>
            @unless ($isCreate)
                @php
                    $stParts = explode(' - ', old('subject_topic', $lessonPlan->subject_topic), 2);
                @endphp
            @endunless
            <tr class="lp-top">
                <td class="lp-lbl">Name of School</td>
                <td class="lp-top">
                    <input type="text" name="school_name" id="school_name"
                        value="{{ old('school_name', $isCreate ? 'Bezaleel International School, Abuja' : $lessonPlan->school_name) }}"
                        class="lp-input font-semibold uppercase tracking-wide text-slate-700 @error('school_name') lp-field-invalid @enderror"
                        style="font-size: 12px;">
                </td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Term</td>
                <td class="lp-top"><input type="text" name="term_name" value="{{ old('term_name', $isCreate ? '' : $lessonPlan->term_name) }}" class="lp-input @error('term_name') lp-field-invalid @enderror"></td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Week</td>
                <td class="lp-top"><input type="text" name="week_name" value="{{ old('week_name', $isCreate ? '' : $lessonPlan->week_name) }}" class="lp-input"></td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Class</td>
                <td class="lp-top">
                    @if ($isCreate)
                        <span id="lpEchoClass" class="block text-sm text-slate-900 font-medium leading-snug">{{ $initialClassEcho }}</span>
                    @else
                        <input type="text" readonly class="lp-input opacity-80" value="{{ old('class_grade_level', $lessonPlan->class_grade_level) }}" title="Edit in Class / grade level field above">
                    @endif
                </td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Subject</td>
                <td class="lp-top">
                    @if ($isCreate)
                        <span id="lpEchoSubject" class="block text-sm text-slate-900 font-medium leading-snug">{{ $initialSubjectEcho }}</span>
                    @else
                        <input type="text" readonly class="lp-input opacity-80" value="{{ $stParts[0] ?? old('subject_topic', $lessonPlan->subject_topic) }}">
                    @endif
                </td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Theme</td>
                <td class="lp-top"><input type="text" name="theme" value="{{ old('theme', $isCreate ? '' : $lessonPlan->theme) }}" class="lp-input"></td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Topic</td>
                <td class="lp-top">
                    @if ($isCreate)
                        <input type="text" id="topic_dup" readonly class="lp-input opacity-80 cursor-default" value="{{ old('topic') }}" title="Same as curriculum topic in the cover block">
                    @else
                        <input type="text" readonly class="lp-input opacity-80" value="{{ $stParts[1] ?? '' }}">
                    @endif
                </td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Subtopic</td>
                <td class="lp-top"><input type="text" name="subtopic" value="{{ old('subtopic', $isCreate ? '' : $lessonPlan->subtopic) }}" class="lp-input"></td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Periods</td>
                <td class="lp-top"><input type="text" name="periods" value="{{ old('periods', $isCreate ? '' : $lessonPlan->periods) }}" class="lp-input"></td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Time</td>
                <td class="lp-top"><input type="text" name="time_slot" value="{{ old('time_slot', $isCreate ? '' : $lessonPlan->time_slot) }}" class="lp-input" placeholder="—"></td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Duration</td>
                <td class="lp-top">
                    <input type="text" name="duration" required value="{{ old('duration', $isCreate ? '' : $lessonPlan->duration) }}"
                        class="lp-input @error('duration') lp-field-invalid @enderror" placeholder="e.g. 40 minutes">
                    @error('duration')<p class="mt-1 text-xs text-red-600 no-print">{{ $message }}</p>@enderror
                </td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">No. in Class</td>
                <td class="lp-top"><input type="text" name="class_size" value="{{ old('class_size', $isCreate ? '' : $lessonPlan->class_size) }}" class="lp-input"></td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Average Age</td>
                <td class="lp-top"><input type="text" name="average_age" value="{{ old('average_age', $isCreate ? '' : $lessonPlan->average_age) }}" class="lp-input"></td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Sex</td>
                <td class="lp-top"><input type="text" name="sex_label" value="{{ old('sex_label', $isCreate ? 'Mixed Gender' : $lessonPlan->sex_label) }}" class="lp-input"></td>
            </tr>
            <tr>
                <td class="lp-lbl">Learning Objectives</td>
                <td class="lp-val">
                    <p style="font-weight: 600; margin: 0 0 6px 0;">By the end of this lesson, students should be able to:</p>
                    <textarea name="objectives" id="objectives" rows="5" required class="lp-textarea @error('objectives') lp-field-invalid @enderror">{{ old('objectives', $isCreate ? '' : $lessonPlan->objectives) }}</textarea>
                    @error('objectives')<p class="mt-1 text-xs text-red-600 no-print">{{ $message }}</p>@enderror
                </td>
            </tr>
            <tr>
                <td class="lp-lbl">Rationale</td>
                <td class="lp-val">
                    <textarea name="rationale" id="rationale" rows="3" class="lp-textarea">{{ old('rationale', $isCreate ? '' : $lessonPlan->rationale) }}</textarea>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="lp-doc mb-6">
        <tbody>
            <tr>
                <td class="lp-lbl">Previous Knowledge</td>
                <td class="lp-val">
                    <textarea name="previous_knowledge" id="previous_knowledge" rows="3" class="lp-textarea">{{ old('previous_knowledge', $isCreate ? '' : $lessonPlan->previous_knowledge) }}</textarea>
                </td>
            </tr>
            <tr>
                <td class="lp-lbl">Learning Aids</td>
                <td class="lp-val">
                    <p class="text-xs text-slate-500 mb-1 no-print">Optional aids (if empty, materials below are used on save).</p>
                    <textarea name="learning_aids" id="learning_aids" rows="2" class="lp-textarea mb-2">{{ old('learning_aids', $isCreate ? '' : $lessonPlan->learning_aids) }}</textarea>
                    <p class="text-xs text-slate-600 mb-1 font-medium">Materials / resources (required)</p>
                    <textarea name="materials_resources" id="materials_resources" rows="3" required class="lp-textarea @error('materials_resources') lp-field-invalid @enderror">{{ old('materials_resources', $isCreate ? '' : $lessonPlan->materials_resources) }}</textarea>
                    @error('materials_resources')<p class="mt-1 text-xs text-red-600 no-print">{{ $message }}</p>@enderror
                </td>
            </tr>
            <tr>
                <td class="lp-lbl">References</td>
                <td class="lp-val">
                    <textarea name="reference_books" id="reference_books" rows="2" class="lp-textarea">{{ old('reference_books', $isCreate ? '' : $lessonPlan->reference_books) }}</textarea>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="lp-doc mb-6">
        <thead>
            <tr>
                <th class="lp-hdr-cell" colspan="4" style="font-size: 13px;">LESSON DEVELOPMENT</th>
            </tr>
            <tr class="lp-dev-h">
                <th style="width: 14%;">Stage / Step</th>
                <th style="width: 32%;">Teacher&rsquo;s Activities</th>
                <th style="width: 32%;">Learners&rsquo; Activities</th>
                <th style="width: 22%;">Learning Point</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="lp-st">Introduction</td>
                <td class="lp-val">
                    <textarea name="lesson_introduction" id="lesson_introduction" rows="5" required class="lp-textarea @error('lesson_introduction') lp-field-invalid @enderror">{{ old('lesson_introduction', $isCreate ? '' : $lessonPlan->lesson_introduction) }}</textarea>
                    @error('lesson_introduction')<p class="mt-1 text-xs text-red-600 no-print">{{ $message }}</p>@enderror
                </td>
                <td class="lp-val">Students respond to questions, observe examples, and participate as the teacher leads set induction.</td>
                <td class="lp-lp">Activating prior knowledge; motivation and context for the topic.</td>
            </tr>
            <tr>
                <td class="lp-st">Presentation</td>
                <td class="lp-val">
                    <textarea name="lesson_development" id="lesson_development" rows="6" required class="lp-textarea @error('lesson_development') lp-field-invalid @enderror">{{ old('lesson_development', $isCreate ? '' : $lessonPlan->lesson_development) }}</textarea>
                    @error('lesson_development')<p class="mt-1 text-xs text-red-600 no-print">{{ $message }}</p>@enderror
                </td>
                <td class="lp-val">Students listen, take notes, ask questions, practise tasks, and work individually or in groups as directed.</td>
                <td class="lp-lp">Main content delivery, demonstration, and guided practice.</td>
            </tr>
            <tr>
                <td class="lp-st">Evaluation</td>
                <td class="lp-eval-mid" colspan="2">
                    <textarea name="assessment_evaluation" id="assessment_evaluation" rows="4" required class="lp-textarea @error('assessment_evaluation') lp-field-invalid @enderror">{{ old('assessment_evaluation', $isCreate ? '' : $lessonPlan->assessment_evaluation) }}</textarea>
                    @error('assessment_evaluation')<p class="mt-1 text-xs text-red-600 no-print">{{ $message }}</p>@enderror
                </td>
                <td class="lp-lp"></td>
            </tr>
            <tr>
                <td class="lp-st">Conclusion</td>
                <td class="lp-val">
                    <textarea name="conclusion" id="conclusion" rows="4" required class="lp-textarea @error('conclusion') lp-field-invalid @enderror">{{ old('conclusion', $isCreate ? '' : $lessonPlan->conclusion) }}</textarea>
                    @error('conclusion')<p class="mt-1 text-xs text-red-600 no-print">{{ $message }}</p>@enderror
                </td>
                <td class="lp-val">Students compare with models, note corrections, and copy homework or next steps as assigned.</td>
                <td class="lp-lp">Summary, reinforcement, and assignment.</td>
            </tr>
        </tbody>
    </table>

    <table class="lp-doc mb-6">
        <tbody>
            <tr>
                <td class="lp-lbl" style="width: 30%;">Reflection (teacher, optional)</td>
                <td class="lp-val">
                    <textarea name="reflection" id="reflection" rows="3" class="lp-textarea">{{ old('reflection', $isCreate ? '' : $lessonPlan->reflection) }}</textarea>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="lp-doc mb-6">
        <tbody>
            <tr>
                <td class="lp-val" colspan="2">
                    <span class="lp-header-school" id="lpLessonNoteSchoolEcho">{{ strtoupper(old('school_name', $isCreate ? 'Bezaleel International School, Abuja' : ($lessonPlan->school_name ?? 'Bezaleel International School, Abuja'))) }}</span><br>
                    <span class="lp-header-meta text-xs">Lesson note (optional, printed after the main plan)</span>
                </td>
            </tr>
            <tr>
                <td class="lp-hdr-cell" colspan="2">LESSON NOTE</td>
            </tr>
            <tr>
                <td class="lp-val" colspan="2" style="padding: 12px;">
                    <textarea name="lesson_note" id="lesson_note" rows="8" class="lp-textarea font-mono text-xs" placeholder="Long teaching note, definitions, examples…">{{ old('lesson_note', $isCreate ? '' : $lessonPlan->lesson_note) }}</textarea>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@push('scripts')
    <script>
        (function () {
            var school = document.getElementById('school_name');
            var topicTop = document.getElementById('topic');
            var topicDup = document.getElementById('topic_dup');
            var noteSchool = document.getElementById('lpLessonNoteSchoolEcho');
            var subjectSel = document.getElementById('subject_id');
            var classSel = document.getElementById('class_id');
            var echoSubject = document.getElementById('lpEchoSubject');
            var echoClass = document.getElementById('lpEchoClass');

            function syncSchoolEcho() {
                if (school && noteSchool) noteSchool.textContent = (school.value || '').toUpperCase();
            }
            function syncTopicDup() {
                if (topicTop && topicDup) topicDup.value = topicTop.value;
            }
            function optionLabel(sel) {
                if (!sel || sel.selectedIndex < 0) return '—';
                var opt = sel.options[sel.selectedIndex];
                if (!opt || opt.value === '') return '—';
                return opt.textContent.trim();
            }
            function syncSubjectEcho() {
                if (echoSubject && subjectSel) echoSubject.textContent = optionLabel(subjectSel);
            }
            function syncClassEcho() {
                if (echoClass && classSel) echoClass.textContent = optionLabel(classSel);
            }

            if (school) { school.addEventListener('input', syncSchoolEcho); syncSchoolEcho(); }
            if (topicTop) { topicTop.addEventListener('input', syncTopicDup); syncTopicDup(); }
            if (subjectSel && echoSubject) {
                subjectSel.addEventListener('change', syncSubjectEcho);
                syncSubjectEcho();
            }
            if (classSel && echoClass) {
                classSel.addEventListener('change', syncClassEcho);
                syncClassEcho();
            }

            // If validation fails, take user directly to the first invalid field.
            var firstInvalid = document.querySelector('.lp-field-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                if (typeof firstInvalid.focus === 'function') {
                    firstInvalid.focus({ preventScroll: true });
                }
            }
        })();
    </script>
@endpush

