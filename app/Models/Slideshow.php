<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slideshow extends Model
{
    use HasFactory;
    protected $table = 'slideshows';
    protected $fillable = [
        'foto',
        'caption_title',
        'caption_content',
        'user_id'
    ];

    public function Owner()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
