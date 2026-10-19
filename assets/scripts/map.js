// ============================================
// DEFAULT LOCATION: IROSIN, SORSOGON
// ============================================
const IROSIN = { lat: 12.7036, lng: 124.0390, zoom: 14 };   // approx. town center, adjust if needed

// If the form already has coordinates (edit page), start there instead
const latInput = document.getElementById('lat');
const lngInput = document.getElementById('lng');
const savedLat = parseFloat(latInput.value);
const savedLng = parseFloat(lngInput.value);
const hasSaved = !isNaN(savedLat) && !isNaN(savedLng);

const map = L.map('map').setView(
    hasSaved ? [savedLat, savedLng] : [IROSIN.lat, IROSIN.lng],
    hasSaved ? 17 : IROSIN.zoom
);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

let activeMarker = null;

// Edit mode: show the saved pin without overwriting the saved address fields
if (hasSaved) {
    activeMarker = L.marker([savedLat, savedLng]).addTo(map);
}

// Add mode: pre-fill Municipality and Province (only if still empty)
if (!hasSaved) {
    const mun  = document.getElementById('municipality1');
    const prov = document.getElementById('province1');
    if (mun  && !mun.value)  mun.value  = 'Irosin';
    if (prov && !prov.value) prov.value = 'Sorsogon';
}

function updateSelectedLocation(lat, lng) {
    latInput.value = lat.toFixed(6);
    lngInput.value = lng.toFixed(6);

    const latlng = [lat, lng];

    if (activeMarker) {
        activeMarker.setLatLng(latlng);
    } else {
        activeMarker = L.marker(latlng).addTo(map);
    }

    activeMarker.bindPopup(`<b>Selected Point</b><br>Lat: ${lat.toFixed(4)}<br>Lng: ${lng.toFixed(4)}`).openPopup();

    fetchAddressDetails(lat, lng);
}

// Philippines ZIP to Municipality lookup
const zipToMunicipality = {
    "4707": "Irosin",
    "4700": "Sorsogon City",
    "4701": "Bacon",
    "4702": "Casiguran",
    "4703": "Castilla",
    "4704": "Donsol",
    "4705": "Gubat",
    "4706": "Juban",
    "4708": "Magallanes",
    "4709": "Matnog",
    "4710": "Pilar",
    "4711": "Prieto Diaz",
    "4712": "Santa Magdalena",
    "4713": "Bulusan",
    "4714": "Barcelona",
    "4715": "Bulan",
};

function fetchAddressDetails(lat, lng) {
    document.getElementById('barangay1').value = "Fetching...";
    document.getElementById('municipality1').value = "Fetching...";
    document.getElementById('province1').value = "Fetching...";

    const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && data.address) {
                const addr = data.address;

                const barangay = addr.neighbourhood
                    || addr.suburb
                    || addr.village
                    || addr.quarter
                    || addr.hamlet
                    || "";

                let municipality = addr.city
                    || addr.town
                    || addr.municipality
                    || addr.county
                    || addr.district
                    || "";

                const province = addr.province
                    || addr.state
                    || addr.region
                    || "";

                const postcode = addr.postcode || "";
                if (!municipality && postcode && zipToMunicipality[postcode]) {
                    municipality = zipToMunicipality[postcode];
                }

                if (!municipality && data.display_name) {
                    const parts = data.display_name.split(',').map(p => p.trim());
                    if (parts.length >= 3) {
                        municipality = parts[1];
                    }
                }

                document.getElementById('barangay1').value = barangay;
                document.getElementById('municipality1').value = municipality;
                document.getElementById('province1').value = province;

                activeMarker.setPopupContent(
                    `<b>${barangay || 'Selected Location'}</b><br>${municipality}${municipality && province ? ', ' : ''}${province}`
                );

                console.log('Address:', addr);
                console.log('Postcode:', postcode);
                console.log('Display:', data.display_name);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('barangay1').value = "Error loading";
            document.getElementById('municipality1').value = "Error loading";
            document.getElementById('province1').value = "Error loading";
        });
}

// --- MAP CLICK EVENT ---
map.on('click', function (e) {
    updateSelectedLocation(e.latlng.lat, e.latlng.lng);
});

// --- SEARCH BAR SETUP (biased toward Irosin, Sorsogon) ---
const searchControl = new GeoSearch.GeoSearchControl({
    provider: new GeoSearch.OpenStreetMapProvider({
        params: {
            countrycodes: 'ph',
            viewbox: '123.90,12.85,124.15,12.55',  // left,top,right,bottom around Irosin
            bounded: 0                              // 0 = prefer Irosin, 1 = only Irosin
        }
    }),
    style: 'bar',
    showMarker: false,
    showPopup: false,
    marker: { icon: new L.Icon.Default(), draggable: false },
    maxMarkers: 1,
    retainZoomLevel: false,
    animateZoom: true,
    autoClose: true,
    searchLabel: 'Enter address or barangay in Irosin...'
});

map.addControl(searchControl);

map.on('geosearch/showlocation', function (result) {
    updateSelectedLocation(result.location.y, result.location.x);
});