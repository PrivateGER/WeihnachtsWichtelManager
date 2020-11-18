<template>
    <div v-if="loaded && !error">
        <v-list-item>
            <v-list-item-content>
                <v-list-item-title>
                    <UserInfo class="header" :userid="targetid" :extended=true :selfid="selfid"></UserInfo>
                </v-list-item-title>
            </v-list-item-content>
        </v-list-item>

        <v-divider></v-divider>


    </div>
    <div v-else-if="loaded && error">
        <v-alert
            elevation="5"
            type="error"
        >Ungültiger Anmeldeschlüssel. Stimmt der Link?</v-alert>
    </div>
    <div v-else>
        <p>Lädt...</p>
    </div>
</template>

<script>
import UserInfo from "./UserInfo";
export default {
    name: "GiftTarget",
    components: {UserInfo},
    props: {
        targetid: Number,
        selfid: Number
    },
    data() {
        return {
            loaded: false,
            error: false,
            gifters: []
        }
    },
    methods: {
        fetchRelationship: async function() {
            let result = await fetch("/api/" + this.targetid + "/gifters?token=" + window.findGetParameter("token"))
            let json = await result.json();
            if (result.status === 403) {
                this.error = true;
                return;
            }

            this.gifters = json;
            this.loaded = true;
        },
        claimWish() {

        }
    },
    mounted() {
        this.fetchRelationship();
    }
}
</script>

<style scoped>
.header {
    text-decoration: underline;
    font-weight: bold;
}
</style>
