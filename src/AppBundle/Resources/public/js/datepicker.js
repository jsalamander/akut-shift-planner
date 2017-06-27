$(function() {
    var start = $.format.date(new Date(), "yyyy-MM-dd");
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        startDate: start,
        todayHighlight: true,
        autoclose: true
    });
    $('.datepicker').attr('readOnly', true)
});