<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\ClassSubject;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamSubject;
use App\Models\Expense;
use App\Models\FeeStructure;
use App\Models\Guardian;
use App\Models\Invoice;
use App\Models\Leave;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::first();
        AcademicYear::factory()->create([
            'name' => ($academicYear->start_date->year - 1).'/'.$academicYear->start_date->year,
            'start_date' => $academicYear->start_date->copy()->subYear(),
            'end_date' => $academicYear->start_date->copy()->subDay(),
        ]);

        $subjects = Subject::factory()->count(8)->create();

        $classes = collect(range(1, 5))->map(
            fn ($grade) => SchoolClass::factory()->create(['name' => "Grade $grade"])
        );

        $sections = $classes->flatMap(fn ($class) => collect(['A', 'B'])->map(
            fn ($name) => Section::factory()->create(['class_id' => $class->id, 'name' => $name, 'capacity' => 30])
        ));

        $teachers = Staff::factory()->count(6)->create();
        foreach ($teachers as $teacher) {
            $teacher->user->assignRole('teacher');
        }
        $generalStaff = Staff::factory()->count(2)->create();
        foreach ($generalStaff as $staff) {
            $staff->user->assignRole('staff');
        }
        $allStaff = $teachers->merge($generalStaff);

        $classSubjects = $classes->flatMap(function (SchoolClass $class) use ($subjects, $teachers) {
            return $subjects->random(4)->map(fn ($subject) => ClassSubject::create([
                'class_id' => $class->id,
                'subject_id' => $subject->id,
                'teacher_id' => $teachers->random()->id,
            ]));
        });

        $guardians = Guardian::factory()->count(20)->create();

        $students = $sections->flatMap(function (Section $section) use ($academicYear, $guardians) {
            return Student::factory()->count(5)->create([
                'class_id' => $section->class_id,
                'section_id' => $section->id,
                'academic_year_id' => $academicYear->id,
            ])->each(function (Student $student) use ($guardians) {
                $student->user->assignRole('student');
                $student->guardians()->attach($guardians->random(rand(1, 2))->pluck('id'));
            });
        });

        foreach ($sections as $section) {
            $sectionClassSubjects = $classSubjects->where('class_id', $section->class_id)->values();
            foreach ($sectionClassSubjects as $index => $classSubject) {
                $day = ($index % 5) + 1;
                $hour = 8 + intdiv($index, 5);

                \App\Models\TimetableSlot::create([
                    'section_id' => $section->id,
                    'class_subject_id' => $classSubject->id,
                    'day_of_week' => $day,
                    'start_time' => sprintf('%02d:00:00', $hour),
                    'end_time' => sprintf('%02d:00:00', $hour + 1),
                    'room' => 'Room '.rand(1, 20),
                ]);
            }
        }

        $exam = Exam::factory()->create([
            'academic_year_id' => $academicYear->id,
            'name' => 'Midterm Exam',
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(10),
        ]);

        foreach ($classSubjects as $classSubject) {
            $examSubject = ExamSubject::create([
                'exam_id' => $exam->id,
                'class_subject_id' => $classSubject->id,
                'exam_date' => now()->addDays(rand(5, 10)),
                'max_marks' => 100,
                'pass_marks' => 40,
            ]);

            $studentsInClass = $students->where('class_id', $classSubject->class_id);
            foreach ($studentsInClass as $student) {
                $marks = rand(30, 100);
                ExamResult::create([
                    'exam_subject_id' => $examSubject->id,
                    'student_id' => $student->id,
                    'marks_obtained' => $marks,
                    'grade' => match (true) {
                        $marks >= 90 => 'A',
                        $marks >= 75 => 'B',
                        $marks >= 60 => 'C',
                        $marks >= 40 => 'D',
                        default => 'F',
                    },
                ]);
            }
        }

        $attendanceStatuses = ['present', 'present', 'present', 'present', 'absent', 'late'];
        foreach ($students as $student) {
            for ($d = 1; $d <= 5; $d++) {
                Attendance::create([
                    'student_id' => $student->id,
                    'section_id' => $student->section_id,
                    'date' => now()->subDays($d)->format('Y-m-d'),
                    'status' => $attendanceStatuses[array_rand($attendanceStatuses)],
                    'marked_by' => $teachers->random()->id,
                ]);
            }
        }

        foreach ($allStaff as $staff) {
            for ($d = 1; $d <= 5; $d++) {
                StaffAttendance::create([
                    'staff_id' => $staff->id,
                    'date' => now()->subDays($d)->format('Y-m-d'),
                    'status' => $attendanceStatuses[array_rand($attendanceStatuses)],
                ]);
            }
        }

        $adminStaff = Staff::whereHas('user', fn ($q) => $q->where('email', 'admin@school.test'))->first();

        foreach ($teachers->random(3) as $staff) {
            Leave::factory()->create(['staff_id' => $staff->id, 'status' => 'pending']);
        }
        $approvedLeave = Leave::factory()->create(['staff_id' => $teachers->first()->id]);
        $approvedLeave->update(['status' => 'approved', 'approved_by' => $adminStaff?->id]);

        $feeStructures = $classes->map(fn ($class) => FeeStructure::create([
            'class_id' => $class->id,
            'academic_year_id' => $academicYear->id,
            'name' => 'Tuition Fee',
            'amount' => 500,
            'frequency' => 'term',
        ]));

        foreach ($feeStructures as $feeStructure) {
            $classStudents = $students->where('class_id', $feeStructure->class_id)->where('status', 'active');

            foreach ($classStudents as $student) {
                $invoice = Invoice::create([
                    'student_id' => $student->id,
                    'fee_structure_id' => $feeStructure->id,
                    'amount_due' => $feeStructure->amount,
                    'due_date' => now()->addDays(30),
                    'status' => 'unpaid',
                ]);

                $roll = rand(1, 3);
                if ($roll === 1) {
                    Payment::create([
                        'invoice_id' => $invoice->id,
                        'amount_paid' => $feeStructure->amount,
                        'payment_date' => now()->subDays(rand(1, 15)),
                        'payment_method' => 'cash',
                        'transaction_ref' => Str::uuid(),
                    ]);
                    $invoice->recalculateStatus();
                } elseif ($roll === 2) {
                    Payment::create([
                        'invoice_id' => $invoice->id,
                        'amount_paid' => $feeStructure->amount / 2,
                        'payment_date' => now()->subDays(rand(1, 15)),
                        'payment_method' => 'card',
                        'transaction_ref' => Str::uuid(),
                    ]);
                    $invoice->recalculateStatus();
                }
            }
        }

        $expenseCategories = ['Utilities', 'Maintenance', 'Supplies', 'Salaries'];
        foreach (range(1, 6) as $i) {
            Expense::create([
                'category' => $expenseCategories[array_rand($expenseCategories)],
                'amount' => rand(50, 2000),
                'expense_date' => now()->subDays(rand(1, 30)),
                'description' => 'Sample expense entry',
                'recorded_by' => $adminStaff?->id ?? $allStaff->first()->id,
            ]);
        }
    }
}
