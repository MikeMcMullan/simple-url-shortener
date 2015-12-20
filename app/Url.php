<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = ['token', 'url', 'count'];

    public function incrementVisits()
    {
        $this->increment('visits');
    }
}