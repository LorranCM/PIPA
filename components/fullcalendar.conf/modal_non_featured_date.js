import { show_custom_modal, close_custom_modal } from "./modal_customs.js";

export function non_featured_date_click(dateStr) {
    show_custom_modal(
        'Nada nesta data',
        'Não há agendamentos para o dia ' + dateStr + '.',
        [{
            text: 'OK',
            class: 'btn-primary',
            onClick: close_custom_modal
        }]
    );
}