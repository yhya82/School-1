<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\StudentDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentDocument>
 */
class StudentDocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'type' => $this->faker->randomElement(['birth_certificate', 'medical_record', 'id_photo']),
            'file_path' => 'documents/'.$this->faker->uuid().'.pdf',
        ];
    }
}
