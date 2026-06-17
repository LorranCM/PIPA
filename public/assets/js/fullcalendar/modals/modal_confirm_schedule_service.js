import { show_custom_modal, close_custom_modal } from './modal_customs.js';
import { toggle_enable_close } from './modal_customs.js';

export function show_modal_confirm_schedule_event(dateStr, formated_date, props) {
    show_custom_modal(
        'Confirmar Agendamento',
        'Tem certeza que deseja agendar um atendimento com o(a) professor(a) ' + 
        props.teacher + ' no dia ' + formated_date + '?',

        [
            {
                text: 'Sim, Agendar', 
                class: 'btn-warning',
                onClick: show_modal_scheduling_event.bind(null, dateStr, props.classroom_id)
            },

            {
                text: 'Não',
                class: 'btn-secondary',
                onClick: close_custom_modal
            }
        ]
    );
}

async function show_modal_scheduling_event(dateStr, classroom_id) {
    
    show_custom_modal(
        "Marcando atendimento",
        "<div class=\"loading\"></div>"
    );

    toggle_enable_close();

    const modal = document.getElementById('customModal');

    let response = await fetch(
        baseUrl + `/requires/schedule_event/${classroom_id}/${dateStr}`, {method: "POST",}
    );

    const data = await response.json();
        
    if (data.success) {
        const modal_title = modal.querySelector('#modal-title');
        modal_title.textContent = "Atualizando calendario";

        const params = new URLSearchParams(window.location.search);
        const load_response = await window.load_calendar_data();
        if (load_response) {
            show_custom_modal(
                "Feito!",
                "Atendimento feito com sucesso, aguarde a confirmação de seu professor.",
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
