<?php

namespace App\Livewire\Students;

use App\Models\AcademicYear;
use App\Models\Guardian;
use App\Models\Section;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StudentCreate extends Component
{
    public string $name = '';

    public string $email = '';

    public string $admission_no = '';

    public ?int $class_id = null;

    public ?int $section_id = null;

    public ?int $academic_year_id = null;

    public string $dob = '';

    public string $gender = '';

    public string $admission_date = '';

    public array $selectedGuardianIds = [];

    public function mount(): void
    {
        Gate::authorize('create', Student::class);
    }

    public function updatedClassId(): void
    {
        $this->section_id = null;
    }

    public function sectionOptions()
    {
        return $this->class_id
            ? Section::where('class_id', $this->class_id)->orderBy('name')->get()
            : collect();
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'admission_no' => 'required|string|max:30|unique:students,admission_no',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'dob' => 'required|date|before:today',
            'gender' => 'required|in:M,F,O',
            'admission_date' => 'required|date',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $section = Section::find($data['section_id']);
        if ($section->class_id !== (int) $data['class_id']) {
            $this->addError('section_id', __('Selected section does not belong to the selected class.'));

            return;
        }

        $password = Str::password(12);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
        ]);
        $user->assignRole('student');

        $student = Student::create([
            ...collect($data)->except(['name', 'email'])->toArray(),
            'user_id' => $user->id,
            'status' => 'active',
        ]);

        $student->guardians()->sync($this->selectedGuardianIds);

        session()->flash('generated_password', $password);

        $this->redirectRoute('students.students.edit', $student, navigate: true);
    }

    public function render()
    {
        return view('livewire.students.student-create', [
            'classes' => SchoolClass::orderBy('name')->get(),
            'sections' => $this->sectionOptions(),
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(),
            'guardians' => Guardian::orderBy('name')->get(),
        ]);
    }
}
