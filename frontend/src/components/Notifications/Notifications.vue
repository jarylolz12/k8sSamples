<template>
	<v-menu
		:close-on-content-click="false"
		max-width="350"
		min-width="300"
		offset-y
		right
		eager>
		<template v-slot:activator="{ on, attrs }">
			<v-badge
				overlap
				dot
				color="red"
				:value="hasNew"
				:class="{
					'ml-3': $vuetify.breakpoint.width < 1024,
					'mr-3': !$vuetify.breakpoint.width < 1024,
				}"
			>
				<v-btn
					@click="setHasNew(false)"
					small
					icon
					:color="
						$vuetify.breakpoint.width < 1024 ? 'white' : 'shifl'
					"
					v-bind="attrs"
					v-on="on"
				>
					<v-icon size="20">{{
						`${hasNew ? "mdi-bell" : "mdi-bell-outline"}`
					}}</v-icon>
				</v-btn>
			</v-badge>
		</template>

		<v-card
			:loading="notificationsIsLoading"
			:disabled="notificationsIsLoading"
		>
			<template v-slot:progress>
				<v-progress-linear indeterminate></v-progress-linear>
			</template>
			<v-card-title class="notification-header px-4 py-3" style="font-size: 18px;">
				<span class="font-medium" style="color: #4a4a4a;">Notifications</span>
			</v-card-title>
			<v-divider style="border-color: #EBF2F5;"></v-divider>
			<v-list
				v-if="notifications.length"
				class="overflow-auto notification-lists-import pt-0 pb-0"
				max-height="300"
			>
				<Notification
					v-for="notification in notifications"
					:key="notification.id"
					:notification="notification"
				/>
			</v-list>
			<div v-else class="py-4 text-center" style="color: #4a4a4a;">
				You have no Notification
			</div>
			<v-divider style="border-color: #EBF2F5;"></v-divider>
			<v-card-actions>
				<v-btn
					small
					text
					:disabled="!hasUnreadNotifications"
					@click="markAllAsRead"
					style="letter-spacing: 0 !important;"
					color="#4a4a4a"
					>Mark all as read</v-btn
				>
			</v-card-actions>
		</v-card>
	</v-menu>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
// import BaseNotification from "@/components/Notifications/BaseNotification";
import Notification from "@/components/Notifications/Notification";

export default {
	name: "Notifications",
	components: {
		Notification,
		// BaseNotification
	},
	data() {
		return {};
	},
	mounted() {
		this.fetchNotifications();
	},
	computed: {
		...mapGetters({
			notifications: "notifications/getNotifications",
			hasNew: "notifications/getHasNew",
			notificationsIsLoading: "notifications/getNotificationsIsLoading",
			getUser: "getUser",
		}),
		hasUnreadNotifications() {
			return this.notifications.some((n) => n.read_at === null);
		},
	},
	methods: {
		...mapMutations({
			setHasNew: "notifications/SET_HAS_NEW",
		}),
		...mapActions({
			fetchNotifications: "notifications/fetchNotifications",
			markAllAsRead: "notifications/markAllAsRead",
		}),
		extractComponentName(string) {
			const componentName = string.split("\\").pop();
			return this.checkIfComponentIsAvailable(componentName)
				? componentName
				: "BaseNotification";
		},
		checkIfComponentIsAvailable(componentName) {
			return !!this.$options.components[componentName];
		},
	},
};
</script>
<style lang="scss">
@import "@/assets/scss/pages_scss/dialog/globalDialog.scss";

.notification-lists-import {
	&::-webkit-scrollbar {
		width: 6px;
	}

	/* Track */
	&::-webkit-scrollbar-track {
		background-color: #f1f1f1; 
	}

	/* Handle */
	&::-webkit-scrollbar-thumb {
		background-color: #e2e2e2;
	}

	/* Handle on hover */
	&::-webkit-scrollbar-thumb:hover {
		background-color: #e2e2e2;
	}
}
</style>
