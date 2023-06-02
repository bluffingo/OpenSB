// REWRITE THIS WITH VANILLA JS -chaziz 6/2/2023
$(document).ready(function () {
    $('#submission-favorite').on('click', function () {
        console.log("Favoriting submission");
        $.post("/api/finalium/submission_interaction.php",
            {
                action: "favorite",
                video_id: submission_id
            },
        function (data, status) {
            if (status === "success") {
                console.log(data.number);
                $("#submission-favorites").text(data.number);
            }
        });
    })
});