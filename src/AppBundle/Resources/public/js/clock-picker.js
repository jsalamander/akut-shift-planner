$(function() {
    $('.add_shift_link').on('click', function(e) {
        console.log('yolo');
        e.preventDefault();
        initClockPicker();
    });

    initClockPicker();
});

function initClockPicker() {
    $('.clockpicker').each(function (key, el) {
        $(el).clockpicker();
    })
}