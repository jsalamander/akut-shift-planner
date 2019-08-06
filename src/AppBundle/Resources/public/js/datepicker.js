$(function() {
    var start = $.format.date(new Date(), "yyyy-MM-dd");
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        startDate: start,
        todayHighlight: true,
        autoclose: true,
        weekStart: 1
    });
    $('.datepicker').attr('readOnly', true)
});