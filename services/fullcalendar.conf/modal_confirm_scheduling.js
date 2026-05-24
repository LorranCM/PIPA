import { show_custom_modal, close_custom_modal } from './modal_customs.js';
import { toggle_enable_close } from './modal_customs.js';
import { load_events } from '../view/interactive_calendar.js';

export function show_modal_confirm_scheduling(dateStr, props) {

    let message;

    message = 'Tem certeza que deseja confirmar o agendamento da disciplina de ' 
    + props.curricular_unit + ' no dia ' + dateStr + '?';

    show_custom_modal(
        'Confirmar Agendamento',
        message,

        [
            {
                text: 'Sim, Confirmar', 
                class: 'btn-primary',
                onClick: show_modal_confirming_event.bind(null, props.event_id)
            },

            {
                text: 'Não',
                class: 'btn-secondary',
                onClick: close_custom_modal
            }
        ]
    );
}

async function show_modal_confirming_event(event_id) {
    
    show_custom_modal(
        "Confirmando Agendamento",
        "<div class=\"loading\"></div>"
    );

    toggle_enable_close();

    const modal = document.getElementById('customModal');

    let response = await fetch(
        "services/db.reqs/confirm_event.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({event_id})
        }
    );

    const data = await response.json();
        
    if (data.success) {
        const modal_title = modal.querySelector('#modal-title');
        modal_title.textContent = "Atualizando calendario";
        
        const load_response = await load_events();
        if (load_response) {
            show_custom_modal(
                "Feito!",
                "Agendamento confirmado com sucesso.",
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
