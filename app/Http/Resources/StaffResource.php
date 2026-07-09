<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_no' => $this->employee_no,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'designation' => $this->designation,
            'department' => $this->department,
            'joining_date' => $this->joining_date?->format('Y-m-d'),
        ];
    }
}
