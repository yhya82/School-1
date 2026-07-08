<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimetableSlot extends Model
{
    /** @use HasFactory<\Database\Factories\TimetableSlotFactory> */
    use HasFactory;

    protected $fillable = ['section_id', 'class_subject_id', 'day_of_week', 'start_time', 'end_time', 'room'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function classSubject(): BelongsTo
    {
        return $this->belongsTo(ClassSubject::class);
    }

    protected function dayName(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn () => ['', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'][$this->day_of_week] ?? (string) $this->day_of_week,
        );
    }
}
