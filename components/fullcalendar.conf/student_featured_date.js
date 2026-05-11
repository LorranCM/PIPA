import { calendar } from './interactive_calendar.js';
import { format_date } from './format.js';
import { show_custom_modal, close_custom_modal } from './modal_customs.js';

export function student_event_click(info) {
    // obtem o a data clicada e os eventos desta data
    let dateStr = info.dateStr;
    let d_events = calendar.getEvents().filter(function(event) {
        return event.startStr.substring(0, 10) === dateStr;
    });

    // caso haja algum evento no dia
    if (d_events.length > 0) {

        // obtem o primeiro evento do dia (mudar)
        const event = d_events[0];
        const props = event.extendedProps;
        const formated_date = format_date(dateStr);

        show_custom_modal(
            'Agendamento Encontrado',

            'Você tem um agendamento <strong>' +
            (props.status === 'confirmed' ? 'confirmado' : 'pendente') + // ajustar isso
            '</strong> com ' + props.teacher +
            ' para o dia ' + formated_date + '.<br><br>' +
            'Deseja acessar a sala do professor ou cancelar este agendamento?',

            [
                {
                    text: 'Ir para a sala',
                    class: 'btn-primary',
                    onClick: close_custom_modal
                },

                {
                    text: 'Cancelar agendamento',
                    class: 'btn-danger',
                    onClick: close_custom_modal
                }
            ]
        )

    } else {
        console.log("bbb");
    }
}