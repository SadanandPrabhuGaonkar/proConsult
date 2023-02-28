import loadGoogleMapsApi from 'load-google-maps-api';
import { mapStyle, mapOptions } from './constants';

export default class Maps {
  constructor() {
    this.mapContainer = document.getElementById('map_canvas');
    if (this.mapContainer) {
      loadGoogleMapsApi({
        key: '',
      }).then((google) => {
        this.google = google;
        this.init(google);
      });
    }
  }

  init = (google) => {
    this.myLatlng = new google.LatLng(25.187738, 55.258221);
    this.mapCenter = new google.LatLng(25.146218, 55.229845);
    this.mapIconUrl = `${CCM_REL}/themes/theme/dist/images/marker.png`;
    this.map = new google.Map(this.mapContainer, {
      ...mapOptions(google),
      ...{
        center: this.mapCenter,
        styles: mapStyle,
      },
    });
    this.marker = new google.Marker({
      position: this.myLatlng,
      map: this.map,
      animation: google.Animation.DROP,
      icon: this.mapIconUrl,
      title: 'Maps',
    });
    this.bindEvents();
  };

  bindEvents = () => {
    this.google.event.addListener(this.marker, 'click', this.toggleBounce);
  };

  toggleBounce = (markerClicked) => {
    if (markerClicked.getAnimation() != null) {
      markerClicked.setAnimation(null);
    } else {
      markerClicked.setAnimation(this.google.Animation.BOUNCE);
    }
  };
}
