<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'inviter_id',
        'invitee_id',
        'role',
        'status',
        'token',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    // Générer un token unique
    public static function generateToken()
    {
        return Str::random(32);
    }

    // Relations
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function invitee()
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }

    // Vérifier si l'invitation est encore valide
    public function isValid()
    {
        return $this->status === 'pending' && 
               (!$this->expires_at || $this->expires_at->isFuture());
    }
}