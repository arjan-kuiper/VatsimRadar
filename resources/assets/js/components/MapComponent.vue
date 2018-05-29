<template>
    <div>
        <div id="radar-map" v-bind:id="mapName"></div>
        <div>{{ showATC }}</div><div>{{ searchQuery }}</div>

        <!-- No flight info modal -->
        <div class="modal fade" id="noFlightData" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Oh no!</h5>
                        <button v-on:click.stop="clearCurrentSelected" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        We couldn't retrieve any information about this flight.<br>
                        This pilot must have forgotten to fill in his flightplan. 📋
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Copy!</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // Function to get the differences between arrays. Used for removing markers
    Array.prototype.diff = function(a){
        return this.filter(function(i){ return a.indexOf(i) < 0; });
    };

    import axios from 'axios';

    export default {
        name: 'radar-map',
        props: ['name'],

        data: function(){
            return {
                mapName: "radar-map",
                clients: [],
                markers: [],
                loaded: false,
                lastSearchQuery: '',
                selectedPlane: undefined,
                updatedATC: true,
            }
        },

        mounted() {
            console.log('Map mounted.');

            this.map = L.map('radar-map', {zoomControl: false}).setView([51.260197, 4.402771], 6);
            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                attribution: 'VatsimRadar 📡 is powered by <a href="https://www.openstreetmap.org/">OpenStreetMap</a> & <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 18,
                id: 'mapbox.dark',
                accessToken: 'pk.eyJ1IjoiYXJqYW5rIiwiYSI6ImNqaDk0ZnV2NzBha3czYXFoNm9haDc3ZnAifQ.mMG34-9irYVnXu2mpl06pw',
                //noWrap: true, - Disabled, because it causes errors with Mapbox tiles
            }).addTo(this.map);

            let self = this;
            this.map.on('click', function(){
                self.clearCurrentSelected();
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
                    console.log('TOTAL CIENTS: ' + this.clients.length);

                    this.loadClients();
                    this.loaded = true;
                }).catch(e => {
                    console.log(e);
                });
            },

            loadClients: function(){
                if(this.map === undefined) return;
                let self = this;
                this.map.eachLayer(function(layer){
                    if(layer.identifier === 'PILOT' || layer.identifier === 'ATC')
                        self.map.removeLayer(layer);
                });
                for(let i = 0; i < this.clients.length; i++){
                    this.addMarker(this.clients[i], this.clients[i].latitude, this.clients[i].longitude, this.clients[i].heading);
                }
            },

            addMarker: function(client, lat, lon, heading){
                lat = parseFloat(lat);
                lon = parseFloat(lon);
                let icon, selectedId;

                if(this.selectedPlane !== undefined && this.selectedPlane.client_cid === client.cid){
                    selectedId = client.cid;
                    icon = L.icon({
                        iconUrl: 'css/images/plane_selected.svg',
                        iconSize:     [22, 22], // size of the icon
                        iconAnchor:   [11, 22], // point of the icon which will correspond to marker's location
                    });
                }else{
                    icon = L.icon({
                        iconUrl: 'css/images/plane.svg',
                        iconSize:     [22, 22], // size of the icon
                        iconAnchor:   [11, 22], // point of the icon which will correspond to marker's location
                    });
                }

                if(!isNaN(lat) && !isNaN(lon)) {
                    if(client.clienttype === 'PILOT'){
                        let marker = L.marker([lat, lon], {icon: icon, rotationAngle: heading}).addTo(this.map);
                        marker.identifier = 'PILOT';

                        if(selectedId !== undefined){
                            this.selectedPlane = marker;
                            this.selectedPlane.client_cid = client.cid;
                        }

                        // Info tooltip
                        marker.bindTooltip(
                            client.callsign
                            ,{
                                offset: [-22, 0],
                                tooltipAnchor: [22, 22],
                                direction: 'left'
                            });

                        // Data for the sidebar and show it
                        let self = this;
                        marker.on('click', function(){
                            self.clearCurrentSelected();
                            let icon = L.icon({
                                iconUrl: 'css/images/plane_selected.svg',
                                iconSize:     [22, 22], // size of the icon
                                iconAnchor:   [11, 22], // point of the icon which will correspond to marker's location
                            });
                            marker.setIcon(icon);
                            marker.client_cid = client.cid;
                            self.selectedPlane = marker;
                            self.selectedPlane.client_cid = client.cid;

                            console.log(client.callsign + ' - ' + client.planned_aircraft);
                            self.showFlightInfo(client);
                        });

                        client.marker = marker;
                        return marker;
                    }else if(client.clienttype === 'ATC'){
                        if(!this.$store.state.showATC) return;
                        let radius = {
                            'GND': [5000, 'gray'],
                            'TWR': [25000, 'blue'],
                            'APP': [50000, 'red']
                        };
                        let type = client.callsign.slice(-3);
                        if(type === 'TIS'){ type = 'ATIS'; }
                        if(type !== 'CTR' && type !== 'TWR' && type !== 'APP') return;

                        let marker;
                        if(type !== 'CTR'){
                            console.log('Called TWR');
                            marker = L.circle([parseFloat(lat),parseFloat(lon)], {
                                color: radius[type][1],
                                opacity: 0.6,
                                fillColor: radius[type][1],
                                fillOpacity: 0.3,
                                radius: (!isNaN(radius[type][0])) ? radius[type][0] : 0
                            }).addTo(this.map);
                        }else if(type === 'CTR'){
                            let callsign = client.callsign.substr(0, client.callsign.length - 4);
                            //console.log('Called CTR');

                            axios.get('/api/fir/' + callsign).then(response =>{
                                let coords = [];
                                for(let i = 0; i < response.data.length; i++){
                                    coords.push([
                                        parseFloat(response.data[i][0]),
                                        parseFloat(response.data[i][1])
                                    ]);
                                }
                                marker = L.polygon(coords, {color: 'green', opacity: 0.6, fillOpacity: 0.3}).addTo(this.map);
                                marker.identifier = 'ATC';
                                marker.bindTooltip(
                                    '<strong>' + client.callsign + '</strong><br>' +
                                    'Frequency: ' + client.frequency + '</br>' +
                                    'Visual Range: ' + client.visualrange + 'nm</br>' +
                                    'Rating: ' + client.rating
                                );
                                return marker;
                            }).catch(e => {
                                console.log(e);
                            });
                        }

                        if(marker === undefined) return;
                        marker.identifier = 'ATC';

                        // Build an info tooltip
                        marker.bindTooltip(
                            '<strong>' + client.callsign + '</strong><br>' +
                            'Frequency: ' + client.frequency + '</br>' +
                            'Visual Range: ' + client.visualrange + 'nm</br>' +
                            'Rating: ' + client.rating
                        );

                        client.marker = marker;
                        return marker;
                    }
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
                aircraftType = pilot.planned_aircraft.indexOf('B744') >= 0 ? 'B744' : aircraftType;
                aircraftType = pilot.planned_aircraft.indexOf('A320') >= 0 ? 'A320' : aircraftType;
                aircraftType = pilot.planned_aircraft.indexOf('A319') >= 0 ? 'A319' : aircraftType;
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
                let departureSeconds = (plannedDeptime === '--:--' ? 0 : (plannedDeptime[0] * 60 * 60)) + (plannedDeptime[1] * 60);
                let arrivalSeconds = plannedArrival === '--:--' ? 0 : (plannedArrival[0] * 60 * 60) + plannedArrival[1] * 60;
                let currentSeconds = (new Date().getUTCHours() * 60 * 60) + (new Date().getUTCMinutes() * 60);
                let percentage_covered = Math.round((currentSeconds - departureSeconds) / (arrivalSeconds - departureSeconds) * 100);
                console.log('DEP: ' + departureSeconds);
                console.log('CUR: ' + currentSeconds);
                console.log('ARR: ' + arrivalSeconds);
                console.log('%: ' + percentage_covered);

                let promises = [];
                promises.push(axios.get('/api/airport/' + pilot.planned_depairport + '/IATA'));
                promises.push(axios.get('/api/airport/' + pilot.planned_destairport + '/IATA'));
                promises.push(axios.get('/api/aircraftimg/' + aircraftType + '/' + airline));

                let self = this;
                axios.all(promises).then(function(result){
                    self.$store.state.flightInformation['image'] = (result[2].data === 404) ? '/img/no-aircraft.png' : result[2].data;
                    self.$store.state.flightInformation['flightnr'] = pilot.callsign;
                    self.$store.state.flightInformation['departure_airport'] = pilot.planned_depairport;
                    self.$store.state.flightInformation['departure_airport_iata'] = (result[0].data === 404) ? 'N/A' : result[0].data;
                    self.$store.state.flightInformation['arrival_airport'] = pilot.planned_destairport;
                    self.$store.state.flightInformation['arrival_airport_iata'] = (result[1].data === 404) ? 'N/A' : result[1].data;
                    self.$store.state.flightInformation['travel_percentage'] = (isNaN(percentage_covered) || percentage_covered < 0) ? 0 : percentage_covered;
                    self.$store.state.flightInformation['departure_estimated'] = plannedDeptime === '--:--' ? plannedDeptime : (plannedDeptime[0] + ':' + plannedDeptime[1]);
                    self.$store.state.flightInformation['departure_actual'] = plannedActualDeptime === '--:--' ? plannedActualDeptime : (plannedActualDeptime[0] + ':' + plannedActualDeptime[1]);
                    self.$store.state.flightInformation['arrival_estimated'] = plannedArrival === '--:--' ? plannedArrival : (plannedArrival[0] + ':' + plannedArrival[1]);
                    self.$store.state.flightInformation['flightplan'] = pilot.planned_route;
                    self.$store.state.flightInformation['aircraft_type'] = pilot.planned_aircraft;
                    self.$store.state.flightInformation['aircraft_pilot'] = pilot.realname;
                    self.$store.state.flightInformation['aircraft_cid'] = pilot.cid;
                    self.$store.state.flightInformation['aircraft_speed'] = pilot.groundspeed;
                    self.$store.state.flightInformation['aircraft_heading'] = pilot.heading;
                    self.$store.state.flightInformation['aircraft_altitude'] = pilot.altitude.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                    self.$store.state.showSidebar = true;
                }).catch(function(error){
                    $('#noFlightData').modal('show');
                });
            },

            clearCurrentSelected(){
                console.log('Clear: ' + this.selectedPlane);
                if(this.selectedPlane === undefined) return;
                let self = this;
                this.map.eachLayer(function(layer){
                    if(layer.client_cid = self.selectedPlane.client_cid){
                        let icon = L.icon({
                            iconUrl: 'css/images/plane.svg',
                            iconSize:     [22, 22], // size of the icon
                            iconAnchor:   [11, 22], // point of the icon which will correspond to marker's location
                        });
                        self.selectedPlane.setIcon(icon);
                    }
                });

                this.selectedPlane = undefined;
            }
        },

        computed: {
            showATC(){
                if(this.$store.state.showATC === this.updatedATC) return;
                this.loadClients();
                this.updatedATC = this.$store.state.showATC;
                return this.$store.state.showATC;
            },

            searchQuery(){
                let query = this.$store.state.searchQuery;
                if(query === this.lastSearchQuery) return;
                if(query.length > 3){
                    query = query.toUpperCase();
                    this.clients.forEach((client) =>{
                       if(query === client.callsign){
                           this.map.setView([parseFloat(client.latitude), parseFloat(client.longitude)], 12);
                           this.lastSearchQuery = query;
                       }
                    });
                }
            }
        }
    }
</script>
