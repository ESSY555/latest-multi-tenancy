@php
    $branchName = $lessonPlan->branch?->name;
    $schoolLine = $lessonPlan->school_name
        ?? ($branchName ? $branchName . (str_contains(strtolower($branchName), 'abuja') ? '' : ', Abuja') : 'Bezaleel International School, Abuja');
    $st = $lessonPlan->subject_topic ?? '';
    $subjectParts = explode(' - ', $st, 2);
    $subjectName = $subjectParts[0] ?? $st;
    $topicFromField = $subjectParts[1] ?? '';
    $learningAids = $lessonPlan->learning_aids ?: $lessonPlan->materials_resources;
    $previousKnowledge = $lessonPlan->previous_knowledge ?: $lessonPlan->lesson_introduction;
    $timeDisplay = $lessonPlan->time_slot;
    if ($timeDisplay === null || $timeDisplay === '') {
        $timeDisplay = '—';
    }
    $metaLine = trim(implode(' | ', array_filter([
        $subjectName,
        $lessonPlan->class_grade_level,
        $lessonPlan->term_name,
    ])));
@endphp

{{-- Bordered table layout; CSS from official-format-styles-inline (included in page body) --}}
<div class="official-lesson-format text-gray-900 text-sm leading-relaxed max-w-5xl mx-auto">
    {{-- Cover: one bordered table — left: school + session line; right: topic (as in sample) --}}
    <table class="lp-doc mb-2">
        <tbody>
            <tr>
                <td class="lp-val" style="width: 58%; vertical-align: top;">
                    <div class="lp-header-school">{{ strtoupper($schoolLine) }}</div>
                    <div class="lp-header-meta" style="margin-top: 4px;">{{ $metaLine !== '' ? $metaLine : '—' }}</div>
                </td>
                <td class="lp-val" style="width: 42%; vertical-align: top; text-align: right;">
                    <div class="lp-header-topic"><span style="font-weight: 600;">Topic:</span> {{ $lessonPlan->lesson_title }}</div>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Main title bar: full-width navy row --}}
    <table class="lp-doc mb-6">
        <tbody>
            <tr>
                <td class="lp-hdr-cell">LESSON PLAN &mdash; {{ strtoupper($lessonPlan->lesson_title) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Block 1: two-column label / value (beige then white for objectives) --}}
    <table class="lp-doc mb-6">
        <tbody>
            <tr class="lp-top">
                <td class="lp-lbl">Name of School</td>
                <td class="lp-top">{{ $schoolLine }}</td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Term</td>
                <td class="lp-top">{{ $lessonPlan->term_name ?? '—' }}</td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Week</td>
                <td class="lp-top">{{ $lessonPlan->week_name ?? '—' }}</td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Class</td>
                <td class="lp-top">{{ $lessonPlan->class_grade_level ?? '—' }}</td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Subject</td>
                <td class="lp-top">{{ $subjectName }}</td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Theme</td>
                <td class="lp-top">{{ $lessonPlan->theme ?? '—' }}</td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Topic</td>
                <td class="lp-top">{{ $topicFromField !== '' ? $topicFromField : $lessonPlan->lesson_title }}</td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Subtopic</td>
                <td class="lp-top">{{ $lessonPlan->subtopic ?? '—' }}</td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Periods</td>
                <td class="lp-top">{{ $lessonPlan->periods ?? '—' }}</td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Time</td>
                <td class="lp-top">{{ $timeDisplay }}</td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Duration</td>
                <td class="lp-top">{{ $lessonPlan->duration ?? '—' }}</td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">No. in Class</td>
                <td class="lp-top">{{ $lessonPlan->class_size ?? '—' }}</td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Average Age</td>
                <td class="lp-top">{{ $lessonPlan->average_age ?? '—' }}</td>
            </tr>
            <tr class="lp-top">
                <td class="lp-lbl">Sex</td>
                <td class="lp-top">{{ $lessonPlan->sex_label ?? '—' }}</td>
            </tr>
            <tr>
                <td class="lp-lbl">Learning Objectives</td>
                <td class="lp-val">
                    <p style="font-weight: 600; margin: 0 0 6px 0;">By the end of this lesson, students should be able to:</p>
                    <div style="white-space: pre-wrap;">{!! nl2br(e($lessonPlan->objectives)) !!}</div>
                </td>
            </tr>
            <tr>
                <td class="lp-lbl">Rationale</td>
                <td class="lp-val" style="white-space: pre-wrap;">{!! $lessonPlan->rationale ? nl2br(e($lessonPlan->rationale)) : '—' !!}</td>
            </tr>
        </tbody>
    </table>

    {{-- Previous knowledge / aids / references --}}
    <table class="lp-doc mb-6">
        <tbody>
            <tr>
                <td class="lp-lbl">Previous Knowledge</td>
                <td class="lp-val" style="white-space: pre-wrap;">{!! nl2br(e($previousKnowledge)) !!}</td>
            </tr>
            <tr>
                <td class="lp-lbl">Learning Aids</td>
                <td class="lp-val" style="white-space: pre-wrap;">{!! nl2br(e($learningAids)) !!}</td>
            </tr>
            <tr>
                <td class="lp-lbl">References</td>
                <td class="lp-val" style="white-space: pre-wrap;">{!! $lessonPlan->reference_books ? nl2br(e($lessonPlan->reference_books)) : '—' !!}</td>
            </tr>
        </tbody>
    </table>

    {{-- LESSON DEVELOPMENT: one 4-column table including Evaluation & Conclusion rows --}}
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
                <td class="lp-val" style="white-space: pre-wrap;">{!! nl2br(e($lessonPlan->lesson_introduction)) !!}</td>
                <td class="lp-val">Students respond to questions, observe examples, and participate as the teacher leads set induction.</td>
                <td class="lp-lp">Activating prior knowledge; motivation and context for the topic.</td>
            </tr>
            <tr>
                <td class="lp-st">Presentation</td>
                <td class="lp-val" style="white-space: pre-wrap;">{!! nl2br(e($lessonPlan->lesson_development)) !!}</td>
                <td class="lp-val">Students listen, take notes, ask questions, practise tasks, and work individually or in groups as directed.</td>
                <td class="lp-lp">Main content delivery, demonstration, and guided practice.</td>
            </tr>
            <tr>
                <td class="lp-st">Evaluation</td>
                <td class="lp-eval-mid" colspan="2" style="white-space: pre-wrap;">{!! nl2br(e($lessonPlan->assessment_evaluation)) !!}</td>
                <td class="lp-lp"></td>
            </tr>
            <tr>
                <td class="lp-st">Conclusion</td>
                <td class="lp-val" style="white-space: pre-wrap;">{!! nl2br(e($lessonPlan->conclusion)) !!}</td>
                <td class="lp-val">Students compare with models, note corrections, and copy homework or next steps as assigned.</td>
                <td class="lp-lp">Summary, reinforcement, and assignment.</td>
            </tr>
        </tbody>
    </table>

    @if($lessonPlan->reflection)
        <table class="lp-doc mb-6">
            <tbody>
                <tr>
                    <td class="lp-lbl" style="width: 30%;">Reflection (teacher)</td>
                    <td class="lp-val" style="white-space: pre-wrap;">{{ $lessonPlan->reflection }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    @php
        $lessonNoteBody = $lessonPlan->lesson_note;
        $showLessonNoteBlock = filled($lessonNoteBody) || !empty($lessonNoteShellStub);
    @endphp
    @if($showLessonNoteBlock)
        <table class="lp-doc mb-6">
            <tbody>
                <tr>
                    <td class="lp-val" colspan="2">
                        <span class="lp-header-school">{{ strtoupper($schoolLine) }}</span><br>
                        <span class="lp-header-meta">{{ $metaLine !== '' ? $metaLine : '—' }}</span>
                        <div class="lp-header-topic" style="margin-top: 6px;"><span style="font-weight: 600;">Topic:</span> {{ $lessonPlan->lesson_title }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="lp-hdr-cell" colspan="2">LESSON NOTE &mdash; {{ strtoupper($lessonPlan->lesson_title) }}</td>
                </tr>
                <tr>
                    <td class="lp-val" colspan="2" style="white-space: pre-wrap; padding: 12px;">
                        @if(filled($lessonNoteBody))
                            {!! nl2br(e($lessonNoteBody)) !!}
                        @else
                            <span class="text-slate-500 italic">Optional long teaching note (definitions, examples, numbered content). Type in the Lesson note field in the form below. On the saved lesson plan view and printout, this section appears only when that field has text.</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    @endif
</div>

