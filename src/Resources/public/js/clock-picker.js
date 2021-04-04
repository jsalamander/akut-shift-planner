$(function() {
    $('.add_shift_link').on('click', function(e) {
        e.preventDefault();
        initClockPicker();
    });

    initClockPicker();
});

function initClockPicker() {
    $('.clockpicker').each(function (key, el) {
        $(el).clockpicker(config);
    })
}


var config = {
    donetext: Translator.trans('done'),
    autoclose: true
}