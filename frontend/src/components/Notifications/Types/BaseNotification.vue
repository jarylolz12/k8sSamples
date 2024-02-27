<template>
	<v-list-item-content>
		<v-list-item-title>
			<span style="color: #4a4a4a;" class="font-medium">{{ title }}</span>
		</v-list-item-title>
		<v-list-item-subtitle style="color: #6D858F;" class="font-regular">
			<span class="font-regular"> {{ subTitle }}</span>
		</v-list-item-subtitle>
	</v-list-item-content>
</template>
<script>
import { mapActions } from "vuex";

export default {
	name: "BaseNotification",
	data() {
		return {
			icon: "mdi-bell",
		};
	},
	props: {
		notification: {
			required: true,
		},
	},
	computed: {
		hasBeenRead() {
			return !!this.notification.read_at;
		},
		title() {
			return this.notification.type
				.split("\\")
				.pop()
				.replace(/([A-Z])/g, " $1");
		},
		subTitle() {
			return this.notification.data.message ?? "";
		},
	},
	methods: {
		...mapActions({
			markAsRead: "notifications/markAsRead",
		}),
		async markNotificationAsRead() {
			await this.markAsRead(this.notification);
		},
		async action() {
			if (!this.hasBeenRead) await this.markNotificationAsRead();
			if (typeof this.onOpen === "function") this.onOpen();
		},
		async goToRoute(route) {
			if (route === this.$router.currentRoute.fullPath) return false;
			await this.$router.push(route);
		},
		pushNotify() {
			const pushed = this.$store.getters[
				"notifications/getShouldPushNotify"
			].includes(this.notification.id);
			if (pushed)
				this.$store.commit(
					"notifications/REMOVE_FROM_SHOULD_NOTIFY",
					this.notification.id
				);
			if (Notification.permission === "granted" && pushed) {
				const desktopNotification = new Notification(this.title, {
					body: this.subTitle ?? "You have a new notification",
					icon: "/favicon-shifl.ico",
				});
				desktopNotification.onclick = this.action;
			}
		},
	},
	created() {
		this.pushNotify();
	},
};
</script>
<style scoped lang="scss"></style>
