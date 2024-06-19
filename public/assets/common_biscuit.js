function error(error) {
    play('error');
    console.error("OpenSB Biscuit Frontend Error: " + error);
}

let uiSounds = false;
const cookie = document.cookie.split('; ').find(row => row.startsWith('SBOPTIONS='));
if (cookie) {
    const encodedOptions = cookie.split('=')[1];
    const decodedOptions = decodeURIComponent(encodedOptions);
    const options = JSON.parse(atob(decodedOptions));
    if (options.hasOwnProperty('sounds')) {
        uiSounds = options.sounds;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    // Get all tab links
    const tabLinks = document.querySelectorAll(".tablink");

    // Add click event listener to each tab link
    tabLinks.forEach(tabLink => {
        tabLink.addEventListener("click", function() {
            const tabId = this.getAttribute("data-tab");

            // Hide all tab content
            const tabContents = document.querySelectorAll(".tabcontent");
            tabContents.forEach(tabContent => {
                tabContent.style.display = "none";
            });

            // Remove 'active' class from all tab links
            tabLinks.forEach(link => {
                link.classList.remove("active");
            });

            // Show the selected tab content and mark the button as active
            document.getElementById(tabId).style.display = "block";
            this.classList.add("active");
        });
    });

    // Get all menu buttons and menus
    const menuButtons = document.querySelectorAll('.menuButton');

    // Add event listeners for each menu button
    menuButtons.forEach(button => {
        const menuId = button.getAttribute('data-menu-id');
        const menu = document.getElementById(menuId);

        // Show the menu on mobile when the button is tapped.
        button.addEventListener('touchstart', () => {
            if (menu.style.display === 'none') {
                menu.style.display = 'block';
            } else {
                menu.style.display = 'none';
            }
        });

        // Show the menu when hovering over the button or the menu
        button.addEventListener('mouseenter', () => {
            menu.style.display = 'block';
        });

        // Hide the menu when not hovering over the button or the menu
        button.addEventListener('mouseleave', () => {
            menu.style.display = 'none';
        });

        menu.addEventListener('mouseenter', () => {
            menu.style.display = 'block';
        });

        menu.addEventListener('mouseleave', () => {
            menu.style.display = 'none';
        });
    });

    function closeAllReplyForms() {
        const openReplyForms = document.querySelectorAll(".reply-form");
        openReplyForms.forEach(form => {
            form.style.display = "none";
        });
    }

    function submitComment(type, id, content, replyTo = 0) {
        play('click');
        fetch("/api/biscuit/commenting", {
            method: "POST",
            body: JSON.stringify({
                type: type,
                id: id,
                comment: content,
                reply_to: replyTo
            }),
            headers: {
                "Content-type": "application/json; charset=UTF-8"
            }
        })
            .then(response => response.json())
            .then(json => {
                if (json.error) {
                    error(json.error);
                } else {
                    play('comment');
                    if (replyTo !== 0) {
                        const repliesContainer = document.getElementById(`replies-${replyTo}`);
                        if (repliesContainer) {
                            repliesContainer.insertAdjacentHTML("beforeend", json.html);
                        } else {
                            error(`replies-${replyTo} doesn't exist. Biscuit fucked up.`);
                        }
                    } else {
                        const commentsSection = document.getElementById('new-comments-here');
                        if (commentsSection) {
                            commentsSection.insertAdjacentHTML("afterbegin", json.html);
                        } else {
                            error(`Comments section doesn't exist????? Biscuit fucked up.`);
                        }
                    }

                    closeAllReplyForms();
                }
            });
    }

    let comment_field = (document.getElementById('comment_field'));
    if (comment_field) {
        let comment_button = document.getElementById('comment_button');
        let comment_contents = document.getElementById('comment_contents');
        comment_button.onclick = function() {
            submitComment(comment_type, comment_id, comment_contents.value);
        };
    }

    document.addEventListener("click", function(event) {
        if (event.target && event.target.classList.contains("reply-button")) {
            let commentId = event.target.getAttribute("data-comment-id");

            closeAllReplyForms();

            let replyForm = document.getElementById(`reply-form-${commentId}`);
            if (replyForm) {
                replyForm.style.display = "block";
            }
        }

        if (event.target && event.target.classList.contains("submit-reply-button")) {
            let commentId = event.target.getAttribute("data-comment-id");
            let replyContents = document.getElementById(`reply_contents_${commentId}`);
            if (replyContents) {
                submitComment(comment_type, comment_id, replyContents.value, commentId);
            }
        }
    });

    let follow_button = (document.getElementById('follow-user'));
    if (follow_button) {
        let follow_count = (document.getElementById('follower_count'));
        follow_button.onclick = function () {
            play('click');
            fetch("/api/biscuit/user_interaction", {
                method: "POST",
                body: JSON.stringify({
                    action: "follow",
                    member: user_id,
                }),
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            })
                .then((response) => response.json())
                .then((json) => {
                        if(json["error"])
                        {
                            error(json["error"])
                        }
                        else
                        {
                            follow_count.textContent = json["number"];
                            follow_button.textContent = json["text"];
                            if (json["followed"]) {
                                play('subscribe');
                            }
                        }
                    }
                )
            ;

        }
    }
});

function play(sound) {
    if (JSON.parse(uiSounds) == true) {
        var audio = new Audio('/assets/sounds/' + sound + '.ogg');
        audio.play();
    }
}