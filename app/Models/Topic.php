<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'time', 'is_done', 'done_at', 'user_id', 'audio_id'];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function audio()
    {
        return $this->belongsTo(Audio::class);
    }

    protected $casts = [
        'done_at' => 'datetime:D, j M - H:i:s T'
    ];
}
