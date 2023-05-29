function getUpcomingMeetups() {
    fetch("Upcoming meetings from database")
        .then(response => response.json())
        .then(data => {
            for (var i = 0; i <20; i += 4) {
                const book1 = data.items[i].volumeInfo;

                const title1 = book1.title;
                const author1 = book1.authors ? book1.authors[0] : "Unknown";
                const isbn1 = book1.industryIdentifiers ? book1.industryIdentifiers[0].identifier : "Not available";
                const imageLinks1 = book1.imageLinks ? book1.imageLinks.thumbnail : "no-image-available.jpg"; // Default image if no thumbnail available

                var booksHTML = `
                    <div class="profile">
                        <img id="profile_picture" src="{{ asset('images/Profile.png') }}" alt="Profile picture">
                    <div class="person">
                        <h2>John Doe</h2>
                        <a href="#library page" class="Library">Bib Tweebronnen</a><br><br>
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-linkedin"></i></a><br>
                        <button type="button" class="cancel"> Cancel meetup</button>
                    </div>
                    <div class="date">
                        <p><b>Meetup time</b></p>
                    </div>
                </div>
                `;

                document.getElementById("output").innerHTML += booksHTML;
            }
        });
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

// Get the element with id="UpcomingMeetups" and click on it
document.getElementById("defaultOpen").click();