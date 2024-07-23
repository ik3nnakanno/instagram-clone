document.addEventListener("DOMContentLoaded", function () {
    const changePhotoButton = document.getElementById("chp");
    const changePhotoDiv = document.querySelector(".chgpht");
    const cancelButton = document.querySelector(".canc");
    const removeButton = document.querySelector(".remove");
    const uploadButton = document.querySelector(".uploadn");
    const submitButton = document.querySelector(".edtbtn");
    const bioTextarea = document.getElementById("txt");
    const fileInput = document.createElement("input");
    const counter = document.querySelector("small");

    fileInput.type = "file";
    fileInput.accept = "image/jpeg, image/png, image/jpg";
    fileInput.style.display = "none";
    document.body.appendChild(fileInput);

    if (changePhotoButton && changePhotoDiv) {
        changePhotoButton.addEventListener("click", function (event) {
            event.stopPropagation();
            changePhotoDiv.style.display = changePhotoDiv.style.display === "block" ? "none" : "block";
        });

        document.addEventListener("click", function (event) {
            if (!changePhotoDiv.contains(event.target) && event.target !== changePhotoButton) {
                changePhotoDiv.style.display = "none";
            }
        });
    }

    if (cancelButton) {
        cancelButton.addEventListener("click", function () {
            changePhotoDiv.style.display = "none";
        });
    }

    if (uploadButton) {
        uploadButton.addEventListener("click", function () {
            fileInput.click();
        });
    }

    fileInput.addEventListener("change", function () {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const formData = new FormData();
            formData.append("photo", file);
            formData.append("caption", "Profile Photo");

            if (file.size < 2048 || file.size > 10485760) {
                showNotification("File size must be between 2KB and 10MB");
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "upload_photo.php", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        showNotification(response.message);
                        if (response.success) {
                            location.reload();
                        }
                    } catch (e) {
                        showNotification("Error parsing JSON response");
                    }
                }
            };
            xhr.send(formData);
        }
    });

    if (removeButton) {
        removeButton.addEventListener("click", function () {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "remove_photo.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        showNotification(response.message);
                        if (response.success) {
                            location.reload();
                        }
                    } catch (e) {
                        showNotification("Error parsing JSON response");
                    }
                }
            };
            xhr.send("action=removePhoto");
        });
    }

    if (submitButton && bioTextarea) {
        submitButton.addEventListener("click", function () {
            const bio = bioTextarea.value;

            if (bio.length > 100) {
                showNotification("Bio must be 100 characters or less.");
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_bio.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        showNotification(response.message);
                    } catch (e) {
                        console.error("Parsing error:", e);
                        console.error("Response text:", xhr.responseText);
                        showNotification("Error parsing JSON response");
                    }
                }
            };
            xhr.send("bio=" + encodeURIComponent(bio));
        });
    }

    if (bioTextarea && counter) {
        bioTextarea.addEventListener("input", function () {
            const length = bioTextarea.value.length;
            counter.textContent = `${length}/100`;
        });
    }

    function showNotification(message) {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.style.display = 'block';

        setTimeout(function () {
            notification.style.display = 'none';
        }, 5000);
    }
});
