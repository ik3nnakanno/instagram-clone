document.addEventListener("DOMContentLoaded", function () {
    // Function to show notification
    function showNotification(message) {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.style.display = 'block';

        setTimeout(() => {
            notification.style.display = 'none';
        }, 5000);
    }

    // Add input event listener to all textarea elements with class "txtcom" or "ttcom"
    document.querySelectorAll("textarea.ttcom, textarea.txtcom").forEach(textarea => {
        textarea.addEventListener("input", function () {
            const postId = this.getAttribute("data-post-id");
            const button = document.querySelector(`button.dbut[data-post-id='${postId}'], button.cbut[data-post-id='${postId}']`);
            console.log(`Input detected in textarea for post ID: ${postId}`); // Debug
            if (this.value.trim() !== "") {
                if (button) {
                    console.log(`Showing button for post ID: ${postId}`); // Debug
                    button.style.display = "inline-block";
                    button.removeAttribute("disabled");
                }
            } else {
                if (button) {
                    console.log(`Hiding button for post ID: ${postId}`); // Debug
                    button.style.display = "none";
                    button.setAttribute("disabled", "disabled");
                }
            }
        });

        // Add keydown event listener to handle Enter and Shift+Enter key presses
        textarea.addEventListener("keydown", function (event) {
            if (event.keyCode === 13 && !event.shiftKey) {
                event.preventDefault();
                const postId = this.getAttribute("data-post-id");
                const button = document.querySelector(`button.dbut[data-post-id='${postId}'], button.cbut[data-post-id='${postId}']`);
                if (button && !button.disabled) {
                    console.log(`Submitting comment for post ID: ${postId}`); // Debug
                    button.click();
                }
            }
        });
    });

    // Add click event listener to all buttons with class "dbut" or "cbut"
    document.querySelectorAll("button.dbut, button.cbut").forEach(button => {
        button.addEventListener("click", function () {
            const postId = this.getAttribute("data-post-id");
            const textarea = document.querySelector(`textarea.ttcom[data-post-id='${postId}'], textarea.txtcom[data-post-id='${postId}']`);
            const comment = textarea.value.trim();

            if (comment === "") {
                return;
            }

            console.log(`Posting comment: ${comment}`); // Debug
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "add_comment.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    console.log(response); // Debug
                    showNotification(response.message);
                    if (response.status === "success") {
                        textarea.value = '';
                        button.style.display = "none";
                        button.setAttribute("disabled", "disabled");
                    } else {
                        showNotification(response.message);
                    }
                }
            };
            xhr.send(`post_id=${postId}&comment=${encodeURIComponent(comment)}`);
        });
    });
});
