<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'epic_id',
        'sprint_id',
        'title',
        'description',
        'assigned_to',
        'status',
        'priority',
        'due_date',
        'order',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function epic()
    {
        return $this->belongsTo(Epic::class);
    }

    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_task')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}