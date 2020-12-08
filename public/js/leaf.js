var map = L.map('map', {
    center: [48.852969, 2.349903],
    minZoom: 5,
    zoom: 12
});

L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png').addTo(map);
// L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png').addTo(map);

var myIcon = L.icon({
    iconUrl: '.\\uploads\\background\\instagram.png',
    iconSize: [64, 64],
    iconAnchor: [9, 21],
    popupAnchor: [0, -14]
});

var markerClusters = L.markerClusterGroup();

for (var i = 0; i < markers.length; ++i) {
    var popup = "<b>Salut, je suis </b>" + '<b>' + markers[i].name + ', </b><br/>je réside à ' + markers[i].city + '<br> et je suis un ' + markers[i].profil
    ;

    var m = L.marker([markers[i].lat, markers[i].lng], {icon: myIcon}).bindPopup(popup);
    markerClusters.addLayer(m);
}

map.addLayer(markerClusters);
