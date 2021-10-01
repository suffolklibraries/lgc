/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/events.js":
/*!********************************!*\
  !*** ./resources/js/events.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "loadGoogleMapsScript": () => (/* binding */ loadGoogleMapsScript),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _renderEvents__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./renderEvents */ "./resources/js/renderEvents.js");

function loadGoogleMapsScript(api, cb) {
  var script = document.createElement("script");
  script.src = "https://maps.google.com/maps/api/js?key=".concat(api, "&sensor=false&libraries=places,geometry&callback=").concat(cb);
  document.body.appendChild(script);
}

function Events() {
  var autocompleteEl = document.querySelector(".js-map-autocomplete");
  var eventsJSON = document.getElementById("json-events");
  var mapEl = document.getElementById("map");
  if (!autocompleteEl) return;
  var settings = window._maps;
  var events = [];
  loadGoogleMapsScript(settings.api, "loadGoogleMaps");
  var markers = [];
  var map;
  var infowindow;
  var autocomplete;
  var bounds;
  var mapOptions = {
    center: {
      lat: 52.188902,
      lng: 0.997712
    },
    zoom: 10,
    maxZoom: 19
  };

  function loadGoogleMaps() {
    if (mapEl) {
      events = JSON.parse(eventsJSON.innerHTML);
      window.addEventListener('map-tab', loadGoogleMapsTab, {
        once: true
      });
    }

    var locationButton = document.querySelector(".js-location");

    if (locationButton) {
      locationButton.addEventListener("click", gpsLocate);
    }

    if (autocompleteEl) {
      autocomplete = new google.maps.places.Autocomplete(autocompleteEl, {
        componentRestrictions: {
          country: "gb"
        }
      });
      autocomplete.addListener("place_changed", function () {
        if (infowindow) {
          infowindow.close();
        }

        var place = autocomplete.getPlace();

        if (!place.geometry) {
          updateMesage("No address found for your search: ".concat(place.name), "error");
          return;
        }

        updateMesage("");
        locate(place.geometry.location.lat(), place.geometry.location.lng());
      });
    }
  }

  function loadGoogleMapsTab() {
    map = new google.maps.Map(mapEl, mapOptions);
    infowindow = new google.maps.InfoWindow();
    map.addListener("click", function (e) {
      infowindow.close();
    });
    window.addEventListener('render-markers', function (_ref) {
      var detail = _ref.detail;
      bounds = new google.maps.LatLngBounds();
      markers.forEach(function (marker) {
        marker.setMap(null);
      });
      (0,_renderEvents__WEBPACK_IMPORTED_MODULE_0__["default"])(detail, map, infowindow, markers, bounds);
    });
    window.dispatchEvent(new CustomEvent('render-markers', {
      detail: events
    }));
  }

  window.loadGoogleMaps = loadGoogleMaps;

  function updateMesage() {
    var message = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : "";
    var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : "info";
    var el = document.querySelector(".js-map-alert");
    if (!el) return;

    if (message) {
      el.classList.remove("c-alert--info", "c-alert--error", "js-hidden");
      el.classList.add("c-alert--".concat(type));
      el.children[0].textContent = message;
    } else {
      el.classList.add("js-hidden");
    }
  }

  function locate(lat, lng) {
    var latEl = document.querySelector('[name="lat"]');
    var lngEl = document.querySelector('[name="lng"]');
    if (!latEl || !lngEl) return;
    latEl.value = lat;
    lngEl.value = lng;
    latEl.dispatchEvent(new Event('change', {
      bubbles: true
    }));
    lngEl.dispatchEvent(new Event('change', {
      bubbles: true
    }));
    console.log(lat, lng);
  }

  function gpsLocate(e) {
    e.target.textContent = "Locating...";

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
        updateMesage("");
        e.target.textContent = "Using your location";
        locate(position.coords.latitude, position.coords.longitude);
      }, function error() {
        e.target.textContent = "Use your location";
        updateMesage("Please enable location services on your device.", "error");
      }, {
        enableHighAccuracy: true
      });
    } else {
      updateMesage("Location search is not supported in your browser.", "error");
    }
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Events);

/***/ }),

/***/ "./resources/js/linkIcons.js":
/*!***********************************!*\
  !*** ./resources/js/linkIcons.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
function LinkIcons() {
  document.querySelectorAll('.js-link-icons a:first-child').forEach(function (anchor) {
    if (anchor.parentNode.childNodes[0] === anchor) {
      anchor.classList.add('c-link-icon');
    }
  });
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (LinkIcons);

/***/ }),

/***/ "./resources/js/map.js":
/*!*****************************!*\
  !*** ./resources/js/map.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _events__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./events */ "./resources/js/events.js");


function Map() {
  var mapEl = document.getElementById('static-map');
  if (!mapEl) return;
  var settings = window._maps;
  (0,_events__WEBPACK_IMPORTED_MODULE_0__.loadGoogleMapsScript)(settings.api, 'loadStaticMap');

  function loadStaticMap() {
    var position = {
      lat: Number(mapEl.getAttribute('data-lat')),
      lng: Number(mapEl.getAttribute('data-lng'))
    };
    var mapOptions = {
      center: position,
      zoom: 17
    };
    var map = new google.maps.Map(mapEl, mapOptions);
    var marker = new google.maps.Marker({
      position: position,
      map: map,
      visible: true,
      opacity: 1,
      icon: '/img/map-pin.svg'
    });
  }

  window.loadStaticMap = loadStaticMap;
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Map);

/***/ }),

/***/ "./resources/js/menu.js":
/*!******************************!*\
  !*** ./resources/js/menu.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
function Menu() {
  Array.from(document.querySelectorAll('.c-navicon'), function (el) {
    el.addEventListener('click', toggle('main-nav-open'));
  });
  Array.from(document.querySelectorAll('.c-top_search-trigger'), function (el) {
    el.addEventListener('click', toggle('search-open', function () {
      if (document.body.classList.contains('search-open')) {
        var input = document.querySelector('[name="q"]');

        if (input) {
          input.focus();
        }
      }
    }));
  });

  function toggle(cl) {
    var cb = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
    return function (e) {
      document.body.classList.toggle(cl);
      if (cb) cb(e);
    };
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Menu);

/***/ }),

/***/ "./resources/js/renderEvents.js":
/*!**************************************!*\
  !*** ./resources/js/renderEvents.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
function renderEvents(events, map, infowindow) {
  var markers = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : [];
  var bounds = arguments.length > 4 ? arguments[4] : undefined;

  if (events.length === 0) {
    map.setCenter(new google.maps.LatLng(52.188902, 0.997712));
    map.setZoom(9);
    return;
  }

  events.forEach(function (event) {
    var content = "<div class=\"o-flow o-flow--xs u-pad-s u-font step--1\">\n      <h3 class=\"step--1\">\n        <a href=\"".concat(event.url, "\">").concat(event.title, "</a>\n      </h3>\n      ").concat(event.address && event.postcode ? "<div class=\"u-pad-right-s\">\n        <p>".concat(event.address, ", ").concat(event.postcode, "</p>\n      </div>") : '', "\n      <p><a href=\"").concat(event.url, "\" class=\"u-blue\">\n        <i class=\"c-icon c-icon--arrow-right c-icon--inline u-green\">\n          <svg>\n            <use xlink:href=\"/icons/sprite.svg#arrow-right\" }}\"></use>\n          </svg>\n        </i>\n         See the event</a>\n      </p>\n    </div>");
    var marker = new google.maps.Marker({
      position: {
        lat: Number(event.lat),
        lng: Number(event.lng)
      },
      map: map,
      content: content,
      infowindow: infowindow,
      url: event.url,
      visible: true,
      opacity: 1,
      icon: '/img/map-pin.svg'
    });
    markers.push(marker);
    bounds.extend(marker.position);
    marker.addListener('click', function () {
      infowindow.setContent(content);
      infowindow.open(map, marker);
    });
  });
  map.fitBounds(bounds);
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (renderEvents);

/***/ }),

/***/ "./resources/js/search.js":
/*!********************************!*\
  !*** ./resources/js/search.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function Search() {
  var searchForm = document.querySelector('.js-search');
  if (!searchForm) return;
  searchForm.addEventListener('change', onChange);
  searchForm.addEventListener('submit', onSubmit);
}

function onChange(e) {
  console.log('Nothing?');
  var form = e.currentTarget;
  var checkedCategories = form.querySelectorAll('[name="category[]"]:checked');

  if (e.target.name === 'category-clear') {
    form.querySelectorAll('[name="category[]"]').forEach(function (el) {
      el.checked = false;
    });

    if (!checkedCategories.length) {
      e.target.checked = true;
    }

    setTimeout(function () {
      form.dispatchEvent(new Event("submit"));
    }, 100);
    return;
  }

  if (e.target.name === 'category[]') {
    form.querySelectorAll('[name="category-clear"]').forEach(function (el) {
      el.checked = !checkedCategories.length;
    });
  }

  if (form.classList.contains('js-search--simple')) {
    setTimeout(function () {
      form.submit();
    }, 100);
    return true;
  }

  if (e.target.name === 'place') {
    setTimeout(function () {
      form.dispatchEvent(new Event("submit"));
    }, 100);
    return;
  }

  console.log(1);
  form.dispatchEvent(new Event('submit'));
}

function onSubmit(e) {
  if (e.currentTarget.classList.contains('js-search--simple')) {
    return;
  }

  console.log(2);
  e.preventDefault();
  var formData = new FormData(e.currentTarget);
  var searchParams = new URLSearchParams();
  searchParams["delete"]('category-clear');

  var keys = _toConsumableArray(formData.keys());

  keys.forEach(function (key) {
    if (keys.filter(function (x) {
      return x === key;
    }).length > 1) {
      formData.getAll(key).forEach(function (value) {
        searchParams.append(key, value);
      });
      keys.forEach(function (k, i) {
        if (k === key) {
          keys.splice(i, 1);
        }
      });
    } else {
      searchParams.set(key, formData.get(key));
    }
  });
  var queryString = searchParams.toString();
  var url = window.location.pathname + '?' + queryString;
  fetch(url).then(function (r) {
    return r.text();
  }).then(function (result) {
    return Promise.resolve(result.replace(/<script(?! type="application\/json").\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, ""));
  }).then(function (response) {
    var results = document.createElement('div');
    results.innerHTML = response;
    var newEvents = results.querySelector('.js-events');
    var oldEvents = document.querySelector('.js-events');

    if (newEvents && oldEvents) {
      oldEvents.innerHTML = newEvents.innerHTML;
    }

    var newHeader = results.querySelector('.js-search-header');
    var oldHeader = document.querySelector('.js-search-header');

    if (newHeader && oldHeader) {
      oldHeader.innerHTML = newHeader.innerHTML;
    }

    var newPagination = results.querySelector('.js-pagination');
    var oldPagination = document.querySelector('.js-pagination');

    if (newPagination && oldPagination) {
      oldPagination.innerHTML = newPagination.innerHTML;
    }

    var newJSON = results.querySelector('#json-events');

    if (newJSON) {
      window.dispatchEvent(new CustomEvent('render-markers', {
        detail: JSON.parse(newJSON.innerHTML)
      }));
    }

    window.history.replaceState('', '', url);
  })["catch"](console.error);
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Search);

/***/ }),

/***/ "./resources/js/site.js":
/*!******************************!*\
  !*** ./resources/js/site.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _menu__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./menu */ "./resources/js/menu.js");
/* harmony import */ var _events__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./events */ "./resources/js/events.js");
/* harmony import */ var _map__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./map */ "./resources/js/map.js");
/* harmony import */ var _search__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./search */ "./resources/js/search.js");
/* harmony import */ var _tabs__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./tabs */ "./resources/js/tabs.js");
/* harmony import */ var _linkIcons__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./linkIcons */ "./resources/js/linkIcons.js");







(function () {
  (0,_menu__WEBPACK_IMPORTED_MODULE_0__["default"])();
  (0,_events__WEBPACK_IMPORTED_MODULE_1__["default"])();
  (0,_map__WEBPACK_IMPORTED_MODULE_2__["default"])();
  (0,_search__WEBPACK_IMPORTED_MODULE_3__["default"])();
  (0,_tabs__WEBPACK_IMPORTED_MODULE_4__["default"])();
  (0,_linkIcons__WEBPACK_IMPORTED_MODULE_5__["default"])();
})();

/***/ }),

/***/ "./resources/js/tabs.js":
/*!******************************!*\
  !*** ./resources/js/tabs.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
function Tabs() {
  var tabTriggers = document.querySelectorAll('.c-tab-trigger');
  var tabs = document.querySelectorAll('.c-tab');
  tabTriggers.forEach(function (trigger) {
    trigger.addEventListener('click', onClick);
  });

  function onClick(e) {
    e.preventDefault();
    var currentTrigger = e.currentTarget;
    var currentTab = document.querySelector(currentTrigger.getAttribute('href'));
    tabs.forEach(function (tab) {
      tab.classList.toggle('c-tab--active', currentTab === tab);
    });
    tabTriggers.forEach(function (trigger) {
      trigger.setAttribute('aria-expanded', currentTrigger === trigger ? 'true' : 'false');
    });

    if (currentTab.classList.contains('c-tab--map')) {
      setTimeout(function () {
        window.dispatchEvent(new Event('map-tab'));
      }, 100);
    }
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Tabs);

/***/ }),

/***/ "./resources/css/site.css":
/*!********************************!*\
  !*** ./resources/css/site.css ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/site": 0,
/******/ 			"css/site": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkIds[i]] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["css/site"], () => (__webpack_require__("./resources/js/site.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css/site"], () => (__webpack_require__("./resources/css/site.css")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;