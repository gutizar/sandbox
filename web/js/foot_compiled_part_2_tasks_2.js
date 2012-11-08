// Get the div that holds the collection of tags
var collectionHolder = $('div.tags');

// setup an "add a tag" link
var $addTagLink = $('<a href="#" class="add_tag_link">Add a tag</a>');
var $newLinkDiv = $('<div></div>').append($addTagLink);

function addTagForm(collectionHolder, $newLinkDiv) {
    // Get the data-prototype we explained earlier
    var prototype = collectionHolder.attr('data-prototype');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on the current collection's length.
    var newForm = prototype.replace(/\$\$name\$\$/g, collectionHolder.children().length);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<div class="tag"></div>').append(newForm);
    $newLinkDiv.before($newFormLi);

    // add a delete link to the new form
    var $newFormControls = $newFormLi.find('div.controls');
    addTagFormDeleteLink($newFormControls);
}

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormA = $('<button class="btn btn-mini" type="button">Remove</button>');
    $tagFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $tagFormLi.parents('div.tag').remove();
    });
}

jQuery(document).ready(function() {
	// add a delete link to all of the existing tag form li elements
    collectionHolder.find('div.tag div.controls').each(function() {
        addTagFormDeleteLink($(this));
    });
    // add the "add a tag" anchor and li to the tags ul
    collectionHolder.append($newLinkDiv);

    $addTagLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addTagForm(collectionHolder, $newLinkDiv);
    });
});