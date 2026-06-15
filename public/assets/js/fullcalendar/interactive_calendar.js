export let calendar;

function set_calendar() {
    
    var calendarEl = document.getElementById('calendar');
    
    calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            height: 'auto',
            events: [],
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            
            dateClick: null,
            eventClick: null
        }
    );

    calendar.render(); 
    window.load_calendar_data();

}

document.addEventListener("DOMContentLoaded", set_calendar);