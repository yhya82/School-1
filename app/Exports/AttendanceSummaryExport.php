<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Section;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceSummaryExport implements FromCollection, WithHeadings
{
    public function __construct(protected string $startDate, protected string $endDate)
    {
    }

    public function collection()
    {
        return Section::with('schoolClass')->orderBy('class_id')->orderBy('name')->get()->map(function (Section $section) {
            $query = Attendance::where('section_id', $section->id)
                ->whereDate('date', '>=', $this->startDate)
                ->whereDate('date', '<=', $this->endDate);

            $total = (clone $query)->count();
            $present = (clone $query)->where('status', 'present')->count();
            $percentage = $total > 0 ? round(($present / $total) * 100, 1) : 0;

            return [
                $section->schoolClass->name,
                $section->name,
                $total,
                $present,
                $percentage.'%',
            ];
        });
    }

    public function headings(): array
    {
        return ['Class', 'Section', 'Total Records', 'Present', 'Attendance %'];
    }
}
