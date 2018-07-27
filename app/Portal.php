<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Portal extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'portal_id', 'portal_name', 'portal_slug',
    ];
}
