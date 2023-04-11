$(document).ready(function() {
    $('#sign-up-form').submit(function(event) {
        var payload = {
            email: $('input[name=email]').val(),
            password: $('input[name=password]').val(),
            repeat_password: $('input[name=repeatPassword]').val(),
            coins: $('input[name=coins]').val(),
        };

        $.ajax({
            type: 'POST',
            url: '/sign-up',
            contentType: 'application/json;charset=utf-8',
            data: JSON.stringify(payload), // our data object
            dataType: 'json' // what type of data do we expect back from the server
        })
            .done(function(data) {
                console.log(data);
            })
            .fail(function(error) {
                console.log(error);
            });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });
});