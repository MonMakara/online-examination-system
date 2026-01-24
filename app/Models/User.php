<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_image',
        'otp',
        'otp_expires_at',
        'email_verified',
        'google_id',
    ];

    public function managedClasses()
    {
        return $this->hasMany(ClassRoom::class, 'teacher_id');
    }

    public function enrolledClasses()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_student', 'student_id', 'class_id')->withTimestamps();
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'email_verified' => 'boolean',
    ];
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return str_starts_with($this->profile_image, 'http')
                ? $this->profile_image
                : asset('storage/' . $this->profile_image);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&bg=4f46e5&color=fff';
    }
}
