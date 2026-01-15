<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['class_id', 'title', 'duration'];

    public function classRoom() {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function results() {
        return $this->hasMany(Result::class);
    }
}
