/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function toggleMenu() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}



function getBooks() {
    document.getElementById('output').innerHTML = "";
    fetch("https://www.googleapis.com/books/v1/volumes?q=" + document.getElementById('input').value + "&maxResults=40")
        .then(response => response.json())
        .then(data => {
            for (var i = 0; i < 40; i += 2) {
                const book1 = data.items[i].volumeInfo;
                const book2 = data.items[i + 1].volumeInfo;

                const title1 = book1.title;
                const author1 = book1.authors ? book1.authors[0] : "Unknown";
                const isbn1 = book1.industryIdentifiers ? book1.industryIdentifiers[0].identifier : "Not available";
                const pages1 = book1.pageCount ? book1.pageCount : "Unknown";
                const language1 = book1.language ? book1.language : "Unknown";
                const imageLinks1 = book1.imageLinks ? book1.imageLinks.thumbnail : "no-image-available.jpg"; // Default image if no thumbnail available

                const title2 = book2.title;
                const author2 = book2.authors ? book2.authors[0] : "Unknown";
                const isbn2 = book2.industryIdentifiers ? book2.industryIdentifiers[0].identifier : "Not available";
                const pages2 = book2.pageCount ? book2.pageCount : "Unknown";
                const language2 = book2.language ? book2.language : "Unknown";
                const imageLinks2 = book2.imageLinks ? book2.imageLinks.thumbnail : "no-image-available.jpg"; // Default image if no thumbnail available

                var booksHTML = `
                    <div class="row">
                        <div class="book" style="display: flex; width: 50%; margin-bottom: 20px;">
                        <a href="">
                            <div class="book-image" style="flex: 0 0 30%; height: 200px; margin-right: 20px; border: 2px solid gray;">
                                <img src="${imageLinks1}" alt="Book Cover" style="width: 100%; height: 100%; object-fit: contain;">
                            </div>
                        </a>
                            <div class="book-info" style="flex: 1;">
                                <a href=""><h3>Title: ${title1}</h3></a>
                                <p>Author: ${author1}</p>
                                <p>Pages: ${pages1}</p>
                                <p>Language: ${language1}</p>
                                <button class="favorite-button" onclick="handleFavorite('${isbn1}', this)">Favorite</button>
                            </div>
                        </div>
                        <div class="book" style="display: flex; width: 50%; margin-bottom: 20px;">
                        <a href="">
                            <div class="book-image" style="flex: 0 0 30%; height: 200px; margin-right: 20px; border: 2px solid gray;">
                                <img src="${imageLinks2}" alt="Book Cover" style="width: 100%; height: 100%; object-fit: contain;">
                            </div>
                        </a>
                            <div class="book-info" style="flex: 1;">
                                <a href=""><h3>Title: ${title2}</h3></a>
                                <p>Author: ${author2}</p>
                                <p>Pages: ${pages2}</p>
                                <p>Language: ${language2}</p>
                                <button class="favorite-button" onclick="">Favorite</button>
                            </div>
                        </div>
                    </div>
                `;

                document.getElementById("output").innerHTML += booksHTML;
            }
        });
}

function handleFavorite(bookId, buttonElement) {
    // Send HTTP POST request to your Symfony backend endpoint
    fetch('favorite-book?bookId=${bookId}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ bookId: bookId }),
    })
        .then(response => response.json())
        .then(data => {
            // Check the response message and update the button accordingly
            if (data.message === 'Book favorited successfully') {
                buttonElement.textContent = 'Unfavorite';
                // Add any additional styling or behavior changes as needed
            } else if (data.message === 'Book unfavorited successfully') {
                buttonElement.textContent = 'Favorite';
                // Add any additional styling or behavior changes as needed
            } else {
                // Handle other possible response messages or errors
            }
        })
        .catch(error => {
            // Handle any errors that occur during the request
        });
}

// Get the input field and search button elements
const input = document.getElementById('input');
const searchButton = document.getElementById('search-button');

// Add event listener to the input field for keyup event
input.addEventListener('keyup', function(event) {
    if (event.key === 'Enter') {
        // Trigger search function
        getBooks();
    }
});

