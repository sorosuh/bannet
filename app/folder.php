<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class folder extends Model
{
    public function media(){
        return $this->hasMany(media::class);
    }
}
