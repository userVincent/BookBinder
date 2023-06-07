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

window.onload = function() {
    displayTrendingBooks();
};


function displayTrendingBooks() {
    document.getElementById('output').innerHTML = "";

    // Fetch the trending books from the NYT API
    fetch("https://api.nytimes.com/svc/books/v3/lists/current/hardcover-fiction.json?api-key=NhXVWQ20PAjJUUAVw2n6OoH4KJW4U0X1")
        .then(response => response.json())
        .then(data => {
            const books = data.results.books;

            // Iterate through the books and display them
            for (let i = 0; i < 12; i += 4) {
                const book1 = books[i];
                const book2 = books[i+1];
                const book3 = books[i+2];
                const book4 = books[i+3];

                const title1 = book1.title;
                const author1 = book1.author;
                const isbn1 = book1.primary_isbn13;
                const imageLinks1 = book1.book_image ? book1.book_image : "images/no-image-available.png"; // Default image if no thumbnail available

                const title2 = book2.title;
                const author2 = book2.author;
                const isbn2 = book2.primary_isbn13;
                const imageLinks2 = book2.book_image ? book2.book_image : "images/no-image-available.png"; // Default image if no thumbnail available

                const title3 = book3.title;
                const author3 = book3.author;
                const isbn3 = book3.primary_isbn13;
                const imageLinks3 = book3.book_image ? book3.book_image : "images/no-image-available.png"; // Default image if no thumbnail available

                const title4 = book4.title;
                const author4 = book4.author;
                const isbn4 = book4.primary_isbn13;
                const imageLinks4 = book4.book_image ? book4.book_image : "images/no-image-available.png"; // Default image if no thumbnail available

                // Create the HTML
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
                document.getElementById('home-output-heading').textContent = 'Check out some of the trending books from this week!';

            }
        });
}


function getBooks() {
    if (document.getElementById('input').value.trim() === "") {
        displayTrendingBooks();
    }
    else {
        document.getElementById('output').innerHTML = "";
        fetch("https://www.googleapis.com/books/v1/volumes?q=" + document.getElementById('input').value + "&maxResults=40")
            .then(response => response.json())
            .then(data => {
                for (var i = 0; i <20; i += 4) {
                    const book1 = data.items[i].volumeInfo;
                    const book2 = data.items[i + 1].volumeInfo;
                    const book3 = data.items[i + 2].volumeInfo;
                    const book4 = data.items[i + 3].volumeInfo;

                    const title1 = book1.title;
                    const author1 = book1.authors ? book1.authors[0] : "Unknown";
                    const isbn1 = book1.industryIdentifiers ? book1.industryIdentifiers[0].identifier : "Not available";
                    const imageLinks1 = book1.imageLinks ? book1.imageLinks.thumbnail : "images/no-image-available.png"; // Default image if no thumbnail available

                    const title2 = book2.title;
                    const author2 = book2.authors ? book2.authors[0] : "Unknown";
                    const isbn2 = book2.industryIdentifiers ? book2.industryIdentifiers[0].identifier : "Not available";
                    const imageLinks2 = book2.imageLinks ? book2.imageLinks.thumbnail : "images/no-image-available.png"; // Default image if no thumbnail available

                    const title3 = book3.title;
                    const author3 = book3.authors ? book3.authors[0] : "Unknown";
                    const isbn3 = book3.industryIdentifiers ? book3.industryIdentifiers[0].identifier : "Not available";
                    const imageLinks3 = book3.imageLinks ? book3.imageLinks.thumbnail : "images/no-image-available.png"; // Default image if no thumbnail available

                    const title4 = book4.title;
                    const author4 = book4.authors ? book4.authors[0] : "Unknown";
                    const isbn4 = book4.industryIdentifiers ? book4.industryIdentifiers[0].identifier : "Not available";
                    const imageLinks4 = book4.imageLinks ? book4.imageLinks.thumbnail : "images/no-image-available.png"; // Default image if no thumbnail available

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
                    document.getElementById('home-output-heading').textContent = 'Showing search results for "' + document.getElementById('input').value + '"';

                }
            });
        sessionStorage.setItem('searchResults', JSON.stringify(results));
    }

}

function loadPreviousData() {
    var savedResults = sessionStorage.getItem('searchResults');
    if (savedResults) {
        var results = JSON.parse(savedResults);
        getBooks(results);
    }
}








document.addEventListener("keyup", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        document.getElementById('search-button').click();
    }
});

