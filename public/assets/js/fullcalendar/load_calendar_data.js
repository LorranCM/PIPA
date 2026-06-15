import { format_event } from './format.js';
import { set_available_dates } from './format.js';
import { calendar } from './interactive_calendar.js';

export async function get_self_calendar() {
    const response = await fetch(baseUrl + '/requires/get_calendar_data', {method: 'POST'});
    const data = await response.json();
    const events = data.events;
    const uid = data.uid;
    const formated_events = events.map(event => format_event(event, uid));
    calendar.setOption('events', formated_events);
    return true;
}

export async function get_classroom_calendar() {
    
}
