(self["webpackChunk"] = self["webpackChunk"] || []).push([["app"],{

/***/ "./assets/app.js":
/*!***********************!*\
  !*** ./assets/app.js ***!
  \***********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _styles_app_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./styles/app.scss */ "./assets/styles/app.scss");
/* harmony import */ var _js_dropdown__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./js/dropdown */ "./assets/js/dropdown.js");
/* harmony import */ var _js_dropdown__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_js_dropdown__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _js_sidebar__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./js/sidebar */ "./assets/js/sidebar.js");
/* harmony import */ var _js_sidebar__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_js_sidebar__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _fortawesome_fontawesome_free_css_all_css__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @fortawesome/fontawesome-free/css/all.css */ "./node_modules/@fortawesome/fontawesome-free/css/all.css");
/* harmony import */ var _js_dropdonw_role_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./js/dropdonw_role.js */ "./assets/js/dropdonw_role.js");
/* harmony import */ var _js_dropdonw_role_js__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_js_dropdonw_role_js__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var chart_js_auto__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! chart.js/auto */ "./node_modules/chart.js/auto/auto.js");
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)



// import './js/image';
// import './js/pass';




/***/ }),

/***/ "./assets/js/dropdonw_role.js":
/*!************************************!*\
  !*** ./assets/js/dropdonw_role.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(/*! core-js/modules/es.array.for-each.js */ "./node_modules/core-js/modules/es.array.for-each.js");
__webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
__webpack_require__(/*! core-js/modules/web.dom-collections.for-each.js */ "./node_modules/core-js/modules/web.dom-collections.for-each.js");
__webpack_require__(/*! core-js/modules/es.function.name.js */ "./node_modules/core-js/modules/es.function.name.js");
document.addEventListener('DOMContentLoaded', function () {
  var labels = document.querySelectorAll('label[data-target]');
  labels.forEach(function (label) {
    var targetId = label.getAttribute('data-target');
    var input = document.getElementById(targetId);
    input.addEventListener('change', function () {
      var fileName = this.files[0].name;
      label.textContent = ' ' + fileName;
    });
  });
});

/***/ }),

/***/ "./assets/js/dropdown.js":
/*!*******************************!*\
  !*** ./assets/js/dropdown.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(/*! core-js/modules/es.array.for-each.js */ "./node_modules/core-js/modules/es.array.for-each.js");
__webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
document.addEventListener('DOMContentLoaded', function () {
  var dropdowns = ['FactureDevis', 'Gestion', 'Comptabilite'];
  dropdowns.forEach(function (dropdown) {
    var button = document.getElementById('dropdown' + dropdown);
    var menu = document.getElementById('menu' + dropdown);
    var icon = document.getElementById('icon' + dropdown);
    button.addEventListener('click', function () {
      menu.classList.toggle('hidden');
      // Faire pivoter l'icône de 180 degrés quand le menu est ouvert
      icon.classList.toggle('rotate-180');
    });
  });
});

/***/ }),

/***/ "./assets/js/sidebar.js":
/*!******************************!*\
  !*** ./assets/js/sidebar.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(/*! core-js/modules/es.array.for-each.js */ "./node_modules/core-js/modules/es.array.for-each.js");
__webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
__webpack_require__(/*! core-js/modules/web.dom-collections.for-each.js */ "./node_modules/core-js/modules/web.dom-collections.for-each.js");
__webpack_require__(/*! core-js/modules/es.regexp.exec.js */ "./node_modules/core-js/modules/es.regexp.exec.js");
__webpack_require__(/*! core-js/modules/es.string.replace.js */ "./node_modules/core-js/modules/es.string.replace.js");
document.addEventListener('DOMContentLoaded', function () {
  // Votre script existant pour les menus déroulants
  var toggleButtons = document.querySelectorAll('[id^="toggle"]');
  toggleButtons.forEach(function (button) {
    button.addEventListener('click', function (e) {
      e.stopPropagation();
      var menuId = button.getAttribute('id').replace('toggle', 'menu');
      var menu = document.getElementById(menuId);
      menu.classList.toggle('hidden');
      document.addEventListener('click', function (e) {
        if (!menu.contains(e.target) && !button.contains(e.target)) {
          menu.classList.add('hidden');
        }
      });
    });
  });

  // Gestion du menu de profil utilisateur
  var userProfileContainer = document.getElementById('userProfileContainer');
  userProfileContainer.addEventListener('click', function () {
    var userMenu = document.getElementById('userMenu');
    userMenu.classList.toggle('hidden');
  });

  // Fermer le menu utilisateur si on clique en dehors
  document.addEventListener('click', function (e) {
    if (!userProfileContainer.contains(e.target)) {
      var userMenu = document.getElementById('userMenu');
      if (!userMenu.classList.contains('hidden')) {
        userMenu.classList.add('hidden');
      }
    }
  });
});

/***/ }),

/***/ "./assets/styles/app.scss":
/*!********************************!*\
  !*** ./assets/styles/app.scss ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["vendors-node_modules_fortawesome_fontawesome-free_css_all_css-node_modules_core-js_modules_es-427194"], () => (__webpack_exec__("./assets/app.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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QUFJLENBQUNFLFNBQVMsQ0FBQ1MsR0FBRyxDQUFDLFFBQVEsQ0FBQztRQUNoQztNQUNKLENBQUMsQ0FBQztJQUNOLENBQUMsQ0FBQztFQUNOLENBQUMsQ0FBQzs7RUFFRjtFQUNBLElBQU1DLG9CQUFvQixHQUFHN0IsUUFBUSxDQUFDUyxjQUFjLENBQUMsc0JBQXNCLENBQUM7RUFDNUVvQixvQkFBb0IsQ0FBQzVCLGdCQUFnQixDQUFDLE9BQU8sRUFBRSxZQUFNO0lBQ2pELElBQU02QixRQUFRLEdBQUc5QixRQUFRLENBQUNTLGNBQWMsQ0FBQyxVQUFVLENBQUM7SUFDcERxQixRQUFRLENBQUNYLFNBQVMsQ0FBQ0MsTUFBTSxDQUFDLFFBQVEsQ0FBQztFQUN2QyxDQUFDLENBQUM7O0VBRUY7RUFDQXBCLFFBQVEsQ0FBQ0MsZ0JBQWdCLENBQUMsT0FBTyxFQUFFLFVBQUNxQixDQUFDLEVBQUs7SUFDdEMsSUFBSSxDQUFDTyxvQkFBb0IsQ0FBQ0gsUUFBUSxDQUFDSixDQUFDLENBQUNLLE1BQU0sQ0FBQyxFQUFFO01BQzFDLElBQU1HLFFBQVEsR0FBRzlCLFFBQVEsQ0FBQ1MsY0FBYyxDQUFDLFVBQVUsQ0FBQztNQUNwRCxJQUFJLENBQUNxQixRQUFRLENBQUNYLFNBQVMsQ0FBQ08sUUFBUSxDQUFDLFFBQVEsQ0FBQyxFQUFFO1FBQ3hDSSxRQUFRLENBQUNYLFNBQVMsQ0FBQ1MsR0FBRyxDQUFDLFFBQVEsQ0FBQztNQUNwQztJQUNKO0VBQ0osQ0FBQyxDQUFDO0FBQ04sQ0FBQyxDQUFDOzs7Ozs7Ozs7Ozs7QUNuQ0YiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvYXBwLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9kcm9wZG9ud19yb2xlLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9kcm9wZG93bi5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvc2lkZWJhci5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvc3R5bGVzL2FwcC5zY3NzPzNlOGEiXSwic291cmNlc0NvbnRlbnQiOlsiLypcbiAqIFdlbGNvbWUgdG8geW91ciBhcHAncyBtYWluIEphdmFTY3JpcHQgZmlsZSFcbiAqXG4gKiBXZSByZWNvbW1lbmQgaW5jbHVkaW5nIHRoZSBidWlsdCB2ZXJzaW9uIG9mIHRoaXMgSmF2YVNjcmlwdCBmaWxlXG4gKiAoYW5kIGl0cyBDU1MgZmlsZSkgaW4geW91ciBiYXNlIGxheW91dCAoYmFzZS5odG1sLnR3aWcpLlxuICovXG5cbi8vIGFueSBDU1MgeW91IGltcG9ydCB3aWxsIG91dHB1dCBpbnRvIGEgc2luZ2xlIGNzcyBmaWxlIChhcHAuY3NzIGluIHRoaXMgY2FzZSlcbmltcG9ydCAnLi9zdHlsZXMvYXBwLnNjc3MnO1xuaW1wb3J0ICcuL2pzL2Ryb3Bkb3duJztcbmltcG9ydCAnLi9qcy9zaWRlYmFyJztcbi8vIGltcG9ydCAnLi9qcy9pbWFnZSc7XG4vLyBpbXBvcnQgJy4vanMvcGFzcyc7XG5pbXBvcnQgJ0Bmb3J0YXdlc29tZS9mb250YXdlc29tZS1mcmVlL2Nzcy9hbGwuY3NzJztcbmltcG9ydCAnLi9qcy9kcm9wZG9ud19yb2xlLmpzJztcbmltcG9ydCBDaGFydCBmcm9tICdjaGFydC5qcy9hdXRvJztcbiIsImRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ0RPTUNvbnRlbnRMb2FkZWQnLCBmdW5jdGlvbigpIHtcbiAgICB2YXIgbGFiZWxzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnbGFiZWxbZGF0YS10YXJnZXRdJyk7XG4gICAgbGFiZWxzLmZvckVhY2goZnVuY3Rpb24obGFiZWwpIHtcbiAgICAgICAgdmFyIHRhcmdldElkID0gbGFiZWwuZ2V0QXR0cmlidXRlKCdkYXRhLXRhcmdldCcpO1xuICAgICAgICB2YXIgaW5wdXQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0YXJnZXRJZCk7XG4gICAgICAgIGlucHV0LmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsIGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgdmFyIGZpbGVOYW1lID0gdGhpcy5maWxlc1swXS5uYW1lO1xuICAgICAgICAgICAgbGFiZWwudGV4dENvbnRlbnQgPSAnICcgKyBmaWxlTmFtZTtcbiAgICAgICAgfSk7XG4gICAgfSk7XG59KTtcbiIsImRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ0RPTUNvbnRlbnRMb2FkZWQnLCBmdW5jdGlvbiAoKSB7XG4gICAgdmFyIGRyb3Bkb3ducyA9IFsnRmFjdHVyZURldmlzJywgJ0dlc3Rpb24nLCAnQ29tcHRhYmlsaXRlJ107XG4gICAgZHJvcGRvd25zLmZvckVhY2goZnVuY3Rpb24oZHJvcGRvd24pIHtcbiAgICAgICAgdmFyIGJ1dHRvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdkcm9wZG93bicgKyBkcm9wZG93bik7XG4gICAgICAgIHZhciBtZW51ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ21lbnUnICsgZHJvcGRvd24pO1xuICAgICAgICB2YXIgaWNvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdpY29uJyArIGRyb3Bkb3duKTtcblxuICAgICAgICBidXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIG1lbnUuY2xhc3NMaXN0LnRvZ2dsZSgnaGlkZGVuJyk7XG4gICAgICAgICAgICAvLyBGYWlyZSBwaXZvdGVyIGwnaWPDtG5lIGRlIDE4MCBkZWdyw6lzIHF1YW5kIGxlIG1lbnUgZXN0IG91dmVydFxuICAgICAgICAgICAgaWNvbi5jbGFzc0xpc3QudG9nZ2xlKCdyb3RhdGUtMTgwJyk7XG4gICAgICAgIH0pO1xuICAgIH0pO1xufSk7XG5cbiIsImRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ0RPTUNvbnRlbnRMb2FkZWQnLCAoKSA9PiB7XG4gICAgLy8gVm90cmUgc2NyaXB0IGV4aXN0YW50IHBvdXIgbGVzIG1lbnVzIGTDqXJvdWxhbnRzXG4gICAgY29uc3QgdG9nZ2xlQnV0dG9ucyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ1tpZF49XCJ0b2dnbGVcIl0nKTtcblxuICAgIHRvZ2dsZUJ1dHRvbnMuZm9yRWFjaChidXR0b24gPT4ge1xuICAgICAgICBidXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoZSkgPT4ge1xuICAgICAgICAgICAgZS5zdG9wUHJvcGFnYXRpb24oKTtcbiAgICAgICAgICAgIGNvbnN0IG1lbnVJZCA9IGJ1dHRvbi5nZXRBdHRyaWJ1dGUoJ2lkJykucmVwbGFjZSgndG9nZ2xlJywgJ21lbnUnKTtcbiAgICAgICAgICAgIGNvbnN0IG1lbnUgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChtZW51SWQpO1xuICAgICAgICAgICAgbWVudS5jbGFzc0xpc3QudG9nZ2xlKCdoaWRkZW4nKTtcblxuICAgICAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoZSkgPT4ge1xuICAgICAgICAgICAgICAgIGlmICghbWVudS5jb250YWlucyhlLnRhcmdldCkgJiYgIWJ1dHRvbi5jb250YWlucyhlLnRhcmdldCkpIHtcbiAgICAgICAgICAgICAgICAgICAgbWVudS5jbGFzc0xpc3QuYWRkKCdoaWRkZW4nKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSk7XG4gICAgfSk7XG5cbiAgICAvLyBHZXN0aW9uIGR1IG1lbnUgZGUgcHJvZmlsIHV0aWxpc2F0ZXVyXG4gICAgY29uc3QgdXNlclByb2ZpbGVDb250YWluZXIgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndXNlclByb2ZpbGVDb250YWluZXInKTtcbiAgICB1c2VyUHJvZmlsZUNvbnRhaW5lci5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsICgpID0+IHtcbiAgICAgICAgY29uc3QgdXNlck1lbnUgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndXNlck1lbnUnKTtcbiAgICAgICAgdXNlck1lbnUuY2xhc3NMaXN0LnRvZ2dsZSgnaGlkZGVuJyk7XG4gICAgfSk7XG5cbiAgICAvLyBGZXJtZXIgbGUgbWVudSB1dGlsaXNhdGV1ciBzaSBvbiBjbGlxdWUgZW4gZGVob3JzXG4gICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoZSkgPT4ge1xuICAgICAgICBpZiAoIXVzZXJQcm9maWxlQ29udGFpbmVyLmNvbnRhaW5zKGUudGFyZ2V0KSkge1xuICAgICAgICAgICAgY29uc3QgdXNlck1lbnUgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndXNlck1lbnUnKTtcbiAgICAgICAgICAgIGlmICghdXNlck1lbnUuY2xhc3NMaXN0LmNvbnRhaW5zKCdoaWRkZW4nKSkge1xuICAgICAgICAgICAgICAgIHVzZXJNZW51LmNsYXNzTGlzdC5hZGQoJ2hpZGRlbicpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSk7XG59KTtcbiIsIi8vIGV4dHJhY3RlZCBieSBtaW5pLWNzcy1leHRyYWN0LXBsdWdpblxuZXhwb3J0IHt9OyJdLCJuYW1lcyI6WyJDaGFydCIsImRvY3VtZW50IiwiYWRkRXZlbnRMaXN0ZW5lciIsImxhYmVscyIsInF1ZXJ5U2VsZWN0b3JBbGwiLCJmb3JFYWNoIiwibGFiZWwiLCJ0YXJnZXRJZCIsImdldEF0dHJpYnV0ZSIsImlucHV0IiwiZ2V0RWxlbWVudEJ5SWQiLCJmaWxlTmFtZSIsImZpbGVzIiwibmFtZSIsInRleHRDb250ZW50IiwiZHJvcGRvd25zIiwiZHJvcGRvd24iLCJidXR0b24iLCJtZW51IiwiaWNvbiIsImNsYXNzTGlzdCIsInRvZ2dsZSIsInRvZ2dsZUJ1dHRvbnMiLCJlIiwic3RvcFByb3BhZ2F0aW9uIiwibWVudUlkIiwicmVwbGFjZSIsImNvbnRhaW5zIiwidGFyZ2V0IiwiYWRkIiwidXNlclByb2ZpbGVDb250YWluZXIiLCJ1c2VyTWVudSJdLCJzb3VyY2VSb290IjoiIn0=