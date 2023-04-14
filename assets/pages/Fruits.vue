<template>
    <v-container class="my-5">
        <h1 class="mb-5">All Fruits</h1>

        <v-row class="mb-5">
            <v-col cols="12" md="6">
                <v-text-field v-model="name" label="Search by name"></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
                <v-autocomplete
                    v-model="selectedFamilies"
                    :items="families"
                    label="Filter by family"
                    multiple
                    clearable
                    chips
                ></v-autocomplete>
            </v-col>
        </v-row>

        <v-row>
            <v-col v-for="fruit in fruits" :key="fruit.id" cols="12" sm="6" md="4" lg="3">
                <v-card>
                    <v-card-title>
                        <div>{{ fruit.name }}</div>
                        <div>{{ fruit.family }}</div>
                    </v-card-title>

                    <v-card-actions>
                        <v-btn :disabled="isFavorite(fruit) || favorites.length >= 10" @click="addToFavorites(fruit)">Add to favorites</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>

        <v-pagination v-model="page" :length="totalPages" @input="fetchFruits"></v-pagination>

    </v-container>
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            name: '',
            families: [],
            selectedFamilies: [],
            fruits: [],
            favorites: [],
            page: 1,
            perPage: 10,
            totalItems: 0,
        }
    },
    created() {
        this.fetchFruits()
        this.fetchFamilies()
        this.favorites = JSON.parse(localStorage.getItem('favorites')) || []
    },
    methods: {
        fetchFruits() {
            axios.get('/fruits', {
                params: {
                    name: this.name,
                    families: this.selectedFamilies,
                    page: this.page,
                    per_page: this.perPage,
                },
            })
                .then(response => {
                    this.fruits = response.data.fruits
                    this.totalItems = response.data.count
                })
                .catch(error => {
                    console.log(error)
                })
        },
        fetchFamilies() {
            axios.get('/fruits/families')
                .then(response => {
                    this.families = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        },
        addToFavorites(fruit) {
            this.favorites.push(fruit)
            localStorage.setItem('favorites', JSON.stringify(this.favorites))
        },
        isFavorite(fruit) {
            return this.favorites.some(f => f.id === fruit.id)
        },
    },
    computed: {
        totalPages() {
            return Math.ceil(this.totalItems / this.perPage)
        },
    },
    watch: {
        page() {
            this.fetchFruits()
        },
        name() {
            this.fetchFruits()
        },
        selectedFamilies() {
            this.fetchFruits()
        },
    },
}
</script>
