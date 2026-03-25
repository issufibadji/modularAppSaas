<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSideBar extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *active
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'icon',
        'style',
        'module',
        'menu_above',
        'level',
        'route',
        'acl',
        'order',
        'active',
    ];
}
