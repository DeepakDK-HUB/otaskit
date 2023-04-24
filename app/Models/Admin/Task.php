<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    
    public function employee() {
        return $this->belongsTo(\App\Models\Admin\Employee::class,'employee_id');
    }
}
