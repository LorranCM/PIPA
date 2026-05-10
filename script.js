    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var user_events = <?php echo $meus_agendamentos_json; ?>;

        var formated_events = user_events.map(function(ag) {
            let cor = ag.status === 'confirmed' ? '#3498db' : '#f39c12';
            let titulo = ag.status === 'confirmed' ? 'Confirmado' : 'Pendente';

            return {
                title: titulo + ' - ' + ag.professor,
                start: ag.date,
                color: cor,
                allDay: true,
                extendedProps: {
                    status: ag.status,
                    professor: ag.professor,
                    professor_id: ag.professor_id,
                    date: ag.date
                }
            };
        });

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            height: 'auto',
            events: formated_events,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            dateClick: function(info) {
                const dateStr = info.dateStr;
                const eventosDoDia = calendar.getEvents().filter(function(ev) {
                    return ev.startStr.substring(0, 10) === dateStr;
                });

                if (eventosDoDia.length > 0) {
                    // Tem eventos neste dia
                    const evento = eventosDoDia[0];
                    const props = evento.extendedProps;
                    const dataFormatada = formatDate(dateStr);

                    showCustomModal(
                        'Agendamento Encontrado',
                        'Você tem um agendamento <strong>' +
                        (props.status === 'confirmed' ? 'confirmado' : 'pendente') +
                        '</strong> com ' + props.professor +
                        ' para o dia ' + dataFormatada + '.<br><br>' +
                        'Deseja acessar a sala do professor ou cancelar este agendamento?',
                        [{
                                text: 'Ir para Sala',
                                class: 'btn-primary',
                                onClick: function() {
                                    window.location.href = 'teacher_profile.php?id=' + props
                                        .professor_id;
                                }
                            },
                            {
                                text: 'Cancelar Agendamento',
                                class: 'btn-danger',
                                onClick: function() {
                                    showCustomModal(
                                        'Confirmar Cancelamento',
                                        'Tem certeza que deseja cancelar o agendamento com ' +
                                        props.professor + ' no dia ' + dataFormatada +
                                        '?',
                                        [{
                                                text: 'Sim, Cancelar',
                                                class: 'btn-danger',
                                                onClick: function() {
                                                    // Cria um formulário dinâmico para cancelar
                                                    var form = document
                                                        .createElement('form');
                                                    form.method = 'POST';
                                                    form.action =
                                                        'teacher_profile.php?id=' +
                                                        props.professor_id;

                                                    var input = document
                                                        .createElement('input');
                                                    input.type = 'hidden';
                                                    input.name = 'cancelar_data';
                                                    input.value = props.date;

                                                    form.appendChild(input);
                                                    document.body.appendChild(form);
                                                    form.submit();
                                                }
                                            },
                                            {
                                                text: 'Não',
                                                class: 'btn-secondary',
                                                onClick: closeCustomModal
                                            }
                                        ]
                                    );
                                }
                            },
                            {
                                text: 'Fechar',
                                class: 'btn-secondary',
                                onClick: closeCustomModal
                            }
                        ]
                    );
                } else {
                    // Nenhum evento neste dia
                    showCustomModal(
                        'Nada nesta data',
                        'Não há agendamentos para o dia ' + formatDate(dateStr) + '.',
                        [{
                            text: 'OK',
                            class: 'btn-primary',
                            onClick: closeCustomModal
                        }]
                    );
                }
            }
        });
        calendar.render();
    });

    // Funções auxiliares
    function formatDate(dateStr) {
        return new Date(dateStr + 'T12:00:00').toLocaleDateString('pt-BR');
    }

    // Modal personalizado genérico
    function showCustomModal(title, message, buttons) {
        // Remove modal anterior se existir
        const existingModal = document.getElementById('customModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Cria o modal dinamicamente
        const modalHTML = `
            <div id="customModal" class="modal-overlay" style="display: flex;">
                <div class="modal-content">
                    <span class="modal-close" onclick="closeCustomModal()">&times;</span>
                    <h3>${title}</h3>
                    <p>${message}</p>
                    <div class="modal-footer">
                        ${buttons.map((btn, index) => 
                            `<button class="modal-btn ${btn.class}" id="customBtn${index}">${btn.text}</button>`
                        ).join('')}
                    </div>
                </div>
            </div>
        `;

        // Adiciona o novo modal
        document.body.insertAdjacentHTML('beforeend', modalHTML);

        // Adiciona event listeners aos botões
        buttons.forEach((btn, index) => {
            const buttonEl = document.getElementById(`customBtn${index}`);
            if (buttonEl && btn.onClick) {
                buttonEl.addEventListener('click', btn.onClick);
            }
        });
    }

    function closeCustomModal() {
        const modal = document.getElementById('customModal');
        if (modal) {
            modal.remove();
        }
    }

    // Fechar modal ao clicar fora
    window.onclick = function(e) {
        if (e.target === document.getElementById('customModal')) {
            closeCustomModal();
        }
    }

    function togglePopup() {
        var popup = document.getElementById('popup-menu');
        popup.style.display = popup.style.display === 'grid' ? 'none' : 'grid';
    }

    function abrirModal() {
        document.getElementById("modal").style.display = "flex";

        // Preenche com valores atuais
        inputNome.value = nome.innerText;
        inputSemestre.value = semestre.innerText;
        inputCurso.value = curso.innerText;
    }

    function fecharModal() {
        document.getElementById("modal").style.display = "none";
    }

    window.onclick = function(event) {
        const modal = document.getElementById("modal");

        if (event.target === modal) {
            modal.style.display = "none";
        }
    }

    function salvar() {
        nome.innerText = inputNome.value;
        semestre.innerText = inputSemestre.value;
        curso.innerText = inputCurso.value;

        fecharModal();
}