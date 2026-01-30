<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Task extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'task_id';
    public $incrementing = true;

    protected $fillable = [
        'title',
        'description',
        'status',
        'deadline',
        'user_id',
        'created_by'
    ];

    protected $casts = [
        'dateline' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}