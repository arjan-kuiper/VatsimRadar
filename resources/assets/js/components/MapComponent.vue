<template>
    <div id="radar-map" v-bind:id="mapName"></div>
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
                atc: [],
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

            this.requestClientData();
            setInterval(this.requestClientData, 1000 * 10);
        },

        methods: {
            requestClientData: function(){
                axios.get('/api/clientdata').then(response =>{
                    console.log('[' + new Date().getHours() + ':' + new Date().getMinutes() + '] - RECEIVED MAP DATA');
                    this.clients = response.data;

                    // Remove all non pilots from the clients list.
                    let self = this;
                    this.atc = [];
                    this.clients = this.clients.filter(function(client){
                        if(client.clienttype === 'ATC'){
                            self.atc.push(client);
                        }
                        return client.clienttype === 'PILOT';
                    });

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
                        this.addMarker(this.clients[i].cid, this.clients[i].latitude, this.clients[i].longitude, this.clients[i].heading);
                    }else{
                        if(this.clients[i].latitude !== undefined && this.clients[i].longitude !== undefined){
                            this.markers[this.clients[i].cid].setLatLng(new L.LatLng(parseFloat(this.clients[i].latitude), parseFloat(this.clients[i].longitude)));
                        }
                    }
                    this.markers[this.clients[i].cid].last_update = new Date();
                }
                for(let i = 0; i < this.atc.length; i++){
                    if(this.markers[this.atc[i].cid] === undefined){
                        if(this.atc[i].latitude !== undefined && this.atc[i].longitude !== undefined){
                            this.addATC(this.atc[i].cid, 'red', this.atc[i].callsign.slice(-3), this.atc[i].latitude, this.atc[i].longitude);
                        }
                    }
                }
                this.removeUnusedMarkers();
            },

            addMarker: function(cid, lat, lon, heading){
                lat = parseFloat(lat);
                lon = parseFloat(lon);

                let icon = L.icon({
                    iconUrl: 'css/images/plane.svg',
                    iconSize:     [22, 22], // size of the icon
                    iconAnchor:   [11, 22], // point of the icon which will correspond to marker's location
                });

                if(lat !== undefined && lon !== undefined){
                    this.markers[cid] = L.marker([lat, lon], {icon: icon, rotationAngle: heading}).addTo(this.map);
                }
            },

            addATC: function(cid, color, type, lat, lon){
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
                    let atcIds = [];
                    for (let i = 0; i < this.atc.length; i++) {
                        atcIds.push(this.atc[i].cid);
                    }
                    toRemove.forEach((marker, markerId) => {
                        if (clientIds.indexOf(markerId) > -1 || atcIds.indexOf(markerId) > -1) {
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

            showFlightInfo(show, client = undefined){
                console.log('Showing flight info of ' + client.cid + ' :P');
            }
        }
    }
</script>
