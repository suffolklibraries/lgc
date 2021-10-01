import { loadGoogleMapsScript } from './events';

function Map() {
  const mapEl = document.getElementById('static-map');
  if (!mapEl) return;

  const settings = window._maps;
  loadGoogleMapsScript(settings.api, 'loadStaticMap');


  function loadStaticMap() {
    const position = {
      lat: Number(mapEl.getAttribute('data-lat')),
      lng: Number(mapEl.getAttribute('data-lng')),
    };

    const mapOptions = {
      center: position,
      zoom: 17
    };

    const map = new google.maps.Map(mapEl, mapOptions);

    const marker = new google.maps.Marker({
      position,
      map,
      visible: true,
      opacity: 1,
      icon: '/img/map-pin.svg',
    });
  }
  window.loadStaticMap = loadStaticMap
}

export default Map;
