<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use JWTAuth;

class media extends Model
{
    public function folder(){
        return $this->belongsTo(folder::class);
    }
}
