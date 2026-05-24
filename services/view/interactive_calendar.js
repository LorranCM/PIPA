import { student_event_click } from "../fullcalendar.conf/student_featured_date.js";
import { teacher_event_click } from "../fullcalendar.conf/teacher_featured_date.js";
import { format_event } from "../fullcalendar.conf/format.js";

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

    fetch("services/db.reqs/get_role.php")
        .then(response => response.json())
        .then(data => {
                let role = data.role;
                let dateClickfunction;
    
                if (role === "student") {
                    dateClickfunction = student_event_click;
                } 
                else if (role === "teacher") {
                    dateClickfunction = teacher_event_click;
                } 
    
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

}

document.addEventListener("DOMContentLoaded", set_calendar);

export async function load_events() {
    const response = await fetch("services/db.reqs/get_calendar_data.php");
    const data = await response.json();

    const events = data.events;
    const formated_events = events.map(format_event);
    calendar.setOption('events', formated_events);

    return true;
}