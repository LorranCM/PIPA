import { calendar } from "../view/interactive_calendar.js";
import { format_event } from "./format.js";

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
    calendar.setOption('events', formated_events);
    return true;
}