document.addEventListener('DOMContentLoaded', function () {
    function showNotification(message) {
        var notification = document.getElementById('notification');
        notification.textContent = message;
        notification.style.display = 'block';

        setTimeout(function () {
            notification.style.display = 'none';
        }, 5000);
    }

    document.querySelector('a.create').addEventListener('click', function (event) {
        event.preventDefault();
        document.getElementById('createbox').style.display = 'flex';
    });

    document.getElementById('xx').addEventListener('click', function () {
        clearImagePreview();
        document.getElementById('createbox').style.display = 'none';
    });

    document.getElementById('xxs').addEventListener('click', function () {
        clearImagePreview();
        document.getElementById('createbox').style.display = 'none';
    });

    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    const shareDiv = document.getElementById('share');
    const drop = document.getElementById('drop');
    const previewImage = document.getElementById('preview');
    const submitButton = document.getElementById('submit');
    let selectedFile;

    dropZone.addEventListener('click', () => {
        fileInput.click();
    });

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('dragover');

        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    fileInput.addEventListener('change', (e) => {
        const files = e.target.files;
        handleFiles(files);
    });

    function handleFiles(files) {
        for (let file of files) {
            if (isValidImageFile(file)) {
                displayImage(file);
                selectedFile = file;
                break;
            } else {
                showNotification('Please upload a valid image file between 2KB and 10MB.');
                clearImagePreview();
            }
        }
    }

    function isValidImageFile(file) {
        const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        const maxSize = 10 * 1024 * 1024;

        return validImageTypes.includes(file.type) && file.size <= maxSize && file.size >= 10048;
    }

    function displayImage(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.src = e.target.result;
            drop.style.display = 'none';
            shareDiv.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    submitButton.addEventListener('click', () => {
        if (selectedFile) {
            const caption = document.getElementById('caption').value;
            uploadImage(selectedFile, caption);
        } else {
            showNotification('No file selected');
        }
    });

    function uploadImage(file, caption) {
        const formData = new FormData();
        formData.append('photo', file);
        formData.append('caption', caption);
        formData.append('post', true);

        fetch('upload.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = "profile.php";
                } else {
                    showNotification('Image upload failed: ' + data.message);
                    clearImagePreview();
                }
            })
            .catch(error => {
                showNotification('Image upload failed: ' + error.message);
                clearImagePreview();
            });
    }

    function clearImagePreview() {
        previewImage.src = '';
        dropZone.style.display = 'flex';
        shareDiv.style.display = 'none';
        selectedFile = null;
    }

    document.addEventListener('click', function (event) {
        const createbox = document.getElementById('createbox');
        const dropZone = document.getElementById('drop-zone');
    });
});
