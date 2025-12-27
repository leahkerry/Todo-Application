<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'completed', 'due_date', 'user_id'];
    protected $casts = [
        'completed' => 'boolean',
    ];
    // Define the relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}