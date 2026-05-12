import { format_event } from "./format.js";
import { student_event_click } from "./student_featured_date.js";
import { teacher_event_click } from "./teacher_featured_date.js";

// role e events sera passado pro script.js via php
// const role = "..."
// const events = [...];

export let calendar;
let dateClickfunction;

// define como o calendario se comporta dependendo do tipo de usuario
// ainda falta mudar o tratamento para caso o usuario seja o dono do calendario
if (role === "student" || role === "moderator") {
    dateClickfunction = student_event_click;
} else if (role === "teacher") {
    dateClickfunction = teacher_event_click;
} 

function set_calendar() {
    
    var calendarEl = document.getElementById('calendar');
    var formated_events = events.map(format_event);
    
    calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            height: 'auto',
            events: formated_events,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            
            dateClick(info) {
                dateClickfunction(info.dateStr);
            },
            eventClick(info) {
                dateClickfunction(info.event.startStr);
            }
        }
    );

    calendar.render();
}

document.addEventListener("DOMContentLoaded", set_calendar);