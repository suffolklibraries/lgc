function renderEvents(events, map, infowindow, markers = [], bounds) {
  if (events.length === 0) {
    map.setCenter(new google.maps.LatLng(52.188902, 0.997712));
    map.setZoom(9);
    return;
  }

  events.forEach((event) => {
    if (!event.lat || !event.lng) return;

    const content = `<div class="o-flow o-flow--xs u-pad-s u-font step--1">
      <h3 class="step--1">
        <a href="${event.url}">${event.title}</a>
      </h3>
      ${event.address && event.postcode ? `<div class="u-pad-right-s">
        <p>${event.address}, ${event.postcode}</p>
      </div>` : ''}
      <p><a href="${event.url}" class="u-blue">
        <i class="c-icon c-icon--arrow-right c-icon--inline u-green">
          <svg>
            <use xlink:href="/icons/sprite.svg#arrow-right" }}"></use>
          </svg>
        </i>
         See the event</a>
      </p>
    </div>`;

    const marker = new google.maps.Marker({
      position: {
        lat: Number(event.lat),
        lng: Number(event.lng),
      },
      map,
      content,
      infowindow,
      url: event.url,
      visible: true,
      opacity: 1,
      icon: '/img/map-pin.svg',
    });

    markers.push(marker);

    bounds.extend(marker.position);

    marker.addListener('click', () => {
      infowindow.setContent(content);
      infowindow.open(map, marker);
    });
  });

  map.fitBounds(bounds);
}

export default renderEvents;
