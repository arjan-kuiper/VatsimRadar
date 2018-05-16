<template>
    <div>
        <div id="radar-map" v-bind:id="mapName"></div>
        <div>{{ showATC }}</div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: 'radar-map',
        props: ['name'],

        data: function(){
            return {
                mapName: "radar-map",
                clients: [],
                markers: [],
                loaded: false
            }
        },

        mounted() {
            console.log('Map mounted.');

            this.map = L.map('radar-map', {zoomControl: false}).setView([51.505, -0.09], 13);
            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                attribution: 'Powered by <a href="https://www.openstreetmap.org/">OpenStreetMap</a> & <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 18,
                id: 'mapbox.dark',
                accessToken: 'pk.eyJ1IjoiYXJqYW5rIiwiYSI6ImNqaDk0ZnV2NzBha3czYXFoNm9haDc3ZnAifQ.mMG34-9irYVnXu2mpl06pw'
            }).addTo(this.map);

            let self = this;
            this.map.on('click', function(){
                self.$store.state.showSidebar = false;
            });

            this.requestClientData();
            setInterval(this.requestClientData, 1000 * 10);
        },

        methods: {
            requestClientData: function(){
                axios.get('/api/clientdata').then(response =>{
                    console.log('[' + new Date().getHours() + ':' + new Date().getMinutes() + '] - RECEIVED MAP DATA');
                    this.clients = response.data;

                    // Update our global client counter
                    this.$store.state.totalClients = this.clients.length;

                    this.loadClients();
                    this.loaded = true;
                }).catch(e => {
                    console.log(e);
                });
            },

            loadClients: function(){
                for(let i = 0; i < this.clients.length; i++){
                    if(this.markers[this.clients[i].cid] === undefined){
                        if(this.clients[i].clienttype === 'PILOT'){
                            this.addMarker(this.clients[i], this.clients[i].latitude, this.clients[i].longitude, this.clients[i].heading);
                        }else if(this.clients[i].clienttype === 'ATC'){
                            this.addATC(this.clients[i], this.clients[i].callsign.slice(-3), this.clients[i].latitude, this.clients[i].longitude);
                        }
                    }else{
                        if(this.clients[i].clienttype === 'PILOT' || this.clients[i].clienttype === 'ATC'){
                            if(this.clients[i].latitude !== undefined && this.clients[i].longitude !== undefined){
                                this.markers[this.clients[i].cid].setLatLng(new L.LatLng(parseFloat(this.clients[i].latitude), parseFloat(this.clients[i].longitude)));
                            }
                        }
                    }
                }
                this.removeUnusedMarkers();
            },

            addMarker: function(pilot, lat, lon, heading){
                let cid = pilot.cid;
                lat = parseFloat(lat);
                lon = parseFloat(lon);

                let icon = L.icon({
                    iconUrl: 'css/images/plane.svg',
                    iconSize:     [22, 22], // size of the icon
                    iconAnchor:   [11, 22], // point of the icon which will correspond to marker's location
                });

                if(!isNaN(lat) && !isNaN(lon)){
                    this.markers[cid] = L.marker([lat, lon], {icon: icon, rotationAngle: heading}).addTo(this.map);
                    this.markers[cid].last_update = new Date();
                    this.markers[cid].identifier = 'PILOT';

                    // Info tooltip
                    this.markers[cid].bindTooltip(
                        pilot.callsign
                    ,{
                        offset: [-22, 0],
                        tooltipAnchor: [22, 22],
                        direction: 'left'
                    });

                    // Data for the sidebar and show it
                    let self = this;
                    this.markers[cid].on('click', function(){
                        console.log(pilot.callsign + ' - ' + pilot.planned_aircraft);
                        self.showFlightInfo(pilot);
                    });
                }
            },

            addATC: function(atc, type, lat, lon){
                let cid = atc.cid;
                let radius = {
                    'GND': [5000, 'gray'],
                    'TWR': [25000, 'blue'],
                    'APP': [50000, 'red']
                };
                if(type === 'TIS'){ type = 'ATIS'; }
                if(type !== 'GND' && type !== 'TWR' && type !== 'APP') return;

                this.markers[cid] = L.circle([parseFloat(lat),parseFloat(lon)], {
                    color: radius[type][1],
                    fillColor: radius[type][1],
                    fillOpacity: 0.5,
                    radius: (!isNaN(radius[type][0])) ? radius[type][0] : 0
                }).addTo(this.map);
                this.markers[cid].last_update = new Date();
                this.markers[cid].identifier = 'ATC';

                // Build an info tooltip
                this.markers[cid].bindTooltip(
                    '<strong>' + atc.callsign + '</strong><br>' +
                    'Frequency: ' + atc.frequency + '</br>' +
                    'Visual Range: ' + atc.visualrange + 'nm</br>' +
                    'Rating: ' + atc.rating
                );
            },

            removeUnusedMarkers: function(){
                let toRemove = [];
                this.markers.forEach((marker) => {
                    if ((new Date() - marker.last_update) > 1000 * 60 * 2) {
                        toRemove.push(marker);
                    }
                });
                if (toRemove.length > 0) {
                    let clientIds = [];
                    for (let i = 0; i < this.clients.length; i++) {
                        clientIds.push(this.clients[i].cid);
                    }
                    toRemove.forEach((marker, markerId) => {
                        if (clientIds.indexOf(markerId) > -1) {
                            marker.setMap(null);
                            this.markers.splice(this.markers.indexOf(marker), 1);
                        }
                    });
                    console.log('Removed ' + toRemove.length + ' disconnected clients');
                }
            },

            showData: function(cid){
                let client;
                for (let i = 0; i < this.clients.length; i++) {
                    if (this.clients[i].cid === cid) {
                        client = this.clients[i];
                        break;
                    }
                }
                //drawFlightplan(client);
                this.showFlightInfo(true, client)
            },

            showFlightInfo(pilot){
                /*
                AIRCRAFTS
                 */
                let aircraftType = 'UNKNOWN';
                aircraftType = (pilot.planned_aircraft.indexOf('B738') >= 0 || pilot.planned_aircraft.indexOf('B737') >= 0) >= 0 ? 'B738' : aircraftType;
                aircraftType = pilot.planned_aircraft.indexOf('A320') >= 0 ? 'A320' : aircraftType;
                aircraftType = pilot.planned_aircraft.indexOf('DH8D') >= 0 ? 'DH8D' : aircraftType;
                let airline = pilot.callsign.substr(0, 3);
                /*
                SCHEDULING
                 */
                let plannedDeptime = pilot.planned_deptime, plannedActualDeptime = pilot.planned_actdeptime;
                let plannedHours = pilot.planned_hrsenroute, plannedMinutes = pilot.planned_minenroute, plannedArrival = '--:--';
                if(plannedDeptime.length === 3){ plannedDeptime = 0 + plannedDeptime}
                if(plannedActualDeptime.length === 3){ plannedActualDeptime = 0 + plannedActualDeptime}
                plannedDeptime = [plannedDeptime.substr(0, 2), plannedDeptime.substr(2, 4)];
                plannedActualDeptime = [plannedActualDeptime.substr(0, 2), plannedActualDeptime.substr(2, 4)];
                if(plannedDeptime[0] === '0') { plannedDeptime = '--:--'; }
                if(plannedActualDeptime[0] === '0') { plannedActualDeptime = '--:--'; }
                if(plannedDeptime !== '--:--' && plannedDeptime !== '--:--' && plannedHours !== undefined && plannedMinutes !== undefined){
                    let newHours = parseInt(plannedDeptime[0]) + parseInt(plannedHours);
                    let newMinutes = parseInt(plannedDeptime[1]) + parseInt(plannedMinutes);
                    if(newHours >= 24) { newHours = newHours % 24; newMinutes = 0; }
                    if(newMinutes >= 60) { newMinutes = newMinutes % 60; newHours++; }
                    if(newHours < 10) { newHours = '0' + newHours; }
                    if(newMinutes < 10) { newMinutes = '0' + newMinutes; }

                    plannedArrival = [newHours, newMinutes];
                }

                let promises = [];
                promises.push(axios.get('/api/airport/' + pilot.planned_depairport + '/IATA'));
                promises.push(axios.get('/api/airport/' + pilot.planned_destairport + '/IATA'));

                let self = this;
                axios.all(promises).then(function(result){
                    self.$store.state.flightInformation['image'] = '/img/planes/' + aircraftType + '/' + airline + '/img.jpg';
                    self.$store.state.flightInformation['flightnr'] = pilot.callsign;
                    self.$store.state.flightInformation['departure_airport'] = pilot.planned_depairport;
                    self.$store.state.flightInformation['departure_airport_iata'] = result[0].data;
                    self.$store.state.flightInformation['arrival_airport'] = pilot.planned_destairport;
                    self.$store.state.flightInformation['arrival_airport_iata'] = result[1].data;
                    self.$store.state.flightInformation['departure_estimated'] = plannedDeptime === '--:--' ? plannedDeptime : (plannedDeptime[0] + ':' + plannedDeptime[1]);
                    self.$store.state.flightInformation['departure_actual'] = plannedActualDeptime === '--:--' ? plannedActualDeptime : (plannedActualDeptime[0] + ':' + plannedActualDeptime[1]);
                    self.$store.state.flightInformation['arrival_estimated'] = plannedArrival === '--:--' ? plannedArrival : (plannedArrival[0] + ':' + plannedArrival[1]);
                    self.$store.state.showSidebar = true;
                });
            }
        },

        computed: {
            showATC(){
                if(this.$store.state.showATC === true){
                    this.markers.forEach((marker, markerIndex) =>{
                        if(marker.identifier === 'ATC'){
                            this.map.addLayer(marker);
                        }
                    });
                }else{
                    this.markers.forEach((marker, markerIndex) =>{
                        if(marker.identifier === 'ATC'){
                            this.map.removeLayer(marker);
                        }
                    });
                }
                return this.$store.state.showATC;
            }
        }
    }
</script>
