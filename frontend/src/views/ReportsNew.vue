<template>
	<div class="reports-wrapper" v-resize="onResize">
        <div class="reports-wrapper-content">
            <div class="reports-header-content">
                <h2>Reports</h2>

                <button class="btn-blue" @click="openNewReport" v-if="!fetchReportsDataLoading">New Report</button>
            </div>

            <div v-if="!fetchReportsDataLoading && getReportsSchedule !== null">
                <div class="reports-body-content" v-if="!isMobile">
                    <!-- COMPANY REPORTS -->
                    <div class="report-content company-reports-content">
                        <div class="report-content-header">
                            <p class="font-semi-bold">Company Reports</p>
                        </div>

                        <div class="report-content-body">
                            <div class="report-content-body-items" 
                                v-for="(item, i) in currentCustomersCompanyReports" :key="i">
                                <ReportInfo 
                                    reportTitle="Shipment Report"
                                    :toggleData.sync="item.active"
                                    :frequency="findFrequency(item.frequency)"
                                    :daysOfTheWeek="findDaysOfTheWeek(item.day)"
                                    :daysInMonth="''"
                                    :month="''"
                                    :time="convertTime(item.time)"
                                    :type="findReportByOption(item.report_type)"
                                    :emails="item.report_recipients"
                                    :isCompanyReport="true"
                                    @editReport="editReport('company', item)"
                                    @sendReport="sendReportCompany(item)"
                                    @downloadReport="downloadReport(item)"
                                    @deleteReport="deleteReport(false, item)"
                                    @updateStatusReport="updateReport(item, i)"
                                    :isMobile="isMobile"
                                    :currentItem="item"
                                />
                            </div>

                            <div v-if="typeof currentCustomersCompanyReports !== 'undefined' && 
                                currentCustomersCompanyReports.length === 0" class="d-flex justify-center pt-8">
                                
                                <div class="empty-report-content text-center">
                                    <img src="@/assets/icons/reports-blue.svg" class="mb-1" width="40px" height="40px" />
                                        
                                    <div class="no-reports-heading">
                                        <p class="mb-0"> There are no company reports listed. </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PERSONALIZED REPORTS -->
                    <div class="report-content personalized-reports-content">
                        <div class="report-content-header">
                            <p class="font-semi-bold">Personalized Reports</p>
                        </div>

                        <div class="report-content-body">
                            <div class="report-content-body-items" 
                                v-for="(item, i) in currentCustomersPersonalizedReports" :key="i">
                                <ReportInfo 
                                    reportTitle="Shipment Report"
                                    :toggleData.sync="item.active"
                                    :frequency="findFrequency(item.frequency)"
                                    :daysOfTheWeek="findDaysOfTheWeek(item.day)"
                                    :daysInMonth="''"
                                    :month="''"
                                    :time="convertTime(item.time)"
                                    :type="findReportByOption(item.report_type)"
                                    :emails="item.report_recipients"
                                    :isCompanyReport="false"
                                    @editReport="editReport('personalized', item)"
                                    @sendReport="sendReportPersonalized(item)"
                                    @downloadReport="downloadReport(item)"
                                    @deleteReport="deleteReport(false, item)"
                                    @updateStatusReport="updateReport(item, i)"
                                    :isMobile="isMobile"
                                    :currentItem="item"
                                />
                            </div>

                            <div v-if="typeof currentCustomersPersonalizedReports !== 'undefined' && 
                                currentCustomersPersonalizedReports.length === 0" class="d-flex justify-center pt-8">
                                
                                <div class="empty-report-content text-center">
                                    <img src="@/assets/icons/reports-blue.svg" class="mb-1" width="40px" height="40px" />
                                        
                                    <div class="no-reports-heading">
                                        <p class="mb-0"> There are no personalized reports listed. </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="reports-body-content-mobile" v-if="isMobile">
                    <v-tabs @change="onTabChange" v-model="currentTab">
                        <v-tab v-for="(tab, index) in tabs" 
                            :key="index" 
                            class="font-semi-bold">
                            {{ tab }}
                        </v-tab>
                    </v-tabs>

                    <div class="report-content company-reports-content" v-if="currentTab === 0">
                        <div class="report-content-body">
                            <div class="report-content-body-items" 
                                v-for="(item, i) in currentCustomersCompanyReports" :key="i">
                                <ReportInfo 
                                    reportTitle="Shipment Report"
                                    :toggleData.sync="item.active"
                                    :frequency="findFrequency(item.frequency)"
                                    :daysOfTheWeek="findDaysOfTheWeek(item.day)"
                                    :daysInMonth="''"
                                    :month="''"
                                    :time="convertTime(item.time)"
                                    :type="findReportByOption(item.report_type)"
                                    :emails="item.report_recipients"
                                    :isCompanyReport="true"
                                    @editReport="editReport('company', item)"
                                    @sendReport="sendReportCompany(item)"
                                    @downloadReport="downloadReport(item)"
                                    @deleteReport="deleteReport(false, item)"
                                    @updateStatusReport="updateReport(item, i)"
                                    :isMobile="isMobile"
                                    :currentItem="item"
                                />
                            </div>

                            <div v-if="typeof currentCustomersCompanyReports !== 'undefined' && 
                                currentCustomersCompanyReports.length === 0" class="d-flex justify-center pt-8">
                                
                                <div class="empty-report-content text-center">
                                    <img src="@/assets/icons/reports-blue.svg" class="mb-1" width="40px" height="40px" />
                                        
                                    <div class="no-reports-heading">
                                        <p class="mb-0"> There are no company reports listed. </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="report-content personalized-reports-content" v-if="currentTab === 1">
                        <div class="report-content-body">
                            <div class="report-content-body-items" 
                                v-for="(item, i) in currentCustomersPersonalizedReports" :key="i">
                                <ReportInfo 
                                    reportTitle="Shipment Report"
                                    :toggleData.sync="item.active"
                                    :frequency="findFrequency(item.frequency)"
                                    :daysOfTheWeek="findDaysOfTheWeek(item.day)"
                                    :daysInMonth="''"
                                    :month="''"
                                    :time="convertTime(item.time)"
                                    :type="findReportByOption(item.report_type)"
                                    :emails="item.report_recipients"
                                    :isCompanyReport="false"
                                    @editReport="editReport('personalized', item)"
                                    @sendReport="sendReportPersonalized(item)"
                                    @downloadReport="downloadReport(item)"
                                    @deleteReport="deleteReport(false, item)"
                                    @updateStatusReport="updateReport(item, i)"
                                    :isMobile="isMobile"
                                    :currentItem="item"
                                />
                            </div>

                            <div v-if="typeof currentCustomersPersonalizedReports !== 'undefined' && 
                                currentCustomersPersonalizedReports.length === 0" class="d-flex justify-center pt-8">
                                
                                <div class="empty-report-content text-center">
                                    <img src="@/assets/icons/reports-blue.svg" class="mb-1" width="40px" height="40px" />
                                        
                                    <div class="no-reports-heading">
                                        <p class="mb-0"> There are no personalized reports listed. </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty UI for report -->
            <!-- <div class="empty-report-wrapper" v-if="isBothReportTypeEmpty && !fetchReportsDataLoading">
                <div class="empty-report-content">
                    <img src="@/assets/icons/reports-blue.svg" class="mb-3" width="40px" height="40px" />
						
                    <div class="no-reports-heading">
                        <h3>Create Report</h3>
                        <p>
                            There is no report to show. <br />
                            Let's create a new report.
                        </p>
                    </div>

                    <button class="btn-white" @click="openNewReport">New Report</button>
                </div>
            </div> -->

            <div class="preloader" v-if="fetchReportsDataLoading">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div> 
        </div>

        <NewReportDialog
            :dialogData.sync="openNewReportDialog" 
            @close="closeNewReport"
            :frequencyItems="frequency"
			:dayItems="daysOfTheWeek"
			:day="daysInMonth"
			:report="reportByOption"
            :isMobile="isMobile"
            :editedReportIndex.sync="editedReportIndex"
            :editedReportData.sync="editedReportData"
            :container_fields.sync="default_by_container_fields"
            :reference_fields.sync="default_by_reference_fields"
            @fetchReportsScheduleAPI="fetchReportsScheduleAPI"
            :typeReportData.sync="editedReportData.report"
            :shipmentTypeByData.sync="editedReportData.report_type"
            :defaultContainerFieldsCompare="container_fields_compare"
            :defaultReferenceFieldsCompare="reference_fields_compare" />

        <ConfirmDialog :dialogData.sync="newReportDeleteDialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Delete <span class="text-capitalize">{{ reportParentType }}</span> Report</h2>
			</template>

			<template v-slot:dialog_content>
				<p>Do you want to delete the 
                    <span class="font-semi-bold">'Shipment Report - Daily'</span> 
                    from the {{ reportParentType }} report list?
                </p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="deleteReport(true)" text :disabled="getDeleteReportScheduleLoading">
                    {{ getDeleteReportScheduleLoading ? 'Deleting...' : 'Delete' }}
				</v-btn>

				<v-btn class="btn-white" text @click="closeDeleteReport" :disabled="getDeleteReportScheduleLoading">
					Cancel
				</v-btn>
			</template>
		</ConfirmDialog>

        <!-- Send Report component -->
        <SendReportDialog 
            :dialogData.sync="sendReportDialog" 
            @close="closeReport"
            :editedReportData.sync="currentSelectedReport"
            :frequencyItems="frequency" />

        <ConfirmDialog :dialogData.sync="csvReportDialog">
            <template v-slot:dialog_icon>
				<div v-if="getEmailReportDownloadLoading && !csvReportButtonShowCondition" class="header-wrapper-close">
                    <img src="@/assets/icons/progress.svg" style="transition-duration: 0.8s;transition-property: transform;" alt="alert">
                </div>
                <div v-else class="header-wrapper-close">
                    <img src="@/assets/icons/report-generate-file.svg" alt="alert" v-if="!csvHasError">
                    <img src="@/assets/images/alert.svg" alt="alert" v-if="csvHasError">
                    <v-btn class="ma-0 pa-0" @click="closeDownloadReport" icon><v-icon color="#4A4A4A">mdi-close</v-icon></v-btn>
                </div>
			</template>

			<template v-slot:dialog_title> 
				<h2 v-if="getEmailReportDownloadLoading && !csvReportButtonShowCondition">Preparing File...</h2>
                <h2 v-else>
                    <span v-if="!csvHasError">File is ready!</span>
                    <span v-if="csvHasError">Download Failed</span>
                </h2>
			</template>

			<template v-slot:dialog_content>
				<p v-if="getEmailReportDownloadLoading && !csvReportButtonShowCondition"> 
					We are preparing your requested file. Please wait a few moment.
				</p>
                <p v-else>
                    <!-- We have prepared your requested file. Download will be started automatically in a moment. -->
                    <span v-if="!csvHasError">We have prepared your requested file. Download will be started automatically in a moment.</span>
                    <span v-if="csvHasError">Something's wrong in downloading your file. Please try again later.</span>
                </p>
			</template>

			<template v-slot:dialog_actions>
                <!-- <div v-if="getEmailReportDownloadLoading && !csvReportButtonShowCondition">
                    <v-btn style="color: #4A4A4A !important;"  class="btn-white" text @click="cancelDownloadReport">
					Cancel
				</v-btn>
                </div>	
                <div v-else>
                    <v-btn style="color: #4A4A4A !important;"  class="btn-white" text @click="csvReportDialog = false">
					Close
				</v-btn> -->

                <v-btn style="color: #4A4A4A !important;"  class="btn-white" text @click="closeDownloadReport">
                    Close
                </v-btn>
			</template>
		</ConfirmDialog>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import ReportInfo from '../components/ReportsComponents/ReportInfo.vue'
import NewReportDialog from '../components/ReportsComponents/Dialog/NewReportDialog.vue'
import SendReportDialog from '../components/ReportsComponents/Dialog/SendReportDialog.vue'
import ConfirmDialog from "../components/Dialog/GlobalDialog/ConfirmDialog.vue";
import jQuery from 'jquery'
import _ from 'lodash'
import inventoryGlobalMethods from '../utils/inventoryMethods/inventoryGlobalMethods'
import globalMethods from '../utils/globalMethods'
import moment from 'moment'

import axios from 'axios'
var cancel
var CancelToken = axios.CancelToken

export default {
	name: "ReportsNew",
	components: {
        ReportInfo,
        NewReportDialog,
        ConfirmDialog,
        SendReportDialog
	},
	data: () => ({
        isMobile: false,
        openNewReportDialog: false,
        fetchReportsDataLoading: false,
        // 
        reg: /^(([^<>()\]\\.,;:\s@"]+(\.[^<>()\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/,
        currentTab: 0,
        tabs: ['Company Reports', 'Personalized Reports'],
        editedReportIndex: -1,
        editedReportData: {
            customer_admin_id: null,
            frequency: '',
            time: '',
            currentTime: '',
            day: '',
            month_day: '',
            active: 1,
            report_type: '',
            report: null,
            report_recipients: [],
            report_columns: [],
            reportTypeSub: 'shipment'
        },
        defaultReportData: {
            customer_admin_id: null,
            frequency: '',
            time: '',
            currentTime: '',
            day: '',
            month_day: '',
            active: 1,
            report_type: '',
            report: null,
            report_recipients: [],
            report_columns: [],
            reportTypeSub: 'shipment'
        },
        newReportDeleteDialog: false,
        reportParentType: '',
        sendReportDialog: false,
        // emails
        options: [],
		valid: true,
        rules: [
            (v) => !!v || "Input is required."
        ],
        tagsInput: {
            touched: false,
            hasError: false,
            errorMessage: 'Please confirm the entered email address by pressing the "Enter" or "," key in your keyboard.'
        },
        reportsEmailAddress: '',
        documentProto: document,
        arrayNotEmptyRules: [
            (v) => !!v || "Email is required",
            () => this.optionsFiltered.length > 0 || "Make sure to supply at least 1 email." 
        ],
        tagsValidation: [{
            classes: 't-new-tag-input-text-error',
            rule: (/^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/),
            disableAdd: true
        }],
        // 
        currentSelectedReport: {
            customer_admin_id: null,
            frequency: '',
            time: '',
            currentTime: '',
            day: '',
            month_day: '',
            active: 1,
            report_type: '',
            report: null,
            report_recipients: [],
            report_columns: [],
        },
        company_reports: [],
        personalized_reports: [],
        personalizedReportsToggle: [],
        companyReportsToggle: [],
        combined_fields: [],
        csvReportDialog: false,
        csvReportButtonShowCondition: false,
        cancelerrorIs: false,
        csvHasError: false
	}),
	created() {
		//set current page
		this.$store.dispatch("page/setPage", "reports")
        this.fetchDropdownValue()
	},
	async mounted() {
        this.fetchReportsDataLoading = true
        
        try {
            await this.fetchReportsScheduleAPI()
            this.fetchReportsDataLoading = false
            await this.fetchReportColumns()
        } catch (e) {
            this.fetchReportsDataLoading = false
            this.notificationCustom(e)
        }
	},
    watch: {
        // tagsInput(value) {
		// 	if (value.hasError) {
		// 		jQuery(".ti-input").addClass("ti-new-tag-input-error")
		// 	} else {
		// 		jQuery(".ti-input").removeClass("ti-new-tag-input-error")
		// 	}
		// },
        // sendReportDialog(value, oldValue) {
		// 	this.tagsInput.hasError = false
		// 	this.tagsInput.touched = false

		// 	jQuery(".ti-input").removeClass("ti-new-tag-input-error")
		// 	if (typeof el !== "undefined") {
		// 		let el = document.getElementsByClassName("ti-input")[0]
		// 		el.classList.remove("ti-new-tag-input-error")
		// 	}

        //     if (!value) {
		// 		this.options = []
		// 		this.reportsEmailAddress = ""
		// 	} else if (value && !oldValue) {
		// 		let validEmailAddress = []
        //         if (this.currentSelectedReport.report_recipients !== null &&
        //             this.currentSelectedReport.report_recipients.length > 0) {
        //             this.currentSelectedReport.report_recipients.map((wea) => {
        //                 if (wea !== null) {
        //                     validEmailAddress.push({ text: wea, tiClasses: ["ti-valid"] })
        //                 }
        //             })
        //         }

        //         this.options = validEmailAddress
		// 	}
		// },
    },
	computed: {
		...mapGetters({
			getUser: "getUser",
			getDefaultValue: "getDefaultValue",
			getEmailReports: "getEmailReports",
            // new
            getReportsSchedule: "getReportsSchedule",
            getReportsScheduleLoading: "getReportsScheduleLoading",
            getReportColumns: "getReportColumns",
            getDeleteReportScheduleLoading: "getDeleteReportScheduleLoading",
            getSendReportScheduleLoading: "getSendReportScheduleLoading",
            getEmailReportDownloadLoading: 'getEmailReportDownloadLoading',
            getEmailReportDownloadData: 'getEmailReportDownloadData'
		}),
		frequency() {
			const frequency = this.getDefaultValue.frequency
			const frequencyObj = []
			if (frequency != undefined || frequency != null) {
				for (var prop in frequency) {
					// hide monthly and yearly for now
					if (prop !== 'MONTHLYON' && prop !== 'YEARLYON') {
						frequencyObj.push({ id: prop, text: frequency[prop] })
					}					
				}
			}
			return frequencyObj
		},
		daysOfTheWeek() {
			const weekObj = []
			const Week = this.getDefaultValue.days_of_the_week
			if (Week != undefined || Week != null) {
				for (var prop in Week) {
					weekObj.push({ id: parseInt(prop), text: Week[prop] })
				}
			}
			return weekObj
		},
		daysInMonth() {
			const monthObj = []
			const month = this.getDefaultValue?.days_in_month
			if (month != undefined || month != null) {
				for (var prop in month) {
					monthObj.push({ id: prop, text: month[prop] })
				}
			}
			return monthObj
		},
		reportByOption() {
			const reportObj = [];
			const reportBy = this.getDefaultValue.report_by_option;
			if (reportBy != undefined || reportBy != null) {
				for (var prop in reportBy) {
					if (prop !== 'BYREFERENCE') {
                        reportObj.push({ id: prop, text: reportBy[prop] });
                    }
				}
			}
			return reportObj;
		},
        default_by_container_fields() {
            const container_fields = []
			const container = this.getReportColumns !== null ? this.getReportColumns.by_container : null
			if (container !== undefined || container !== null) {
				for (var prop in container) {
					container_fields.push({ 
                        id: parseInt(prop), 
                        text: container[prop],
                        isShow: true,
                        isChecked: true,
                        default: true,
                        order: parseInt(prop),
                        disabled: container[prop] === 'Shifl Ref#' ? true : false,
                    })
				}
			}
			return container_fields
        },
        default_by_reference_fields() {
            const reference_fields = []
			const ref = this.getReportColumns !== null ? this.getReportColumns.by_reference : null
			if (ref !== undefined || ref !== null) {
				for (var prop in ref) {
					reference_fields.push({ 
                        id: parseInt(prop), 
                        text: ref[prop],
                        isShow: true,
                        isChecked: true,
                        default: true,
                        order: parseInt(prop),
                    })
				}
			}
			return reference_fields
        },
        optionsFiltered: {
			get() {
				let validEmailAddress = []

                if (this.currentSelectedReport !== null && 
                    typeof this.currentSelectedReport.report_recipients !== 'undefined' &&
                    this.currentSelectedReport.report_recipients.length !== 'undefined' &&
                    this.currentSelectedReport.report_recipients.length > 0) {
                        
                    this.currentSelectedReport.report_recipients.map((wea) => {
                        if (wea) {
                            validEmailAddress.push({ text: wea, tiClasses: ["ti-valid"] })
                        }
                    })
                }

				return validEmailAddress
			},
			set(value) {
				this.options = value
			},
		},
        isBothReportTypeEmpty() {
            let isEmpty = false

            if (this.currentCustomersCompanyReports !== 'undefined' && 
                this.currentCustomersCompanyReports.length === 0 && 
                this.currentCustomersPersonalizedReports !== 'undefined' && 
                this.currentCustomersPersonalizedReports.length === 0) {
                isEmpty = true
            }

            return isEmpty
        },
        container_fields_compare() {
            const container_fields = []
			const container = this.getReportColumns !== null ? this.getReportColumns.by_container : null
			if (container !== undefined || container !== null) {
				for (var prop in container) {
					container_fields.push({ 
                        id: parseInt(prop), 
                        text: container[prop],
                        isShow: true,
                        isChecked: true,
                        default: true,
                        order: parseInt(prop),
                        disabled: container[prop] === 'Shifl Ref#' ? true : false,
                    })
				}
			}
			return container_fields
        },
        reference_fields_compare() {
            const reference_fields = []
			const ref = this.getReportColumns !== null ? this.getReportColumns.by_reference : null
			if (ref !== undefined || ref !== null) {
				for (var prop in ref) {
					reference_fields.push({ 
                        id: parseInt(prop), 
                        text: ref[prop],
                        isShow: true,
                        isChecked: true,
                        default: true,
                        order: parseInt(prop),
                    })
				}
			}
			return reference_fields
        },
        // filter only company reports of current selected customer
        currentCustomersCompanyReports() {
            let reports = []

            if (typeof this.getReportsSchedule !== 'undefined' && this.getReportsSchedule !== null && 
                typeof this.getReportsSchedule.company_schedules !== 'undefined' && 
                this.getReportsSchedule.company_schedules !== null) {
                
                const current_selected_customer_id = typeof this.getUser === "string"
                    ? JSON.parse(this.getUser).default_customer_id
                    : this.getUser.default_customer_id

                reports = this.getReportsSchedule.company_schedules.filter(v => {
                    return v.selected_customer === current_selected_customer_id
                })
            }

            return reports
        },
        // filter only personalized reports of current selected customer
        currentCustomersPersonalizedReports() {
            let reports = []

            if (typeof this.getReportsSchedule !== 'undefined' && this.getReportsSchedule !== null && 
                typeof this.getReportsSchedule.personalized_schedules !== 'undefined' && 
                this.getReportsSchedule.personalized_schedules !== null) {
                
                const current_selected_customer_id = typeof this.getUser === "string"
                    ? JSON.parse(this.getUser).default_customer_id
                    : this.getUser.default_customer_id

                reports = this.getReportsSchedule.personalized_schedules.filter(v => {
                    return v.selected_customer === current_selected_customer_id
                })
            }

            return reports
        }
	},
	methods: {
		...mapActions({
			fetchDropdownValue: "fetchDropdownValue",
			fetchEmailReportSchedule: "fetchEmailReportSchedule",
            // new API
            fetchReportsSchedule: "fetchReportsSchedule",
            downloadReportSchedule: "downloadReportSchedule",
            fetchReportColumns: "fetchReportColumns",
            deleteReportScehdule: "deleteReportScehdule",
            sendReportSchedule: "sendReportSchedule",
            updateReportStatus: "updateReportStatus"
		}),
        ...globalMethods,
		...inventoryGlobalMethods,
        onTabChange(i) {
            this.currentTab = i
        },
        onResize() {
			if (window.innerWidth < 769) {
				this.isMobile = true
			} else {
				this.isMobile = false
			}
		},
        async fetchReportsScheduleAPI() {
            const userId = typeof this.getUser === "string"
                ? JSON.parse(this.getUser).id
                : this.getUser.id

            await this.fetchReportsSchedule(userId)
        },
        openNewReport() {
            const userId =
			typeof this.getUser === "string"
				? JSON.parse(this.getUser).id
				: this.getUser.id

            this.editedReportData.customer_admin_id = userId
            this.openNewReportDialog = true
        },
        closeNewReport() {
            this.openNewReportDialog = false
            this.$nextTick(() => {
				this.editedReportData = Object.assign({}, this.defaultReportData);
				this.editedReportIndex = -1;
	  		});

            this.combined_fields = []
            this.default_by_container_fields.map(v => {
                v.isChecked = true
                v.isShow = true
                v.default = true
            })
            this.default_by_reference_fields.map(v => {
                v.isChecked = true
                v.isShow = true
                v.default = true
            })
        },
        editReport(type, item) {
            this.editedReportIndex = 0
            item.report_columns_copy = []

            item.report = item.report === null ? 2 : item.report
            item.reportTypeSub = 'shipment'  

            if (type === 'personalized') {
                const current_user_email = typeof this.getUser === "string"
                        ? JSON.parse(this.getUser).email : this.getUser.email  
                
                // this.editedReportIndex = this.getReportsSchedule.personalized_schedules.indexOf(item)
                this.editedReportIndex = this.currentCustomersPersonalizedReports.indexOf(item)                
                
                if (item.report_recipients.length === 0) { 
                    // if reports recipient is empty, add customer admin email instead
                    item.report_recipients.push(current_user_email)
                }
            } else {
                // this.editedReportIndex = this.getReportsSchedule.company_schedules.indexOf(item)
                this.editedReportIndex = this.currentCustomersCompanyReports.indexOf(item)
            }
                    
            const report_columns = typeof item.report_columns === "string"
                ? JSON.parse(item.report_columns) : item.report_columns

            let fields = []
            let missing_fields = []

            if (report_columns !== undefined || report_columns !== null) {
                for (var prop in report_columns) {
                    fields.push({ 
                        id: parseInt(prop), 
                        text: report_columns[prop],
                        isShow: true,
                        isChecked: true,
                        default: true,
                        order: parseInt(prop),
                    })
                }
            }

            if (item.report_type === 'BYCONTAINER') {
                missing_fields = this.default_by_container_fields.filter(e => !fields.some(o => o.text == e.text))
            } else {
                missing_fields = this.default_by_reference_fields.filter(e => !fields.some(o => o.text == e.text))
            }

            // set the missing fields checked to false, as they are not selected by the user
            missing_fields.map(v => {
                v.isChecked = false
                v.default = false
                v.isShow = false
            })

            this.combined_fields = [ ...fields, ...missing_fields ]
            this.combined_fields = this.combined_fields.map((v, i) => {
                return {
                    id: parseInt(i),
                    text: v.text,
                    isShow: v.isShow,
                    isChecked: v.isChecked,
                    default: v.default,
                    order: parseInt(i),
                }
            })

            item.report_columns_copy = this.combined_fields            
            this.editedReportData = Object.assign({}, item)            
            this.openNewReportDialog = true
        },
        // showing download popup when downloading file
        async downloadReport(item) {
            let date = moment().format('YYYY_MM_DD')
            
            if (item !== '') {
                try {
                    this.csvReportButtonShowCondition = false
                    this.csvReportDialog = true

                    let payload = {
                        id: item.id,
                        date,
                        report_type: item.report_type,
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                    }

                    await this.downloadReportSchedule(payload)

                    if (typeof this.getEmailReportDownloadData !== 'undefined' && this.getEmailReportDownloadData !== undefined && 
                        this.getEmailReportDownloadData !== null) {
                        var url = window.URL.createObjectURL(new Blob([this.getEmailReportDownloadData]));
                        let current_report_type = payload.report_type === 'BYCONTAINER' ? 'By_Container' : 'By_Reference'
                        let name = `Shifl Shipment Report_${current_report_type}_${payload.date}.xlsx`
                        this.downloadExcelFileReport(`${name}`, url)
                    }

                    this.csvReportButtonShowCondition = true
                    this.csvHasError = false
                } catch(e) {
                    this.csvReportButtonShowCondition = false
                    this.csvHasError = true
                    if (this.cancelerrorIs) return this.cancelerrorIs = false
                    // this.notificationError(e)
                    this.notificationError("Something's wrong in downloading your file. Please try again later.")
                }                
            }
        },
        downloadExcelFileReport(fileName, urlData) {
            if (typeof this.getEmailReportDownloadData !=='undefined' && this.getEmailReportDownloadData !== undefined && 
                this.getEmailReportDownloadData !== null) {
                var aLink = document.createElement('a');
                aLink.download = fileName;
                aLink.href = urlData;
                document.body.appendChild(aLink);
                aLink.click();
                aLink.remove()
            }
        },
        closeDownloadReport() {
            this.csvReportDialog = false
            this.csvHasError = false
        },
        cancelDownloadReport() {
            this.csvReportDialog = false
            this.csvReportButtonShowCondition = false
            if (cancel !== undefined && this.getEmailReportDownloadLoading) {
                this.cancelerrorIs = true
			    cancel()
		    }
        },
        async deleteReport(isConfirm, item) {
            this.newReportDeleteDialog = true

            if (!isConfirm) {                
                this.currentSelectedReport = item
                this.reportParentType = item.report !== null && item.report === 1 ? 'company' : 'personalized'
            } else {
                if (this.currentSelectedReport !== null) {
                    await this.deleteReportScehdule(this.currentSelectedReport.id)
                    this.notificationCustom('Report has been deleted.')
                    await this.fetchReportsScheduleAPI()
                    this.closeDeleteReport()
                }
            }
        },
        closeDeleteReport() {
            this.newReportDeleteDialog = false
            this.currentSelectedReport = null
            this.reportParentType = ''
        },
        async sendReportCompany(item) {
            this.sendReportDialog = true
            this.currentSelectedReport = Object.assign({}, item)
        },
        async sendReportPersonalized(item) {
            let finalEmailAddress = this.options.length > 0 ? this.options.map((o) => { return o.text }) : []
            item.isSending = true
                
            if (!this.tagsInput.hasError) { 
                if (this.reportsEmailAddress === '') {
                    try {
                        jQuery('.ti-new-tag-input').trigger(
                            jQuery.Event( 'keyup', { keyCode: 13, which: 13 } )
                        )
                        let payload = { id: item.id }
                        await this.sendReportSchedule(payload)
                        item.isSending = false
                        this.notificationMessage('Shipment Report has been sent.')
                        this.closeReport()
                    } catch(e) {
                        item.isSending = false
                        this.notificationError(e)
                        console.log(e)
                    }
                } else {
                    if (this.reg.test(this.reportsEmailAddress)) {
                        try {
                            jQuery('.ti-new-tag-input').trigger(
                                jQuery.Event( 'keyup', { keyCode: 13, which: 13 } )
                            )

                            finalEmailAddress.push(this.reportsEmailAddress)
                            let payload = { id: item.id }
                            await this.sendReportSchedule(payload)
                            item.isSending = false
                            this.notificationMessage('Shipment Report has been sent.')
                            this.closeReport()
                        } catch(e) {
                            item.isSending = false
                            this.notificationError(e)
                            console.log(e)
                        }
                    }
                }
            }
        },
        closeReport() {
            this.sendReportDialog = false
            this.$nextTick(() => {
				this.currentSelectedReport = Object.assign({}, this.defaultReportData)
	  		})
        },
        async updateReport(item) {
            if (item !== null) {
                let payload = { id: item.id, active: item.active }

                try {
                    await this.updateReportStatus(payload)
                    await this.fetchReportsScheduleAPI()
                    this.notificationCustom('Report status has been updated.')
                } catch (e) {
                    this.notificationCustom(e)
                }
            }
        },
        // generateErrorMessage() {
        //     this.tagsInput.hasError = (this.options.length > 0) ? false : true
        //     if (this.tagsInput.hasError)
        //         jQuery('.ti-input').addClass('ti-new-tag-input-error')
        //     else
        //         jQuery('.ti-input').removeClass('ti-new-tag-input-error')
        // },
        // clearErrors() {
        //     this.tagsInput.hasError = false
        //     this.tagsInput.touched = false

        //     jQuery('.ti-input').removeClass('ti-new-tag-input-error')
        //     if (typeof el!=='undefined') {
        //         let el = document.getElementsByClassName('ti-input')[0]
        //         el.classList.remove('ti-new-tag-input-error')
        //     }
        // },
        convertTime(time) {
            return this.formatTimeOnly(time)
        },
        // find data to display methods
        findReportByOption(option) {
            if (option !== null) {
                let findOption = _.find(this.reportByOption, (e) => (e.id === option))

                if (findOption !== undefined) { 
                    return findOption.text 
                } else { 
                    if (option === 'BYREFERENCE') {
                        return 'Shipment by Shifl Reference Number'
                    } else {
                        return '' 
                    }
                }
            }
        },
        findFrequency(freq) {
            if (freq !== null) {
                let findOption = _.find(this.frequency, (e) => (e.id === freq))

                if (findOption !== undefined) { return findOption.text } 
                else { return '' }
            }
        },
        findDaysOfTheWeek(day) {
            if (day !== null) {
                let findOption = _.find(this.daysOfTheWeek, (e) => (e.id === day))

                if (findOption !== undefined) { return findOption.text } 
                else { return '' }
            }
        },
	},
    updated() {}
}
</script>

<style lang="scss">
@import '../assets/scss/pages_scss/report/reportNew.scss';
@import '../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../assets/scss/buttons.scss';
</style>
