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
                onClick: show_modal_cancel_event.bind(null, props.event_id)
            },

            {
                text: 'Não',
                class: 'btn-secondary',
                onClick: close_custom_modal
            }
        ]
    );
}

export function show_modal_cancel_event(event_id) {

    toggle_enable_close();

    show_custom_modal(
        "Cancelando Agendamento",
        "<div class=\"loading\"></div>"
    );

    fetch(
        "services/db.reqs/cancel_event.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({event_id})
        }
    )
        .then(response => response.json())
        .then(data => {
                if (data.success) {
                    toggle_enable_close();
                    const modal_title = document.getElementById('modal-title');
                    modal_title.textContent = "foi";

                } else {
                    console.log(data.error);
                }
            }
        );

}