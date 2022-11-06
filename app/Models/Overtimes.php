<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtimes extends Model
{
    use HasFactory;

    protected $table = 'overtimes';
    protected $guarded = [];

    public function employe()
    {
        return $this->belongsTo(Employees::class, 'employees_id');
    }

}
