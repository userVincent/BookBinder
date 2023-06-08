let currentPage = 1;
const pageSize = 12;
let isLoading = false;
let hasMoreLibraries = true;

function fetchLibraries() {
    if (!isLoading && hasMoreLibraries) {
        isLoading = true;
        
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

function appendLibraries(libraries) {
    const container = document.getElementById('librariesContainer');
    const isLibrarySelectionPage = window.location.href.includes('/library_select');

    libraries.forEach(library => {
        var address = `${library.StreetName} ${library.HouseNumber}, ${library.PostalCode} ${library.Town}`;

        fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(address))
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.length > 0) {
                    var lat = data[0].lat;
                    var lon = data[0].lon;

                    var map = L.map('mapid').setView([lat, lon], 13);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
                        maxZoom: 18
                    }).addTo(map);

                    L.marker([lat, lon]).addTo(map).bindPopup(library.name|escapeHtml('js')).openPopup();

                    document.getElementById('address').textContent = address;
                }
            });

        const libraryElement = document.createElement('div');
        if (isLibrarySelectionPage) {
            libraryElement.innerHTML = `
                    <h2>
                        <a href="#" onclick="selectLibrary(${library.id}); libraryForm.submit();">
                            ${library.name}
                        </a>
                    </h2>
                    <div id="mapid" style="height: 200px;"></div>
                    <p>${address}</p>
                `;
        } else {
            libraryElement.innerHTML = `
                    <h2>
                        <a href="/library/${library.id}">
                            ${library.name}
                        </a>
                    </h2>
                    <div id="mapid" style="height: 200px;"></div>
                    <p>${address}</p>
                `;
        }
        libraryElement.className = "library"
        container.appendChild(libraryElement);
    });
}

function selectLibrary(libraryId) {
    document.getElementById('selectedLibraryId').value = libraryId;
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

window.addEventListener('scroll', scrollHandler);
fetchLibraries(); // Fetch initial data