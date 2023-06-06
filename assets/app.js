// assets/app.js
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
// import './styles/login_register.css';
// import './styles/home.css';
// import './styles/bookprofile.css';
// import './styles/meetup.css';
import './styles/app.scss';
// start the Stimulus application
//import './bootstrap';
// import 'bootstrap/js/dist/alert';
// loads the jquery package from node_modules

// app.js

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything


$(document).ready(function() {

    $('div.alert').not('.alert-important').not('.alert-danger').delay(500).fadeIn('normal', function() {
        $(this).delay(3000).fadeOut(350);
    });
});
