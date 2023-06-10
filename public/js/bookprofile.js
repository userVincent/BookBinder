function likeDislike(x) {
    x.classList.toggle("fas fa-heart");
}

function initializeBookProfile(isbn) {
    // check if the book has already been favorited and set the text on the button based on that
    const fav_button = document.getElementById("favorite_button");
    checkFavoriteStatus(isbn, fav_button);

    // Fetch the book image from Google Books API
    fetch(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`)
        .then(response => response.json())
        .then(data => {
            // Extract the image links from the API response
            const book = data.items[0].volumeInfo;
            const title = book.title;
            const author = book.authors ? book.authors[0] : "Unknown";
            const genres = book.categories ? book.categories.join(', ') : "Unknown";
            const description = book.description ? book.description : "No description available";
            const pages = book.pageCount ? book.pageCount : "Unknown";
            const publicationDate = book.publishedDate ? book.publishedDate : "Unknown";
            const language = book.language ? book.language : "Unknown";

            const imageLink = book.imageLinks ? book.imageLinks.thumbnail : "images/no-image-available.png";

            // Update the image source in the HTML
            document.getElementById('book_img').src = imageLink;

            document.getElementById('book_title').textContent = title;
            document.getElementById('author').textContent = "Author: " + author;
            document.getElementById('genres').textContent =  genres;
            document.getElementById('description').textContent = description;
            document.getElementById('pages').textContent = pages;
            document.getElementById('publication_date').textContent = publicationDate;
            document.getElementById('language').textContent = language;
        })
        .catch(error => console.log(error));
}

function handleFavorite(bookId, title, buttonElement) {
    // Send HTTP POST request to your Symfony backend endpoint
    fetch(`/favorite-book/${bookId}/${title}`, {
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


function checkFavoriteStatus(bookId, buttonElement) {
    fetch(`/check-favorite-book/${bookId}`, {
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