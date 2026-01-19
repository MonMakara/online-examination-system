<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['class_id', 'title', 'duration', 'due_at', 'closed_at'];

    protected $casts = [
        'due_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function isOpen()
    {
        if (! $this->closed_at) {
            return true;
        }

        return now()->lt($this->closed_at);
    }

    public function getStatusAttribute()
    {
        // Optimization: Use the loaded count if available to avoid N+1 queries
        if (isset($this->attributes['questions_count'])) {
            return $this->attributes['questions_count'] > 0 ? 'published' : 'draft';
        }

        return $this->questions()->exists() ? 'published' : 'draft';
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
