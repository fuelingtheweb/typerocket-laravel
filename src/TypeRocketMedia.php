<?php

namespace TypeRocket;

use Illuminate\Database\Eloquent\Model;

class TypeRocketMedia extends Model implements MediaProvider
{
    protected $table = 'tr_media';
    protected $fillable = ['sizes', 'meta'];

    protected $casts = [
        'sizes' => 'array',
        'meta' => 'array',
    ];

    public function getThumbSrc()
    {
        if(empty($this->sizes['local']['thumb'])) {
            return '';
        }
        
        return $this->sizes['local']['thumb'];
    }
}
