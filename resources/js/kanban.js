import Sortable from 'sortablejs';

document.addEventListener('DOMContentLoaded', function () {
    const columns = ['todo', 'in_progress', 'done', 'cancelled'];

    columns.forEach(columnId => {
        const el = document.getElementById(columnId);
        if (!el) return;

        Sortable.create(el, {
            group: 'kanban',
            animation: 150,
            ghostClass: 'opacity-30',
            dragClass: 'dragging',
            
            onEnd: function (evt) {
                const taskId = evt.item.dataset.taskId;
                const newStatus = evt.to.dataset.status;

                fetch(`/tasks/${taskId}/update-status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        evt.item.remove();
                        evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]);
                        alert('Erreur lors de la mise à jour du statut');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    evt.item.remove();
                    evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]);
                });
            }
        });
    });
});