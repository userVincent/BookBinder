
document.addEventListener('DOMContentLoaded', function() {
    loadPreviousData();
});

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
    resultHTML += '<div class="profile-row">';
    for (var i = 0; i < results.length; i += 1) {
        var result = results[i];
        resultHTML += '<a class="check-profile" href="/profile/public/' + result.id + '"><div class="profile">';
        resultHTML += '<div class="profile-content">';
        resultHTML += '<div class="ProfileBox"><img class="profile-img" src="/uploads/profilepics/' + result.profilepicFilename + '" alt="Profile picture"></div>';
        resultHTML += '<div><h3>' + result.firstname + ' ' + result.lastname + '</h3></div>';
        resultHTML += '</div></a>';
        resultHTML += '<button class="selectButton" onclick="sendRequest(' + result.id + ')">Send request</button>';
        resultHTML += '</div>';
    }
    resultHTML += '</div>';
    outputElement.innerHTML = resultHTML;
    sessionStorage.setItem('searchResults', JSON.stringify(results));
}


function sendRequest(userId) {
    window.location.href = `/meetups/arrange/create_meetup/${userId}`;
}


function loadPreviousData(){
    var savedResults = sessionStorage.getItem('searchResults');
    if (savedResults) {
        var results = JSON.parse(savedResults);
        displaySearchResults(results);
    }
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
