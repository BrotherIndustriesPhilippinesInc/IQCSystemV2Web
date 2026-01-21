$(function(){
    var currentBgColor = '#005CAB'; 
    var currentFontColor = '#FFFFFF';
    var isCompiling = false; // A flag to prevent overlaps

    initializeColorPicker('bgColorPicker', 'bg');
    initializeColorPicker('fontColorPicker', 'text');

    // 1. The Update Function (Same logic as before)
    function updateColors(bg, font) {
        if (isCompiling) return; // If busy, skip this frame
        
        isCompiling = true;
        less.modifyVars({
            '@primary-background': bg,
            '@tertiary-text': font
        }).then(function() {
            isCompiling = false; // Unlock for the next update
        });
    }

    // 2. The Throttler
    // This creates a version of the function that can only run once every 'limit' ms
    function throttle(callback, limit) {
        var waiting = false;
        return function () {
            if (!waiting) {
                callback.apply(this, arguments);
                waiting = true;
                setTimeout(function () {
                    waiting = false;
                }, limit);
            }
        }
    }

    // 3. The Handler
    // We update our local state instantly, but we delay the heavy LESS compilation
    var triggerLessUpdate = throttle(function() {
        updateColors(currentBgColor, currentFontColor);
    }, 100); // <-- Runs max once every 100ms

    function initializeColorPicker(inputId, type) {
         const colorInput = document.getElementById(inputId);

        if (colorInput) {
            // We use 'input' for dragging events
            colorInput.addEventListener('input', () => {
                
                // Update State Instantly
                if (type == 'bg') currentBgColor = colorInput.value;
                if (type == 'text') currentFontColor = colorInput.value;

                // Trigger the throttled LESS compiler
                triggerLessUpdate();
            });
        }
    }

    function getCookie(name) {
        let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        if (match) return match[2];
    }

    //Save the colors
    $('#saveColorSettings').on('click', function(){
        const update = {
            id: parseInt(getCookie('userId')),
            bgColor: currentBgColor,
            textColor: currentFontColor
        };
        $.ajax({
            url: 'https://localhost:7246/api/Accounts/UpdateColor', // Replace with your server endpoint
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(update),
            success: function(response) {
                swal.fire({
                    icon: 'success',
                    title: 'Colors saved successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });

            },
            error: function() {
                alert('Error saving colors. Please try again.');
            }
        });
    });

});