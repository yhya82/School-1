<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    /** @use HasFactory<\Database\Factories\StaffFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'employee_no', 'designation', 'department', 'joining_date'];

    protected function casts(): array
    {
        return [
            'joining_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classSubjects(): HasMany
    {
        return $this->hasMany(ClassSubject::class, 'teacher_id');
    }

    public function staffAttendances(): HasMany
    {
        return $this->hasMany(StaffAttendance::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function markedAttendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'marked_by');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'recorded_by');
    }
}
