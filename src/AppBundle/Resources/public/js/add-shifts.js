$(function() {
    var $collectionHolder;

    // setup an "add a shift" link
    var $addShiftLink = $('<a href="#" class="add_shift_link">Add a shift</a>');
    var $newLinkLi = $('<li></li>').append($addShiftLink);

        // Get the ul that holds the collection of shifts
        $collectionHolder = $('ul.shifts');

        // add the "add a shift" anchor and li to the shifts ul
        $collectionHolder.append($newLinkLi);

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addShiftLink.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // add a new shift form (see next code block)
            addShiftForm($collectionHolder, $newLinkLi);
        });
});

function addShiftForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a shift" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}