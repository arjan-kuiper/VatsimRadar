<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="z-index: 1337">
            <a class="navbar-brand" href="#">VatsimRadar 📡 <span class="badge badge-warning">BETA</span></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse collapse order-1 order-md-0 dual-collapse2" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Live Map</a>
                    </li>
                    <!--<li class="nav-item">
                        <a class="nav-link" href="/features">Features</a>
                    </li>-->
                    <li class="nav-item">
                        <a class="nav-link" href="/faq">FAQ</a>
                    </li>
                </ul>
            </div>
            <div class="mx-auto order-0">
                <div>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-search"></i></div>
                        </div>
                        <input v-model="searchQuery" v-bind:onchange="submitSearch"
                               type="text" class="form-control" placeholder="Search by callsign">
                    </div>
                </div>
            </div>
            <div class="navbar-collapse collapse order-3 dual-collapse2">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link">
                            <input class="form-check-input" type="checkbox" id="showATC" v-model="showATC" v-bind:onchange="changeShowATC">
                            <label class="form-check-label" for="showATC">
                                Show ATC
                            </label>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link">|</a>
                    </li>
                    <li class="nav-item">
                        <a id="engine-replace-client_count" class="nav-link">Serving <strong>{{ totalClients }}</strong> clients</a>
                    </li>
                </ul>
            </div>
        </nav>


        <!-- Onload message -->
        <div class="modal fade" id="onloadModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Go around!</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <i class="fas fa-exclamation-triangle" style="color: orange"></i> <strong>[05/20/2018 @ 11:48pm (UTC)]</strong>
                        <i class="fas fa-exclamation-triangle" style="color: orange"></i><br>
                        We are aware of some users experiencing degraded performance and are working on a fix.<br>
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
    export default {
        data: function(){
            return {
                showATC: true,
                searchQuery: '',
                showNotification: false
            }
        },

        mounted(){
            console.log('Navbar mounted.');
            $('#onloadModal').modal('show');
        },

        computed: {
            totalClients(){
                return this.$store.state.totalClients;
            },

            changeShowATC(){
                return this.$store.commit('setShowATC', this.showATC);
            },
            submitSearch(){
                this.searchQuery = this.searchQuery.toUpperCase();
                return this.$store.commit('setSearchQuery', this.searchQuery);
            }
        }
    }
</script>