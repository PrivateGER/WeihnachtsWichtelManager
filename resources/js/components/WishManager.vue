<template>
    <div class="pa-2">
        <h1>Hallo, {{ user.name }}!</h1>

        <v-card>
            <v-list-item>
                <v-list-item-content>
                    <v-list-item-title>
                        <h3>
                            Wunschliste
                        </h3>
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-list-item v-for="wish in this.wishes" v-bind:key="wish.id">
                <v-list-item-content>
                    <v-container class="pa-6">
                        <v-btn v-on:click="deleteWish(wish.id)" icon>Löschen<v-icon>mdi-delete</v-icon></v-btn>
                        <v-list-item-title>{{  wish.wish }}</v-list-item-title>
                    </v-container>
                </v-list-item-content>
            </v-list-item>

            <AddWishForm v-on:updateTableEvent="fetchSavedWishes" />
        </v-card>


    </div>

</template>

<script>
import Swal from "sweetalert2";
import AddWishForm from "./AddWishForm";

export default {
    name: "WishManager",
    components: {AddWishForm},
    props: {
        user: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            wishes: []
        }
    },
    mounted: function () {
        this.fetchSavedWishes();
    },
    methods: {
        fetchSavedWishes() {
            fetch("/api/" + this.user.id + "/wishes")
                .then((res) => { return res.json() })
                .then((res) => {
                    this.wishes = res;
                })
        },
        deleteWish(id) {
            if(!confirm("Bist du sicher dass du diesen Wunsch löschen möchtest?")) return;

            fetch("/api/deletewish", {
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
                if(res.status !== 204) throw new Error();

                this.fetchSavedWishes();
                Swal.fire({
                    text: 'Erfolgreich gelöscht!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                })
            }).catch((res) => {
                Swal.fire({
                    title: "Wunsch konnte nicht gelöscht werden.",
                    text: 'Möglicherweise hat einer deiner Wichtelpartner diesen Wunsch bereits beansprucht.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            })
        }
    }
}
</script>

<style scoped>

</style>
