import { show_custom_modal, close_custom_modal } from './modal_customs.js';
import { toggle_enable_close } from './modal_customs.js';

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
                onClick: show_modal_canceling_event.bind(null, props.event_id)
            },

            {
                text: 'Não',
                class: 'btn-secondary',
                onClick: close_custom_modal
            }
        ]
    );
}

async function show_modal_canceling_event(event_id) {
    
    show_custom_modal(
        "Cancelando Agendamento",
        "<div class=\"loading\"></div>"
    );

    toggle_enable_close();

    const modal = document.getElementById('customModal');

    let response = await fetch(
        baseUrl + `/requires/cancel_event/${event_id}`, {method: "POST",}
    );

    const data = await response.json();
        
    if (data.success) {
        const modal_title = modal.querySelector('#modal-title');
        modal_title.textContent = "Atualizando calendario";
        
        const load_response = await window.load_calendar_data();
        if (load_response) {
            show_custom_modal(
                "Feito!",
                "Agendamento cancelado com sucesso.",
                [
                    {
                        text: 'OK',
                        class: 'btn-primary',
                    }
                ]
            )
        }

    } else {
        console.log(data.error);
    }
}
