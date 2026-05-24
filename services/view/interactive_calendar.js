import { student_event_click } from "../fullcalendar.conf/student_featured_date.js";
import { teacher_event_click } from "../fullcalendar.conf/teacher_featured_date.js";
import { load_calendar_data } from "../fullcalendar.conf/load_calendar_data.js";

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

    
    setDateclickfunc();
    
}

function setDateclickfunc() {
    const params = new URLSearchParams(window.location.search);
    const page = window.location.pathname
                    .split("/")
                    .pop()
                    .replace(".php", "");
                    
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
    
                calendar.setOption("dateClick", function(info){dateClickfunction(info.dateStr);});
                calendar.setOption("eventClick", function(info){dateClickfunction(info.event.startStr);});
            }
        )

    if (params.get("id") === null) {
        if (page === "Home") {
            load_calendar_data();

        } else if (page === "Classroom") {
            window.location.href = "index.php";
        }
        
    } else {
        if (page == "Classroom") {
            load_calendar_data(params.get("id"));
        }
    };
    
}

document.addEventListener("DOMContentLoaded", set_calendar);