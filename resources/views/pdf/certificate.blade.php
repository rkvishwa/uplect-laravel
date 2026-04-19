<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('Certificate') }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; text-align: center; padding: 48px; color: #1a1a1a; }
        h1 { font-size: 28px; margin-bottom: 8px; }
        p { font-size: 14px; line-height: 1.6; }
        .meta { margin-top: 32px; font-size: 12px; color: #444; }
    </style>
</head>
<body>
    <h1>{{ __('Certificate of completion') }}</h1>
    <p>{{ __('This certifies that') }}</p>
    <p><strong>{{ $student->name }}</strong></p>
    <p>{{ __('has successfully completed') }}</p>
    <p><strong>{{ $course->title }}</strong></p>
    <div class="meta">
        <p>{{ __('Certificate no.') }}: {{ $certificateNumber }}</p>
        <p>{{ __('Issued on') }}: {{ $issuedAt->format('Y-m-d') }}</p>
    </div>
</body>
</html>
