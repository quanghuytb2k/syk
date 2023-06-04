<template>
    <App v-if="this.isLogged"></App>
    <Auth v-else></Auth>
</template>
<script>
import { mapGetters } from 'vuex';
import Auth from './auth/Auth.vue';
import App from './App.vue';
export default {
    components: {
        Auth,
        App
    },
    computed: {
        ...mapGetters('auth', [
            'isLogged'
        ])
    }
}
</script>
