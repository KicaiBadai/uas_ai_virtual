<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminChatHistory extends Model
{
    use HasFactory;

    protected $table = 'admin_chat_histories';

    protected $fillable = [
        'admin_id',
        'messages',
    ];

    protected $casts = [
        'messages' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
?>
