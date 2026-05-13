import { show_custom_modal, close_custom_modal } from './modal_customs.js';
import {  toggle_enable_close } from './modal_customs.js';

export function show_modal_confirm_cancel_event(role, dateStr, props) {

    let message;

    if (role === "student") {
        message = 'Tem certeza que deseja cancelar o agendamento com o(a) professor(a) ' +
        props.teacher + ' da disciplina de ' + props.curricular_unit + ' no dia ' + dateStr + '?';
        
    } else if (role === "teacher") {
        message = 'Tem certeza que deseja cancelar o agendamento da disciplina de ' +
        props.curricular_unit + ' no dia ' + dateStr + '?';
    }

    show_custom_modal(
        'Confirmar Cancelamento',
        message,

        [
            {
                text: 'Sim, Cancelar', 
                class: 'btn-danger',
                onClick: show_modal_cancel_event
            },

            {
                text: 'Não',
                class: 'btn-secondary',
                onClick: close_custom_modal
            }
        ]
    );
}

export function show_modal_cancel_event() {

    toggle_enable_close();

    show_custom_modal(
        "Cancelando Agendamento",
        "<div class=\"loading\"></div>"
    );

    fetch("services/db.reqs/cancel_event.php")
        .then(response => response.json())
        .then(data => {
                console.log(data.ok);
            }
        );

}