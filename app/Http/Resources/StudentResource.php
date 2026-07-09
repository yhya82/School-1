<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'admission_no' => $this->admission_no,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'class' => $this->schoolClass?->name,
            'section' => $this->section?->name,
            'academic_year' => $this->academicYear?->name,
            'dob' => $this->dob?->format('Y-m-d'),
            'gender' => $this->gender,
            'admission_date' => $this->admission_date?->format('Y-m-d'),
            'status' => $this->status,
        ];
    }
}
