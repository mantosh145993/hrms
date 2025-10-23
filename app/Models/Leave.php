<?php
namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'days',
        'type',
        'reason',
        'status',
        'is_paid_leave'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::saving(function ($leave) {
            if ($leave->start_date && $leave->end_date) {
                $start = Carbon::parse($leave->start_date);
                $end = Carbon::parse($leave->end_date);
                $leave->days = $start->diffInDays($end) + 1;
            }
        });
    }

    public function LeaveType(){
        return $this->belongsTo(LeaveType::class,'type','id');
    }
}
