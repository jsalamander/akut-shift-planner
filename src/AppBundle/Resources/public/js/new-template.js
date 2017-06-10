$(function() {
    $('#appbundle_plan_isTemplate').change(function() {
        if(this.checked) {
            hideEmailAddress();
        } else {
            showEmailAddress();
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