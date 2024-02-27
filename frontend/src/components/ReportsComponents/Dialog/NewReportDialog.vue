<template>
    <v-dialog v-model="dialog" max-width="800px" content-class="new-report-wrapper-dialog" persistent scrollable>
        <v-card class="new-report-dialog">
            <v-card-title>
                <span class="headline">{{ editedIndex === -1 ? 'New Report' : 'Edit Report' }}</span>
                
                <v-spacer></v-spacer>						

                <v-btn icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text>
                <v-form ref="form" v-model="valid" action="#" @submit.prevent=""> 
                    <div class="new-report-content">
                        <div class="new-report-types mb-3">
                            <v-radio-group v-model="typeReport" mandatory hide-details="auto">
                                <v-radio label="Company Report" :value="1"></v-radio>
                                <v-radio label="Personalized Reports" :value="2"></v-radio>
                            </v-radio-group>
                        </div>

                        <v-row>
                            <v-col cols="12" sm="12" md="12" class="pb-0" v-if="typeReport === 1">
                                <label class="text-item-label">Recipients</label>
                                <vue-tags-input
                                    hide-details="auto"
                                    :rules="typeReport === 1 ? arrayNotEmptyRules : []"
                                    :tags="options"
                                    :add-on-blur=true
                                    allow-edit-tags
                                    :add-on-key="[13, ',']"
                                    :validation="typeReport === 1 ? tagsValidation : []"
                                    v-model="reportsEmailAddress"
                                    :placeholder="options.length > 0 ? '' : 'Enter emails'"
                                    @tags-changed="newTags => {
                                        this.options = newTags
                                        this.tagsInput.touched = true
                                        this.tagsInput.hasError = (this.options.length > 0) ? false : true
                                        let el = this.documentProto.getElementsByClassName('ti-input')[0]
                                        if (typeof el!=='undefined') {
                                            if (this.tagsInput.hasError)
                                                el.classList.add('ti-new-tag-input-error')
                                            else
                                                el.classList.remove('ti-new-tag-input-error')
                                        }
                                    }"/>

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

                        <v-row>
                            <v-col cols="12" sm="12" md="6" class="report pb-2">
                                <label class="text-item-label">Report</label>
                                <v-select
                                    class="text-fields normal-vselect"
                                    placeholder="Select Report"
                                    item-text="text"
                                    item-value="value"
                                    :items="reportTypes"
                                    outlined
                                    v-model="editedItemData.reportTypeSub"
                                    :menu-props="{ bottom: true, offsetY: true }"
                                    hide-details="auto"
                                    height="45px">
                                </v-select>
                            </v-col>

                            <v-col cols="12" sm="12" md="6" class="customize-col">
                                <label class="text-item-label" style="color: #fff !important;"> . </label>

                                <FilterComponent :menu.sync="filterMenu" :isMobile="isMobile" 
                                    :customClass="'from-reports-page'"
                                    @onClickCustomize="onClickCustomize"
                                    :isCustomizedClicked.sync="isCustomizedClicked"
                                    @onClickOutsideCustomize="clickOutsideCustomize"
                                    :alignment="'right'">

                                    <template v-slot:filter_title>
                                        <img src="../../../assets/icons/settings-blue.svg" class="filter-img mr-1" width="18px" height="18px">
                                        <span class="filter-main-title font-medium">Customize</span>
                                    </template>

                                    <template v-slot:filter_title_mobile>
                                        <div class="customized-sub-header-mobile">
                                            <span class="headline">Customize Report</span>
                                            <button class="btn-white deselect-all" @click="clearCustom(true)">
                                                <v-icon>mdi-close</v-icon>
                                            </button>
                                        </div>
                                    </template>

                                    <template v-slot:filter_body>
                                        <div class="customized-sub-header" v-if="!isMobile">
                                            <h3>Customize Report</h3>
                                            <button class="btn-white deselect-all" @click="clearCustom(true)">
                                                <v-icon>mdi-close</v-icon>
                                            </button>
                                        </div>

                                        <v-divider v-if="!isMobile"></v-divider>

                                        <div class="select-deselect-all-wrapper">
                                            <button class="btn-white select-all" @click="isShowSelectAll(true)">Select All</button>
                                            <span style="color:#4a4a4a;">-</span>
                                            <button class="btn-white deselect-all" @click="isShowSelectAll(false)">Deselect All</button>
                                        </div>

                                        <v-divider></v-divider>

                                        <div class="filter-component-body" ref="menu-ref">
                                            <div class="body-wrapper-checkbox">
                                                <draggable v-model="customizedHeaderCopy" v-bind="dragOptions">
                                                    <transition-group type="transition">
                                                        <div class="checkbox-wrapper" 
                                                            v-for="(header, i) in customizedHeaderCopy" 
                                                            :key="header.id"
                                                            :id="'checkbox-item-' + (i+1)">

                                                            <div class="move-icon" v-if="!isMobile">
                                                                <img src="../../../assets/icons/move-icon.svg" alt="">
                                                            </div>
                                                            <v-checkbox                                            
                                                                v-model="header.isChecked"
                                                                :label="header.text"
                                                                hide-details="auto"
                                                                class="mt-0"
                                                                :disabled="header.text === 'Shifl Ref#'"
                                                                @change="setActiveTrue()">
                                                            </v-checkbox>
                                                        </div>
                                                    </transition-group>
                                                </draggable>
                                            </div>
                                        </div>
                                    </template>

                                    <template v-slot:filter_actions>
                                        <v-btn class="btn-apply btn-blue" @click="applyCustom(false)">
                                            Apply
                                        </v-btn>

                                        <v-btn class="btn-restore btn-white" @click="sortToOrignal">
                                            Restore Default
                                        </v-btn>
                                    </template>
                                </FilterComponent>
                            </v-col>
                        </v-row>

                        <!-- desktop view for frequency -->
                        <v-row v-if="!isMobile">
                            <v-col cols="12" sm="12" md="3" class="frequency pb-2">
                                <label class="text-item-label">Frequency</label>
                                <v-select
                                    class="text-fields normal-vselect"
                                    placeholder="Select Frequency"
                                    item-text="text"
                                    item-value="id"
                                    :items="frequencyItems"
                                    outlined
                                    v-model="editedItemData.frequency"
                                    :disabled="editedItemData.active == 0"
                                    :menu-props="{ bottom: true, offsetY: true }"
                                    :rules="rules">
                                </v-select>
                            </v-col>

                            <v-col cols="12" sm="12" md="3" v-if="editedItemData.frequency == 'WEEKLYON'" class="pb-2">
                                <label class="text-item-label">Day</label>
                                <v-select
                                    class="text-fields normal-vselect"
                                    placeholder="Select Day"
                                    :items="dayItems"
                                    outlined
                                    item-text="text"
                                    item-value="id"
                                    v-model="editedItemData.day"
                                    :disabled="editedItemData.active == 0"
                                    :menu-props="{ bottom: true, offsetY: true }"
                                    :rules="rules">
                                </v-select>
                            </v-col>

                            <v-col cols="12" sm="12" md="3" v-if="editedItemData.frequency == 'MONTHLYON'" class="pb-2">
                                <label class="text-item-label">Day</label>
                                <v-select
                                    class="text-fields normal-vselect"
                                    placeholder="Select Day"
                                    :items="day"
                                    outlined
                                    item-text="text"
                                    item-value="id"
                                    v-model="editedItemData.day"
                                    :disabled="editedItemData.active == 0"
                                    :menu-props="{ bottom: true, offsetY: true }"
                                    :rules="rules">
                                </v-select>
                            </v-col>

                            <v-col cols="12" sm="12" md="3" v-if="editedItemData.frequency === 'YEARLYON'" class="pb-2">
                                <label class="text-item-label">Date</label>
                                <v-text-field
                                    type="date"
                                    class="text-fields" 
                                    placeholder="Select Date" 
                                    outlined
                                    hide-details="auto"
                                    :min="currentDateFind" />
                            </v-col>

                            <v-col cols="12" sm="12" md="3">
                                <label class="text-item-label">Time</label>
                                <v-text-field
                                    height="48px"
                                    class="text-fields input-time"
                                    placeholder="12:30:00"
                                    v-model="editedItemData.currentTime"
                                    type="time"
                                    outlined
                                    hide-details="auto"
                                    hint="FORMAT: 12:00 AM"
                                    :disabled="editedItemData.active == 0"
                                    :rules="rules">
                                </v-text-field>
                            </v-col>
                        </v-row>

                        <!-- mobile view for frequency -->
                        <v-row no-gutters v-if="isMobile">
                            <v-col>
                                <v-card flat>
                                    <fieldset class="frequency-legend-rounded-wrapper px-3 mt-3 pb-5 rounded">
                                        <legend class="frequency-legend-rounded rounded">
                                            <div>
                                                <label class="text-item-label">Frequency</label>
                                                <v-select
                                                    class="text-fields normal-vselect"
                                                    placeholder="Select Frequency"
                                                    item-text="text"
                                                    item-value="id"
                                                    :items="frequencyItems"
                                                    outlined
                                                    v-model="editedItemData.frequency"
                                                    :disabled="editedItemData.active == 0"
                                                    :menu-props="{ bottom: true, offsetY: true }"
                                                    :rules="rules">
                                                </v-select>
                                            </div>
                                        </legend>

                                        <v-row>
                                            <v-col cols="12" sm="12" md="4" v-if="editedItemData.frequency == 'WEEKLYON'" class="pt-5 pb-2">
                                                <label class="text-item-label">Day</label>
                                                <v-select
                                                    class="text-fields normal-vselect"
                                                    placeholder="Select Day"
                                                    :items="dayItems"
                                                    outlined
                                                    item-text="text"
                                                    item-value="id"
                                                    v-model="editedItemData.day"
                                                    :disabled="editedItemData.active == 0"
                                                    :menu-props="{ bottom: true, offsetY: true }"
                                                    :rules="rules">
                                                </v-select>
                                            </v-col>

                                            <v-col cols="12" sm="12" md="4" v-if="editedItemData.frequency == 'MONTHLYON'" class="pt-5 pb-2">
                                                <label class="text-item-label">Day</label>
                                                <v-select
                                                    class="text-fields normal-vselect"
                                                    placeholder="Select Day"
                                                    :items="day"
                                                    outlined
                                                    item-text="text"
                                                    item-value="id"
                                                    v-model="editedItemData.day"
                                                    :disabled="editedItemData.active == 0"
                                                    :menu-props="{ bottom: true, offsetY: true }"
                                                    :rules="rules">
                                                </v-select>
                                            </v-col>

                                            <v-col cols="12" sm="12" md="4" v-if="editedItemData.frequency === 'YEARLYON'" class="pt-5 pb-2">
                                                <label class="text-item-label">Date</label>
                                                <v-text-field
                                                    type="date"
                                                    class="text-fields" 
                                                    placeholder="Select Date" 
                                                    outlined
                                                    hide-details="auto"
                                                    :min="currentDateFind" />
                                            </v-col>

                                            <v-col cols="12" sm="12" md="4" class="pt-1">
                                                <label class="text-item-label">Time</label>
                                                <v-text-field
                                                    height="48px"
                                                    class="text-fields input-time"
                                                    placeholder="12:30:00"
                                                    v-model="editedItemData.currentTime"
                                                    type="time"
                                                    outlined
                                                    hide-details="auto"
                                                    hint="FORMAT: 12:00 AM"
                                                    :disabled="editedItemData.active == 0"
                                                    :rules="rules">
                                                </v-text-field>
                                            </v-col>
                                        </v-row>
                                    </fieldset>
                                </v-card>
                            </v-col>
                        </v-row>                    

                        <v-row>
                            <v-col cols="12" sm="12" md="12" class="pt-0">
                                <label class="text-item-label">Type</label>
                                <v-select
                                    class="text-fields normal-vselect"
                                    placeholder="Select Type"
                                    :items="report"
                                    outlined
                                    item-text="text"
                                    item-value="id"
                                    v-model="shipmentTypeBy"
                                    :disabled="editedItemData.active == 0"
                                    :menu-props="{ bottom: true, offsetY: true }"
                                    :rules="rules"
                                    @change="updateHeaderReport(shipmentTypeBy)">
                                </v-select>
                            </v-col>
                        </v-row>
                    </div>
                </v-form>
            </v-card-text>
            
            <v-card-actions class="new-report-btn-wrapper">
                <button class="btn-blue mr-2" text @click="addNewReport" 
                    :disabled="getCreateEmailReportsLoading || getUpdateEmailReportsLoading">
                    <span v-if="editedIndex === -1">                        
                        {{ getCreateEmailReportsLoading ? 'Adding...' : 'Add Report' }}
                    </span>
                    <span v-if="editedIndex > -1">
                        {{ getUpdateEmailReportsLoading ? 'Editing...' : 'Edit Report' }}
                    </span>
                </button>

                <button class="btn-white" text @click="close" 
                    :disabled="getCreateEmailReportsLoading || getUpdateEmailReportsLoading">
                    Cancel
                </button>
            </v-card-actions>
        </v-card>

        <ConfirmDialog :dialogData.sync="isShowWarning">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Update Type</h2>
			</template>

			<template v-slot:dialog_content>
				<p>By changing the report type, the customized columns will be removed. Do you want to continue?</p>
			</template>

			<template v-slot:dialog_actions>
				<!-- <v-btn class="btn-blue" @click="deleteReport(true)" text :disabled="getDeleteReportScheduleLoading">
                    {{ getDeleteReportScheduleLoading ? 'Deleting...' : 'Delete' }}
				</v-btn>

				<v-btn class="btn-white" text @click="closeDeleteReport" :disabled="getDeleteReportScheduleLoading">
					Cancel
				</v-btn> -->

                <v-btn class="btn-blue" text @click="confirmUpdateType">
                    Yes
				</v-btn>

				<v-btn class="btn-white" text @click="cancelUpdateType">
					Discard
				</v-btn>
			</template>
		</ConfirmDialog>
    </v-dialog>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import VueTagsInput from '@johmun/vue-tags-input'
import jQuery from 'jquery'
import moment from "moment"
import FilterComponent from '../../FilterComponent/FilterComponent.vue'
import ConfirmDialog from "../../Dialog/GlobalDialog/ConfirmDialog.vue"
import draggable from 'vuedraggable'
import globalMethods from '../../../utils/globalMethods'

export default {
    name: 'NewReportDialog',
    props: [
        "dialogData",
        "frequencyItems",
		"dayItems",
		"day",
		"report",
        "isMobile",
        "editedReportIndex",
        "editedReportData",
        "container_fields",
        "reference_fields",
        "typeReportData",
        "shipmentTypeByData",
        "defaultContainerFieldsCompare",
        "defaultReferenceFieldsCompare"
    ],
    components: {
		VueTagsInput,
        FilterComponent,
        ConfirmDialog,
        draggable
	},
    data: () => ({
        reportType: 'company',
        reportEmails: [],
        options: [],
		valid: true,
        reg: /^(([^<>()\]\\.,;:\s@"]+(\.[^<>()\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/,
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
        reportTypes: [
            {
                text: 'Shipment Report',
                value: 'shipment'
            }
        ],
        reportTypeSub: '',
        customizedHeader: [],
        customizedHeaderCopy: [],
        filterMenu: false,
        isCustomizedClicked: false,
        isShowWarning: false,
        currentReportTypeByChange: '',
        oldReportTypeByValue: ''
    }),
    watch: {
        dialog(value, oldValue) {
			this.tagsInput.hasError = false
			this.tagsInput.touched = false
            this.customizedHeader = []
            this.customizedHeaderCopy = []

			jQuery(".ti-input").removeClass("ti-new-tag-input-error")
			if (typeof el !== "undefined") {
				let el = document.getElementsByClassName("ti-input")[0]
				el.classList.remove("ti-new-tag-input-error")
			}

			if (!value) {
				this.options = []
				this.reportsEmailAddress = ""
			} else if (value && !oldValue) {
				if (this.editedIndex === -1) {
                    this.options = []
				} else {
					let validEmailAddress = []
					if (this.editedItemData.report_recipients !== null &&
						this.editedItemData.report_recipients.length > 0) {
						this.editedItemData.report_recipients.map((wea) => {
							if (wea !== null) {
								validEmailAddress.push({ text: wea, tiClasses: ["ti-valid"] })
							}
						})
					}

					this.options = validEmailAddress
                    this.customizedHeader = this.editedItemData.report_columns_copy
                    this.customizedHeaderCopy = this.customizedHeader
				}
			}
		},
        typeReport(value) {
            if (this.editedIndex === -1) {
                if (value === 2) {
                    const current_user_email = typeof this.getUser === "string"
                    ? JSON.parse(this.getUser).email : this.getUser.email   

                    let validEmailAddress = { text: current_user_email, tiClasses: ["ti-valid"] }
                    this.options = [validEmailAddress]
                } else {
                    this.options = []
                }
            }
        },
        // shipmentTypeBy(newValue, oldValue) {
        //     console.log(newValue, oldValue, 'watch');
        //     if (newValue !== null && newValue !== '') {
        //         if (newValue === 'BYCONTAINER') {
        //             this.customizedHeader = this.container_fields
        //         } else {
        //             this.customizedHeader = this.reference_fields
        //         }
        //     }

        //     if (oldValue === '') {
        //         this.customizedHeaderCopy = this.customizedHeader
        //     } else if (oldValue === 'BYCONTAINER') {
        //         let fields_updated = this.defaultContainerFieldsCompare.filter(e => 
        //             !this.container_fields.some(o => 
        //                 o.text == e.text && o.isChecked == e.isChecked
        //             )
        //         )
                
        //         if (fields_updated.length > 0) {
        //             // console.log('some fields has been updated');
        //             this.openWarningChangeReportBy('container')
        //         } else {
        //             this.customizedHeaderCopy = this.customizedHeader
        //         }
        //     } else if (oldValue === 'BYREFERENCE') {
        //         let fields_updated = this.defaultReferenceFieldsCompare.filter(e => 
        //             !this.reference_fields.some(o => 
        //                 o.text == e.text && o.isChecked == e.isChecked
        //             )
        //         )
                
        //         if (fields_updated.length > 0) {
        //             this.openWarningChangeReportBy('reference')
        //         } else {
        //             this.customizedHeaderCopy = this.customizedHeader
        //         }
        //     } else if (oldValue === 'BYREFERENCEADV') {
        //         let fields_updated = this.defaultReferenceFieldsCompare.filter(e => 
        //             !this.reference_fields.some(o => 
        //                 o.text == e.text && o.isChecked == e.isChecked
        //             )
        //         )
                
        //         if (fields_updated.length > 0) {
        //             this.openWarningChangeReportBy('reference')
        //         } else {
        //             this.customizedHeaderCopy = this.customizedHeader
        //         }
        //     }
        // }
    },
    computed: {
        ...mapGetters({
			getEmailReports: "getEmailReports",
			getUser: "getUser",
			getUpdateEmailReportsLoading: "getUpdateEmailReportsLoading",
			getEmailReportsLoading: 'getEmailReportsLoading',
			getCreateEmailReportsLoading: 'getCreateEmailReportsLoading'
		}),
        dragOptions() {
            return {
                animation: 100,
                group: "description",
                disabled: false,
                ghostClass: "ghost"
            }
        },
        dialog: {
            get () {
                return this.dialogData
            },
            set (value) {
                if (!value) {
                    this.$emit('update:dialogData', false)
                } else {
                    this.$emit('update:dialogData', true)
                }
            }
        },
        editedIndex: {
            get() {
                return this.editedReportIndex
            },
            set(value) {
                this.$emit('update:editedReportIndex', value)
            }
        },
        editedItemData: {
            get() {
                return this.editedReportData
            },
            set(value) {
                this.$emit('update:editedReportData', value)
            }
        },
        currentDateFind() {
            return new Date().toISOString().substr(0, 10)
        },
        optionsFiltered: {
			get() {
				let validEmailAddress = []

				if (this.editedItemData.report_recipients !== null &&
					this.editedItemData.report_recipients.length > 0) {
					this.editedItemData.report_recipients.map((wea) => {
						if (wea !== null) {
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
        typeReport: {
            get () {
                return this.typeReportData
            },
            set (value) {
                this.$emit('update:typeReportData', value)
            }
        },
        shipmentTypeBy: {
            get () {
                if (this.shipmentTypeByData === 'BYREFERENCE') {
                    return 'BYREFERENCEADV'
                } else {
                    return this.shipmentTypeByData
                }
            },
            set (value) {
                this.$emit('update:shipmentTypeByData', value)
            }
        }
    },
    methods: {
        ...mapActions({ 
            createReportSchedule: 'createReportSchedule',
            editReportSchedule: 'editReportSchedule'
        }),
        ...globalMethods,
        // updateHeaderReport(reportType) {
        //     if (reportType !== null && reportType !== '') {
        //         if (reportType === 'BYCONTAINER') {
        //             this.customizedHeader = this.container_fields
        //         } else {
        //             this.customizedHeader = this.reference_fields
        //         }
        //     }            

        //     this.customizedHeaderCopy = this.customizedHeader
        //     // this.sortToOrignal()
        // },
        updateHeaderReport(reportType) {
            let newValue = this.editedReportData.report_type
            let oldValue = reportType
            this.oldReportTypeByValue = oldValue

            if (newValue !== null && newValue !== '') {
                if (newValue === 'BYCONTAINER') {
                    this.customizedHeader = this.container_fields
                } else {
                    this.customizedHeader = this.reference_fields
                }
            }

            if (oldValue === '') {
                this.customizedHeaderCopy = this.customizedHeader
            } else if (oldValue === 'BYCONTAINER') {
                let fields_updated = []

                if (this.editedIndex === -1) {
                    fields_updated = this.defaultContainerFieldsCompare.filter(e => 
                        !this.container_fields.some(o => 
                            o.text == e.text && o.isChecked == e.isChecked
                        )
                    )
                } else {
                    let currentSelectedFields = this.container_fields
                    fields_updated = currentSelectedFields.filter(e => e.isChecked && !e.default)
                }
                
                if (fields_updated.length > 0) {
                    this.openWarningChangeReportBy('container')
                } else {
                    this.customizedHeaderCopy = this.customizedHeader
                }
            } else if (oldValue === 'BYREFERENCE') {
                let fields_updated = []

                if (this.editedIndex === -1) {
                    fields_updated = this.defaultReferenceFieldsCompare.filter(e => 
                        !this.reference_fields.some(o => 
                            o.text == e.text && o.isChecked == e.isChecked
                        )
                    )
                } else {
                    let currentSelectedFields = this.reference_fields
                    fields_updated = currentSelectedFields.filter(e => e.isChecked && !e.default)
                }
                
                if (fields_updated.length > 0) {
                    this.openWarningChangeReportBy('reference')
                } else {
                    this.customizedHeaderCopy = this.customizedHeader
                }
            } else if (oldValue === 'BYREFERENCEADV') {
                let fields_updated = []

                if (this.editedIndex === -1) {
                    fields_updated = this.defaultReferenceFieldsCompare.filter(e => 
                        !this.reference_fields.some(o => 
                            o.text == e.text && o.isChecked == e.isChecked
                        )
                    )
                } else {
                    let currentSelectedFields = this.reference_fields
                    fields_updated = currentSelectedFields.filter(e => e.isChecked && !e.default)
                }
                
                if (fields_updated.length > 0) {
                    this.openWarningChangeReportBy('reference')
                } else {
                    this.customizedHeaderCopy = this.customizedHeader
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
        clearErrors() {
            this.tagsInput.hasError = false
            this.tagsInput.touched = false

            jQuery('.ti-input').removeClass('ti-new-tag-input-error')
            if (typeof el!=='undefined') {
                let el = document.getElementsByClassName('ti-input')[0]
                el.classList.remove('ti-new-tag-input-error')
            }
        },
        async addNewReport() {
            if (!this.tagsInput.touched)
                this.tagsInput.touched = true

            this.tagsInput.hasError = (this.options.length > 0) ? false : true            
    
            this.generateErrorMessage()

            setTimeout(async () => {
				if (this.$refs.form.validate()) {
                    let report_columns = this.customizedHeader.filter(v => { return v.isChecked })
                    report_columns = report_columns.reduce((obj, cur, i) => ({ ...obj, [i]: cur.text }), {})

                    this.editedItemData.report_columns = report_columns
                    let finalEmailAddress = []

                    // parse get user details
                    let getUserDetails = typeof this.getUser === "string" ? JSON.parse(this.getUser) : this.getUser

                    const current_user_email = getUserDetails.email
                    const selectedUserId = getUserDetails.default_customer_id

                    // this.editedItemData.selected_customer = this.typeReport === 1 ? selectedUserId : 0
                    this.editedItemData.selected_customer = selectedUserId

                    finalEmailAddress = this.typeReport === 1 ? 
                        (this.options.length > 0 ? this.options.map((o) => { return o.text }) : []) 
                        : [current_user_email]

                    this.editedItemData.report_type = this.editedItemData.report_type === 'BYREFERENCE' ? 
                        'BYREFERENCEADV' : this.editedItemData.report_type

                    // convert time to utc in payload
                    let selectedTime = moment(this.editedItemData.currentTime, 'HH:mm:ss')
			        let convertTimeToUtc = moment(selectedTime).utc().format('HH:mm:ss')
                    this.editedItemData.time = convertTimeToUtc                    

                    if (!this.tagsInput.hasError) {
                        if (this.reportsEmailAddress === '') {
                            try {
                                jQuery('.ti-new-tag-input').trigger(
                                    jQuery.Event( 'keyup', { keyCode: 13, which: 13 } )
                                )                             

                                this.editedItemData.report_recipients = finalEmailAddress
                                await this.callAPIReports(this.editedItemData)
                            } catch(e) {
                                this.notificationError(e)
                            }
                        } else {
                            if (this.reg.test(this.reportsEmailAddress)) {
                                try {
                                    jQuery('.ti-new-tag-input').trigger(
                                        jQuery.Event( 'keyup', { keyCode: 13, which: 13 } )
                                    )

                                    finalEmailAddress.push(this.reportsEmailAddress)
                                    this.editedItemData.report_recipients = finalEmailAddress
                                    await this.callAPIReports(this.editedItemData)
                                } catch(e) {
                                    this.notificationError(e)
                                    console.log(e)
                                }
                            }
                        }
                    }
				}
			}, 200)
        },
        async callAPIReports(payload) {
            if (this.editedIndex === -1) {
                await this.createReportSchedule(payload)
                this.notificationMessage('Report has been successfully created.')
            } else {
                // update report
                await this.editReportSchedule(payload)
                this.notificationMessage('Report has been successfully updated.')
            }

            this.close()
            await this.$emit('fetchReportsScheduleAPI')
        },
        close() {
            this.$refs.form.resetValidation()
            this.$emit('close')
            this.clearErrors()
            this.customizedHeader = []
            this.customizedHeaderCopy = []
        },
        // customize
        onClickCustomize() {
            this.scrollCustomizedToTop()
            if (this.editedItemData.report_type === '') {
                    this.notificationError('Please select type first before customizing your report.')
            } else {
                if (!this.filterMenu) {
                    this.filterMenu = true                    
                }
            }            
        },
        clickOutsideCustomize() {
            if (this.isCustomizedClicked) {
                this.filterMenu = false
                this.clearCustom(true)
            }
        },
        setActiveTrue() {
            this.isCustomizedClicked = true
        },
        applyCustom(isRestore) {
            this.customizedHeader = []

            this.customizedHeaderCopy.map(v => {
                let getValue = null

                if (!isRestore) {
                    getValue = v.isChecked
                } else {
                    getValue = v.default
                }

                if (getValue !== null) {
                    if (!getValue) {
                        v.isShow = false
                        v.isChecked = false
                    } else {
                        v.isShow = true
                        v.isChecked = true
                    }
                }
            })

            this.customizedHeader = this.customizedHeaderCopy
            this.filterMenu = false
            this.isCustomizedClicked = false
        },
        isShowSelectAll(isSelectAll) {
            this.isCustomizedClicked = true

            this.customizedHeader.map(v => {
                if (isSelectAll) {
                    v.isChecked = true
                } else {
                    if (v.text !== 'Shifl Ref#') {
                        v.isChecked = false
                    }
                }                
            })            
        },
        sort() {
            this.customizedHeaderCopy = this.customizedHeaderCopy.sort((a, b) => a.order - b.order)            
        },
        clearCustom(isClosed) {
            this.customizedHeader = []
            this.customizedHeaderCopy.filter(v => {
                if (isClosed) {
                    if (v.isChecked !== v.isShow) {
                        v.isChecked = v.isShow
                    }
                } else {
                    if (v.isChecked !== v.default) {
                        v.isChecked = v.default
                        v.isShow = v.default
                    }
                }
            })
            this.customizedHeader = this.customizedHeaderCopy

            if (isClosed) {
                this.filterMenu = false
                this.isCustomizedClicked = false
            }
            // this.sort()
        },
        sortToOrignal() {
            this.sort()
            this.clearCustom(false)
            this.scrollCustomizedToTop()
        },
        scrollCustomizedToTop() {
            let toScroll = document.getElementById("checkbox-item-1")

            if (toScroll !== null) {
                setTimeout(() => {
                   document.getElementById("checkbox-item-1").scrollIntoView() 
                }, 50);
            }
        },
        openWarningChangeReportBy(type) {
            this.isShowWarning = true
            this.currentReportTypeByChange = type
        },
        confirmUpdateType() {
            this.customizedHeaderCopy = this.customizedHeader

            if (this.currentReportTypeByChange !== '') {
                if (this.currentReportTypeByChange === 'container') {
                    if (this.editedIndex === -1) {
                        this.container_fields.map(v => {
                            v.isChecked = true
                            v.isShow = true
                            v.default = true
                        })
                    } else {
                        this.container_fields.map(v => {
                            if (!v.default) {
                                v.isChecked = false
                                v.isShow = false
                            }
                        })
                    }
                } else {                    
                    if (this.editedIndex === -1) {
                        this.reference_fields.map(v => {
                            v.isChecked = true
                            v.isShow = true
                            v.default = true
                        })
                    } else {
                        this.reference_fields.map(v => {
                            if (!v.default) {
                                v.isChecked = false
                                v.isShow = false
                            }
                        })
                    }
                }
            }
            
            this.closeWarningChangeReportBy()
        },
        closeWarningChangeReportBy() {
            this.isShowWarning = false
            this.currentReportTypeByChange = ''
            this.oldReportTypeByValue = ''
        },
        cancelUpdateType() {
            this.editedReportData.report_type = this.oldReportTypeByValue
            this.closeWarningChangeReportBy()
        }
    },
    mounted() {},
    updated() {}
}
</script>

<style lang="scss">
@import '../../../assets/scss/pages_scss/report/newReportDialog.scss';
@import '../../../assets/scss/inputs.scss';
</style>