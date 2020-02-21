<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'slides';

    public function getActive()
    {
        return $this->orderBy('weight')
            ->published()
            ->get();
    }

    public function scopePublished($query)
    {
        $query->where(['active' => 1]);
    }
}
