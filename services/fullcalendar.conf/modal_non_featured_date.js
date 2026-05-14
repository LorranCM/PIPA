import { show_custom_modal } from "./modal_customs.js";

export function show_modal_non_featured_date_click(dateStr) {
    show_custom_modal(
        'Nada nesta data',
        'Não há agendamentos para o dia ' + dateStr + '.',
        [{
            text: 'OK',
            class: 'btn-primary',
        }]
    );
}