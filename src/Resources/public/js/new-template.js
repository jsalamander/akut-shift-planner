$(function() {
    $('#App_plan_isTemplate').change(function() {
        if(this.checked) {
            hideEmailAddress();
            hideDate();
            showIsPublic();
        } else {
            showEmailAddress();
            showDate();
            hideIsPublic();
        }
    });
});

function hideEmailAddress() {
    $('#App_plan_email').parent().css('display', 'none');
    $('#App_plan_email').val('template@placeholder.ch');
}

function showEmailAddress() {
    $('#App_plan_email').parent().css('display', '');
    $('#App_plan_email').val('');
}

function hideDate() {
    $('#App_plan_date').parent().css('display', 'none');
    $('#App_plan_date').val('2099-01-01');
}

function showDate() {
    $('#App_plan_date').parent().css('display', '');
    $('#App_plan_date').parent().parent().css('display', '');
    $('#App_plan_date').val('');
}

function showIsPublic() {
    $('#isPublic').css('display', '');
}

function hideIsPublic() {
    $('#isPublic').css('display', 'none');
    $('#isPublic input').prop('checked', false);;
}
