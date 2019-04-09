<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShortUrls extends Model
{
    protected $fillable = ['short_url', 'long_url'];
    protected $appends = ['short'];
    protected $visible = ['id', 'short', 'long_url'];


    public $timestamps = false;

    /**
     * Return full url
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getShortAttribute()
    {
        return url("/resolve/{$this->short_url}");
    }
}
