document.addEventListener('DOMContentLoaded', function () {
    // Function to show the vview element
    function showVView(dash) {
        const vview = dash.querySelector('.vview');
        if (vview) {
            vview.style.display = 'flex';
        } else {
        }
    }

    // Function to hide the closest vview element
    function hideClosestVView(element) {
        const vview = element.closest('.vview');
        if (vview) {
            vview.style.display = 'none';
        } else {
        }
    }

    // Add event listeners to elements with class 'dashes'
    document.querySelectorAll('.dashes').forEach(function (dash) {
        dash.addEventListener('click', function () {
            showVView(this);
        });
    });

    // Add event listeners to elements with class 'x'
    document.querySelectorAll('.x').forEach(function (closeBtn) {
        closeBtn.addEventListener('click', function (event) {
            event.stopPropagation();  // Prevent the click event from bubbling up to the .dashes div
            hideClosestVView(this);
        });
    });

    // Add event listeners to elements with class 'xs'
    document.querySelectorAll('.xs').forEach(function (closeBtn) {
        closeBtn.addEventListener('click', function (event) {
            event.stopPropagation();  // Prevent the click event from bubbling up to the .dashes div
            hideClosestVView(this);
        });
    });

    // Add event listeners to elements with class 'vview'
    document.querySelectorAll('.vview').forEach(function (viewDiv) {
        viewDiv.addEventListener('click', function (event) {
            if (!event.target.classList.contains('views') && !event.target.closest('.views')) {
                this.style.display = 'none';
            }
        });
    });
});







