async function set_classrooms_list() {

    let response = await fetch(baseUrl + "/requires/get_classrooms_basic_info", {method: "POST"});

    let data = await response.json();
    const classrooms_list = data.classrooms;
    const classrooms_container = document.getElementById("classrooms-visualizer");
    classrooms_container.innerHTML = "";

    if (classrooms_list.length > 0) {
        data.classrooms.forEach((classroom, index) => {
                const redirect_button = document.createElement("button");
                redirect_button.id = `redirect-button${index}`;
                redirect_button.innerHTML = `
                    <p>
                        <strong>${classroom["curricular-unit"]}</strong>
                        ${classroom["teacher-name"]}
                    </p>
                `;
                redirect_button.addEventListener("click", () => {
                        window.location.href = baseUrl + `/my/search/classroom/${classroom.id}`;
                    }
                );
                classrooms_container.appendChild(redirect_button);

            }
        );

    } else {
        classrooms_container.innerHTML = `<p>Nenhuma sala a ser exibida</p>`

    }

}

document.addEventListener("DOMContentLoaded", set_classrooms_list);