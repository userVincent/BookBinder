let currentPage = 1;
const pageSize = 12;
let isLoading = false;
let hasMoreLibraries = true;
let town = null;

function fetchLibraries() {
    if (!isLoading && hasMoreLibraries) {
        isLoading = true;
        if (town != null) {
            fetch('/libraries/data?page=' + currentPage + '&size=' + pageSize + '&town=' + town)
                .then(response => response.json())
                .then(data => {
                    appendLibraries(data.data);
                    currentPage++;
                    isLoading = false;

                    if (data.data.length < pageSize) {
                        hasMoreLibraries = false;
                    }
                });
        }
        else {
            fetch('/libraries/data?page=' + currentPage + '&size=' + pageSize)
            .then(response => response.json())
            .then(data => {
                appendLibraries(data.data);
                currentPage++;
                isLoading = false;

                if (data.data.length < pageSize) {
                    hasMoreLibraries = false;
                }
            });
        }
    }
}

function appendLibraries(libraries) {
    const container = document.getElementById('librariesContainer');
    const isLibrarySelectionPage = window.location.href.includes('/library_select');

    libraries.forEach(library => {
        var address = `${library.StreetName} ${library.HouseNumber}, ${library.PostalCode} ${library.Town}`;
        //createLibraryElement(library, address, 0, 0, isLibrarySelectionPage);
        if (library.Latitude != 0.00000000 && library.Longitude != 0.00000000) {
            createLibraryElement(library, address, library.Latitude, library.Longitude, isLibrarySelectionPage);
        }
        else {
            fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(address))
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.length > 0) {
                    var lat = data[0].lat;
                    var lon = data[0].lon;
                    
                    // Create library element with map
                    createLibraryElement(library, address, lat, lon, isLibrarySelectionPage);

                    // Update library coordinates in database
                    fetch('/libraries/update_coordinates', {
                        method: 'POST',
                        headers: {
                        'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                        libraryId: library.id,
                        latitude: lat,
                        longitude: lon,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data);
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }
            })
            .catch(function(error) {
                console.error('Geocoding error:', error);
            });

        }
    });
    
}

function createLibraryElement(library, address, lat, lon, isLibrarySelectionPage) {
    const container = document.getElementById('librariesContainer');

    const libraryElement = document.createElement('div');
    const mapId = `mapid-${library.id}`;

    if (isLibrarySelectionPage) {
        libraryElement.innerHTML = `
            <h2>
                <a href="#" onclick="selectLibrary(${library.id});">
                    ${library.name}
                </a>
            </h2>
            <div id="${mapId}" style="height: 200px;"></div>
            <p>${address}</p>
        `;
    } else {
        libraryElement.innerHTML = `
            <h2>
                <a href="/library/${library.id}">
                    ${library.name}
                </a>
            </h2>
            <div id="${mapId}" style="height: 200px;"></div>
            <p>${address}</p>
        `;
    }
    libraryElement.className = "library";
    container.appendChild(libraryElement);

    // Create map for the library
    var map = L.map(mapId).setView([lat, lon], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
        maxZoom: 18
    }).addTo(map);
}

function selectLibrary(libraryId) {
    window.location.href = `/meetups/arrange/person_select/${libraryId}`;
}

function scrollHandler() {
    if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 100) {
        fetchLibraries();
    }
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;',
        '/': '&#x2F;'
    };
    return text.replace(/[&<>"'\/]/g, function(m) {
        return map[m];
    });
}

function searchLibrary() {
    const inputElement = document.getElementById('input');
    const searchTerm = encodeURIComponent(inputElement.value);
    town = searchTerm;
    currentPage = 1;  // Reset pagination
    hasMoreLibraries = true;  // Reset the flag to allow loading more libraries

    // Clear current libraries
    const container = document.getElementById('librariesContainer');
    container.innerHTML = '';

    // Modify the fetch URL to include the search term
    fetch('/libraries/data?page=' + currentPage + '&size=' + pageSize + '&town=' + searchTerm)
        .then(response => response.json())
        .then(data => {
            appendLibraries(data.data);
            currentPage++;
            isLoading = false;

            if (data.data.length < pageSize) {
                hasMoreLibraries = false;
            }
        });
}

window.addEventListener('scroll', scrollHandler);
fetchLibraries(); // Fetch initial data