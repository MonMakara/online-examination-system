<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'teacher_id', 'logo'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'student_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'class_id');
    }
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return str_starts_with($this->logo, 'http')
                ? $this->logo
                : asset('storage/' . $this->logo);
        }

        return null;
    }
}
