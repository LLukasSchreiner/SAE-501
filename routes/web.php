<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\EpicController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DeadlineController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Projects
    Route::resource('projects', ProjectController::class);

    //SideBar
    Route::get('/projects/{project}/roadmap', [ProjectController::class, 'roadmap'])->name('projects.roadmap');
    Route::get('/projects/{project}/notes', [ProjectController::class, 'notes'])->name('projects.notes');
    Route::patch('/projects/{project}/update-color', [ProjectController::class, 'updateColor']);
    
    // Tasks
    Route::resource('projects.tasks', TaskController::class)->shallow();
    Route::patch('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    
    // Sprints
    Route::resource('projects.sprints', SprintController::class)->shallow();
    
    // Epics
    Route::resource('projects.epics', EpicController::class)->shallow();
    
    // Courses
    Route::resource('courses', CourseController::class);

    // Membres du projet
    Route::post('/projects/{project}/members', [ProjectController::class, 'addMember'])->name('projects.members.add');
    Route::delete('/projects/{project}/members/{user}', [ProjectController::class, 'removeMember'])->name('projects.members.remove');
    Route::patch('/projects/{project}/members/{user}/role', [ProjectController::class, 'updateMemberRole'])->name('projects.members.updateRole');
    
    // Calendar (remplace deadlines)
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/calendar', [App\Http\Controllers\CalendarController::class, 'index'])->name('calendar.index');
        Route::get('/api/calendar/events', [App\Http\Controllers\CalendarController::class, 'getEvents']);
        Route::post('/calendar/events', [App\Http\Controllers\CalendarController::class, 'store'])->name('calendar.store');
        Route::delete('/calendar/events/{id}', [App\Http\Controllers\CalendarController::class, 'destroy']);
    });
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Invitations
    Route::post('/projects/{project}/invite', [ProjectController::class, 'inviteMember'])->name('projects.invite');
    Route::get('/invitations', [ProjectController::class, 'invitations'])->name('invitations.index');
    Route::post('/invitations/{invitation}/accept', [ProjectController::class, 'acceptInvitation'])->name('invitations.accept');
    Route::post('/invitations/{invitation}/decline', [ProjectController::class, 'declineInvitation'])->name('invitations.decline');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    Route::get('/projects/{project}/reporting', [ProjectController::class, 'reporting'])->name('projects.reporting');

    // Commentaires
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

require __DIR__.'/auth.php';