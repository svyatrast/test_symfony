<template>
    <v-container class="my-5">
        <v-btn class="mx-2" to="/">
            Back
        </v-btn>
        <h1 class="my-4">Favorite Fruits</h1>
        <div v-if="favoriteFruits.length > 0">
            <v-table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Family</th>
                    <th>Calories</th>
                    <th>Fat</th>
                    <th>Protein</th>
                    <th>Carbohydrates</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(fruit, index) in favoriteFruits" :key="index">
                    <td>{{ fruit.name }}</td>
                    <td>{{ fruit.family }}</td>
                    <td>{{ fruit.nutritions.calories }} kcal</td>
                    <td>{{ fruit.nutritions.fat }} g</td>
                    <td>{{ fruit.nutritions.protein }} g</td>
                    <td>{{ fruit.nutritions.carbohydrates }} g</td>
                    <td class="pa-1">
                        <div>
                        <v-btn class="mx-2"  @click="removeFromFavorites(fruit)">
                            Remove from favorites
                        </v-btn>
                        </div>
                    </td>
                </tr>
                </tbody>
            </v-table>
            <div class="my-4">
                <h2 class="my-5">Total Nutrition Facts</h2>

                <v-card>
                    <v-card-title>
                        <v-row>
                            <v-col cols="3">Calories</v-col>
                            <v-col cols="3">Fat</v-col>
                            <v-col cols="3">Protein</v-col>
                            <v-col cols="3">Carbohydrates</v-col>
                        </v-row>
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="3">{{ totalNutritions.calories }}</v-col>
                            <v-col cols="3">{{ totalNutritions.fat }}</v-col>
                            <v-col cols="3">{{ totalNutritions.protein }}</v-col>
                            <v-col cols="3">{{ totalNutritions.carbohydrates }}</v-col>

                        </v-row>
                    </v-card-text>
                </v-card>
            </div>
        </div>
        <div v-else>
            <p>You have not added any fruits to favorites yet.</p>
        </div>
    </v-container>
</template>

<script>
export default {
    name: 'FavoriteFruits',
    data() {
        return {
            favoriteFruits: []
        }
    },
    created() {
        this.favoriteFruits = JSON.parse(localStorage.getItem('favorites')) || []
    },
    computed: {
        totalNutritions() {
            let total = {
                calories: 0,
                carbohydrates: 0,
                fat: 0,
                protein: 0,
                sugar: 0
            }
            this.favoriteFruits.forEach(fruit => {
                total.calories += fruit.nutritions.calories
                total.carbohydrates += fruit.nutritions.carbohydrates
                total.fat += fruit.nutritions.fat
                total.protein += fruit.nutritions.protein
                total.sugar += fruit.nutritions.sugar
            })
            return total
        }
    },
    methods: {
        removeFromFavorites(fruit) {
            const index = this.favoriteFruits.findIndex(f => f.id === fruit.id)
            if (index !== -1) {
                this.favoriteFruits.splice(index, 1)
            }
            localStorage.setItem('favorites', JSON.stringify(this.favoriteFruits))
        }
    },
}
</script>
