<template>
    <div id="google-map" v-bind:id="mapName"></div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: 'google-map',
        props: ['name'],

        data: function(){
            return {
                mapName: "google-map",
                clients: [],
                markers: [],
                loaded: false
            }
        },

        mounted() {
            console.log('Map mounted.');
            const element = document.getElementById(this.mapName);
            const options = {
                center: {lat: 52.379189, lng: 4.899431},
                zoom: 6,
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
            };
            this.map = new google.maps.Map(element, options);
            this.requestClientData();
            setInterval(this.requestClientData, 1000 * 10);
        },

        methods: {
            requestClientData: function(){
                axios.get('/api/clientdata').then(response =>{
                    console.log('[' + new Date().getHours() + ':' + new Date().getMinutes() + '] - RECEIVED MAP DATA');
                    this.clients = response.data;

                    // Remove all non pilots from the clients list.
                    this.clients = this.clients.filter(function(client){
                        return client.clienttype === 'PILOT';
                    });

                    // Update our global client counter
                    this.$store.state.totalClients = this.clients.length;

                    this.loadClients(this.clients);
                    this.loaded = true;
                }).catch(e => {
                    console.log(e);
                });
            },

            loadClients: function(data){
                for (var i = 0; i < data.length; i++) {
                    if(this.markers[data[i].cid] === undefined){
                        this.addMarker(data[i].cid, data[i].latitude, data[i].longitude, data[i].heading);
                    }else{
                        this.markers[data[i].cid].setPosition(new google.maps.LatLng(parseFloat(data[i].latitude), parseFloat(data[i].longitude)));
                        let icon = this.markers[data[i].cid].getIcon();
                        icon.rotation = parseInt(data[i].heading);
                        this.markers[data[i].cid].setIcon(icon);
                    }
                    this.markers[data[i].cid].last_update = new Date();
                }

                this.removeUnusedMarkers();
            },

            addMarker: function(cid, lat, lon, heading){
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
                    map: this.map,
                    icon: icon
                });

                // Click listener
                let self = this;
                marker.addListener('click', function () {
                    console.log('CLICK! - ' + cid);
                    self.showData(cid);
                });

                this.markers[cid] = marker;
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

            showFlightInfo(show, client = undefined){
                console.log('Showing flight info of ' + client.cid + ' :P');
            }
        }
    }
</script>
