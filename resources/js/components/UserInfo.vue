<template>
    <div v-if="this.loaded">
        <v-card>
            <v-card-title>
                <p class="break">{{ this.userdata.name }} - Wunschliste</p>
            </v-card-title>

            <v-list-item v-for="wish in this.wishes" v-bind:key="wish.id">
                <v-list-item-content>
                    <v-container>

                        <v-btn color="primary" v-if="wish.claimed_by === null" v-on:click="claimWish(wish.id)"><p>Beanspruchen<v-icon>mdi-book-lock</v-icon></p></v-btn>
                        <p v-else-if="wish.claimed_by === selfid">Von dir reserviert<v-icon>mdi-lock</v-icon></p>
                        <p v-else class="headline">Bereits reserviert<v-icon>mdi-lock</v-icon></p>

                        <v-list-item-content>
                            <p>{{  wish.wish }}</p>
                        </v-list-item-content>

                        <br />
                        <v-divider></v-divider>
                    </v-container>
                </v-list-item-content>
            </v-list-item>
        </v-card>
    </div>
    <div v-else>
        Lädt...
    </div>
</template>

<script>
import Swal from "sweetalert2";

export default {
    name: "UserInfo",
    props: {
        userid: Number,
        extended: Boolean,
        selfid: Number
    },
    data() {
        return {
            loaded: false,
            wishes: [],
            userdata: {}
        }
    },
    methods: {
        fetchSavedWishes() {
            fetch("/api/" + this.userid + "/wishes")
                .then((res) => { return res.json() })
                .then((res) => { this.wishes = res })
        },
        fetchProfileInfo() {
            fetch("/api/" + this.userid + "/info")
                .then((res) => { return res.json() })
                .then((res) => {
                    this.userdata = res;
                    this.loaded = true;
                })
        },
        claimWish(id) {
            if(confirm("Das Beanspruchen dieses Wunsches wird den Wunsch permanent für den anderen Wichtelpartner sperren. Bist du sicher, dass du den Wunsch beanspruchen möchtest?")) {
                if(!confirm("Dies kann nicht rückgängig gemacht werden. OK?")) return;
            } else return;

            fetch("/api/claimwish", {
                method: "POST",
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    "id": id,
                    "token": window.findGetParameter("token")
                })
            }).then((res) => {
                if(res.status !== 200) throw new Error();

                Swal.fire({
                    text: 'Erfolgreich beansprucht.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                })

                this.fetchSavedWishes();
            }).catch((res) => {
                Swal.fire({
                    text: 'Konnte Wunsch nicht beanspruchen. Versuche, die Webseite neu zu laden.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            });
        },
        fetchNeededData() {
            if(this.extended) {
                this.fetchSavedWishes();
            }
            this.fetchProfileInfo();
        }
    },
    mounted() {
        this.fetchNeededData();

        setInterval(this.fetchSavedWishes, 10000);
    }
}
</script>

<style scoped>
    .break {
        word-wrap: break-spaces;
        word-break: break-word;
    }
</style>

