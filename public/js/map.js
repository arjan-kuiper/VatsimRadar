//(function() {
    let map, loaded = false;
    let clients, markers = [];

    // Gets called by the Google API callback
    function startRenderer() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 52.379189, lng: 4.899431},
            zoom: 6,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        requestClientData();
    }

    function requestClientData() {
        setInterval(function () {
            $.get('/clientdata', function (data) {
                console.log('[' + new Date().getHours() + ':' + new Date().getMinutes() + '] - RECEIVED MAP DATA');
                clients = data;
                loadClients(data);
                loaded = true;
            });
        }, 1000 * 10);
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
        removeUnusedMarkers();
    }

    function addMarker(cid, lat, lon, heading) {
        let icon = {
            path: "M50.915,0.889C50.43,0.325,49.724,0,48.98,0c-0.744,0-1.451,0.325-1.936,0.889c-2.702,3.146-4.188,7.155-4.188,11.302v22.503L0,68.367v10.204l42.857-16.326v15.306c0,1.424,0.351,2.826,1.021,4.082L28.572,91.837V100l20.408-8.163L69.388,100v-8.163L54.082,81.633c0.67-1.256,1.021-2.658,1.021-4.082V62.245L97.96,78.571V68.367L55.103,34.694V12.191C55.103,8.044,53.617,4.035,50.915,0.889",
            fillColor: 'BLACK',
            fillOpacity: 1,
            anchor: new google.maps.Point(parseFloat(lat), parseFloat(lon)),
            strokeWeight: 0,
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
        alert(client.planned_depairport + ' -> ' + client.planned_destairport);
    }
//})();