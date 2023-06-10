function error(error) {
    console.error("BettySB Finalium Frontend Error: " + error);
}

document.addEventListener("DOMContentLoaded", () => {

    let favorite_button = (document.getElementById('submission-favorite'));

    if (favorite_button) {
        let favorite_count = (document.getElementById('submission-favorites'));
        favorite_button.onclick = function () {
            fetch("/api/finalium/submission_interaction.php", {
                method: "POST",
                body: JSON.stringify({
                    action: "favorite",
                    submission: submission_id,
                }),
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            })
                .then((response) => response.json())
                .then((json) => { if(json["error"]) { error(json["error"])} else { favorite_count.textContent = json["number"]}});

        }
    }
});