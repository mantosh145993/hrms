<?php

namespace App\Models;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'role', 'password', 'doj', 'doc','dob','designation','duration'];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'user_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function todayAttendance()
    {
        return $this->hasOne(Attendance::class)->whereDate('work_date', now()->toDateString());
    }

    public function user()
    {
        return $this->belongsTo(Feedback::class);
    }

    protected static function booted()
    {
        static::saving(function ($user) {
            if ($user->doj && $user->doc) {
                $doj = \Carbon\Carbon::parse($user->doj);
                $doc = \Carbon\Carbon::parse($user->doc);

                $years = $doj->diffInYears($doc);
                $months = $doj->diffInMonths($doc) - ($years * 12);

                $duration = '';
                if ($years > 0) {
                    $duration .= $years . ' Year' . ($years > 1 ? 's ' : ' ');
                }
                if ($months > 0) {
                    $duration .= $months . ' Month' . ($months > 1 ? 's' : '');
                }

                $user->duration = $duration ?: 'Less than 1 Month';
            }
        });
    }
}
