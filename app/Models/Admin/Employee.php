<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    
    public function tasks()
    {
        return $this->belongsToMany(\App\Models\Admin\Task::class);
    }
}
