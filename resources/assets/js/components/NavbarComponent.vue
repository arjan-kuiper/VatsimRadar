<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="z-index: 1337">
        <a class="navbar-brand" href="#">VatsimRadar 📡 <span class="badge badge-warning">BETA</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">Live Map <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">FAQ</a>
                </li>
            </ul>
        </div>
        <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a id="engine-replace-client_count" class="nav-link">Serving <strong>{{ totalClients }}</strong> clients</a>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
    export default {
        data: function(){
            return {

            }
        },

        mounted(){
            console.log('Navbar mounted.');
        },

        computed: {
            totalClients(){
                return this.$store.state.totalClients;
            }
        }
    }
</script>