<template>
    <div class="overlay search" :class="(search !== '' && loading) ? 'show' : ''">
        <div class="preloader" v-if="(search !== '' && loading)">
            <v-progress-circular
                :size="40"
                color="#0171a1"
                indeterminate>
            </v-progress-circular>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"

export default {
    props: ['loading', 'search', 'className'],
    components: {},
    data: () => ({}),
    computed: {
        ...mapGetters([]),
    },
    mounted() {},
    methods: {
        ...mapActions([])
    },
    updated() {}
}
</script>
