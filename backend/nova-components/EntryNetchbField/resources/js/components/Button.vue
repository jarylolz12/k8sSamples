<template>
    <button
        class="btn btn-primary p-3"
        @click.prevent="handleClick"
        :disabled="isLoading || isDisabled"
    >
        <template v-if="!isLoading">
            Submit To NETCHB
        </template>
        <template v-else>
            Loading
        </template>
    </button>
</template>

<script>
export default {
    props: ["isLoading", "isDisabled"],
    methods: {
        handleClick: function() {
            this.$emit("onClick");
        }
    }
};
</script>

<style scoped></style>
