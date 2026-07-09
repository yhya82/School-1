<?php

namespace App\Livewire\Settings;

use App\Models\AcademicYear;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SettingsIndex extends Component
{
    public string $school_name = '';

    public string $school_address = '';

    public string $school_phone = '';

    public string $school_email = '';

    public ?int $current_academic_year_id = null;

    public function mount(): void
    {
        abort_unless(Auth::user()->hasRole('admin'), 403);

        $this->school_name = Setting::get('school_name', config('app.name'));
        $this->school_address = Setting::get('school_address', '');
        $this->school_phone = Setting::get('school_phone', '');
        $this->school_email = Setting::get('school_email', '');
        $this->current_academic_year_id = Setting::get('current_academic_year_id')
            ? (int) Setting::get('current_academic_year_id')
            : null;
    }

    protected function rules(): array
    {
        return [
            'school_name' => 'required|string|max:150',
            'school_address' => 'nullable|string|max:255',
            'school_phone' => 'nullable|string|max:20',
            'school_email' => 'nullable|email|max:150',
            'current_academic_year_id' => 'nullable|exists:academic_years,id',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        session()->flash('status', __('Settings saved.'));
    }

    public function render()
    {
        return view('livewire.settings.settings-index', [
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(),
        ]);
    }
}
