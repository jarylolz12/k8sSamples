<!-- @format -->

<template>
	<div>
		<v-list-item
			class="d-block"
			@click="clickedOnNotification"
			:class="{
				'lighten-3 font-weight-bold bg-blue-overlay': !notification.read_at,
			}"
		>
			<component
				ref="component"
				:is="componentType"
				:notification="notification"
			/>
			<div
				class="text-right text-caption font-regular"
				style="
					color: #6d858f !important; 
					font-family: 'Inter-Regular', sans-serif !important; 
					letter-spacing: 0 !important;"
			>
				{{ notification.created_at | humanize }}
			</div>
		</v-list-item>
		<v-divider style="border-color: #EBF2F5;"></v-divider>
	</div>
</template>

<script>
import BaseNotification from "@/components/Notifications/Types/BaseNotification";
import ImportProductsFailedNotification from "@/components/Notifications/Types/ImportProductsFailedNotification";
import ImportProductsSuccessNotification from "@/components/Notifications/Types/ImportProductsSuccessNotification";
import ImportPurchaseOrdersFailedNotification from "@/components/Notifications/Types/ImportPurchaseOrdersFailedNotification";
import ImportPurchaseOrdersSuccessNotification from "@/components/Notifications/Types/ImportPurchaseOrdersSuccessNotification";
import ExportOrderListSuccessNotification from "@/components/Notifications/Types/ExportOrderListSuccessNotification";
export default {
	components: {
		BaseNotification,
		ImportProductsFailedNotification,
		ImportProductsSuccessNotification,
		ImportPurchaseOrdersFailedNotification,
		ImportPurchaseOrdersSuccessNotification,
		ExportOrderListSuccessNotification,
	},
	name: "Notification",
	props: {
		notification: {
			required: true,
		},
	},
	computed: {
		componentType() {
			const componentName = this.notification.type.split("\\").pop();
			return this.componentIsAvailable(componentName)
				? componentName
				: "BaseNotification";
		},
	},
	methods: {
		componentIsAvailable(componentName) {
			return !!this.$options.components[componentName];
		},
		clickedOnNotification() {
			if (typeof this.$refs.component.action === "function")
				this.$refs.component.action();
		},
	},
};
</script>

<style scoped>
.bg-blue-overlay {
	background-color: #f0fbff !important;
}
</style>
