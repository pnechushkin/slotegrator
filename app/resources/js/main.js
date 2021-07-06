$(document).ready(function () {
    $("#get_prize").submit(function (e) {
        e.preventDefault();
        var api_token = $('#api_token').val();

        if (api_token === undefined || api_token === '') {
            alert("Please try again latter");
            return false;
        }

        $.ajax({
            type: "PUT",
            url: "/api/get-prize",
            dataType: 'json',
            headers: {
                "Authorization:": "Bearer " + api_token
            },
            beforeSend: function () {
                console.log('beforeSend')
            },
            success: function (data) {
                console.log(data)
            },
            complete: function () {
                console.log('complete')
            },
            error: function (xhr, status, error) {
                console.log(error, xhr)
            }
        });

        return false;
    });

});
