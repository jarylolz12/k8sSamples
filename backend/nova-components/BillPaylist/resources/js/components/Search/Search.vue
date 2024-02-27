<template>
	<div class="relative h-9 flex-no-shrink mb-6">
	    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="search" role="presentation" class="fill-current absolute search-icon-center ml-3 text-70">
	        <path fill-rule="nonzero" d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path>
	    </svg>
	    <input v-model="search" @keyup="$emit('searchItemNow')" placeholder="Search" type="search" class="appearance-none form-search w-search pl-search shadow">
	</div>
</template>
<script>
export default {
    components:{},
    props: ['searchQuery'],
    computed: {
    	search: {
    		get() {
    			return this.searchQuery
    		},
    		set(value) {
    			this.$emit('update:searchQuery', value)
    		}
    	}
    },
    mounted() {
    },
}
</script>
