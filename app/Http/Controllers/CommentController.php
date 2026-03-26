<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Task $task)
    {
        $this->authorize('view', $task);
        
        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);
        
        $comment = $task->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content']
        ]);
        
        if($task->assigned_to && $task->assigned_to !== auth()->id()) {
            Notification::send(
                $task->assigned_to,
                'task_comment',
                '💬 Nouveau commentaire',
                auth()->user()->name . ' a commenté la tâche "' . $task->title . '"',
                '/tasks/' . $task->id,
                $task->id,
                'Task'
            );
        }
        
        return back()->with('success', 'Commentaire ajouté !');
    }
    
    public function destroy(Comment $comment)
    {
        if($comment->user_id !== auth()->id()) {
            abort(403);
        }
        
        $comment->delete();
        
        return back()->with('success', 'Commentaire supprimé !');
    }
}