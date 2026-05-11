import { format_event } from "./format.js";
import { student_event_click } from "./student_featured_date.js";
import { teacher_event_click } from "./teacher_featured_date.js";

// role e events sera passado pro script.js via php
// const role = "..."
// const events = [...];

let dateClickfunction;

if (role === "student" || role === "moderator") {
    dateClickfunction = student_event_click;
} else if (role === "teacher") {
    dateClickfunction = teacher_event_click;
} 

function set_calendar() {
    
    var calendarEl = document.getElementById('calendar');
    var formated_events = events.map(format_event);
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            height: 'auto',
            events: formated_events,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            
            dateClick: dateClickfunction
        }
    );

    calendar.render();
}

document.addEventListener("DOMContentLoaded", set_calendar);