async function get_info() {

    let response = await fetch(
        "services/db.reqs/cancel_event.php"
        
    );

    let data = await response.json();
    // console.log(data.classrooms);

}

get_info();