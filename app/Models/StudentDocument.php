<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentDocument extends Model
{
    /** @use HasFactory<\Database\Factories\StudentDocumentFactory> */
    use HasFactory;

    protected $fillable = ['student_id', 'type', 'file_path'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
