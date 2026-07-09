<?php

namespace Tests\Feature\Settings;

use App\Livewire\Settings\SettingsIndex;
use App\Models\AcademicYear;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    protected function admin(): User
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        return $admin;
    }

    public function test_non_admin_cannot_access_settings(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $this->actingAs($teacher)->get('/settings')->assertForbidden();
    }

    public function test_admin_can_view_settings_page(): void
    {
        $this->actingAs($this->admin())->get('/settings')->assertOk();
    }

    public function test_admin_can_save_settings(): void
    {
        $year = AcademicYear::factory()->create();

        Livewire::actingAs($this->admin())
            ->test(SettingsIndex::class)
            ->set('school_name', 'Greenwood High')
            ->set('school_address', '123 Main St')
            ->set('school_phone', '555-1234')
            ->set('school_email', 'info@greenwood.test')
            ->set('current_academic_year_id', $year->id)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Greenwood High', Setting::get('school_name'));
        $this->assertSame('123 Main St', Setting::get('school_address'));
        $this->assertEquals($year->id, (int) Setting::get('current_academic_year_id'));
    }

    public function test_settings_requires_valid_academic_year(): void
    {
        Livewire::actingAs($this->admin())
            ->test(SettingsIndex::class)
            ->set('school_name', 'Greenwood High')
            ->set('current_academic_year_id', 99999)
            ->call('save')
            ->assertHasErrors(['current_academic_year_id']);
    }
}
