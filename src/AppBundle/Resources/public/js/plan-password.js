$(function() {
    $('#passwordPrompt').click(function (e) {
        $('#getPasswordModel').modal('show');
    });

    $('.close-cross').click(function (e) {
        $('#getPasswordModel').modal('hide');
    });
});