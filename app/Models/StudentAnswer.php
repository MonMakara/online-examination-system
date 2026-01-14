<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'exam_id', 'questio_id', 'selected_option'];

    public function student() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function exam() {
        return $this->belongsTo(Exam::class);
    }

    public function question() {
        return $this->belongsTo(Question::class);
    }
}
