import { calendar } from "../view/interactive_calendar.js";
import { format_event } from "./format.js";
import { set_available_dates } from "./format.js";

export async function load_calendar_data(classroom_id = null) {
    const response = await fetch("services/db.reqs/get_calendar_data.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({classroom_id})
        }
    );
    const data = await response.json();
    const events = data.events;
    const uid = data.uid;
    const formated_events = events.map(event => format_event(event, uid));
    let events_with_availability = formated_events;
    if (classroom_id !== null) {
        events_with_availability = set_available_dates(
            data["teacher-availability"], 
            data["tenured-teacher-name"], 
            classroom_id,
            formated_events
        );
    }
    calendar.setOption('events', events_with_availability);
    return true;
}