import renderEvents from "./renderEvents";

export function loadGoogleMapsScript(api, cb) {
  const script = document.createElement("script");
  script.src = `https://maps.google.com/maps/api/js?key=${api}&sensor=false&libraries=places,geometry&callback=${cb}`;
  document.body.appendChild(script);
}

function Events() {
  let autocompleteEl = document.querySelector(".js-map-autocomplete");
  let eventsJSON = document.getElementById("json-events");
  let mapEl = document.getElementById("map");
  let form = document.querySelector('.js-search');

  if (!autocompleteEl) return;

  const settings = window._maps;
  let events = [];

  loadGoogleMapsScript(settings.api, "loadGoogleMaps");

  window.addEventListener('event-json-updated', ({detail}) => events = detail);

  window.addEventListener('toggle-pagination', () => {
    let pagination = document.querySelector('.js-pagination')
    let count = document.querySelector('.js-search-header')

    count.classList.toggle('tw-hidden')
    pagination.classList.toggle('tw-hidden')

  });

  const markers = [];
  let map;
  let infowindow;
  let autocomplete;
  let bounds;

  const mapOptions = {
    center: {
      lat: 52.188902,
      lng: 0.997712,
    },
    zoom: 10,
    maxZoom: 19,
  };

  function loadGoogleMaps() {
    if (mapEl) {
      events = JSON.parse(eventsJSON.innerHTML);

      window.addEventListener('map-tab', loadGoogleMapsTab, {
        once: true
      });
    }

    const locationButton = document.querySelector(".js-location");
    if (locationButton) {
      locationButton.addEventListener("click", gpsLocate);
    }

    if (autocompleteEl) {
      autocomplete = new google.maps.places.Autocomplete(autocompleteEl, {
        componentRestrictions: {
          country: "gb",
        },
      });

      autocompleteEl.addEventListener('keydown', (e) => {
        if(e.key === 'Enter') {
            e.preventDefault();
        }
      });

      autocomplete.addListener("place_changed", function () {

        if (infowindow) {
          infowindow.close();
        }

        const place = autocomplete.getPlace();
        if (!place.geometry) {
          updateMesage(
            `No address found for your search: ${place.name}`,
            "error"
          );
          return;
        }

        updateMesage("");
        locate(
          place.geometry.location.lat(),
          place.geometry.location.lng(),
        );
      });
    }
  }

  function loadGoogleMapsTab() {
    map = new google.maps.Map(mapEl, mapOptions);

    infowindow = new google.maps.InfoWindow();

    map.addListener("click", function (e) {
      infowindow.close();
    });

    window.addEventListener('render-markers', ({ detail }) => {
      bounds = new google.maps.LatLngBounds();

      markers.forEach(marker => {
        marker.setMap(null);
      });

      renderEvents(detail, map, infowindow, markers, bounds);
    });

    window.dispatchEvent(new CustomEvent('render-markers', {
      detail: events
    }));
  }

  window.loadGoogleMaps = loadGoogleMaps;

  function updateMesage(message = "", type = "info") {
    const el = document.querySelector(".js-map-alert");
    if (!el) return;

    if (message) {
      el.classList.remove("c-alert--info", "c-alert--error", "js-hidden");
      el.classList.add(`c-alert--${type}`);
      el.children[0].textContent = message;
    } else {
      el.classList.add("js-hidden");
    }
  }

  function locate(lat, lng) {
    const latEl = document.querySelector('[name="lat"]');
    const lngEl = document.querySelector('[name="lng"]');
    if (!latEl || !lngEl) return;

    latEl.value = lat;
    lngEl.value = lng;

    form.dispatchEvent(new Event('submit'));
  }


  function gpsLocate(e) {
    e.target.textContent = "Locating...";

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        function (position) {
          updateMesage("");
          e.target.textContent = "Using your location";
          locate(position.coords.latitude, position.coords.longitude);
        },
        function error() {
          e.target.textContent = "Use your location";
          updateMesage(
            "Please enable location services on your device.",
            "error"
          );
        },
        {
          enableHighAccuracy: true,
        }
      );
    } else {
      updateMesage(
        "Location search is not supported in your browser.",
        "error"
      );
    }
  }
}

export default Events;
