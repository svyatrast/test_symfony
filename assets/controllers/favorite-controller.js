import { createApp } from 'vue';
import  App  from '../pages/FavoriteFruits.vue';
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
    components,
    directives,
})
createApp(App).use(vuetify).mount('#favorites');
