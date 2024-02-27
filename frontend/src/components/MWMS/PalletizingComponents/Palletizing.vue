<template>
    <div class="mwms-receving-wrapper">
        Palletizing here
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
    name: "Palletizing",
    components: {},
    computed: {
        ...mapGetters({}),
    },
    methods: {
        ...mapActions({}),
    },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage","mwms")
    }
};
</script>

<style lang="scss">

</style>
