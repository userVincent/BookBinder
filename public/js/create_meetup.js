function nextPage() {
    // Get the form element
    var form = document.getElementById('meetupDate');

    // Create a hidden input to hold the form data
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'formData');
    hiddenInput.setAttribute('value', JSON.stringify(getFormData(form)));

    // Append the hidden input to the form
    form.appendChild(hiddenInput);

    // Submit the form
    form.submit();
}

function getFormData(form) {
    var formData = {};
    var inputs = form.elements;

    for (var i = 0; i < inputs.length; i++) {
        var input = inputs[i];

        // Exclude the button that triggers the form submission
        if (input.type !== 'submit') {
            formData[input.name] = input.value;
        }
    }

    return formData;
}