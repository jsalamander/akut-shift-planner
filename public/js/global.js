$(function() {
    //init scrolling animations
    new WOW().init();
    $('.multiselect').multiselect();
    SocialShareKit.init({
        text: "Schreibe dich ein!"
    });

    // init multi select of semantic ui
    $('.ui.dropdown')
        .dropdown()
    ;
});