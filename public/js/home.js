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

let currentPage = 1;

function loadMoreBooks() {
    const resultsContainer = document.getElementById("output");
    const scrollPosition = resultsContainer.scrollTop + resultsContainer.clientHeight;
    const containerHeight = resultsContainer.scrollHeight;

    if (scrollPosition >= containerHeight) {
        currentPage++;
        getBooks();
    }
}

const container = document.getElementById('output');


function fetchFromAPI(searchString, maxResults = 40) {
    const URL = "https://www.googleapis.com/books/v1/volumes?q="

    const apiUrl = URL + searchString + "&maxResults=" + maxResults ;

    return fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error(`API request failed with status: ${response.status}`);
            }
            return response.json();
        })
        .catch(error => {
            console.error('An error occurred while fetching data from the API:', error);
            throw error;
        });
}
function getBooks(numBooks = 8) {
    document.getElementById('output').innerHTML = "";
    const maxResults = 8; // Number of additional results to fetch
    fetchFromAPI(document.getElementById('input').value, numBooks)
        .then(data => {
            for (var i = 0; i <numBooks; i += 4) {
                const book1 = data.items[i].volumeInfo;
                const book2 = data.items[i + 1].volumeInfo;
                const book3 = data.items[i + 2].volumeInfo;
                const book4 = data.items[i + 3].volumeInfo;

                const title1 = book1.title;
                const author1 = book1.authors ? book1.authors[0] : "Unknown";
                const isbn1 = book1.industryIdentifiers ? book1.industryIdentifiers[0].identifier : "Not available";
                const imageLinks1 = book1.imageLinks ? book1.imageLinks.thumbnail : "no-image-available.jpg"; // Default image if no thumbnail available

                const title2 = book2.title;
                const author2 = book2.authors ? book2.authors[0] : "Unknown";
                const isbn2 = book2.industryIdentifiers ? book2.industryIdentifiers[0].identifier : "Not available";
                const imageLinks2 = book2.imageLinks ? book2.imageLinks.thumbnail : "no-image-available.jpg"; // Default image if no thumbnail available

                const title3 = book3.title;
                const author3 = book3.authors ? book3.authors[0] : "Unknown";
                const isbn3 = book3.industryIdentifiers ? book3.industryIdentifiers[0].identifier : "Not available";
                const imageLinks3 = book3.imageLinks ? book3.imageLinks.thumbnail : "no-image-available.jpg"; // Default image if no thumbnail available

                const title4 = book4.title;
                const author4 = book4.authors ? book4.authors[0] : "Unknown";
                const isbn4 = book4.industryIdentifiers ? book4.industryIdentifiers[0].identifier : "Not available";
                const imageLinks4 = book4.imageLinks ? book4.imageLinks.thumbnail : "no-image-available.jpg"; // Default image if no thumbnail available

                var booksHTML = `
                    <div class="row">
                    <div class="grid-container">
                        <section class="book_container">
                            <div class="book" style="display: flex; width: 50%; margin-bottom: 20px;">
                            <a href="/book_profile/${encodeURIComponent(title1)}/${isbn1}">
                                <div class="book-image" style="flex: 0 0 30%;">
                                    <img src="${imageLinks1}" alt="Book Cover" style="width: 100%; height: 100%; object-fit: contain;">
                                </div>
                            </a>
                            <div class="book-info" style="flex: 1;">
                                <a href="/book_profile/${encodeURIComponent(title1)}/${isbn1}">
                                <h3>${title1}</h3>
                                </a>
                                <p>${author1}</p>
                            </div>
                        </div>
                        </section>
                        
                        <section class="book_container">
                            <div class="book" style="display: flex; width: 50%; margin-bottom: 20px;">  
                                <a href="/book_profile/${encodeURIComponent(title2)}/${isbn2}">
                                <div class="book-image" style="flex: 0 0 30%;">
                                    <img src="${imageLinks2}" alt="Book Cover" style="width: 100%; height: 100%; object-fit: contain;">
                                </div>
                                </a>
                                <div class="book-info" style="flex: 1;">
                                    <a href="/book_profile/${encodeURIComponent(title2)}/${isbn2}">
                                    <h3>${title2}</h3>
                                    </a>
                                    <p>${author2}</p>
                                </div>
                            </div>
                        </section>
                                
                        <section class="book_container">
                            <div class="book" style="display: flex; width: 50%; margin-bottom: 20px;">
                                <a href="/book_profile/${encodeURIComponent(title3)}/${isbn3}">
                                    <div class="book-image" style="flex: 0 0 30%;">
                                        <img src="${imageLinks3}" alt="Book Cover" style="width: 100%; height: 100%; object-fit: contain;">
                                    </div>
                                </a>
                                <div class="book-info" style="flex: 1;">
                                    <a href="/book_profile/${encodeURIComponent(title3)}/${isbn3}">
                                        <h3>${title3}</h3>
                                    </a>
                                    <p>${author3}</p>
                                </div>
                            </div>
                        </section>
                        
                        <section class="book_container">
                            <div class="book" style="display: flex; width: 50%; margin-bottom: 20px;">
                                <a href="/book_profile/${encodeURIComponent(title4)}/${isbn4}">
                                    <div class="book-image" style="flex: 0 0 30%;">
                                        <img src="${imageLinks4}" alt="Book Cover" style="width: 100%; height: 100%; object-fit: contain;">
                                    </div>
                                </a>
                                <div class="book-info" style="flex: 1;">
                                    <a href="/book_profile/${encodeURIComponent(title4)}/${isbn4}">
                                        <h3>${title4}</h3>
                                    </a>
                                    <p>${author4}</p>
                                </div>
                            </div>
                        </section>
                        
                    </div>
                    </div>
                `;

                document.getElementById("output").innerHTML += booksHTML;
                // Attach the loadMoreBooks function to the scroll event of the results container
                document.getElementById("output").addEventListener("scroll", loadMoreBooks);
            }
        });
}

function handleFavorite(bookId, buttonElement) {
    // Send HTTP POST request to your Symfony backend endpoint
    fetch(`favorite-book/${bookId}`, {
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

// Function to check the favorite status of a book
function checkFavoriteStatus(bookId, buttonElement) {
    fetch(`check-favorite-book/${bookId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Book already favorited') {
                buttonElement.textContent = 'Unfavorite';
            } else if (data.message === 'Book not favorited') {
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

