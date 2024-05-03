function error(error) {
    console.error("OpenSB Biscuit Frontend Error: " + error);
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

    let favorite_button = (document.getElementById('follow-user'));
    let comment_field = (document.getElementById('comment_field'));

    if (comment_field) {
        let comment_button = (document.getElementById('comment_button'));
        let comment_contents = (document.getElementById('comment_contents'));
        comment_button.onclick = function () {
            fetch("/api/finalium/commenting.php", {
                method: "POST",
                body: JSON.stringify({
                    type: comment_type,
                    id: comment_id,
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
        let favorite_count = (document.getElementById('follower_count'));
        favorite_button.onclick = function () {
            fetch("/api/finalium/user_interaction.php", {
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
                            favorite_count.textContent = json["number"];
                            favorite_button.textContent = json["text"];
                        }
                    }
                )
            ;

        }
    }
});