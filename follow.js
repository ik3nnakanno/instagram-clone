$(document).ready(function () {
    // Function to show notification
    function showNotification(message) {
        var notification = $('#notification');
        notification.text(message);
        notification.show();

        // Hide the notification after 5 seconds
        setTimeout(function () {
            notification.hide();
        }, 5000);
    }

    // Function to get the current follower count
    function getFollowerCount() {
        return parseInt($('.fcount').text());
    }

    // Function to set the follower count
    function setFollowerCount(count) {
        $('.fcount').text(count + ' followers');
    }

    // Handle follow button click
    $(document).on('click', '.follow', function () {
        var followee_id = $(this).data('user-id');
        var button = $(this);

        $.ajax({
            url: 'follow.php',
            type: 'POST',
            data: {
                action: 'follow',
                followee_id: followee_id
            },
            success: function (response) {
                if (response == 'followed') {
                    button.removeClass('follow').addClass('unfollow').text('Following');
                    var currentCount = getFollowerCount();
                    setFollowerCount(currentCount + 1);
                } else if (response == 'already_following') {
                    showNotification('You are already following this user.');
                } else {
                    showNotification('An error occurred.');
                }
            }
        });
    });

    // Handle unfollow button click
    $(document).on('click', '.unfollow', function () {
        var followee_id = $(this).data('user-id');
        var button = $(this);

        $.ajax({
            url: 'follow.php',
            type: 'POST',
            data: {
                action: 'unfollow',
                followee_id: followee_id
            },
            success: function (response) {
                if (response == 'unfollowed') {
                    button.removeClass('unfollow').addClass('follow').text('Follow');
                    var currentCount = getFollowerCount();
                    setFollowerCount(currentCount - 1);
                } else {
                    showNotification('An error occurred.');
                }
            }
        });
    });

    // Initialize user ID
    var users_id = "<? php echo json_encode($usersid); ?>";
});
