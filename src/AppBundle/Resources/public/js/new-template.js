$(function() {
    $('#appbundle_plan_isTemplate').change(function() {
        if(this.checked) {
            hideEmailAddress();
            hideDate();
        } else {
            showEmailAddress();
            showDate();
        }
    });
});

function hideEmailAddress() {
    $('#appbundle_plan_email').parent().css('display', 'none');
    $('#appbundle_plan_email').val('template@placeholder.ch');
}

function showEmailAddress() {
    $('#appbundle_plan_email').parent().css('display', '');
    $('#appbundle_plan_email').val('');
}

function hideDate() {
    $('#appbundle_plan_date').parent().css('display', 'none');
    $('#appbundle_plan_date').val('2000-01-01');
}

function showDate() {
    $('#appbundle_plan_date').parent().css('display', '');
    $('#appbundle_plan_date').parent().parent().css('display', '');
    $('#appbundle_plan_date').val('');
}