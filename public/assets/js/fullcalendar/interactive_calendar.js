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
    window.calendar = calendar;
    calendar.setOption("dateClick", function(info){window.date_click_function(info.dateStr);});
    calendar.setOption("eventClick", function(info){window.date_click_function(info.event.startStr);});
    window.load_calendar_data();

}

document.addEventListener("DOMContentLoaded", set_calendar);