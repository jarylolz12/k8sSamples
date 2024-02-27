<template>
    <div v-if="items.length > 0 || firstLoad" class="bg-20 rounded-b">
        <nav class="flex justify-between items-center">
            <button @click="$emit('prevPage')" :disabled="prevPageUrl==null" rel="prev" dusk="previous" :class="(prevPageUrl==null) ? 'opacity-50 text-80 opacity-50' : 'text-primary dim'" class="btn btn-link py-3 px-4">
                <slot name="previous"></slot>
            </button>
            <span class="text-sm text-80 px-4">
                {{from}}-{{ to }} of {{ total }}
            </span>
            <button @click="$emit('nextPage')" :disabled="nextPageUrl==null" rel="next" dusk="next" :class="(nextPageUrl==null) ? 'opacity-50 text-80 opacity-50' : 'text-primary dim'" class="btn btn-link py-3 px-4">
                <slot name="next"></slot>
            </button>
        </nav>
    </div>
</template>

<script>
export default {
    components:{},
    props: ['items', 'from', 'to', 'total', 'nextPageUrl', 'prevPageUrl', 'firstLoad'],
    mounted() {},
    methods: {}
}
</script>