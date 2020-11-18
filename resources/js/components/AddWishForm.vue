<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="600px"
        >
            <template v-slot:activator="{ on, attrs }">

                <v-btn
                    elevation="4"
                    icon
                    rounded
                    block
                    v-bind="attrs"
                    v-on="on"
                    color="primary">
                    Wunsch hinzufügen
                    <v-icon>mdi-note-plus-outline</v-icon>
                </v-btn>
            </template>
            <v-card>
                <v-card-title>
                    <span class="headline">Wunsch hinzufügen</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-textarea
                                    v-model="wishText"
                                    label="Wunsch*"
                                    required
                                    auto-grow
                                    autofocus>
                                </v-textarea>
                            </v-col>
                        </v-row>
                    </v-container>
                    <small>*nötiges Feld</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="dialog = false"
                    >
                        Schließen
                    </v-btn>
                    <v-btn
                        color="blue darken-1"
                        text
                        v-on:click="addWish"
                    >
                        Speichern
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import Swal from "sweetalert2";

export default {
    name: "AddWishForm",
    data: () => ({
        dialog: false,
        wishText: ""
    }),
    methods: {
        addWish() {
            fetch("/api/addwish", {
                method: "POST",
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    "wish": this.wishText,
                    "token": window.findGetParameter("token")
                })
            }).then((res) => {
                if(res.status !== 200) throw new Error();

                Swal.fire({
                    text: 'Erfolgreich gespeichert!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                })
                this.$emit("updateTableEvent")
                this.wishText = "";
                this.dialog = false;
            }).catch((err) => {
                Swal.fire({
                    title: "Fehler",
                    text: 'Konnte nicht speichern.\nVersuche es erneut.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            });
        }
    }
}
</script>

<style scoped>

</style>
