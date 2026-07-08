<?php

namespace App\Livewire\Students;

use App\Models\AcademicYear;
use App\Models\Guardian;
use App\Models\Section;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentDocument;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class StudentEdit extends Component
{
    use WithFileUploads;

    public Student $student;

    public string $name = '';

    public string $email = '';

    public string $admission_no = '';

    public ?int $class_id = null;

    public ?int $section_id = null;

    public ?int $academic_year_id = null;

    public string $dob = '';

    public string $gender = '';

    public string $admission_date = '';

    public string $status = 'active';

    public array $selectedGuardianIds = [];

    public string $documentType = '';

    public $documentFile = null;

    public function mount(Student $student): void
    {
        Gate::authorize('update', $student);

        $student->load('guardians');

        $this->student = $student;
        $this->name = $student->user->name;
        $this->email = $student->user->email;
        $this->admission_no = $student->admission_no;
        $this->class_id = $student->class_id;
        $this->section_id = $student->section_id;
        $this->academic_year_id = $student->academic_year_id;
        $this->dob = $student->dob->format('Y-m-d');
        $this->gender = $student->gender;
        $this->admission_date = $student->admission_date->format('Y-m-d');
        $this->status = $student->status;
        $this->selectedGuardianIds = $student->guardians->pluck('id')->toArray();
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
            'email' => 'required|email|unique:users,email,'.$this->student->user_id,
            'admission_no' => 'required|string|max:30|unique:students,admission_no,'.$this->student->id,
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'dob' => 'required|date|before:today',
            'gender' => 'required|in:M,F,O',
            'admission_date' => 'required|date',
            'status' => 'required|in:active,graduated,withdrawn',
        ];
    }

    public function save(): void
    {
        Gate::authorize('update', $this->student);

        $data = $this->validate();

        $section = Section::find($data['section_id']);
        if ($section->class_id !== (int) $data['class_id']) {
            $this->addError('section_id', __('Selected section does not belong to the selected class.'));

            return;
        }

        $this->student->user->update(['name' => $data['name'], 'email' => $data['email']]);
        $this->student->update(collect($data)->except(['name', 'email'])->toArray());
        $this->student->guardians()->sync($this->selectedGuardianIds);

        $this->redirectRoute('students.students.index', navigate: true);
    }

    public function uploadDocument(): void
    {
        Gate::authorize('create', StudentDocument::class);

        $this->validate([
            'documentType' => 'required|string|max:50',
            'documentFile' => 'required|file|max:10240',
        ]);

        $path = $this->documentFile->store('documents', 'public');

        StudentDocument::create([
            'student_id' => $this->student->id,
            'type' => $this->documentType,
            'file_path' => $path,
        ]);

        $this->reset(['documentType', 'documentFile']);
    }

    public function deleteDocument(int $documentId): void
    {
        $document = StudentDocument::findOrFail($documentId);
        Gate::authorize('delete', $document);

        Storage::disk('public')->delete($document->file_path);
        $document->delete();
    }

    public function render()
    {
        return view('livewire.students.student-edit', [
            'classes' => SchoolClass::orderBy('name')->get(),
            'sections' => $this->sectionOptions(),
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(),
            'guardians' => Guardian::orderBy('name')->get(),
            'documents' => StudentDocument::where('student_id', $this->student->id)->get(),
        ]);
    }
}
