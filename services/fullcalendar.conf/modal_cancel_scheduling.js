import { show_custom_modal, close_custom_modal } from './modal_customs.js';

export function show_modal_cancel_event(role, dateStr, props) {

    let message;

    if (role === "student") {
        message = 'Tem certeza que deseja cancelar o agendamento com o(a) professor(a) ' +
        props.teacher + ' da disciplina de ' + props.curricular_unit + ' no dia ' + dateStr + '?';
    } else if (role === "teacher") {
        message = 'Tem certeza que deseja cancelar o agendamento da disciplina ' +
        param + ' no dia ' + dateStr + '?';
    }

    show_custom_modal(
        'Confirmar Cancelamento',
        message,

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