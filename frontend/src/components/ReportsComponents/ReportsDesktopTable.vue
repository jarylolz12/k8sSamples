<template>
	<div class="reports-content-wrapper">
		<v-form ref="form" v-model="valid" action="#" @submit.prevent="">
			<div class="reports-header">
				<h2>Reports</h2>
				<v-btn
					text
					class="btn-blue"
					@click="saveChanges"
					:disabled="getUpdateEmailReportsLoading"
					v-if="!getEmailReportsLoading && typeof getEmailReports !== 'undefined' && 
						getEmailReports !== null && getEmailReports.length !== 0 && !isCreatingReport">

					{{ getUpdateEmailReportsLoading ? 'Saving Changes...' : 'Save Changes' }}
				</v-btn>

				<v-btn
					text
					class="btn-blue"
					@click="addChanges"
					:disabled="getCreateEmailReportsLoading"
					v-if="!getEmailReportsLoading && typeof getEmailReports !== 'undefined' && 
						getEmailReports !== null && getEmailReports.length === 0 && isCreatingReport">

					{{ getCreateEmailReportsLoading ? 'Saving Changes...' : 'Save Changes' }}
				</v-btn>
			</div>

			<div class="reports-body">
				<div class="report-box">
					<div class="loading-wrapper" v-if="getEmailReportsLoading">
						<v-progress-circular
							:size="40"
							color="#0171a1"
							indeterminate>
						</v-progress-circular>
					</div>

					<!-- If there are no reports and still needs to create a report -->
					<div v-if="!getEmailReportsLoading && typeof getEmailReports !== 'undefined' && 
						getEmailReports !== null && getEmailReports.length === 0 && !isCreatingReport"
						class="no-reports-wrapper">

						<img src="@/assets/icons/reports-blue.svg" class="mb-3" width="40px" height="40px" />
						
						<div class="no-reports-heading">
							<h3>Create Report</h3>
							<p>
								There is no report to show. <br />
								Let's create a new report.
							</p>
						</div>

						<button class="btn-white" @click="showAddShipmentReport">Add Shipment Report</button>
					</div>

					<!-- Has reports and can be updated (enable or disable) -->
					<div v-if="!getEmailReportsLoading && typeof getEmailReports !== 'undefined' && 
						getEmailReports !== null && getEmailReports.length !== 0 && !isCreatingReport">

						<div class="box-header">
							<div class="header-title">
								<p class="report-title">Shipment Report</p>

								<v-radio-group v-model="shipmentReports" row>
									<v-radio label="Enable" color="primary" :value="1"></v-radio>
									<v-radio label="Disable" color="primary" :value="0"></v-radio>
								</v-radio-group>
							</div>

							<!-- <div class="header-button">
								<v-btn color="primary" class="download-button" @click="download">
									Download Report Now
								</v-btn>
							</div> -->
						</div>

						<div class="box-body">
							<v-row>
								<v-col cols="12" sm="12" md="7" class="pb-0">
									<v-row>
										<v-col cols="12" sm="12" md="4" class="frequency pb-2">
											<label class="text-item-label">Frequency</label>
											<v-select
												class="text-fields normal-vselect"
												placeholder="Select Frequency"
												item-text="text"
												item-value="id"
												:items="frequencyItems"
												outlined
												v-model="shipmentFrequency"
												:disabled="shipmentReports == 0"
												:menu-props="{ bottom: true, offsetY: true }">
											</v-select>
										</v-col>

										<v-col cols="12" sm="12" md="4" v-if="shipmentFrequency == 'WEEKLYON'" class="pb-2">
											<label class="text-item-label">Day</label>
											<v-select
												class="text-fields normal-vselect"
												placeholder="Select Day"
												:items="dayItems"
												outlined
												item-text="text"
												item-value="id"
												v-model="shipmentDay"
												:disabled="shipmentReports == 0"
												:menu-props="{ bottom: true, offsetY: true }">
											</v-select>
										</v-col>

										<v-col cols="12" sm="12" md="4" v-if="shipmentFrequency == 'MONTHLYON'" class="pb-2">
											<label class="text-item-label">Day</label>
											<v-select
												class="text-fields normal-vselect"
												placeholder="Select Day"
												:items="day"
												outlined
												item-text="text"
												item-value="id"
												v-model="shipmentDay"
												:disabled="shipmentReports == 0"
												:menu-props="{ bottom: true, offsetY: true }">
											</v-select>
										</v-col>

										<v-col cols="12" sm="12" md="4" v-if="shipmentFrequency === 'YEARLYON'" class="pb-2">
											<label class="text-item-label">Date</label>
											<DatePicker
												:date.sync="shipmentDate"
												:dateFormat="DATE_FORMAT_WITH_MONTH_NAME"
												placeholder="Select Date"
												:disabledDate="shipmentReports == 0"
											/>
										</v-col>

										<v-col cols="12" sm="12" md="4" class="pb-2">
											<label class="text-item-label">Time</label>
											<v-text-field
												height="48px"
												class="text-fields input-time"
												placeholder="12:30:00"
												v-model="shipmentTime"
												type="time"
												outlined
												hide-details="auto"
												hint="FORMAT: 12:00 AM"
												:disabled="shipmentReports == 0">
											</v-text-field>
										</v-col>

										<v-col cols="12" sm="12" class="pl-3 pt-0 d-flex align-center"
											style="color: #819FB2; font-size: 14px;"
											v-if="shipmentDay === '31' && shipmentFrequency === 'MONTHLYON'">
											<img src="../../assets/icons/info.svg" width="16px" height="16px" />
											<span class="ml-1">
												For months with less than 31 days, the report will be sent on the last day of the month.
											</span>
										</v-col>
									</v-row>
									
									<v-row>
										<v-col cols="12" sm="12" md="4" class="pt-0 pb-2">
											<label class="text-item-label">Type</label>
											<v-select
												class="text-fields normal-vselect"
												placeholder="Select Type"
												:items="report"
												outlined
												item-text="text"
												item-value="id"
												v-model="reportBy"
												:disabled="shipmentReports == 0"
												:menu-props="{ bottom: true, offsetY: true }">
											</v-select>
										</v-col>
									</v-row>
								</v-col>
							</v-row>

							<v-row>
								<v-col cols="12" sm="12" md="12">
									<label class="text-item-label">Recipients</label>
                                    <vue-tags-input
                                        hide-details="auto"
                                        :rules="arrayNotEmptyRules"
                                        :tags="options"
                                        :add-on-blur=true
                                        :add-on-key="[13, ',']"
                                        :validation="tagsValidation"
                                        v-model="reportsEmailAddress"
                                        :placeholder="options.length > 0 ? '' : 'Enter emails'"
                                        :disabled="shipmentReports == 0"
                                        @tags-changed="newTags => {
                                            this.options = newTags
                                            this.tagsInput.touched = true
                                            this.tagsInput.hasError = (this.options.length > 0) ? false : true
                                            let el = this.documentProto.getElementsByClassName('ti-input')[0]
                                            if (typeof el!=='udnefined') {
                                                if (this.tagsInput.hasError)
                                                    el.classList.add('ti-new-tag-input-error')
                                                else
                                                    el.classList.remove('ti-new-tag-input-error')
                                            }
                                        }"
                                    />

									<p class="mb-1" style="color: #819FB2; font-size: 12px; margin-top: 5px;">
										Separate multiple email addresses with comma
									</p>

                                    <div v-if="tagsInput.touched" class="v-text-field__details">
                                        <div class="v-messages theme--light error--text" role="alert">
                                            <div class="v-messages__wrapper">
                                                <div v-if="(options.length > 0) && reportsEmailAddress!==''" class="v-messages__message">
                                                    {{ tagsInput.errorMessage }}
                                                </div>

                                                <div v-if="options.length == 0 && reportsEmailAddress!==''" class="v-messages__message">
                                                    {{ tagsInput.errorMessage }}
                                                </div>
                                                <div v-if="options.length == 0 && reportsEmailAddress==''" class="v-messages__message">
                                                    Please provide at least 1 valid email address.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								</v-col>
							</v-row>
						</div>
					</div>

					<!-- For creating a report (one time only) -->
					<div v-if="!getEmailReportsLoading && typeof getEmailReports !== 'undefined' && 
						getEmailReports !== null && getEmailReports.length === 0 && isCreatingReport">

						<div class="box-header">
							<div class="header-title">
								<p class="report-title">Shipment Report</p>

								<!-- <v-radio-group v-model="shipmentReports" row>
									<v-radio label="Enable" color="primary" :value="1"></v-radio>
									<v-radio label="Disable" color="primary" :value="0"></v-radio>
								</v-radio-group> -->
							</div>

							<!-- <div class="header-button">
								<v-btn color="primary" class="download-button" @click="download">
									Download Report Now
								</v-btn>
							</div> -->
						</div>

						<div class="box-body">
							<v-row>
								<v-col cols="12" sm="12" md="7" class="pb-0">
									<v-row>
										<v-col cols="12" sm="12" md="4" class="frequency pb-2">
											<label class="text-item-label">Frequency</label>
											<v-select
												class="text-fields normal-vselect"
												placeholder="Select Frequency"
												item-text="text"
												item-value="id"
												:items="frequencyItems"
												outlined
												v-model="shipmentFrequency"
												:disabled="shipmentReports == 0"
												:menu-props="{ bottom: true, offsetY: true }"
												:rules="rules">
											</v-select>
										</v-col>

										<v-col cols="12" sm="12" md="4" v-if="shipmentFrequency == 'WEEKLYON'" class="pb-2">
											<label class="text-item-label">Day</label>
											<v-select
												class="text-fields normal-vselect"
												placeholder="Select Day"
												:items="dayItems"
												outlined
												item-text="text"
												item-value="id"
												v-model="shipmentDay"
												:disabled="shipmentReports == 0"
												:menu-props="{ bottom: true, offsetY: true }"
												:rules="rules">
											</v-select>
										</v-col>

										<v-col cols="12" sm="12" md="4" v-if="shipmentFrequency == 'MONTHLYON'" class="pb-2">
											<label class="text-item-label">Day</label>
											<v-select
												class="text-fields normal-vselect"
												placeholder="Select Day"
												:items="day"
												outlined
												item-text="text"
												item-value="id"
												v-model="shipmentDay"
												:disabled="shipmentReports == 0"
												:menu-props="{ bottom: true, offsetY: true }"
												:rules="rules">
											</v-select>
										</v-col>

										<v-col cols="12" sm="12" md="4" v-if="shipmentFrequency === 'YEARLYON'" class="pb-2">
											<label class="text-item-label">Date</label>
											<DatePicker
												:date.sync="shipmentDate"
												:dateFormat="DATE_FORMAT_WITH_MONTH_NAME"
												placeholder="Select Date"
												:disabledDate="shipmentReports == 0"
											/>
										</v-col>

										<v-col cols="12" sm="12" md="4">
											<label class="text-item-label">Time</label>
											<v-text-field
												height="48px"
												class="text-fields input-time"
												placeholder="12:30:00"
												v-model="shipmentTime"
												type="time"
												outlined
												hide-details="auto"
												hint="FORMAT: 12:00 AM"
												:disabled="shipmentReports == 0"
												:rules="rules">
											</v-text-field>
										</v-col>

										<v-col cols="12" sm="12" class="pl-3 pt-0 d-flex align-center"
											style="color: #819FB2; font-size: 14px;"
											v-if="shipmentDay === '31' && shipmentFrequency === 'MONTHLYON'">
											<img src="../../assets/icons/info.svg" width="16px" height="16px" />
											<span class="ml-1">
												For months with less than 31 days, the report will be sent on the last day of the month.
											</span>
										</v-col>
									</v-row>
									
									<v-row>
										<v-col cols="12" sm="12" md="4" class="pt-0">
											<label class="text-item-label">Type</label>
											<v-select
												class="text-fields normal-vselect"
												placeholder="Select Type"
												:items="report"
												outlined
												item-text="text"
												item-value="id"
												v-model="reportBy"
												:disabled="shipmentReports == 0"
												:menu-props="{ bottom: true, offsetY: true }"
												:rules="rules">
											</v-select>
										</v-col>
									</v-row>
								</v-col>
							</v-row>

							<v-row>
								<v-col cols="12" sm="12" md="12">
									<label class="text-item-label">Recipients</label>
                                    <vue-tags-input
                                        hide-details="auto"
                                        :rules="arrayNotEmptyRules"
                                        :tags="options"
                                        :add-on-blur=true
                                        :add-on-key="[13, ',']"
                                        :validation="tagsValidation"
                                        v-model="reportsEmailAddress"
                                        :placeholder="options.length > 0 ? '' : 'Enter emails'"
                                        :disabled="shipmentReports == 0"
                                        @tags-changed="newTags => {
                                            this.options = newTags
                                            this.tagsInput.touched = true
                                            this.tagsInput.hasError = (this.options.length > 0) ? false : true
                                            let el = this.documentProto.getElementsByClassName('ti-input')[0]
                                            if (typeof el!=='udnefined') {
                                                if (this.tagsInput.hasError)
                                                    el.classList.add('ti-new-tag-input-error')
                                                else
                                                    el.classList.remove('ti-new-tag-input-error')
                                            }
                                        }"
                                    />

									<p class="mb-1" style="color: #819FB2; font-size: 12px; margin-top: 5px;">
										Separate multiple email addresses with comma
									</p>

                                    <div v-if="tagsInput.touched" class="v-text-field__details">
                                        <div class="v-messages theme--light error--text" role="alert">
                                            <div class="v-messages__wrapper">
                                                <div v-if="(options.length > 0) && reportsEmailAddress!==''" class="v-messages__message">
                                                    {{ tagsInput.errorMessage }}
                                                </div>

                                                <div v-if="options.length == 0 && reportsEmailAddress!==''" class="v-messages__message">
                                                    {{ tagsInput.errorMessage }}
                                                </div>
                                                <div v-if="options.length == 0 && reportsEmailAddress==''" class="v-messages__message">
                                                    Please provide at least 1 valid email address.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								</v-col>
							</v-row>
						</div>
					</div>				
				</div>
            </div>
		</v-form>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import DatePicker from "../DateTimeComponents/DatePicker.vue"
import { DATE_FORMAT_WITH_MONTH_NAME } from "../../constants/DateTime"
import moment from "moment"
import globalMethods from "@/utils/globalMethods"
import jQuery from 'jquery'
import VueTagsInput from '@johmun/vue-tags-input'

export default {
	name: "ReportsDesktopTable",
	props: [
		"frequencyItems",
		"dayItems",
		"day",
		"report",
		"shipmentData",
		"errorEmails",
	],
	components: {
		DatePicker,
        VueTagsInput
	},
	data: () => ({
		snackbar: false,
		utilization_report: "enable",
		efficiency_report: "disable",
		time: null,
		menuTime: false,
		options: [],
		frequency: "",
		utilizationDay: "",
		shipmentEmail: [],
		utilizationEmail: [],
		errorEmail1: "",
		errorEmail2: "",
		reg: /^(([^<>()\]\\.,;:\s@"]+(\.[^<>()\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/,
		shipmentEmailValid: false,
		utilizationEmailValid: false,
		shipmentReports: "",
		shipmentFrequency: "",
		shipmentDay: "",
		shipmentTime: "",
		shipmentDate: "",
		reportBy: "",
		DATE_FORMAT_WITH_MONTH_NAME: DATE_FORMAT_WITH_MONTH_NAME,
		isCreatingReport: false,
		// error for all fields
		valid: true,
		rules: [
            (v) => !!v || "Input is required."
        ],
        tagsValidation: [
            {
                classes: 't-new-tag-input-text-error',
                rule: (/^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/),
                disableAdd: true
            }
        ],
        documentProto: document,
        tagsInput: {
            touched: false,
            hasError: false,
            errorMessage: 'Please confirm the entered email address by pressing the "Enter" or "," key in your keyboard.'
        },
        reportsEmailAddress: '',
        arrayNotEmptyRules: [
            (v) => !!v || "Email is required",
            () => this.optionsFiltered.length > 0 || "Make sure to supply at least 1 email." 
        ],
	}),
	created() {
		this.shipmentReports = this.getShipmentReport
		this.shipmentFrequency = this.getFrequency
		this.shipmentDay = this.getDay
		this.shipmentTime = this.getTime
		this.shipmentDate = this.getDate
		this.shipmentEmail = this.getEmail
		this.reportBy = this.getReport
	},
	watch: {
		shipmentReports: async function() {
			if (this.shipmentReports == undefined) {
				this.shipmentReports = 1
			}

			await this.shipmentReports

			if (this.shipmentReports != this.getEmailReports.active) {
				this.shipmentData.active = this.shipmentReports			

				if (!this.isCreatingReport) {
					this.$emit("save-changes", this.shipmentData, true)
				} else {
					this.$emit("createReport", this.shipmentData, true)
				}
			} else {			
				if (!this.isCreatingReport) {
					this.$emit("save-changes", this.shipmentData, false)
				} else {
					this.$emit("createReport", this.shipmentData, false)
				}
			}
		},
		getShipmentReport: function(newValue) {
			this.shipmentReports = newValue
		},
		shipmentFrequency: async function() {
			await this.shipmentFrequency

			this.shipmentData.frequency = this.shipmentFrequency

			if (this.shipmentFrequency != this.getEmailReports.frequency) {
				if (!this.isCreatingReport) {
					this.$emit("save-changes", this.shipmentData, true)
				} else {
					this.$emit("createReport", this.shipmentData, true)
				}
			} else {
				if (!this.isCreatingReport) {
					this.$emit("save-changes", this.shipmentData, false)
				} else {
					this.$emit("createReport", this.shipmentData, false)
				}
			}
		},
		getFrequency: function(newValue) {
			this.shipmentFrequency = newValue
		},
		shipmentDay: async function() {
			await this.shipmentDay
			this.shipmentData.day = parseInt(this.shipmentDay)
			if (this.shipmentDay != this.getEmailReports.day) {
				if (!this.isCreatingReport) {
					this.$emit("save-changes", this.shipmentData, true)
				} else {
					this.$emit("createReport", this.shipmentData, true)
				}
			} else {
				if (!this.isCreatingReport) {
					this.$emit("save-changes", this.shipmentData, false)
				} else {
					this.$emit("createReport", this.shipmentData, false)
				}
			}
		},
		getDay: function(newValue) {
			this.shipmentDay = newValue
		},
		shipmentEmail: async function() {
			await this.shipmentEmail
            await this.callRecipientsOptions()

			if (this.shipmentEmail != this.getEmailReports.report_recipients) {
				this.shipmentData.report_recipients = this.shipmentEmail
				
				if (!this.isCreatingReport) {
					this.$emit("save-changes", this.shipmentData, true)
				} else {
					this.$emit("createReport", this.shipmentData, true)
				}
			} else {
				if (!this.isCreatingReport) {
					this.$emit("save-changes", this.shipmentData, false)
				} else {
					this.$emit("createReport", this.shipmentData, false)
				}
			}
		},
		getEmail: function(newValue) {
			this.shipmentEmail = newValue
		},
		shipmentTime: async function() {
			await this.shipmentTime
			if (this.shipmentTime != this.getEmailReports.time) {
				this.shipmentData.time = this.shipmentTime
				if (!this.isCreatingReport) {
					this.$emit("save-changes", this.shipmentData, true)
				} else {
					this.$emit("createReport", this.shipmentData, true)
				}
			} else {
				if (!this.isCreatingReport) {
					this.$emit("save-changes", this.shipmentData, false)
				} else {
					this.$emit("createReport", this.shipmentData, false)
				}
			}
		},
		getTime: function(newValue) {
			this.shipmentTime = newValue
		},
		shipmentDate: async function() {
			await this.shipmentDate
			if (
				moment(this.shipmentDate).format("MMMM DD") !=
				this.getEmailReports.month_day
			) {
				this.shipmentData.month_day = this.shipmentDate
				if (!this.isCreatingReport) {
					this.$emit("save-changes", this.shipmentData, true)
				} else {
					this.$emit("createReport", this.shipmentData, true)
				}
			} else {
				if (!this.isCreatingReport) {
					this.$emit("save-changes", this.shipmentData, false)
				} else {
					this.$emit("createReport", this.shipmentData, false)
				}
			}
		},
		getDate: function(newValue) {
			this.shipmentDate = newValue
		},
		reportBy: async function() {
			await this.reportBy
			if (this.reportBy != this.getEmailReports.report_type) {
				this.shipmentData.report_type = this.reportBy
				if (!this.isCreatingReport) {
					this.$emit("save-changes", this.shipmentData, true)
				} else {
					this.$emit("createReport", this.shipmentData, true)
				}
			} else {
				if (!this.isCreatingReport) {
					this.$emit("save-changes", this.shipmentData, false)
				} else {
					this.$emit("createReport", this.shipmentData, false)
				}
			}
		},
		getReport: function(newValue) {
			this.reportBy = newValue
		},
		tagsInput(value) {
            if (value.hasError) {
                jQuery('.ti-input').addClass('ti-new-tag-input-error')
            } else {
                jQuery('.ti-input').removeClass('ti-new-tag-input-error')
            }
        },
	},
	computed: {
		...mapGetters({
			getEmailReports: "getEmailReports",
			getUser: "getUser",
			getUpdateEmailReportsLoading: "getUpdateEmailReportsLoading",
			getEmailReportsLoading: 'getEmailReportsLoading',
			getCreateEmailReportsLoading: 'getCreateEmailReportsLoading'
		}),
		getShipmentReport() {
			return this.getEmailReports.active
		},
		getFrequency() {
			return this.getEmailReports.frequency
		},
		getDay() {
			if (this.getEmailReports.day != null) {
				return this.getEmailReports.day.toString()
			} else {
				return this.getEmailReports.day
			}
		},
		getEmail() {
			return this.getEmailReports.report_recipients === undefined ? [] : this.getEmailReports.report_recipients
		},
		getTime() {
			let time = this.getEmailReports.time

			if (time !== '') {
				// var offset = moment().utcOffset()
				// var localTime = moment.utc(time, 'HH:mm:ss').utcOffset(offset).format("LT")
				// let finalTimeConverted = moment(localTime, 'HH:mm:ss').local().format("HH:mm:ss")
				// time = finalTimeConverted

				var getTime = moment.utc(time, "HH:mm:ss")
				var finalTimeConverted = getTime.local().format('HH:mm:ss')
				time = finalTimeConverted
			}

			return time
			// return this.getEmailReports.time
		},
		getDate() {
			return moment(
				this.getEmailReports.month_day + `, ${new Date().getFullYear()}`
			).format("YYYY-MM-DD")
		},
		getReport() {
			return this.getEmailReports.report_type
		},
	},
	methods: {
		...mapActions({
			fetchEmailReportSchedule: "fetchEmailReportSchedule",
			updateEmailReportSchedule: "updateEmailReportSchedule",
			downloadReport: "downloadReport",
			createEmailReportSchedule: "createEmailReportSchedule",
		}),
		...globalMethods,
		close() {
			this.$emit("close")
		},
        async callRecipientsOptions() {
            this.tagsInput.hasError = false
            this.tagsInput.touched = false

            jQuery('.ti-input').removeClass('ti-new-tag-input-error')
            if (typeof el!=='undefined') {
                let el = document.getElementsByClassName('ti-input')[0]
                el.classList.remove('ti-new-tag-input-error')
            }

            if (this.shipmentEmail.length === 0) {
                this.options = []
                this.reportsEmailAddress = ''
            } else {
                if (this.isCreatingReport) {
                    this.options = []
                } else {
                    let validEmailAddress = []
                    if (this.shipmentEmail.length > 0) {                        
                        this.shipmentEmail.map(wea => {
                            if (wea!==null) {
                                validEmailAddress.push({text: wea,tiClasses:["ti-valid"]})
                            }
                        })
                    }

                    this.options = validEmailAddress
                }
            }
        },
        generateErrorMessage() {
            this.tagsInput.hasError = (this.options.length > 0) ? false : true
            if (this.tagsInput.hasError)
                jQuery('.ti-input').addClass('ti-new-tag-input-error')
            else
                jQuery('.ti-input').removeClass('ti-new-tag-input-error')
        },
		download() {
			let userId;
			if (typeof this.getUser === "string") {
				userId = JSON.parse(this.getUser).id
			} else {
				userId = this.getUser.id
			}
			this.downloadReport(userId)
		},
		async saveChanges() {
			this.errorEmail1 = ""
			this.errorEmail2 = ""

            if (!this.tagsInput.touched)
                this.tagsInput.touched = true

            this.tagsInput.hasError = (this.options.length > 0) ? false : true            
    
            this.generateErrorMessage()

			this.shipmentEmailValid = true
			for (let email of this.shipmentEmail) {
				if (!this.reg.test(email)) {
					this.shipmentEmailValid = false
					break;
				}
			}

			const id = this.getEmailReports.id
            let finalEmailAddress = (this.options.length > 0) ? this.options.map(o => {
                return o.text
            }) : []

			let selectedTime = moment(this.shipmentTime, 'HH:mm:ss')
			let convertTimeToUtc = moment(selectedTime).utc().format('HH:mm:ss')
			// let convertTimeToUtc = moment.utc(selectedTime).format('HH:mm:ss')

			let parms = {
				id,
				active: this.shipmentReports,
				frequency: this.shipmentFrequency,
				// report_recipients: this.shipmentEmail,
                report_recipients: finalEmailAddress,
				day: this.shipmentDay,
				// time: this.shipmentTime,
				time: convertTimeToUtc,
				month_day: this.shipmentDate,
				report_type: this.reportBy,
			}

			if (this.shipmentReports) {
                if (!this.tagsInput.hasError) { 
                    if (this.reportsEmailAddress === '') {
                        try {
                            jQuery('.ti-new-tag-input').trigger(
                                jQuery.Event( 'keyup', { keyCode: 13, which: 13 } )
                            )

                            this.errorEmail1 = ""
                            await this.updateEmailReportSchedule(parms)
                            const userId =
                                typeof this.getUser === "string"
                                    ? JSON.parse(this.getUser).id
                                    : this.getUser.id

                            this.fetchEmailReportSchedule(userId)
                            this.notificationMessageCustomSuccess("All changes are saved!")
                        } catch(e) {
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
                                this.errorEmail1 = ""
                                await this.updateEmailReportSchedule(parms)
                                const userId =
                                    typeof this.getUser === "string"
                                        ? JSON.parse(this.getUser).id
                                        : this.getUser.id

                                this.fetchEmailReportSchedule(userId)
                                this.notificationMessageCustomSuccess("All changes are saved!")
                            } catch(e) {
                                this.notificationError(e)
                                console.log(e)
                            }
                        }
                    }
                }
			} else {
				await this.updateEmailReportSchedule(parms)
				const userId =
					typeof this.getUser === "string"
						? JSON.parse(this.getUser).id
						: this.getUser.id

				this.fetchEmailReportSchedule(userId)
				this.notificationMessageCustomSuccess("All changes are saved!")
			}
		},
		async addChanges() {
            this.$refs.form.validate()
            if (!this.tagsInput.touched)
                this.tagsInput.touched = true

            this.tagsInput.hasError = (this.options.length > 0) ? false : true            
    
            this.generateErrorMessage()
			
            let customer_admin_id = (typeof this.getUser=='string') ? JSON.parse(this.getUser).id : ''
			
			let selectedTime = moment(this.shipmentTime, 'HH:mm:ss')
			let convertTimeToUtc = moment(selectedTime).utc().format('HH:mm:ss')
			// let convertTimeToUtc = moment.utc(selectedTime).format('HH:mm:ss')

            let parms = {
                active: 1,
                frequency: this.shipmentFrequency,
                day: this.shipmentDay,
				time: convertTimeToUtc,
                // time: this.shipmentTime,
                month_day: this.shipmentDate,
                report_type: this.reportBy,
                customer_admin_id
            }	

			if (this.shipmentReports) {
                setTimeout(async () => {
                    if (!this.tagsInput.hasError) { 
                        try {
                            jQuery('.ti-new-tag-input').trigger(
                                jQuery.Event( 'keyup', { keyCode: 13, which: 13 } )
                            )

                            let finalEmailAddress = (this.options.length > 0) ? this.options.map(o => {
                                return o.text
                            }) : []

                            parms.report_recipients = finalEmailAddress
                            await this.createEmailReportSchedule(parms)
                            this.fetchEmailReportSchedule(customer_admin_id)
                            this.notificationMessageCustomSuccess("Report has been created.")
                            this.isCreatingReport = false
                        } catch(e) {
                            this.notificationError(e)
                            console.log(e)
                        }
                    }
                }, 400)                
			}
		},
		showAddShipmentReport() {
			this.isCreatingReport = true
		}
	},
	updated() {}
}
</script>

<style lang="scss"></style>
