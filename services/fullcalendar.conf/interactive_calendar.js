import { student_event_click } from "./student_featured_date.js";
import { format_event } from "./format.js";
// import { teacher_event_click } from "./teacher_featured_date.js";

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
load_events();          

}

document.addEventListener("DOMContentLoaded", set_calendar);

fetch("services/db.reqs/get_role.php")
    .then(response => response.json())
    .then(data => {
            let role = data.role;
            let dateClickfunction;

            if (role === "student") {
                dateClickfunction = student_event_click;
            } 
            // else if (role === "teacher") {
            //     dateClickfunction = teacher_event_click;
            // } 

            calendar.setOption("dateClick", function(info){
                    dateClickfunction(info.dateStr);
                }   
            );
            calendar.setOption("eventClick", function(info){
                    dateClickfunction(info.event.startStr);
                }   
            );
        }
    )


async function load_events() {
    
    console.log("inicio");

    fetch("services/db.reqs/get_calendar_data.php")
        .then(response => response.json())
        .then(data => {
            const events = data.events;
            const formated_events = format_event(events);
            console.log(events);
            calendar.setOption('events', formated_events);
        }
    )

}