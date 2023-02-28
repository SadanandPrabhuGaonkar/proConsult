export const mapStyle = [{
  featureType: 'administrative',
  elementType: 'labels.text.fill',
  stylers: [{
    visibility: 'off',
  }],
},
{
  featureType: 'administrative',
  elementType: 'labels.text.stroke',
  stylers: [{
    color: '#000000',
  }],
},
{
  featureType: 'landscape',
  elementType: 'all',
  stylers: [{
    visibility: 'on',
  }, {
    color: '#ffffff',
  }],
},
{
  featureType: 'poi',
  elementType: 'all',
  stylers: [{
    visibility: 'off',
  }],
},
{
  featureType: 'road',
  elementType: 'all',
  stylers: [{
    visibility: 'on',
  }, {
    color: '#000000',
  }],
},
{
  featureType: 'road',
  elementType: 'labels.text.fill',
  stylers: [{
    visibility: 'off',
  }],
},
{
  featureType: 'road',
  elementType: 'labels.text.stroke',
  stylers: [{
    visibility: 'on',
  }, {
    color: '#ffffff',
  }],
},
{
  featureType: 'road',
  elementType: 'labels.icon',
  stylers: [{
    visibility: 'off',
  }],
},
{
  featureType: 'transit',
  elementType: 'all',
  stylers: [{
    visibility: 'off',
  }],
},
{
  featureType: 'water',
  elementType: 'all',
  stylers: [{
    visibility: 'off',
  }],
},
];

export const mapOptions = google => ({
  zoom: 13,
  zoomControl: true,
  zoomControlOptions: {
    position: google.ControlPosition.RIGHT_CENTER,
    style: google.ZoomControlStyle.SMALL,
  },
  scaleControl: true,
  scrollwheel: false,
  mapTypeId: google.MapTypeId.ROADMAP, // ROADMAP or SATELLITE
  panControl: true,
  mapTypeControl: false,
  panControlOptions: {
    position: google.ControlPosition.RIGHT_CENTER,
  },
  streetViewControl: false,
});
