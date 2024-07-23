$(document).ready(function () {
    const body = document.body;

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
        $.ajax({
            url: 'like.php',
            type: 'POST',
            data: { post_id: postId },
            dataType: 'json',
            success: function (response) {
                const $container = $actionElement.closest('.post, .pview');
                const $siblingShow = $container.find('.mshow');
                const $siblingHide = $container.find('.mhide');
                const $mliked = $container.find('.mliked');

                if (showAnimation) {
                    // Display the .mliked animation for 0.7 seconds
                    $mliked.show();
                    setTimeout(() => $mliked.hide(), 700);
                }

                if (response.status === 'liked') {
                    $siblingShow.hide();
                    $siblingHide.show();
                } else if (response.status === 'unliked' && !isDoubleClick) {
                    $siblingHide.hide();
                    $siblingShow.show();
                } else {
                    // Do nothing
                }

                $container.find('.count').text(`${response.like_count} likes`);
            },
            error: function () {
                showNotification('An error occurred. Please try again.');
            }
        });
    }

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

    // Event delegation for like buttons
    $(document).on('click', '.mshow', function () {
        const postId = $(this).data('post-id') || $(this).data('view-id');
        toggleLike(postId, $(this), true);
    });

    $(document).on('click', '.mhide', function () {
        const postId = $(this).data('post-id') || $(this).data('view-id');
        toggleLike(postId, $(this), false);
    });

    // Double click handler for .ppp and .diss elements within .post and .pview
    $(document).on('dblclick', '.display .ppp, .diss', function () {
        const $this = $(this);
        const $container = $this.closest('.post, .pview');
        const postId = $container.data('post-id') || $container.data('view-id');
        const $mshow = $container.find('.mshow');
        const $mliked = $container.find('.mliked');

        // Show the .mliked animation for 0.7 seconds
        $mliked.show();
        setTimeout(() => $mliked.hide(), 700);

        // Only toggle like status if not already liked
        if ($mshow.is(':visible')) {
            toggleLike(postId, $mshow, false, true);
        }
    });

    // Initial check for like status on page load
    $('.post, .pview').each(function () {
        const postId = $(this).data('post-id') || $(this).data('view-id');
        const $this = $(this);

        $.ajax({
            url: 'check_like.php',
            type: 'GET',
            data: { post_id: postId },
            dataType: 'json',
            success: function (response) {
                const $show = $this.find('.mshow');
                const $hide = $this.find('.mhide');

                if (response.liked) {
                    $show.hide();
                    $hide.show();
                } else {
                    $show.show();
                    $hide.hide();
                }

                // Apply theme settings
                const storedTheme = localStorage.getItem('theme') || 'dark';
                setTheme(storedTheme);
            },
            error: function () {
                console.log('An error occurred while checking the like status.');
            }
        });
    });

    // Show .pview when .xve is clicked
    $(document).on('click', '.xve', function () {
        const postId = $(this).data('post-id');
        const $post = $(`.post[data-post-id="${postId}"]`);
        const $pview = $post.find('.pview');

        $pview.css('display', 'flex');
    });

    // Hide .pview when clicking outside of .views
    // $(document).on('click', function (event) {
    //     if (!$(event.target).closest('.views').length) {
    //         $('.pview').css('display', 'none');
    //     }
    // });

    $('.x, .xs').click(function () {
        const $pview = $(this).closest('.pview');
        $pview.css('display', 'none');
    });

    const storedTheme = localStorage.getItem('theme') || 'dark';
    setTheme(storedTheme);
});
