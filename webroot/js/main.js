$(function() {

	// Validates the form before a submit
    $( "form" ).submit( function () {
		// Clears any existing errors
        $('#errors .error').text('');
        $('#errors').hide();
        var validated = validateForm();

        if ( validated ) {
			// No errors so we let the form being sent
            return true;
        }

		// Prevent the form to be sent and show the errors
        $('#errors').show();
        return false;
    });

});

// Checks if given string contains numbers
// Returns true if number is found
function containsNumber(string){
	return string.match(/\d+/g);
}

// Checks if given string contains any html-code
// Returns true if number is found
function containsHTML(string){
	// Try to match what is inside "<>" if there is any
	return string.match(/<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/);
}

// Validates the form
function validateForm() {
	var validated = true;
	var message = new Array();

	var description = $('textarea[name="product[description]"]');
	var descriptionVal = description.val();
	if ( containsHTML( descriptionVal ) ) {
		// description contains html
		validated = false;
		message.push('Description must not contain any html-code.');
		description.focus();
	}

	if (!validated) {
		var length = message.length;
		var element = null;
		$('#errors').empty();
		for (var i = 0; i < length; i++) {
			text = message[i];
			$('#errors').append('<div class="error">' + text + '</div>');
		}
	}

	return validated;
}