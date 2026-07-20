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

                    
                        const barangay = addr.neighbourhood || addr.suburb || addr.village || addr.quarter || addr.brgy || "";
                        const municipality = addr.city || addr.town || addr.municipality || "";
                        const province = addr.province || addr.state || "";

                        document.getElementById('barangay').value = barangay;
                        document.getElementById('municipality').value = municipality;
                        document.getElementById('province').value = province;
                        
         
                        activeMarker.setPopupContent(`<b>${barangay || 'Selected Location'}</b><br>${municipality}, ${province}`);
                    }
                })
                .catch(error => {
                    console.error('Error fetching address:', error);
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