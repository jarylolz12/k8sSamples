<template>
	<v-list-item-content>
		<v-list-item-title>
			<span style="color: #4a4a4a;" class="font-medium">{{ title }}</span>
		</v-list-item-title>
		<v-list-item-subtitle style="color: #6D858F;" class="font-regular">
			<span class="font-regular"> {{ subTitle }}</span>
		</v-list-item-subtitle>
		<ImportEntityDialog
			ref="importProductEntityDialog"
			:importFn="importProducts"
			:import-errors="importErrors"
			:template-url="templateURL"
			:isOpen.sync="isOpen"
		/>
	</v-list-item-content>
</template>

<script>
import ImportEntityDialog from "@/components/Dialog/ImportEntityDialog";
import BaseNotification from "@/components/Notifications/Types/BaseNotification";
import { mapActions } from "vuex";

export default {
	extends: BaseNotification,
	name: "ImportProductsFailedNotification",
	components: { ImportEntityDialog },
	data() {
		return {
			importErrors: {},
			isOpen: false,
			templateURL: `${process.env.VUE_APP_PO_URL}/products/export-template`,
		};
	},
	created() {
		this.importErrors.file = this.notification.data?.file;
		this.importErrors.errors = this.notification.data.errors;
	},
	methods: {
		...mapActions({
			importProducts: "products/importProducts",
		}),
		onOpen() {
			this.isOpen = true;
		},
	},
};
</script>
<style scoped lang="scss"></style>
