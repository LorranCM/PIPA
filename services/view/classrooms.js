async function set_classrooms_list() {

    let response = await fetch(
        "services/db.reqs/get_classrooms_basic_info.php"
    );

    let data = await response.json();
    classrooms_list = data.classrooms;
    classrooms_container = document.getElementById("classrooms-visualizer");

    if (classrooms_list.length > 0) {
        data.classrooms.forEach((classroom, index) => {
                redirect_button = document.createElement("button");
                redirect_button.id = `redirect-button${index}`;
                redirect_button.innerHTML = `
                    <p>
                        <strong>${classroom["curricular-unit"]}</strong>
                        ${classroom["teacher-name"]}
                    </p>
                `;
                redirect_button.addEventListener("click", () => {
                        window.location.href = `index.php`; // mudar redirect
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
