<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $admin = User::factory()->create([
            'name' => 'School Admin',
            'email' => 'admin@school.test',
        ]);
        $admin->assignRole('admin');

        Staff::create([
            'user_id' => $admin->id,
            'employee_no' => 'EMP-00001',
            'designation' => 'Administrator',
            'department' => 'Administration',
            'joining_date' => now(),
        ]);

        AcademicYear::factory()->create([
            'name' => now()->format('Y').'/'.now()->addYear()->format('Y'),
            'start_date' => now()->startOfYear(),
            'end_date' => now()->endOfYear(),
        ]);

        $this->call(DemoDataSeeder::class);
    }
}
