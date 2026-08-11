  const map = L.map('map').setView([14.3292, 120.9367], 13); 


        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let activeMarker = null;


        function updateSelectedLocation(lat, lng) {
            document.getElementById('lat').value = lat.toFixed(6);
            document.getElementById('lng').value = lng.toFixed(6);

            const latlng = [lat, lng];

            if (activeMarker) {
                activeMarker.setLatLng(latlng);
            } else {
                activeMarker = L.marker(latlng).addTo(map);
            }
            
            activeMarker.bindPopup(`<b>Selected Point</b><br>Lat: ${lat.toFixed(4)}<br>Lng: ${lng.toFixed(4)}`).openPopup();
            
        
            fetchAddressDetails(lat, lng);
        }

    // Philippines ZIP to Municipality lookup — dagdagan mo na lang
const zipToMunicipality = {
    "4707": "Irosin",
    "4700": "Sorsogon City",
    "4701": "Bacon",      // Sorsogon
    "4702": "Casiguran",  // Sorsogon
    "4703": "Castilla",   // Sorsogon
    "4704": "Donsol",     // Sorsogon
    "4705": "Gubat",      // Sorsogon
    "4706": "Juban",      // Sorsogon
    "4708": "Magallanes", // Sorsogon
    "4709": "Matnog",     // Sorsogon
    "4710": "Pilar",      // Sorsogon
    "4711": "Prieto Diaz",// Sorsogon
    "4712": "Santa Magdalena", // Sorsogon
    "4713": "Bulusan",    // Sorsogon
    "4714": "Barcelona",  // Sorsogon
    "4715": "Bulan",      // Sorsogon
  
};

function fetchAddressDetails(lat, lng) {
    document.getElementById('barangay').value = "Fetching...";
    document.getElementById('municipality').value = "Fetching...";
    document.getElementById('province').value = "Fetching...";

    const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && data.address) {
                const addr = data.address;

                // ---  Barangay ---
                const barangay = addr.neighbourhood 
                    || addr.suburb 
                    || addr.village 
                    || addr.quarter 
                    || addr.hamlet 
                    || "";

                // ---  Municipality (multi-layer fallback) ---
                let municipality = addr.city 
                    || addr.town 
                    || addr.municipality 
                    || addr.county 
                    || addr.district 
                    || "";

                // ---  Province ---
                const province = addr.province 
                    || addr.state 
                    || addr.region 
                    || "";

                // ---  ZIP Code lookup fallback ---
                const postcode = addr.postcode || "";
                if (!municipality && postcode && zipToMunicipality[postcode]) {
                    municipality = zipToMunicipality[postcode];
                }

            
                // Format: "Barangay, Municipality, Province, Region, Country"
                if (!municipality && data.display_name) {
                    const parts = data.display_name.split(',').map(p => p.trim());
                    // Sa Pilipinas: index 1 = municipality/city
                    if (parts.length >= 3) {
                        municipality = parts[1];
                    }
                }

                document.getElementById('barangay').value = barangay;
                document.getElementById('municipality').value = municipality;
                document.getElementById('province').value = province;

                activeMarker.setPopupContent(
                    `<b>${barangay || 'Selected Location'}</b><br>${municipality}${municipality && province ? ', ' : ''}${province}`
                );

                // Debug: tingnan mo sa browser console (F12)
                console.log('Address:', addr);
                console.log('Postcode:', postcode);
                console.log('Display:', data.display_name);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('barangay').value = "Error loading";
            document.getElementById('municipality').value = "Error loading";
            document.getElementById('province').value = "Error loading";
        });
}
        // --- MAP CLICK EVENT ---
        map.on('click', function(e) {
            updateSelectedLocation(e.latlng.lat, e.latlng.lng);
        });

        // --- SEARCH BAR SETUP ---
        const searchControl = new GeoSearch.GeoSearchControl({
            provider: new GeoSearch.OpenStreetMapProvider(),
            style: 'bar',
            showMarker: false,
            showPopup: false,
            marker: { icon: new L.Icon.Default(), draggable: false },
            maxMarkers: 1,
            retainZoomLevel: false,
            animateZoom: true,
            autoClose: true,
            searchLabel: 'Enter address or city...'
        });

        map.addControl(searchControl);

        // Listen for when a user selects a result from the search bar drop-down
        map.on('geosearch/showlocation', function(result) {
            const lat = result.location.y;
            const lng = result.location.x;
            updateSelectedLocation(lat, lng);
        });