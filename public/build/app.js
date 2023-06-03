"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["app"],{

/***/ "./assets/app.js":
/*!***********************!*\
  !*** ./assets/app.js ***!
  \***********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _styles_app_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./styles/app.scss */ "./assets/styles/app.scss");
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

// start the Stimulus application
//import './bootstrap';
// import 'bootstrap/js/dist/alert';
// loads the jquery package from node_modules

// app.js

var $ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything

$(document).ready(function () {
  $('div.alert').not('.alert-important').delay(500).fadeIn('normal', function () {
    $(this).delay(3000).fadeOut(350);
  });
});
// $(function() {
//     $('div.alert').not('.alert-important').delay(5000).fadeIn(500).delay(3000).fadeOut(350);
// });
// $(function() {
//     $('#flash').delay(500).fadeIn('normal', function() {
//         $(this).delay(2500).fadeOut();
//     });
// });

/***/ }),

/***/ "./assets/styles/app.scss":
/*!********************************!*\
  !*** ./assets/styles/app.scss ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["vendors-node_modules_jquery_dist_jquery_js"], () => (__webpack_exec__("./assets/app.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYXBwLmpzIiwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUMyQjtBQUMzQjtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQSxJQUFNQSxDQUFDLEdBQUdDLG1CQUFPLENBQUMsb0RBQVEsQ0FBQztBQUMzQjtBQUNBOztBQUdBRCxDQUFDLENBQUNFLFFBQVEsQ0FBQyxDQUFDQyxLQUFLLENBQUMsWUFBVztFQUV6QkgsQ0FBQyxDQUFDLFdBQVcsQ0FBQyxDQUFDSSxHQUFHLENBQUMsa0JBQWtCLENBQUMsQ0FBQ0MsS0FBSyxDQUFDLEdBQUcsQ0FBQyxDQUFDQyxNQUFNLENBQUMsUUFBUSxFQUFFLFlBQVc7SUFDMUVOLENBQUMsQ0FBQyxJQUFJLENBQUMsQ0FBQ0ssS0FBSyxDQUFDLElBQUksQ0FBQyxDQUFDRSxPQUFPLENBQUMsR0FBRyxDQUFDO0VBQ3BDLENBQUMsQ0FBQztBQUNOLENBQUMsQ0FBQztBQUNGO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7O0FDdkNBIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2FwcC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvc3R5bGVzL2FwcC5zY3NzPzNlOGEiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gYXNzZXRzL2FwcC5qc1xuLypcbiAqIFdlbGNvbWUgdG8geW91ciBhcHAncyBtYWluIEphdmFTY3JpcHQgZmlsZSFcbiAqXG4gKiBXZSByZWNvbW1lbmQgaW5jbHVkaW5nIHRoZSBidWlsdCB2ZXJzaW9uIG9mIHRoaXMgSmF2YVNjcmlwdCBmaWxlXG4gKiAoYW5kIGl0cyBDU1MgZmlsZSkgaW4geW91ciBiYXNlIGxheW91dCAoYmFzZS5odG1sLnR3aWcpLlxuICovXG5cbi8vIGFueSBDU1MgeW91IGltcG9ydCB3aWxsIG91dHB1dCBpbnRvIGEgc2luZ2xlIGNzcyBmaWxlIChhcHAuc2NzcyBpbiB0aGlzIGNhc2UpXG4vLyBpbXBvcnQgJy4vc3R5bGVzL2xvZ2luX3JlZ2lzdGVyLmNzcyc7XG4vLyBpbXBvcnQgJy4vc3R5bGVzL2hvbWUuY3NzJztcbi8vIGltcG9ydCAnLi9zdHlsZXMvYm9va3Byb2ZpbGUuY3NzJztcbi8vIGltcG9ydCAnLi9zdHlsZXMvbWVldHVwLmNzcyc7XG5pbXBvcnQgJy4vc3R5bGVzL2FwcC5zY3NzJztcbi8vIHN0YXJ0IHRoZSBTdGltdWx1cyBhcHBsaWNhdGlvblxuLy9pbXBvcnQgJy4vYm9vdHN0cmFwJztcbi8vIGltcG9ydCAnYm9vdHN0cmFwL2pzL2Rpc3QvYWxlcnQnO1xuLy8gbG9hZHMgdGhlIGpxdWVyeSBwYWNrYWdlIGZyb20gbm9kZV9tb2R1bGVzXG5cbi8vIGFwcC5qc1xuXG5jb25zdCAkID0gcmVxdWlyZSgnanF1ZXJ5Jyk7XG4vLyB0aGlzIFwibW9kaWZpZXNcIiB0aGUganF1ZXJ5IG1vZHVsZTogYWRkaW5nIGJlaGF2aW9yIHRvIGl0XG4vLyB0aGUgYm9vdHN0cmFwIG1vZHVsZSBkb2Vzbid0IGV4cG9ydC9yZXR1cm4gYW55dGhpbmdcblxuXG4kKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpIHtcblxuICAgICQoJ2Rpdi5hbGVydCcpLm5vdCgnLmFsZXJ0LWltcG9ydGFudCcpLmRlbGF5KDUwMCkuZmFkZUluKCdub3JtYWwnLCBmdW5jdGlvbigpIHtcbiAgICAgICAgJCh0aGlzKS5kZWxheSgzMDAwKS5mYWRlT3V0KDM1MCk7XG4gICAgfSk7XG59KTtcbi8vICQoZnVuY3Rpb24oKSB7XG4vLyAgICAgJCgnZGl2LmFsZXJ0Jykubm90KCcuYWxlcnQtaW1wb3J0YW50JykuZGVsYXkoNTAwMCkuZmFkZUluKDUwMCkuZGVsYXkoMzAwMCkuZmFkZU91dCgzNTApO1xuLy8gfSk7XG4vLyAkKGZ1bmN0aW9uKCkge1xuLy8gICAgICQoJyNmbGFzaCcpLmRlbGF5KDUwMCkuZmFkZUluKCdub3JtYWwnLCBmdW5jdGlvbigpIHtcbi8vICAgICAgICAgJCh0aGlzKS5kZWxheSgyNTAwKS5mYWRlT3V0KCk7XG4vLyAgICAgfSk7XG4vLyB9KTsiLCIvLyBleHRyYWN0ZWQgYnkgbWluaS1jc3MtZXh0cmFjdC1wbHVnaW5cbmV4cG9ydCB7fTsiXSwibmFtZXMiOlsiJCIsInJlcXVpcmUiLCJkb2N1bWVudCIsInJlYWR5Iiwibm90IiwiZGVsYXkiLCJmYWRlSW4iLCJmYWRlT3V0Il0sInNvdXJjZVJvb3QiOiIifQ==