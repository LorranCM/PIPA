// funcao que recolhe os dados do calendario do usuario e formata para o formato do fullcalendar
export function format_event(event) {
    
    let event_color;
    let event_title;

    // titulo e cor do evento de acordo com o status da data
    switch (event['status']) {
        case "pending":
            event_color = '#f39c12';
            event_title = 'Pendente';
            break;
        case "confirmed":
            event_color = '#3498db';
            event_title = 'Confirmado';
            break;
        default:
            event_color = '#510a5a';
            event_title = 'Desconhecido';
    }

    return {
        title: event_title + ' - ' + event.teacher,
        start: event.date,
        color: event_color,
        allDay: true,
        extendedProps: {
            status: event_title,
            teacher: event.teacher,
            event_id: event['event-id'],
            classroom_id: event['classroom-id'],
            curricular_unit: event['curricular-unit'],
        }
    };
};

// (as datas no bd estao no formato yyyy-mm-dd)
export function format_date(dateStr) {
    return new Date(dateStr + 'T12:00:00').toLocaleDateString('pt-BR');
}