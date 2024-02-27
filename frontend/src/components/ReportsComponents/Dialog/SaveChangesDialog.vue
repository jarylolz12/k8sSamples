<!-- @format -->

<template>
	<v-dialog
		v-model="dialog"
		max-width="500px"
		content-class="save-changes-dialog"
		persistent
	>
		<!-- @click:outside="close" -->
		
		<v-card class="save-changes-card">
			<v-card-title class="headline headline-border">
				<div class="delete-icon mt-3 mb-1">
					<img src="../../../assets/icons/icon-delete.svg" alt="" />
				</div>
			</v-card-title>

			<v-card-text class="pb-2 save-card-text">
				<h2>Discard Changes</h2>
				<p>
					<span
						>You have unsaved changes of report settings! Do you want to discard
						the changes?</span
					>
				</p>
			</v-card-text>

			<v-card-actions class="headline-border">
				<v-btn class="btn-blue" text @click="close">
					Discard Changes
				</v-btn>
				<v-btn class="btn-white" text @click="save" :disabled="errorEmail">
					Save Changes
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import globalMethods from "@/utils/globalMethods";

export default {
	name: "SaveChangesDialog",
	props: ["dialog", "editedItemData", "errorEmail"],
	data: () => ({
		reg: /^(([^<>()\]\\.,;:\s@"]+(\.[^<>()\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/,
		shipmentEmailValid: false,
		errorEmail1: "",
	}),
	computed: {
		...mapGetters({
			getUser: "getUser",
		}),
	},
	methods: {
		...mapActions({
			updateEmailReportSchedule: "updateEmailReportSchedule",
			fetchEmailReportSchedule: "fetchEmailReportSchedule",
		}),
		...globalMethods,
		close() {
			this.$emit("close");
		},
		async save() {
			this.errorEmail1 = "";
			this.shipmentEmailValid = true;
			for (let email of this.editedItemData.report_recipients) {
				if (!this.reg.test(email)) {
					this.shipmentEmailValid = false;
					break;
				}
			}

			if (this.editedItemData.report_recipients.length == 0) {
				this.errorEmail1 = "Please Enter Email";
				this.$emit("error-email", this.errorEmail1);
			} else if (!this.shipmentEmailValid) {
				this.errorEmail1 = "Please Enter Valid Email";
				this.$emit("error-email", this.errorEmail1);
			} else {
				this.errorEmail1 = "";
				await this.updateEmailReportSchedule(this.editedItemData);
				const userId =
					typeof this.getUser === "string"
						? JSON.parse(this.getUser).id
						: this.getUser.id;

				this.fetchEmailReportSchedule(userId);
				this.notificationMessageCustomSuccess("All changes are saved!");
				this.close();
			}
		},
	},
};
</script>

<style lang="scss">
@import '../../../assets/scss/pages_scss/report/report.scss';
@import '../../../assets/scss/pages_scss/dialog/globalDialog.scss';
</style>
