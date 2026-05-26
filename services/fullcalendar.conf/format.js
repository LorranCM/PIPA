// funcao que recolhe os dados do calendario do usuario e formata para o formato do fullcalendar
export function format_event(event, uid) {
    
    let event_color;
    let event_title;

    let is_participant = false;
    if (event.participants.includes(uid) || event["teacher-id"] == uid) is_participant = true;

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
            is_participant: is_participant,
            status: event_title,
            teacher: event.teacher,
            event_id: event['event-id'],
            classroom_id: event['classroom-id'],
            curricular_unit: event['curricular-unit'],
            available : false
        }
    };
};

export function set_available_dates(availability, teacher_name, classroom_id, formated_events) {
    const result = [...formated_events];

    const weekDays = {
        sunday: 0,
        monday: 1,
        tuesday: 2,
        wednesday: 3,
        thursday: 4,
        friday: 5,
        saturday: 6
    };

    const today = new Date();

    for (let i = 0; i < 30; i++) {
        const currentDate = new Date();
        currentDate.setDate(today.getDate() + i);

        const currentWeekDay = currentDate.getDay();

        for (const [dayName] of Object.entries(availability)) {
            if (weekDays[dayName.toLowerCase()] !== currentWeekDay) {
                continue;
            }

            const dateStr = currentDate.toLocaleDateString("en-CA");

            // procura evento existente neste dia
            const existingEvent = result.find(event => {
                const eventDate =
                    typeof event.start === "string"
                        ? event.start.split("T")[0]
                        : event.start.toLocaleDateString("en-CA");
                return eventDate ===  dateStr;
            });

            if (!existingEvent) {
                result.push({
                    title: "Disponível",
                    start: dateStr,
                    allDay: true,
                    color: "#17811c",
                    extendedProps: {
                        available: true,
                        teacher: teacher_name,
                        classroom_id: classroom_id
                    }
                });
            }
        }
    }

    return result;
}

// (as datas no bd estao no formato yyyy-mm-dd)
export function format_date(dateStr) {
    return new Date(dateStr + 'T12:00:00').toLocaleDateString('pt-BR');
}