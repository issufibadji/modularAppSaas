<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as BaseRole;
use OwenIt\Auditing\Contracts\Auditable;

class Role extends BaseRole implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use HasFactory;
    protected $fillable = [
        'name',
        'guard_name',
    ];
}
