<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    /** @use HasFactory<\Database\Factories\BranchFactory> */
    use HasFactory;

    public function departments()
{
    return $this->hasMany(Department::class);
}

public function shifts()
{
    return $this->hasMany(Shift::class);
}
}
