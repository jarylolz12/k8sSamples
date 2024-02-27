<!-- @format -->

<template>
	<v-list-item-content>
		<v-list-item-title>
			<span style="color: #4a4a4a;" class="font-medium">{{ title }}</span>
		</v-list-item-title>
		<v-list-item-subtitle style="color: #6D858F;" class="font-regular">
			<span class="font-regular"> {{ subTitle }}</span>
		</v-list-item-subtitle>
		<v-dialog v-model="dialog" width="500">
			<v-card>
				<v-card-title class="text-h5">
					File is ready!
				</v-card-title>
				<v-card-text class="pa-6"
					>For download file click
					<a :href="getLink" download>Click here</a>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="red darken-1" text @click="dialog = false">
						Close
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</v-list-item-content>
</template>

<script>
import BaseNotification from "@/components/Notifications/Types/BaseNotification";
import { mapActions } from "vuex";
// import axios from "axios";

export default {
	extends: BaseNotification,
	name: "ExportOrderListSuccessNotification",
	data() {
		return {
			item: {},
			isOpen: false,
			templateURL: `${process.env.VUE_APP_PO_URL}/products/export-template`,
			dialog: false,
		};
	},
	created() {},
	computed: {
		getLink() {
			return this.notification.data?.returnURL;
		},
	},
	methods: {
		...mapActions({
			importProducts: "products/importProducts",
		}),
		onOpen() {
			this.dialog = true;
		},
	},
};
</script>
<style scoped lang="scss"></style>
