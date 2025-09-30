<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table="feedbacks";
    protected $fillable = [
        'employee_id',
        'title',
        'message',
        'type',
        'given_by',
    ];
    

    // Relationships
    public function employee()
    {
        return $this->belongsTo(User::class);
    }

    public function givenBy()
    {
        return $this->belongsTo(User::class, 'given_by');
    }
}
