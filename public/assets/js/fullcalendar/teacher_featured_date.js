import { calendar } from './interactive_calendar.js';
import { format_date } from './format.js';
import { show_custom_modal, close_custom_modal } from './modals/modal_customs.js';
import { show_modal_non_featured_date_click } from './modals/modal_non_featured_date.js';
import { show_modal_confirm_cancel_event } from './modals/modal_cancel_scheduling.js';
import { show_modal_confirm_scheduling } from './modals/modal_confirm_scheduling.js';

export function teacher_event_click(info) {
    // obtem o a data clicada e os eventos desta data
    let dateStr = info;
    let formated_date = format_date(dateStr);
    let d_events = calendar.getEvents().filter(function(event) {
        return event.startStr.substring(0, 10) === dateStr;
    });

    // caso haja algum evento no dia
    if (d_events.length > 0) {

        // obtem o primeiro evento do dia (mudar)
        const event = d_events[0];
        const props = event.extendedProps;

        if (props.is_participant) {
            let message =
                'Você tem um agendamento <strong>' + props.status +'</strong>'+
                ' para o dia ' + formated_date + '.<br><br>' +
                'Deseja acessar a sala da disciplina de ' + props.curricular_unit;
                
            let buttons = [
                {
                    text: 'Ir para a sala',
                    class: 'btn-primary',
                    onClick: function () {
                        window.location.href = baseUrl + `/my/search/classroom/${props.classroom_id}`
                    }
                },
    
                {
                    text: 'Cancelar agendamento',
                    class: 'btn-danger',
                    onClick: show_modal_confirm_cancel_event.bind(null, "teacher", formated_date, props)
                }
            ]
    
            if (props.status === "Pendente") {
                message += ", cancelar ou confirmar este agendamento?"
    
                buttons.push({
                    text: 'Confirmar agendamento',
                    class: 'btn-warning',
                    onClick: show_modal_confirm_scheduling.bind(null, formated_date, props)
                })
            } else {
                message += " ou cancelar este agendamento?"
            }
    
            show_custom_modal(
                'Agendamento Encontrado',
                message,
                buttons
            )
            
        } else if(props.available) {
            show_custom_modal(
                'Disponível',

                'essa data está disponível para agendamento.<br><br>',

                [
                    {
                        text: 'Ok',
                        class: 'btn-primary',
                        onClick: close_custom_modal
                    }
                ]
            )
 
        } else {
            show_custom_modal(
                'Agendamento Encontrado',

                'Há um agendamento <strong>' + props.status +
                '</strong> com ' + props.teacher +
                ' para o dia ' + formated_date,

                [
                    {
                        text: 'Ok',
                        class: 'btn-primary',
                        onClick: close_custom_modal
                    }
                ]
            )
        }


    } else {
        show_modal_non_featured_date_click(formated_date);
    }
}