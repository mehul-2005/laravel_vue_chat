<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group_chat extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected  $appends = ['original_date'];
    public function getCreatedAtAttribute($value)
    {
        return date('d-m-Y h:i:s a', strtotime($value));
    }
    public function getOriginalDateAttribute()
    {
        return date('Y-m-d H:i:s', strtotime($this->created_at));
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'sender_id')->select(['name', 'id']);
    }
}
