// для login.php
$(document).ready(function() {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();

        var login = $('#login').val();
        var password = $('#password').val();

        $.ajax({
            url: 'login_controller.php',
            type: 'POST',
            dataType: 'json',
            data: {
                login: login,
                password: password
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = '../index/index.php';
                } else {
                    alert(response.error);
                }
            }
        });
    });
});

// для registeg.php
$(document).ready(function() {
    $('#registerForm').on('submit', function(e) {
        e.preventDefault();

        var login = $('#login').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirmPassword').val();

        $.ajax({
            url: 'register_controller.php',
            type: 'POST',
            dataType: 'json',
            data: {
                login: login,
                password: password,
                confirmPassword: confirmPassword,
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = 'login.php';
                } else {
                    alert(response.error);
                }
            }
        });
    });
});