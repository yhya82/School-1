<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Student::with(['user', 'schoolClass', 'section'])
            ->where('status', 'active')
            ->orderBy('admission_no')
            ->get()
            ->map(fn (Student $student) => [
                $student->admission_no,
                $student->user->name,
                $student->schoolClass->name,
                $student->section->name,
                $student->gender,
                $student->dob->format('Y-m-d'),
                $student->admission_date->format('Y-m-d'),
                ucfirst($student->status),
            ]);
    }

    public function headings(): array
    {
        return ['Admission No', 'Name', 'Class', 'Section', 'Gender', 'Date of Birth', 'Admission Date', 'Status'];
    }
}
