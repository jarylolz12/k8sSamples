<!-- @format -->

<template>
	<div class="reports-wrapper" v-resize="onResize">
		<!-- <ReportsDesktop
			:frequencyItems="frequency"
			:dayItems="daysOfTheWeek"
			:day="daysInMonth"
			:report="reportByOption"
			v-if="!isMobile"
			@save-changes="save"
			:shipmentData="shipmentData"
			:errorEmails="errorEmails"
			@createReport="createReport"
		/> -->

		<ReportsDesktopTable
			:frequencyItems="frequency"
			:dayItems="daysOfTheWeek"
			:day="daysInMonth"
			:report="reportByOption"
			v-if="!isMobile"
			@save-changes="save"
			:shipmentData="shipmentData"
			:errorEmails="errorEmails"
			@createReport="createReport"
		/>		

		<ReportsMobile
			:frequencyItems="frequency"
			:dayItems="daysOfTheWeek"
			:day="daysInMonth"
			:report="reportByOption"
			v-if="isMobile"
			@save-changes="save"
			:shipmentData="shipmentData"
			:errorEmails="errorEmails"
			@createReport="createReport"
		/>

		<SaveChangesDialog
			:dialog="dialog"
			@close="close"
			:editedItemData.sync="editedItem"
			@error-email="errorEmail"
		/>
	</div>
</template>

<script>
import SaveChangesDialog from "../components/ReportsComponents/Dialog/SaveChangesDialog.vue";
// import ReportsDesktop from "../components/ReportsComponents/ReportsDesktop.vue";
import ReportsMobile from "../components/ReportsComponents/ReportsMobile.vue";
import "vue-select/src/scss/vue-select.scss";

import ReportsDesktopTable from "../components/ReportsComponents/ReportsDesktopTable.vue";
import { mapActions, mapGetters } from "vuex";

export default {
	name: "Reports",
	components: {
		SaveChangesDialog,
		// ReportsDesktop,
		ReportsMobile,
		ReportsDesktopTable
	},
	data: () => ({
		dialog: false,
		isMobile: false,
		editedItem: "",
		shipmentReportUpdate: false,
		shipmentReportCreate: false,
		to: null,
		shipmentData: {},
		errorEmails: "",
	}),
	beforeRouteLeave(to, from, next) {
		if (!this.shipmentReportUpdate && !this.shipmentReportCreate) {
			next();
		} else {
			this.to = to;
			this.dialog = true;
		}
	},
	created() {
		//set current page
		this.$store.dispatch("page/setPage", "reports");
		this.fetchDropdownValue();
	},
	async mounted() {
		const userId =
			typeof this.getUser === "string"
				? JSON.parse(this.getUser).id
				: this.getUser.id;
		await this.fetchEmailReportSchedule(userId).then(() => {
			let parms = {
				id: this.getEmailReports.id,
				active: this.getEmailReports.active,
				frequency: this.getEmailReports.frequency,
				report_recipients: this.getEmailReports.report_recipients,
				day: this.getEmailReports.day,
				time: this.getEmailReports.time,
				month_day: this.getEmailReports.month_day,
				report_type: this.getEmailReports.report_type,
			};
			Object.assign(this.shipmentData, parms);
		});
	},
	computed: {
		...mapGetters({
			getUser: "getUser",
			getDefaultValue: "getDefaultValue",
			getEmailReports: "getEmailReports",
		}),
		frequency() {
			const frequency = this.getDefaultValue.frequency;
			const frequencyObj = [];
			if (frequency != undefined || frequency != null) {
				for (var prop in frequency) {
					// frequencyObj.push({ id: prop, text: frequency[prop] }); - shows monthly and yearly

					// hide monthly and yearly for now
					if (prop !== 'MONTHLYON' && prop !== 'YEARLYON') {
						frequencyObj.push({ id: prop, text: frequency[prop] });
					}					
				}
			}
			return frequencyObj;
		},
		daysOfTheWeek() {
			const weekObj = [];
			const Week = this.getDefaultValue.days_of_the_week;
			if (Week != undefined || Week != null) {
				for (var prop in Week) {
					weekObj.push({ id: prop, text: Week[prop] });
				}
			}
			return weekObj;
		},
		daysInMonth() {
			const monthObj = [];
			const month = this.getDefaultValue?.days_in_month;
			if (month != undefined || month != null) {
				for (var prop in month) {
					monthObj.push({ id: prop, text: month[prop] });
				}
			}
			return monthObj;
		},
		reportByOption() {
			const reportObj = [];
			const reportBy = this.getDefaultValue.report_by_option;
			if (reportBy != undefined || reportBy != null) {
				for (var prop in reportBy) {
					reportObj.push({ id: prop, text: reportBy[prop] });
				}
			}
			return reportObj;
		},
	},
	methods: {
		...mapActions({
			fetchDropdownValue: "fetchDropdownValue",
			fetchEmailReportSchedule: "fetchEmailReportSchedule",
		}),
		onResize() {
			if (window.innerWidth < 769) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
		},
		close() {
			this.dialog = false;
			this.shipmentReportUpdate = false;
			this.shipmentReportCreate = false;
			this.$router.push(this.to);
		},
		saveChangeReports(item) {
			this.dialog = true;
			this.editedItem = Object.assign({}, item);
		},
		save(item, value) {
			if (value) {
				this.shipmentReportUpdate = true;
				this.editedItem = Object.assign({}, item);
			} else {
				this.shipmentReportUpdate = false;
			}
		},
		errorEmail(item) {
			this.errorEmails = item;
			this.dialog = false;
		},
		// for creating new report
		createReport(item, value) {
			if (value) {
				this.shipmentReportCreate = true;
				this.editedItem = Object.assign({}, item);
			} else {
				this.shipmentReportCreate = false;
			}
		},
	},
};
</script>

<style lang="scss">
@import '../assets/scss/pages_scss/report/report.scss';
@import '../assets/scss/pages_scss/dialog/globalDialog.scss';
</style>
