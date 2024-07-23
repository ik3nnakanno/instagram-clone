const searchButton = document.querySelector('.search');
const hideButton = document.querySelector('.searchh');
const naviDiv = document.querySelector('.navi');
const searchnavDiv = document.querySelector('.searchnav');

// Function to toggle the visibility and swipe effect
function toggleNav() {
    if (window.innerWidth > 800) {
        // For screens larger than 800px
        if (naviDiv.classList.contains('hidden')) {
            naviDiv.classList.remove('hidden');
            naviDiv.style.transform = 'translateX(0)';
            searchnavDiv.style.transform = 'translateX(-100%)';
            setTimeout(() => {
                searchnavDiv.classList.add('hidden');
            }, 500);
        } else {
            naviDiv.style.transform = 'translateX(-100%)';
            searchnavDiv.classList.remove('hidden');
            searchnavDiv.style.transform = 'translateX(0)';
            setTimeout(() => {
                naviDiv.classList.add('hidden');
            }, 500);
        }
    } else {
        // For screens smaller than 800px
        if (naviDiv.classList.contains('hidden')) {
            naviDiv.classList.remove('hidden');
            naviDiv.style.transform = 'translateY(0)';
            searchnavDiv.style.transform = 'translateY(100%)';
            setTimeout(() => {
                searchnavDiv.classList.add('hidden');
            }, 500);
        } else {
            naviDiv.style.transform = 'translateY(100%)';
            searchnavDiv.classList.remove('hidden');
            searchnavDiv.style.transform = 'translateY(0)';
            setTimeout(() => {
                naviDiv.classList.add('hidden');
            }, 500);
        }
    }
}

// Function to hide the searchnav
function hideSearchNav() {
    if (!searchnavDiv.classList.contains('hidden')) {
        if (window.innerWidth > 800) {
            searchnavDiv.style.transform = 'translateX(-100%)';
            naviDiv.classList.remove('hidden');
            naviDiv.style.transform = 'translateX(0)';
            setTimeout(() => {
                searchnavDiv.classList.add('hidden');
            }, 500);
        } else {
            searchnavDiv.style.transform = 'translateY(100%)';
            naviDiv.classList.remove('hidden');
            naviDiv.style.transform = 'translateY(0)';
            setTimeout(() => {
                searchnavDiv.classList.add('hidden');
            }, 500);
        }
    }
}

// Add click event listener to the search button
searchButton.addEventListener('click', function (event) {
    toggleNav();
    event.stopPropagation();
});

// Add click event listener to the hide button
hideButton.addEventListener('click', function (event) {
    hideSearchNav();
    event.stopPropagation();
});

// Add click event listener to the document
document.addEventListener('click', function (event) {
    if (!searchnavDiv.contains(event.target) && !searchButton.contains(event.target)) {
        hideSearchNav();
    }
});

$(document).ready(function () {
    $('#search').on('keyup', function () {
        var search = $(this).val();
        if (search.length > 1) {
            $.ajax({
                url: 'search.php',
                type: 'POST',
                data: { search: search },
                success: function (data) {
                    $('#searchview').html(data);
                }
            });
        } else {
            $('#searchview').html('');
        }
    });
});