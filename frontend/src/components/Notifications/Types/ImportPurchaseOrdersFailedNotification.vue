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
			:importFn="importPOs"
			:import-errors="importErrors"
			templateText="You can import PO by uploading Excel or CSV file. To ensure accurate import, download and use this template:"
			:template-url="templateURL"
			title="Upload PO"
			:isOpen.sync="isOpen"
		/>
	</v-list-item-content>
</template>
<script>
import ImportEntityDialog from "@/components/Dialog/ImportEntityDialog";
import { mapActions } from "vuex";
import BaseNotification from "@/components/Notifications/Types/BaseNotification";

export default {
	name: "ImportPurchaseOrdersFailedNotification",
	extends: BaseNotification,
	components: { ImportEntityDialog },
	data() {
		return {
			importErrors: {},
			isOpen: false,
			templateURL: `${process.env.VUE_APP_PO_URL}/orders/export-template`,
		};
	},
	created() {
		this.importErrors.file = this.notification.data?.file;
		this.importErrors.errors = this.notification.data.errors;
	},
	methods: {
		...mapActions({
			importPOs: "po/importPOs",
		}),
		onOpen() {
			this.isOpen = true;
		},
	},
};
</script>

<style scoped></style>
