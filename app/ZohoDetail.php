<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ZohoDetail extends Model
{
	protected $fillable = [
        'access_token', 'refresh_token', 'gen_time'
    ];
}
