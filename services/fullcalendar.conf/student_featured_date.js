import { calendar } from './interactive_calendar.js';
import { format_date } from './format.js';
import { show_modal_non_featured_date_click } from './modal_non_featured_date.js';
import { show_custom_modal } from './modal_customs.js';
import { show_modal_confirm_cancel_event } from './modal_cancel_scheduling.js';

export function student_event_click(info) {
    // obtem o a data clicada e os eventos desta data
    let dateStr = info;
    let formated_date = format_date(dateStr);
    let d_events = calendar.getEvents().filter(function(event) {
        return event.startStr.substring(0, 10) === dateStr;
    });

    // caso haja algum evento no dia
    if (d_events.length > 0) {

        // obtem o primeiro evento do dia (mudar)
        const event = d_events[0];
        const props = event.extendedProps;

        show_custom_modal(
            'Agendamento Encontrado',

            'Você tem um agendamento <strong>' + props.status +
            '</strong> com ' + props.teacher +
            ' para o dia ' + formated_date + '.<br><br>' +
            'Deseja acessar a sala do professor ou cancelar este agendamento?',

            [
                {
                    text: 'Ir para a sala',
                    class: 'btn-primary',
                    onClick: null
                },

                {
                    text: 'Cancelar agendamento',
                    class: 'btn-danger',
                    onClick: show_modal_confirm_cancel_event.bind(null, "student", formated_date, props)
                }
            ]
        )

    } else {
        show_modal_non_featured_date_click(formated_date);
    }
}