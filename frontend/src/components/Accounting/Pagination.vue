<template>
	<!-- <v-sheet class="d-flex justify-md-end justify-lg-end justify-end mx-4">
		<h6 v-if="!isMobile" class="text-subtitle-1 mt-2 labelcolor--text">
			{{ (count || 0).toLocaleString() }} <span class="">{{ 'records' }}</span>
		</h6>
		<v-spacer v-if="!isMobile" />
		<div class="select-page-limit">
			<v-select
				v-model="limit"
				:items="[25, 50, 100]"
				label="Per page"
				hide-details
				dense
				flat
				solo
				@change="onChangePageLimit"
			></v-select>
		</div>
		<v-pagination
			v-model="page"
			:length="total"
			:total-visible="!isMobile && totalVisible ? totalVisible : 5"
			color="primary"
			previous-aria-label="Previous"
			next-aria-label="Next"
			@input="itemClicked"
		/>
	</v-sheet> -->

	<!-- should only show if there are more than 1 page -->
	 <div class="pagination-wrapper" v-if="total > 1"
	 	style="border: 1px solid #EBF2F5 !important; border-left: none; border-right: none;">
		<div v-if="!isMobile" class="">
			<div class="pagination-record-info">
				<span>Showing {{from}}-{{to}} of {{count}} records.</span>
			</div>
		</div>
        <v-pagination
            v-model="page"
            :length="total"
            prev-icon="Previous"
            next-icon="Next"
            :total-visible="!isMobile ? '11' : '5' "
            @input="itemClicked">
        </v-pagination>
    </div>
</template>
<script>
	export default {
		props: ['total', 'currentPage', 'totalVisible', 'pageLimit', 'count','from','to'],
		methods: {
			itemClicked(val) {
				this.$emit('pageSelected', val);
			},
			onChangePageLimit() {
				this.$emit('changeLimit', this.pageLimit);
			}
		},
		computed: {
			limit: {
				get() {
					return this.pageLimit;
				},
				set(value) {
					return this.$emit('update:pageLimit', value);
				}
			},
			page: {
				get() {
					return this.currentPage;
				},
				set(value) {
					this.$emit('pageSelected', value);
				}
			},
			isMobile() {
				return this.$vuetify.breakpoint.mobile;
			}
		}
	};
</script>
<style lang="scss" scoped>
	.pagination-record-info {
		display: flex-inline;
		align-items: center;
		font-size: 14px;
		color: #0171a1 !important;
	}
</style>
