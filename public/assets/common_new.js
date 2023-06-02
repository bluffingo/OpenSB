// REWRITE THIS WITH VANILLA JS -chaziz 6/2/2023
$(document).ready(function () {
    $('#submission-favorite').on('click', function () {
        console.log("Favoriting submission");
        $.post("/api/finalium/submission_interaction.php",
            {
                action: "favorite",
                submission: submission_id
            },
        function (data, status) {
            if (status === "success") {
                console.log(data.number);
                $("#submission-favorites").text(data.number);
            }
        });
    })
    $('#footer-button').on('click', function () {
        $.ajax({
            url: "/customizer.php",
            success: function (returndata) {
                $('#optionsModal').html(returndata);
                $("#optionsModal").show();
            },
            dataType: "html"
        });
    })
});