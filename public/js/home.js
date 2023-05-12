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

function getbooks() {
    document.getElementById('output').innerHTML = "";
    fetch("https://www.googleapis.com/books/v1/volumes?q=" + document.getElementById('input').value)
        .then(response => response.json())
        .then(data => {
            for (var i = 0; i < 10; i++) {
                var title = data.items[i].volumeInfo.title;

                var bookHTML = "<h2>" + title + "</h2>";
                document.getElementById("output").innerHTML += bookHTML;
            }
        });
}


