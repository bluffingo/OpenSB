index = 0;

$(document).ready(function () {
    console.log("Sorry, We removed the sounds. -Chaziz 5/27/2023");
    $("#action_unlogged").click(function () {
        alert('You must be logged in.');
    });
    contents = $.trim($("#commentContents").val());
    if (contents === null || contents == "" && $("#post").attr("class") != "button button-primary disabled") {
        $("#post").addClass("disabled");
    }
    $("#commentContents").keydown(function (e) {
        if (e.key == "Backspace") {
            contents = $.trim($("#commentContents").val()).slice(0, -1);
        } else {
            contents = $.trim($("#commentContents").val()) + e.key;
        }
        if (contents == "") {
            $("#post").addClass("disabled");
        } else if (contents != "") {
            $("#post").removeClass("disabled")
        }
    });
    $("#post").click(function () {
        $("#commentPostingSpinner").removeClass('d-none');

        if (!$('#commentContents').val()) {
            return alert('you must put something to comment!');
        }

        $.post("comment.php",
            {
                comment: $.trim($('#commentContents').val()),
                vidid: video_id,
                really: "ofcourse",
                type: "video"
            },
            function (data, status) {
                if (status == "success") {
                    console.log("Commented " + $('#commentContents').val());
                    $('#comment').prepend(data);
                    $("#commentContents").val('');
                    $("#post").addClass("disabled");
                    $("#commentPostingSpinner").addClass('d-none');
                }
            });
    });
    $("#post-user").click(function () {
        $("#commentPostingSpinner").removeClass('d-none');
        $.post("comment.php",
            {
                comment: $.trim($('#commentContents').val()),
                uid: user_id,
                really: "ofcourse",
                type: "profile"
            },
            function (data, status) {
                if (status == "success") {
                    console.log("Commented " + $('#commentContents').val());
                    $('#comment').prepend(data);
                    $("#commentContents").val('');
                    $("#post").addClass("disabled");
                    $("#commentPostingSpinner").addClass('d-none');
                }
            });
    });
    $("#subscribe").click(function () {
        $.post("subscribe.php",
            {
                subscription: user_id
            },
            function (data, status) {
                if (status == "success") {
                    if (data == subscribe_string) {
                        $("#subscribe").text(subscribe_string);
                        $("#subscribe").attr("class", "button button-primary");
                        console.log("Unsubscribed " + user_id);
                    } else if (data == unsubscribe_string) {
                        $("#subscribe").text(unsubscribe_string);
                        $("#subscribe").attr("class", "button button-secondary");
                        console.log("Subscribed " + user_id);
                    } else {
                        alert('unexpected output! report to https://gitlab.com/qobo/opensb/-/issues');
                    }
                }
            });
    });
    $("#subscribe-watch").click(function () {
        $.post("subscribe.php",
            {
                subscription: user_id
            },
            function (data, status) {
                if (status == "success") {
                    if (data == subscribe_string) {
                        $("#subscribe-watch").text(subscribe_string);
                        $("#subscribe-watch").attr("class", "btn btn-primary btn-sm");
                        console.log("Unfollowed " + user_id);
                    } else if (data == unsubscribe_string) {
                        $("#subscribe-watch").text(unsubscribe_string);
                        $("#subscribe-watch").attr("class", "btn btn-default btn-sm");
                        console.log("Followed " + user_id);
                    } else {
                        alert('unexpected output! report to https://gitlab.com/qobo/opensb/-/issues');
                    }
                }
            });
    });
    $("#like").click(function () {
        if ($("#like").attr("class") != "button button-success") {
            $.post("rate.php",
                {
                    rating: 1,
                    vidid: video_id
                },
                function (data, status) {
                    if (status == "success") {
                        if (data == 1) {
                            $("#like").attr("class", "btn btn-success");
                            $("#likes").text(parseInt($("#likes").text()) + 1)
                            $("#dislikes").text(parseInt($("#dislikes").text()) - 1)
                            $("#dislike").attr("class", "btn btn-default");
                        } else if (data == 0) {
                            $("#like").click();
                        } else {
                            alert('unexpected output! report to https://gitlab.com/qobo/opensb/-/issues');
                        }
                    }
                });
        }
    });
    $("#dislike").click(function () {
        if ($("#dislike").attr("class") != "button button-danger") {
            $.post("rate.php",
                {
                    rating: 0,
                    vidid: video_id
                },
                function (data, status) {
                    if (status == "success") {
                        if (data == 1) {
                            $("#dislike").attr("class", "btn btn-danger");
                            $("#dislikes").text(parseInt($("#dislikes").text()) + 1)
                            $("#likes").text(parseInt($("#likes").text()) - 1)
                            $("#like").attr("class", "btn btn-default");
                        } else if (data == 0) {
                            $("#dislike").click();
                        } else {
                            alert('unexpected output! report to https://gitlab.com/qobo/opensb/-/issues');
                        }
                    }
                });
        }
    });
    $("#favorite").click(function () {
        if ($("#favorite").attr("class") != "btn btn-warning") {
            $.post("favorite.php",
                {
                    action: "favorite",
                    video_id: video_id
                },
                function (data, status) {
                    if (status == "success") {
                        if (data == 1) {
                            $("#favorite").attr("class", "btn btn-warning");
                        } else if (data == 0) {
                            $("#favorite").click();
                        } else {
                            alert('unexpected output! report to https://gitlab.com/qobo/opensb/-/issues');
                        }
                    }
                });
        }
    });
    $("#showSearch").click(function () {
        $("#masthead-search").attr("style", "");
        $("#showSearch").attr("style", "display:none");
        $("#header-main").attr("style", "display:none");
    });
    $(".options-button").click(function () {
        $.ajax({
            url: "/customizer.php",
            success: function (returndata) {
                $('#optionsModal').html(returndata);
                $("#optionsModal").show();
            },
            dataType: "html"
        });
    });
    $(".debug-button").click(function () {
		$("#debugModal").show();
    });
});

function showReplies(id) {
    $.post("get_replies.php",
        {
            comment_id: id
        },
        function (data, status) {
            if (status == "success") {
                $('#' + id).append(data);
            }
        });
}

function showMoreVideos() {
    if ($("#fromUserVideoList").attr("class") != "card-body") {
        $.post("ajax_watch.php",
            {
                from: index,
                limit: 10,
            },
            function (data, status) {
                if (status == "success") {
                    index += 10;
                    $('#fromUserVideoList').append(data);
                    $("#fromUserVideoList").removeClass("collapsed");
                    $("#fromUser").remove();
                }
            });
    } else {
        $("#fromUserVideoList").empty();
        $("#fromUserVideoList").addClass("collapsed");
    }
}

function reply(id) {
    if (!$("#" + id + " #commentField").length) {
        $("#commentField").clone().appendTo("#" + id);
    }
    $("#" + id + " #commentField .col-md-16 .text-right #post").click(function () {
        $.post("comment.php",
            {
                comment: $.trim($('#' + id + ' #commentContents').val()),
                reply_to: id,
                really: "ofcourse",
                type: "video"
            },
            function (data, status) {
                if (status == "success") {
                    console.log("Commented " + $('#' + id + ' #commentContents').val());
                    $("#" + id).append(data);
                    $("#" + id + " #commentField").remove();
                }
            });
    });
    $("#" + id + " #commentField .col-md-16 .text-right #post-user").click(function () {
        $.post("comment.php",
            {
                comment: $.trim($('#' + id + ' #commentContents').val()),
                reply_to: id,
                really: "ofcourse",
                type: "video"
            },
            function (data, status) {
                if (status == "success") {
                    console.log("Commented " + $('#' + id + ' #commentContents').val());
                    $("#" + id).append(data);
                    $("#" + id + " #commentField").remove();
                }
            });
    });
}