<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

use App\Traits\Uuid;

class AppConfig extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use Uuid;

    protected $fillable = [
        'key',
        'value',
        'description',
        'path_archive',
        'extension',
        'required',
    ];
}