<template>
    <v-app class="pa-6">
        <div v-if="this.loaded && !this.error">
            <WishManager :user="this.userData" class="pa-8"></WishManager>

            <WichtelKontaktManager :userid="userData.id" class="pa-8"></WichtelKontaktManager>
        </div>
        <div v-else-if="this.loaded && this.error">
            <v-alert
                elevation="5"
                type="error"
            >Ungültiger Anmeldeschlüssel. Stimmt der Link?</v-alert>
        </div>
        <div v-else>
            <p>Lädt...</p>
        </div>
    </v-app>
</template>

<script>


import WishManager from "./WishManager";
import WichtelKontaktManager from "./WichtelKontaktManager";
export default {
    name: "App",
    components: {WichtelKontaktManager, WishManager},
    props: {

    },
    data() {
        return {
            loaded: false,
            error: false,
            userData: null,
        }
    },
    methods: {
        loadUser: function () {
            let token = window.findGetParameter("token");
            if(token === null) {
                this.error = true;
                this.loaded = true;
                console.log("No token")
            }

             fetch("/api/user?token=" + token)
                .then((res) => {
                    if(res.status === 403) throw new Error();
                    return res.json()
                })
                .then((res) => {
                    this.userData = res;
                    this.error = false;
                    this.loaded = true;
                })
                .catch((err) => {
                    this.error = true;
                    this.loaded = true;
                })
        }
    },
    mounted: function () {
        this.loadUser();
    }
}
</script>

<style scoped>

</style>
