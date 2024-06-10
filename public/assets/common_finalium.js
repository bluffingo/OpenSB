function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

index = 0;
sbnextSounds = getCookie("SBSOUNDS");

$(document).ready(function () {
    console.log("squareBracket Sounds: " + sbnextSounds);
    $("#masthead-loggedin").click(function () {
        var x = document.getElementById("masthead-below");
        if (x.style.display === "block") {
            x.style.display = "none";
        } else {
            x.style.display = "block";
        }
    });
    $("#guide-toggle").click(function () {
        var x = document.getElementById("guide");
        if (x.style.display === "block") {
            x.style.display = "none";
        } else {
            x.style.display = "block";
        }
    });
    $("#action_unlogged").click(function () {
        play("error");
        alert('you must be logged in.');
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
        play("click");
        $("#commentPostingSpinner").removeClass('d-none');

        if (!$('#commentContents').val()) {
            play("error");
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
                    play("comment");
                }
            });
    });
    $("#post-user").click(function () {
        play("click");
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
                    play("comment");
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
                        play("click");
                    } else if (data == unsubscribe_string) {
                        $("#subscribe").text(unsubscribe_string);
                        $("#subscribe").attr("class", "button button-secondary");
                        console.log("Subscribed " + user_id);
                        play("subscribe");
                    } else {
                        play("error");
                        alert('unexpected output! report to https://github.com/bluffingo/OpenSB');
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
                        $("#subscribe-watch").attr("class", "button button-primary button-small");
                        console.log("Unsubscribed " + user_id);
                        play("click");
                    } else if (data == unsubscribe_string) {
                        $("#subscribe-watch").text(unsubscribe_string);
                        $("#subscribe-watch").attr("class", "button button-secondary button-small");
                        console.log("Subscribed " + user_id);
                        play("subscribe");
                    } else {
                        play("error");
                        alert('unexpected output! report to https://github.com/bluffingo/OpenSB');
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
                            $("#like").attr("class", "button button-success");
                            $("#likes").text(parseInt($("#likes").text()) + 1)
                            $("#dislikes").text(parseInt($("#dislikes").text()) - 1)
                            $("#dislike").attr("class", "button button-secondary");
                            play("like");
                        } else if (data == 0) {
                            $("#like").click();
                        } else {
                            play("error");
                            alert('unexpected output! report to https://github.com/bluffingo/OpenSB');
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
                            $("#dislike").attr("class", "button button-danger");
                            $("#dislikes").text(parseInt($("#dislikes").text()) + 1)
                            $("#likes").text(parseInt($("#likes").text()) - 1)
                            $("#like").attr("class", "button button-secondary");
                            play("dislike");
                        } else if (data == 0) {
                            $("#dislike").click();
                        } else {
                            play("error");
                            alert('unexpected output! report to https://github.com/bluffingo/OpenSB');
                        }
                    }
                });
        }
    });
    $("#favorite").click(function () {
        if ($("#favorite").attr("class") != "button button-warning") {
            $.post("favorite.php",
                {
                    action: "favorite",
                    video_id: video_id
                },
                function (data, status) {
                    if (status == "success") {
                        if (data == 1) {
                            $("#favorite").attr("class", "button button-warning");
                            play("favorite");
                        } else if (data == 0) {
                            $("#favorite").click();
                        } else {
                            play("error");
                            alert('unexpected output! report to https://github.com/bluffingo/OpenSB');
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

function openTab(evt, tab) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tab).style.display = "block";
    evt.currentTarget.className += " active";
}

function myFunction() {
    var x = document.getElementById("billboard-search-box");
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
}

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
    $("#" + id + " #commentField .col-md-11 .right #post").click(function () {
        play("click");
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
                    play("comment");
                }
            });
    });
    $("#" + id + " #commentField .col-md-11 .right #post-user").click(function () {
        play("click");
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
                    play("comment");
                }
            });
    });
}

function play(sound) {
    if (JSON.parse(sbnextSounds) == true) {
        var audio = new Audio('/assets/sounds/' + sound + '.ogg');
        audio.play();
    }
}