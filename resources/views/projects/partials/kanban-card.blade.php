<div data-task-id="{{ $task->id }}" 
     data-epic-id="{{ $task->epic_id ?? '' }}"
     class="kanban-card group" 
     style="--card-accent: {{ $task->epic ? $task->epic->color : '#6b7280' }};">
    
    @if($task->epic)
        <div class="mb-2">
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-bold" 
                  style="background: {{ $task->epic->color }}20; color: {{ $task->epic->color }}; border: 1px solid {{ $task->epic->color }};">
                <span class="w-2 h-2 rounded-full" style="background: {{ $task->epic->color }};"></span>
                {{ $task->epic->name }}
            </span>
        </div>
    @endif
    
    <div class="flex justify-between items-start mb-2">
        <a href="{{ route('tasks.show', $task) }}" class="kanban-card-title flex-1">
            {{ $task->title }}
        </a>
        
        <!-- Bouton Modifier (apparaît au hover) -->
        <a href="{{ route('tasks.edit', $task) }}" 
           class="ml-2 opacity-0 group-hover:opacity-100 transition-opacity bg-blue-500 hover:bg-blue-700 text-white rounded px-2 py-1 text-xs font-bold"
           onclick="event.stopPropagation();">
            ✏️
        </a>
    </div>
    
    @if($task->description)
        <p class="kanban-card-description">
            {{ Str::limit($task->description, 80) }}
        </p>
    @endif
    
    <div class="kanban-card-meta">
        <div class="kanban-card-meta-row">
            <span class="priority-badge priority-{{ $task->priority }}">
                {{ $task->priority }}
            </span>
        </div>
        <div class="flex justify-between">
        @if($task->due_date)
            <div class="kanban-card-meta-row">
                <span class="due-date {{ \Carbon\Carbon::parse($task->due_date)->isPast() ? 'overdue' : '' }}">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                    {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                </span>
            </div>
        @endif
            <span class="assigned-user">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                {{ $task->assignedUser->name ?? 'Non assigné' }}
            </span>
        </div>        
    </div>
</div>