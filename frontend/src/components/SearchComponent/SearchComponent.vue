<template>
    <div class="search-wrapper-global-component" v-if="handleSearchComponent">
        <img src="@/assets/images/search-icon.svg" alt="">

        <input
            class="search" 
            type="text"
            id="search-input"
            v-model.trim="search"
            :placeholder="placeholder"
            @input="handleSearch"
            autocomplete="off" />

        <button @click="clearSearched" v-if="search !== ''">
            <img src="@/assets/images/close.svg" alt="clear" class="img-clear-searched">
        </button>
    </div>
</template>

<script>
export default {
    props: ['handleSearchComponent', 'searchValue', 'placeholder'],
    data: () => ({}),
    computed: {
        search: {
            get() {
                return this.searchValue
            },
            set(value) {
                this.$emit('update:searchValue', value)
            }
        }
    },
    methods: {
        handleSearch() {
            this.$emit('handleSearch')
        },
        clearSearched() {
            this.$emit('clearSearched')
        }
    },
    mounted() {}
}
</script>

<style lang="scss">
@import './search.scss';
</style>
