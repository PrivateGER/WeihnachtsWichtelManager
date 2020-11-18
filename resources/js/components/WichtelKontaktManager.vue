<template>
    <div>
        <div v-if="this.loaded && !this.error">
            <v-card shaped v-for="target in this.targetData" class="ma-6" v-bind:key="target.id">
                <GiftTarget :targetid="target.to" :selfid="userid"></GiftTarget>
                <br />
            </v-card>
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
    </div>
</template>

<script>
import GiftTarget from "./GiftTarget";
export default {
    name: "WichtelKontaktManager",
    components: {GiftTarget},
    props: {
        userid: Number
    },
    data: function () {
        return {
            targetData: [],
            loaded: false,
            error: false,
        }
    },
    methods: {
        getTargets: function () {
            fetch("/api/targets?token=" + window.findGetParameter("token"))
                .then((res) => {
                    if(res.status === 403) throw new Error();
                    return res.json();
                })
                .then((res) => {
                    this.targetData = res;
                    this.error = false;
                    this.loaded = true;
                })
                .catch((err) => {
                    this.error = true;
                    this.loaded = true;
                });
        },
    },
    mounted() {
        this.getTargets();
    }
}
</script>

<style scoped>

</style>
