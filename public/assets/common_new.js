function error(error) {
    console.error("OpenSB Biscuit Frontend Error: " + error);
}

document.addEventListener("DOMContentLoaded", () => {

    let favorite_button = (document.getElementById('submission-favorite'));
    let comment_field = (document.getElementById('comment_field'));

    if (comment_field) {
        let comment_button = (document.getElementById('comment_button'));
        let comment_contents = (document.getElementById('comment_contents'));
        comment_button.onclick = function () {
            fetch("/api/finalium/commenting.php", {
                method: "POST",
                body: JSON.stringify({
                    type: "submission",
                    submission: submission_id,
                    comment: comment_contents.value,
                }),
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            })
                .then((response) => response.json())
                .then((json) => { if(json["error"]) { error(json["error"])} else
                {
                    document.getElementById('comment').insertAdjacentHTML(
                        "afterbegin",
                        json["html"],
                    )
                }});

        }
    }

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