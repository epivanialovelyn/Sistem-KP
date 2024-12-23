<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['start_date', 'end_date'];

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    protected $casts = [
        'start_date' => 'date',  
        'end_date' => 'date', 
    ];
}
