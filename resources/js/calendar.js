import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek'
        },
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week: 'Semaine'
        },
        firstDay: 1,
        height: 'auto',
        events: '/api/calendar/events', // On va créer cette route
        eventClick: function(info) {
            // Remplir le formulaire avec les données de l'événement
            const event = info.event;
            document.getElementById('event_id').value = event.id;
            document.getElementById('event_title').value = event.title;
            document.getElementById('event_type').value = event.extendedProps.type;
            document.getElementById('event_start').value = event.startStr.split('T')[0];
            
            if(event.extendedProps.type === 'project') {
                document.getElementById('event_end_container').style.display = 'block';
                document.getElementById('event_end').value = event.endStr ? event.endStr.split('T')[0] : '';
            } else {
                document.getElementById('event_end_container').style.display = 'none';
            }
            
            document.getElementById('event_color').value = event.backgroundColor;
            document.getElementById('form_mode').value = 'edit';
            document.querySelector('button[type="submit"]').textContent = '✏️ Modifier';
            document.getElementById('delete_btn').style.display = 'block';
        },
        dateClick: function(info) {
            // Préremplir la date cliquée
            document.getElementById('event_start').value = info.dateStr;
            resetForm();
        }
    });

    calendar.render();

    // Gestion du type d'événement
    document.getElementById('event_type').addEventListener('change', function(e) {
        const endContainer = document.getElementById('event_end_container');
        if(e.target.value === 'project') {
            endContainer.style.display = 'block';
        } else {
            endContainer.style.display = 'none';
        }
    });

    // Réinitialiser le formulaire
    window.resetForm = function() {
        document.getElementById('calendar_form').reset();
        document.getElementById('event_id').value = '';
        document.getElementById('form_mode').value = 'create';
        document.querySelector('button[type="submit"]').textContent = '➕ Ajouter';
        document.getElementById('delete_btn').style.display = 'none';
        document.getElementById('event_end_container').style.display = 'none';
    };

    // Supprimer un événement
    window.deleteEvent = function() {
        if(!confirm('Supprimer cet événement ?')) return;
        
        const eventId = document.getElementById('event_id').value;
        
        fetch(`/calendar/events/${eventId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                calendar.refetchEvents();
                resetForm();
                alert('Événement supprimé !');
            }
        });
    };
});