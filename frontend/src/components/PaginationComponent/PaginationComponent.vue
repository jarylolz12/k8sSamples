<template>
    <div class="pagination-wrapper" v-if="getTotalPage > 1">
        <v-pagination
            v-model="getCurrentPage"
            :length="getTotalPage"
            prev-icon="Previous"
            next-icon="Next"
            :total-visible="!isMobile ? '11' : '5' "
            @input="handlePageChange">
        </v-pagination>
    </div>
</template>

<script>
export default {
    props: ['totalPage', 'currentPage', 'isMobile'],
	name: 'PaginationComponent',
	components: { },
	data: () => ({ }),
	computed: {
        getTotalPage: {
            get() {
                return this.totalPage
            },
            set (value) {
                this.$emit('update:totalPage', value)
            }
        },
        getCurrentPage: {
            get() {
                return this.currentPage
            },
            set (value) {
                this.$emit('update:currentPage', value)
            }
        },
    },
	methods: {
        async handlePageChange(page) {
            this.$emit('handlePageChange', page)
        }
	},
	mounted() { },
	updated() { }
}
</script>
