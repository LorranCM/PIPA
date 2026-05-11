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
        case "canceled":
            event_color = '#e74c3c';
            event_title = 'Cancelado';
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
            status: event.status,
            teacher: event.teacher,
            teacher_id: event['teacher-id'],
            date: event.date
        }
    };
};

// nao sei o que e isso alvaro me explica depois por favor
// (as datas no bd estao no formato yyyy-mm-dd)
export function format_date(dateStr) {
    return new Date(dateStr + 'T12:00:00').toLocaleDateString('pt-BR');
}