<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; }
        .header { text-align: center; margin-bottom: 24px; }
        .title { font-size: 22px; font-weight: 700; }
        .muted { color: #6B7280; font-size: 12px; }
        .content { font-size: 14px; line-height: 1.6; }
        .box { border: 1px solid #E5E7EB; padding: 16px; border-radius: 6px; }
    </style>
    </head>
<body>
    <div class="header">
        <div class="title">{{ $title }}</div>
        <div class="muted">Mode: {{ $mode ?? 'preview' }}</div>
    </div>
    <div class="content box">
        <p>This is a generated PDF for "{{ $title }}".</p>
        <p>Replace this template with your actual book content or embed a real PDF file as needed.</p>
    </div>
</body>
</html>



