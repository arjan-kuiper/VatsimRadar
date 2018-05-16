<template>
    <nav id="sidebar" v-if="showSidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <img width="300px" alt="Sorry! The aircraft type of this airline has no image yet!" v-bind:src="flightInformation['image']">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md callsign-background" align="center">
                    <span class="callsign">{{ flightInformation['flightnr'] }}</span>
                </div>
            </div>
            <div class="row row-spacer" align="center">
                <div class="col-md">
                    <span class="airport-title">{{ flightInformation['departure_airport'] }}</span><br>
                    <span class="airport-small">{{ flightInformation['departure_airport_iata'] }}</span>
                </div>
                <div class="col-md">
                    <i class="fas fa-plane fa-2x"></i>
                </div>
                <div class="col-md">
                    <span class="airport-title">{{ flightInformation['arrival_airport'] }}</span><br>
                    <span class="airport-small">{{ flightInformation['arrival_airport_iata'] }}</span>
                </div>
            </div>
            <div class="row row-spacer" align="center">
                <div class="col-md">
                    <table class="table schedule">
                        <thead>
                        <tr>
                            <th scope="col"><span class="badge badge-success" style="width:100%">On Schedule</span></th>
                            <th scope="col">Estimated</th>
                            <th scope="col">Actual</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th>Departure</th>
                            <th>{{ flightInformation['departure_estimated'] }}</th>
                            <th>{{ flightInformation['departure_actual'] }}</th>
                        </tr>
                        <tr>
                            <th>Arrival</th>
                            <th>{{ flightInformation['arrival_estimated'] }}</th>
                            <th>{{ flightInformation['arrival_actual'] }}</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row row-spacer">
                //TODO Plane info :D
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        data: function(){
            return {

            }
        },

        mounted() {
            console.log('Sidebar mounted.');
        },

        computed: {
            showSidebar(){
                return this.$store.state.showSidebar;
            },

            flightInformation(){
                return this.$store.state.flightInformation;
            }
        }
    }
</script>