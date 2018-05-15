//(function() {
    let map, loaded = false;
    let clients, markers = [], flightPath;

    // Gets called by the Google API callback
    function startRenderer() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 52.379189, lng: 4.899431},
            zoom: 6,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [
                {elementType: 'geometry', stylers: [{color: '#242f3e'}]},
                {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
                {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
                {
                    featureType: 'administrative.locality',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563'}]
                },
                {
                    featureType: 'poi',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563'}]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'geometry',
                    stylers: [{color: '#263c3f'}]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#6b9a76'}]
                },
                {
                    featureType: 'road',
                    elementType: 'geometry',
                    stylers: [{color: '#38414e'}]
                },
                {
                    featureType: 'road',
                    elementType: 'geometry.stroke',
                    stylers: [{color: '#212a37'}]
                },
                {
                    featureType: 'road',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#9ca5b3'}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry',
                    stylers: [{color: '#746855'}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry.stroke',
                    stylers: [{color: '#1f2835'}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#f3d19c'}]
                },
                {
                    featureType: 'transit',
                    elementType: 'geometry',
                    stylers: [{color: '#2f3948'}]
                },
                {
                    featureType: 'transit.station',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563'}]
                },
                {
                    featureType: 'water',
                    elementType: 'geometry',
                    stylers: [{color: '#17263c'}]
                },
                {
                    featureType: 'water',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#515c6d'}]
                },
                {
                    featureType: 'water',
                    elementType: 'labels.text.stroke',
                    stylers: [{color: '#17263c'}]
                }
            ]
        });

        map.addListener('click', function(){
            if(flightPath !== undefined) flightPath.setMap(null); // Clear selected flightpath on click
        });

        // Hide flight info by default
        showFlightInfo(false);

        // Start the data loop
        requestClientData();
        setInterval(requestClientData, 1000 * 10);
    }

    function requestClientData() {
        $.get('/api/clientdata', function (data) {
            console.log('[' + new Date().getHours() + ':' + new Date().getMinutes() + '] - RECEIVED MAP DATA');
            clients = data;

            // Remove all non pilots from the clients list.
            clients = clients.filter(function(client){
               return client.clienttype === 'PILOT';
            });

            loadClients(clients);
            loaded = true;
        });
    }

    function updateMarkers() {
        let shown = 0, hidden = 0;
        for (let i = 0; i < clients.length; i++) {
            if (map.getBounds().contains({
                lat: parseFloat(clients[i].latitude),
                lng: parseFloat(clients[i].longitude)
            })) {
                markers[clients[i].cid].setVisible(true);
                shown++;
            } else {
                markers[clients[i].cid].setVisible(false);
                hidden++;
            }
        }
        //console.log('Shown ' + shown + ' planes. Hid ' + hidden + ' planes');
    }

    function loadClients(receivedData) {
        for (var i = 0; i < receivedData.length; i++) {
            if (markers[receivedData[i].cid] === undefined) {
                addMarker(receivedData[i].cid, receivedData[i].latitude, receivedData[i].longitude, receivedData[i].heading);
            } else {
                markers[receivedData[i].cid].setPosition(new google.maps.LatLng(parseFloat(receivedData[i].latitude), parseFloat(receivedData[i].longitude)));
                let icon = markers[receivedData[i].cid].getIcon();
                icon.rotation = parseInt(receivedData[i].heading);
                markers[receivedData[i].cid].setIcon(icon);
            }
            markers[receivedData[i].cid].last_update = new Date();
        }

        // Update our navbar counter of connected clients
        document.getElementById('engine-replace-client_count').innerHTML = 'Serving <b>' + clients.length + '</b> clients';

        removeUnusedMarkers();
    }

    function addMarker(cid, lat, lon, heading) {
        let icon = {
            path: "M50.915,0.889C50.43,0.325,49.724,0,48.98,0c-0.744,0-1.451,0.325-1.936,0.889c-2.702,3.146-4.188,7.155-4.188,11.302v22.503L0,68.367v10.204l42.857-16.326v15.306c0,1.424,0.351,2.826,1.021,4.082L28.572,91.837V100l20.408-8.163L69.388,100v-8.163L54.082,81.633c0.67-1.256,1.021-2.658,1.021-4.082V62.245L97.96,78.571V68.367L55.103,34.694V12.191C55.103,8.044,53.617,4.035,50.915,0.889",
            fillColor: 'orange',
            fillOpacity: 1,
            anchor: new google.maps.Point(parseFloat(lat), parseFloat(lon)),
            strokeColor: 'black',
            strokeWeight: 1,
            scale: .25,
            rotation: parseInt(heading)
        };

        let marker = new google.maps.Marker({
            position: {lat: parseFloat(lat), lng: parseFloat(lon)},
            map: map,
            icon: icon
        });

        // Click listener
        marker.addListener('click', function () {
            console.log('CLICK! - ' + cid);
            showData(cid);
        });

        markers[cid] = marker;
    }

    function removeUnusedMarkers() {
        let toRemove = [];
        markers.forEach((marker) => {
            if ((new Date() - marker.last_update) > 1000 * 60 * 2) {
                toRemove.push(marker);
            }
        });
        if (toRemove.length > 0) {
            let clientIds = [];
            for (let i = 0; i < clients.length; i++) {
                clientIds.push(clients[i].cid);
            }
            toRemove.forEach((marker, markerId) => {
                if (clientIds.indexOf(markerId) > -1) {
                    marker.setMap(null);
                    markers.splice(markers.indexOf(marker), 1);
                }
            });
            console.log('Removed ' + toRemove.length + ' disconnected clients');
        }

    }

    function showData(cid) {
        let client;
        for (let i = 0; i < clients.length; i++) {
            if (clients[i].cid === cid) {
                client = clients[i];
                break;
            }
        }

        drawFlightplan(client);
        showFlightInfo(true, client);
    }

    function drawFlightplan(client){
        let coordinates = [];
        if(flightPath !== undefined) flightPath.setMap(null);

        $.get('/api/airport/' + client.planned_depairport, function (data) {
            if(JSON.parse(data) !== 404){
                let airport = JSON.parse(data);
                coordinates.unshift({
                    lat: parseFloat(airport[0]),
                    lng: parseFloat(airport[1])
                });
                $.get('/api/airport/' + client.planned_destairport, function (data) {
                    if(JSON.parse(data) !== 404){
                        let airport = JSON.parse(data);
                        coordinates.push({
                            lat: parseFloat(airport[0]),
                            lng: parseFloat(airport[1])
                        });

                        flightPath = new google.maps.Polyline({
                            path: coordinates,
                            geodesic: true,
                            strokeColor: '#FF0000',
                            strokeOpacity: 1.0,
                            strokeWeight: 2
                        });

                        flightPath.setMap(map);
                    }else{
                        console.warn('Could not fetch airport');
                    }
                });
            }else{
                console.warn('Could not fetch airport');
            }
        });
    }

    function showFlightInfo(show, client = undefined){
        if(client === undefined){
            document.getElementById('sidebar').style.display = (show) ? 'block' : 'none';
        }else{
            /*
            AIRCRAFTS
             */
            let aircraftType = 'UNKNOWN', departure_iata, destination_iata;
            aircraftType = (client.planned_aircraft.indexOf('B738') >= 0 || client.planned_aircraft.indexOf('B737') >= 0) >= 0 ? 'B738' : aircraftType;
            aircraftType = client.planned_aircraft.indexOf('A320') >= 0 ? 'A320' : aircraftType;
            aircraftType = client.planned_aircraft.indexOf('DH8D') >= 0 ? 'DH8D' : aircraftType;
            let airline = client.callsign.substr(0, 3);
            /*
            SCHEDULING
             */
            let plannedDeptime = client.planned_deptime, plannedActualDeptime = client.planned_actdeptime;
            let plannedHours = client.planned_hrsenroute, plannedMinutes = client.planned_minenroute, plannedArrival = '--:--';
            if(plannedDeptime.length == 3){ plannedDeptime = 0 + plannedDeptime}
            if(plannedActualDeptime.length == 3){ plannedActualDeptime = 0 + plannedActualDeptime}
            plannedDeptime = [plannedDeptime.substr(0, 2), plannedDeptime.substr(2, 4)];
            plannedActualDeptime = [plannedActualDeptime.substr(0, 2), plannedActualDeptime.substr(2, 4)];
            if(plannedDeptime[0] === '0') { plannedDeptime = '--:--'; }
            if(plannedActualDeptime[0] === '0') { plannedActualDeptime = '--:--'; }
            console.log(plannedHours + ':' + plannedMinutes);
            if(plannedDeptime !== '--:--' && plannedDeptime !== '--:--' && plannedHours !== undefined && plannedMinutes !== undefined){
                let newHours = parseInt(plannedDeptime[0]) + parseInt(plannedHours);
                let newMinutes = parseInt(plannedDeptime[1]) + parseInt(plannedMinutes);
                if(newHours >= 24) { newHours = newHours % 24; newMinutes = 0; }
                if(newMinutes >= 60) { newMinutes = newMinutes % 60; newHours++; }
                if(newHours < 10) { newHours = '0' + newHours; }
                if(newMinutes < 10) { newMinutes = '0' + newMinutes; }

                plannedArrival = [newHours, newMinutes];
            }

            $.get('/api/airport/' + client.planned_depairport + '/IATA', function (data) {
                if(JSON.parse(data) !== 404) {
                    departure_iata = data;
                    $.get('/api/airport/' + client.planned_destairport + '/IATA', function (data) {
                        if(JSON.parse(data) !== 404) {
                            destination_iata = data;
                            document.getElementById('engine-replace-callsign').innerHTML = (client.callsign !== undefined) ? client.callsign : '???';

                            document.getElementById('engine-replace-departure_airport').innerHTML = (client.planned_depairport !== undefined) ? client.planned_depairport : '???';
                            document.getElementById('engine-replace-departure_airport_iata').innerHTML = departure_iata.replace(/"/g, '');

                            document.getElementById('engine-replace-destination_airport').innerHTML = (client.planned_destairport !== undefined) ? client.planned_destairport : '???';
                            document.getElementById('engine-replace-destination_airport_iata').innerHTML = destination_iata.replace(/"/g, '');

                            document.getElementById('engine-replace-aircraft_image').src = '/img/planes/' + aircraftType + '/' + airline + '/img.jpg';

                            document.getElementById('engine-replace-departure_planned').innerHTML = plannedDeptime === '--:--' ? plannedDeptime : (plannedDeptime[0] + ':' + plannedDeptime[1]);
                            document.getElementById('engine-replace-departure_actual').innerHTML = plannedActualDeptime === '--:--' ? plannedActualDeptime : (plannedActualDeptime[0] + ':' + plannedActualDeptime[1]);
                            document.getElementById('engine-replace-destination_planned').innerHTML = plannedArrival === '--:--' ? plannedArrival : (plannedArrival[0] + ':' + plannedArrival[1]);
                            console.warn(client.planned_aircraft);
                            document.getElementById('sidebar').style.display = (show) ? 'block' : 'none';
                        }
                    });
                }
            });
        }
    }
//})();