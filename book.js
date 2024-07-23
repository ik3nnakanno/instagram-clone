$(document).ready(function () {
    function showNotification(message) {
        var notification = $('#notification');
        if (notification.length) {
            notification.text(message);
            notification.show();

            setTimeout(function () {
                notification.hide();
            }, 5000);
        } else {
        }
    }

    function toggleBookmark(postId, $actionElement) {
        $.ajax({
            url: 'save_post.php',
            type: 'POST',
            data: { post_id: postId },
            dataType: 'json',
            success: function (response) {
                const $container = $actionElement.closest('.save');
                const $bookShow = $container.find('.book');
                const $unbookShow = $container.find('.unbook');

                if (response.status === 'saved') {
                    $bookShow.hide();
                    $unbookShow.show();
                    showNotification('Post Saved');
                } else if (response.status === 'unsaved') {
                    $unbookShow.hide();
                    $bookShow.show();
                    showNotification('Saved post has been deleted');
                } else {
                    showNotification(response.message);
                }

                updateBookmarkIcons(); // Update icons based on current theme
            },
            error: function () {
                showNotification('An error occurred. Please try again.');
            }
        });
    }

    $(document).on('click', '.book, .unbook', function () {
        const postId = $(this).data('post-id');
        toggleBookmark(postId, $(this));
    });

    function updateBookmarkIcons() {
        const theme = $('body').hasClass('light-theme') ? 'light' : 'dark';

        $('.save').each(function () {
            const postId = $(this).find('.book, .unbook').data('post-id');
            const $this = $(this);

            $.ajax({
                url: 'check_save_post.php',
                type: 'GET',
                data: { post_id: postId },
                dataType: 'json',
                success: function (response) {
                    const $book = $this.find('.book');
                    const $unbook = $this.find('.unbook');
                    if (response.saved) {
                        $book.hide();
                        $unbook.hide(); // Hide both initially
                        $unbook.filter(`.${theme}`).show(); // Show the one with the current theme
                    } else {
                        $unbook.hide();
                        $book.hide(); // Hide both initially
                        $book.filter(`.${theme}`).show(); // Show the one with the current theme
                    }
                },
                error: function () {
                    showNotification('An error occurred while checking the save status.');
                }
            });
        });
    }

    updateBookmarkIcons();
});
