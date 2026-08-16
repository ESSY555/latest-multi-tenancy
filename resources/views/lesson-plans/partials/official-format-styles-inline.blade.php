{{-- Scoped table CSS; included in <body> so it always applies (no @push timing vs @stack('head')). --}}
<style>
    /* Official lesson plan: force visible table borders (Tailwind preflight sets border-width: 0 on all elements) */
    .official-lesson-format { --lp-navy: #1d3557; --lp-step: #d9e1f2; --lp-focus: #fff2cc; --lp-beige: #f7f0e1; }
    .official-lesson-format table.lp-doc {
        width: 100% !important;
        border-collapse: collapse !important;
        table-layout: fixed !important;
    }
    .official-lesson-format table.lp-doc th,
    .official-lesson-format table.lp-doc td {
        border: 1px solid #000 !important;
        padding: 8px 10px !important;
        vertical-align: top !important;
        word-wrap: break-word !important;
    }
    .official-lesson-format .lp-lbl {
        background: var(--lp-navy) !important;
        color: #fff !important;
        font-weight: 700 !important;
        width: 30% !important;
    }
    .official-lesson-format .lp-hdr-cell {
        background: var(--lp-navy) !important;
        color: #fff !important;
        text-align: center !important;
        font-weight: 800 !important;
        padding: 12px 10px !important;
        font-size: 1rem !important;
        letter-spacing: 0.04em !important;
    }
    .official-lesson-format .lp-top { background: var(--lp-beige) !important; }
    .official-lesson-format .lp-val { background: #fff !important; }
    .official-lesson-format .lp-dev-h th {
        background: var(--lp-navy) !important;
        color: #fff !important;
        text-align: center !important;
        font-size: 11px !important;
        font-weight: 700 !important;
    }
    .official-lesson-format .lp-st {
        background: var(--lp-step) !important;
        font-weight: 700 !important;
        text-align: center !important;
        width: 14% !important;
    }
    .official-lesson-format .lp-lp { background: var(--lp-focus) !important; width: 22% !important; }
    .official-lesson-format .lp-eval-mid { background: var(--lp-focus) !important; }
    .official-lesson-format .lp-header-school { font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 600; letter-spacing: 0.06em; }
    .official-lesson-format .lp-header-meta { color: #b45309; font-size: 13px; font-weight: 600; }
    .official-lesson-format .lp-header-topic { font-size: 13px; color: #334155; text-align: right; }
    .official-lesson-format .lp-input,
    .official-lesson-format .lp-textarea,
    .official-lesson-format .lp-select {
        width: 100% !important;
        max-width: 100% !important;
        border: 0 !important;
        background: transparent !important;
        font: inherit !important;
        color: inherit !important;
        box-shadow: none !important;
        outline: none !important;
        margin: 0 !important;
    }
    .official-lesson-format .lp-textarea { min-height: 4.5rem !important; resize: vertical !important; display: block !important; }
    .official-lesson-format .lp-input { min-height: 1.75rem !important; }
    .official-lesson-format td.lp-val:focus-within,
    .official-lesson-format td.lp-top:focus-within,
    .official-lesson-format td.lp-lp:focus-within,
    .official-lesson-format td.lp-eval-mid:focus-within {
        box-shadow: inset 0 0 0 2px rgba(99, 102, 241, 0.35) !important;
    }
    .official-lesson-format .lp-hdr-cell .lp-input { color: #fff !important; font-weight: 800 !important; text-align: center !important; }
    .official-lesson-format .lp-field-invalid { box-shadow: inset 0 0 0 2px rgba(220, 38, 38, 0.45) !important; }
    @media print {
        .no-print { display: none !important; }
        .official-lesson-print-wrap { box-shadow: none !important; border: 0 !important; }
    }
</style>

