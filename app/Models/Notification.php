<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'action_url',
        'related_id',
        'related_type',
        'read'
    ];

    protected $casts = [
        'read' => 'boolean',
        'created_at' => 'datetime'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Marquer comme lue
    public function markAsRead()
    {
        $this->update(['read' => true]);
    }

    // Créer une notification
    public static function send($userId, $type, $title, $message, $actionUrl = null, $relatedId = null, $relatedType = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl,
            'related_id' => $relatedId,
            'related_type' => $relatedType
        ]);
    }
}