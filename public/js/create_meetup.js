function nextPage() {
    // Get the date-time value
    var datetime = document.getElementById('datetime').value;

    // Send the value to the server
    fetch("{{ path('update_session') }}", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            // add CSRF token if needed
        },
        body: JSON.stringify({datetime: datetime})
    })
    .then(response => response.json())
    .then(data => {
        // Redirect to the next page if the session update was successful
        if (data.success) {
            window.location.href = "{{ path('library_select') }}";
        }
    });
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