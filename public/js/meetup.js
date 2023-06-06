function selectPeople() {

    var searchQuery = document.getElementById('input').value;
    searchPeople(searchQuery);
}
function searchPeople(searchQuery) {
    // Send an AJAX request to the backend controller
    // Replace "peopleSelectController" with the actual route/controller handling the search request
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/searchProfiles', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            displaySearchResults(response);
        }
    };
    xhr.send('searchQuery=' + encodeURIComponent(searchQuery));
}

function displaySearchResults(results) {
    var outputElement = document.getElementById('output');
    outputElement.innerHTML = ''; // Clear previous results

    if (results.length === 0) {
        outputElement.innerHTML = '<p>No results found.</p>';
        return;
    }

    var resultHTML = '';
    for (var i = 0; i < results.length; i++) {
        var result = results[i];
        resultHTML += '<div class="profile">';
        resultHTML += '<h3>' + result.firstname + result.lastname + '</h3>';
        resultHTML += '<h3>' + result.address + '</h3>';
        resultHTML += '<h3>' + result.email + '</h3>'; // Adjust the property name if needed resultHTML += '<h3>' + result.name + '</h3>
        resultHTML += '</div>';
    }

    outputElement.innerHTML = resultHTML;
}

function openMeetups(evt, meetups) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(meetups).style.display = "block";
    evt.currentTarget.className += " active";
}
