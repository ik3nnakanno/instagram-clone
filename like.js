$(document).ready(function () {
    function showNotification(message) {
        var notification = $('#notification');
        notification.text(message);
        notification.show();

        // Hide the notification after 5 seconds
        setTimeout(function () {
            notification.hide();
        }, 5000);
    }

    function toggleLike(postId, $actionElement, showAnimation = true, isDoubleClick = false) {
        console.log(`Toggling like for post ID: ${postId}`); // Debug
        $.ajax({
            url: 'like.php',
            type: 'POST',
            data: { post_id: postId },
            dataType: 'json',
            success: function (response) {
                console.log(response); // Debug
                const $container = $actionElement.closest('.post, .pview, .vview');
                const $siblingShow = $container.find('.show');
                const $siblingHide = $container.find('.hide');
                const $mliked = $container.find('.mliked');

                if (showAnimation && response.status === 'liked') {
                    $mliked.show().addClass('like-animation'); // Add CSS class for animation
                    setTimeout(() => $mliked.hide().removeClass('like-animation'), 700); // Remove animation class after timeout
                }

                if (response.status === 'liked') {
                    $siblingShow.hide();
                    $siblingHide.show();
                } else if (response.status === 'unliked' && !isDoubleClick) {
                    $siblingHide.hide();
                    $siblingShow.show();
                } else if (isDoubleClick) {
                } else {
                    showNotification(response.message);
                }

                $container.find('.count').text(`${response.like_count} likes`);
            },
            error: function () {
                showNotification('An error occurred. Please try again.');
            }
        });
    }

    function attachToggleHandler(selector) {
        $(document).on('click', selector, function () {
            const postId = $(this).data('post-id') || $(this).data('view-id');
            toggleLike(postId, $(this), true);
        });
    }

    attachToggleHandler('.show');
    attachToggleHandler('.hide');

    $(document).on('dblclick', '.vpost', function () {
        const $vview = $(this).closest('.vview');
        const postId = $vview.data('view-id');
        const $vliked = $vview.find('.vliked');
        const $show = $vview.find('.show');

        $vliked.show();
        setTimeout(() => $vliked.hide(), 700);

        if ($show.is(':visible')) {
            toggleLike(postId, $show, true, true); // Pass true as isDoubleClick
        }
    });

    function setTheme(theme) {
        const body = document.body;
        body.classList.remove('light-theme', 'dark-theme');
        body.classList.add(theme === 'light' ? 'light-theme' : 'dark-theme');

        const lightImages = document.querySelectorAll('.show.light');
        const darkImages = document.querySelectorAll('.show.dark');
        lightImages.forEach(img => img.style.display = theme === 'light' ? 'block' : 'none');
        darkImages.forEach(img => img.style.display = theme === 'light' ? 'none' : 'block');

        const views = document.querySelector('.views');
        if (views) {
            views.style.backgroundColor = theme === 'light' ? 'white' : 'black';
            views.style.color = theme === 'light' ? 'black' : 'white';
        }
    }

    $('.vview, .post, .pview').each(function () {
        const postId = $(this).data('post-id') || $(this).data('view-id');
        const $this = $(this);

        $.ajax({
            url: 'check_like.php',
            type: 'GET',
            data: { post_id: postId },
            dataType: 'json',
            success: function (response) {
                console.log(response); // Debug
                const $show = $this.find('.show');
                const $hide = $this.find('.hide');

                if (response.liked) {
                    $show.hide();
                    $hide.show();
                } else {
                    $show.show();
                    $hide.hide();
                }

                const storedTheme = localStorage.getItem('theme') || 'dark';
                setTheme(storedTheme);
            },
            error: function () {
                console.log('An error occurred while checking the like status.');
            }
        });
    });

    const storedTheme = localStorage.getItem('theme') || 'dark';
    setTheme(storedTheme);

});
