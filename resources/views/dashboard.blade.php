<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Dashboard</title>
    <!-- Add your stylesheets and scripts here -->
</head>

<body style="margin: 0; padding: 0; height: 100%; width: 100%;">
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </x-slot>
        <div style="width: 100%; height: 100%">
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; width: 100%">
                <!-- Input section -->
                <div class="bg-white dark:bg-gray-800" style="display: flex; flex-direction: column; width: 100%; height: 100%">
                    <div class="p-6 text-gray-900 dark:text-gray-100" style="display: flex; flex-direction: column; gap: 12px; width: 100%;">
                        {{ __("Where do you want to go ?") }}
                        
                        <!-- Input text bar with bottom margin -->
                        <input type="text" class="border rounded-md p-2 mt-2 text-black" placeholder="Enter your location" style="color: black;" list="locations" id="location-input">

                        <!-- Datalist for location options -->
                        <datalist id="locations">
                            <option value="My Location">My Location</option>
                            <!-- Add more options here -->
                        </datalist>

                        <!-- Second input text bar with top margin -->
                        <input type="text" class="border rounded-md p-2 mt-2 text-black" placeholder="Enter your destination" style="color: black;">

                        <!-- Button -->
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="background-color: green;">
                            Submit
                        </button>
                    </div>
                </div>
            </div>

            <!-- Map section -->
            
        </div>
        <div id="mapContainer" class="bg-white dark:bg-gray-800" style="width: 100%; height: 45vh">
                <div id="map" style="width: 100%; height: 100%">
                    <!-- Map will be inserted here -->
                </div>
        </div>
    </x-app-layout>

    <script>
        const mapContainer = document.getElementById('mapContainer');
        const map = document.getElementById('map');
        const locationInput = document.getElementById('location-input');

        locationInput.addEventListener('input', function(event) {
            if (event.target.value.toLowerCase() === 'my location') {
                getUserLocation();
            }
        });

        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
            } else {
                console.log("Geolocation is not supported by this browser.");
            }
        }

        function successCallback(position) {
            const { latitude, longitude } = position.coords;
            mapContainer.style.display = 'block';
            map.innerHTML = '<iframe width="100%" height="100%" src="https://maps.google.com/maps?q=' + latitude + ',' + longitude + '&amp;z=15&amp;output=embed"></iframe>';

            // Reverse geocoding to get the address
            reverseGeocode(latitude, longitude);

            // Scroll down to the map
            mapContainer.scrollIntoView({ behavior: 'smooth' });
        }

        function errorCallback(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    console.log("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    console.log("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    console.log("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    console.log("An unknown error occurred.");
                    break;
            }
        }

        function reverseGeocode(latitude, longitude) {
            fetch(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key={{ env('GOOGLE_MAPS_API_KEY') }}`)
                .then(response => response.json())
                .then(data => {
                    const address = data.results[0].formatted_address;
                    locationInput.value = address;
                })
                .catch(error => console.log("Error fetching address:", error));
        }
    </script>
</body>

</html>