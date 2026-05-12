import { calendar } from './interactive_calendar.js';
import { format_date } from './format.js';
import { non_featured_date_click } from './modal_non_featured_date.js';
import { show_custom_modal, close_custom_modal } from './modal_customs.js';

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

        switch (props.status) {
            case 'confirmed':
                status = 'confirmado';
                break;
            case 'pending':
                status = 'pendente';
                break;
            case 'canceled':
                status = 'cancelado';
                break;
            default:
                status = 'desconhecido';
        }

        show_custom_modal(
            'Agendamento Encontrado',

            'Você tem um agendamento <strong>' + status +
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
                    onClick: cancel_event.bind(null, props.teacher, formated_date)
                }
            ]
        )

    } else {
        non_featured_date_click(formated_date);
    }
}

function cancel_event(teacher_name, dateStr) {
    show_custom_modal(
        'Confirmar Cancelamento',

        'Tem certeza que deseja cancelar o agendamento com ' +
        teacher_name + ' no dia ' + dateStr +
        '?',

        [
            {
                text: 'Sim, Cancelar', 
                class: 'btn-danger',
                onClick: null
            },
            {
                text: 'Não',
                class: 'btn-secondary',
                onClick: close_custom_modal
            }
        ]
    );
}