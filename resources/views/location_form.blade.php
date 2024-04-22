<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Coordinates</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Get Coordinates</h1>
        <form id="locationForm">
            @csrf <!-- Add this line to include the CSRF token -->
            
            <div class="mb-3">
                <label for="from" class="form-label">From:</label>
                <input type="text" id="fromInput" name="from" class="form-control" placeholder="Enter location">
            </div>
            <div class="mb-3">
                <label for="to" class="form-label">To:</label>
                <input type="text" id="toInput" name="to" class="form-control" placeholder="Enter location">
            </div>
            <button type="submit" class="btn btn-primary">Get Coordinates</button>
        </form>

        <div id="result" class="mt-4"></div>
        <!-- Map container -->
        <div id="map" style="height: 400px;"></div>
    </div>

    <!-- Bootstrap JS (optional if you need JavaScript features) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            var map;
            var fromMarker;
            var toMarker;
            var directionsService = new google.maps.DirectionsService();
            var directionsRenderer = new google.maps.DirectionsRenderer();

            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: -34.397, lng: 150.644},
                    zoom: 8
                });
                directionsRenderer.setMap(map);
            }

            // Initialize Google Places Autocomplete for 'from' input
            var fromAutocomplete = new google.maps.places.Autocomplete(document.getElementById('fromInput'));

            // Initialize Google Places Autocomplete for 'to' input
            var toAutocomplete = new google.maps.places.Autocomplete(document.getElementById('toInput'));

            $('#locationForm').submit(function(e){
                e.preventDefault();
                var formData = $(this).serialize();
                $.post('/get-coordinates', formData, function(response){
                    $('#result').html('<p>From Latitude: ' + response.from.lat + ', Longitude: ' + response.from.lng + '</p><p>To Latitude: ' + response.to.lat + ', Longitude: ' + response.to.lng + '</p>');
                    if (fromMarker) {
                        fromMarker.setMap(null);
                    }
                    if (toMarker) {
                        toMarker.setMap(null);
                    }
                    fromMarker = new google.maps.Marker({
                        position: {lat: response.from.lat, lng: response.from.lng},
                        map: map,
                        title: 'From Location'
                    });
                    toMarker = new google.maps.Marker({
                        position: {lat: response.to.lat, lng: response.to.lng},
                        map: map,
                        title: 'To Location'
                    });

                    // Request directions from Directions Service
                    var request = {
                        origin: {lat: response.from.lat, lng: response.from.lng},
                        destination: {lat: response.to.lat, lng: response.to.lng},
                        travelMode: 'DRIVING'
                    };

                    directionsService.route(request, function(result, status) {
                        if (status == 'OK') {
                            directionsRenderer.setDirections(result);
                        } else {
                            window.alert('Directions request failed due to ' + status);
                        }
                    });
                });
            });

            // Limit suggestions to 5 for each input
            google.maps.event.addListener(fromAutocomplete, 'place_changed', function() {
                var place = fromAutocomplete.getPlace();
                $('#fromInput').val(place.formatted_address);
            });

            google.maps.event.addListener(toAutocomplete, 'place_changed', function() {
                var place = toAutocomplete.getPlace();
                $('#toInput').val(place.formatted_address);
            });

            // Initialize map
            initMap();
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDG1AnHY1lXmST7UDPL70sve-JRyBxxk0o&libraries=places&callback=initMap"></script>
    <!-- Replace YOUR_API_KEY with your actual Google Places API key -->
</body>
</html>
