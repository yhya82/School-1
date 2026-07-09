<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #1f2937; }
        h1 { font-size: 18px; color: #16243f; margin-bottom: 0; }
        p.subtitle { color: #6b7280; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #d1d5db; padding: 6px 8px; text-align: left; }
        th { background-color: #eef2f8; text-transform: uppercase; font-size: 10px; color: #4b5563; }
        tfoot td { font-weight: bold; background-color: #f9fafb; }
        .meta { margin-top: 10px; }
        .meta td { border: none; padding: 2px 8px 2px 0; }
    </style>
</head>
<body>
    <h1>Report Card</h1>
    <p class="subtitle">{{ $exam->name }}</p>

    <table class="meta">
        <tr>
            <td><strong>Student:</strong></td>
            <td>{{ $student->user->name }}</td>
            <td><strong>Admission No:</strong></td>
            <td>{{ $student->admission_no }}</td>
        </tr>
        <tr>
            <td><strong>Class:</strong></td>
            <td>{{ $student->schoolClass->name }} - {{ $student->section->name }}</td>
            <td><strong>Exam Period:</strong></td>
            <td>{{ $exam->start_date->format('Y-m-d') }} &ndash; {{ $exam->end_date->format('Y-m-d') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Marks Obtained</th>
                <th>Max Marks</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($results as $result)
                <tr>
                    <td>{{ $result->examSubject->classSubject->subject->name }}</td>
                    <td>{{ $result->marks_obtained }}</td>
                    <td>{{ $result->examSubject->max_marks }}</td>
                    <td>{{ $result->grade }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No results recorded for this exam.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td>{{ $totalObtained }}</td>
                <td>{{ $totalMax }}</td>
                <td>{{ $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 1).'%' : '—' }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
