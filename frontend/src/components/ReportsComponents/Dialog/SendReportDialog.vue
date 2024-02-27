<template>
    <v-dialog v-model="dialog" max-width="560px" content-class="send-report-wrapper-dialog" scrollable persistent>
        <v-card class="send-report-dialog">
            <v-card-text>
                <div class="send-report-content mt-2">
                    <div class="header-wrapper-close mb-3">
                        <img src="@/assets/icons/question-blue.svg" alt="alert" />
                    </div>

                    <h2 class="send-report-title pb-3">Sending Report</h2>
                    <p class="send-report-sub mb-3">Do you want to send the 
                        <span class="font-semi-bold">
                            'Shipment Report - 
                            {{ editedItemData !== null ? findFrequency(editedItemData.frequency) : '' }}'
                        </span> 
                        to the following email addresses?
                    </p>
                </div>

                <v-form ref="form" v-model="valid" action="#" @submit.prevent=""> 
                    <div class="send-report-content">
                        <v-row>
                            <v-col cols="12" sm="12" md="12" class="pb-0" >
                                <label class="text-item-label">EMAIL</label>
                                <vue-tags-input
                                    hide-details="auto"
                                    :rules="arrayNotEmptyRules"
                                    :tags="options"
                                    :add-on-blur=true
                                    allow-edit-tags
                                    :add-on-key="[13, ',']"
                                    :validation="tagsValidation"
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
                                    }
                                "/>

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
                </v-form>
            </v-card-text>
            
            <v-card-actions class="send-report-btn-wrapper">
                <v-btn class="btn-blue" @click="sendReportCompany" text :disabled="getSendReportScheduleLoading">
                    {{ getSendReportScheduleLoading ? 'Sending...' : 'Send Now' }}
				</v-btn>

				<v-btn class="btn-white" text @click="close" :disabled="getSendReportScheduleLoading">
					Cancel
				</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import VueTagsInput from '@johmun/vue-tags-input'
import jQuery from 'jquery'
import globalMethods from '../../../utils/globalMethods'
import _ from 'lodash'

export default {
    name: 'NewReportDialog',
    props: [
        "dialogData",
        "isMobile",
        "editedReportData",
        "frequencyItems"
    ],
    components: {
		VueTagsInput,
	},
    data: () => ({
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
    },
    computed: {
        ...mapGetters({
			getSendReportScheduleLoading: "getSendReportScheduleLoading",
		}),
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
        editedItemData: {
            get() {
                return this.editedReportData
            },
            set(value) {
                this.$emit('update:editedReportData', value)
            }
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
        frequency() {
            return this.frequencyItems
        }
    },
    methods: {
        ...mapActions({ 
            sendReportSchedule: "sendReportSchedule",
        }),
        ...globalMethods,
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
        async sendReportCompany() {
            if (!this.tagsInput.touched)
                this.tagsInput.touched = true

            this.tagsInput.hasError = (this.options.length > 0) ? false : true            
    
            this.generateErrorMessage()

            setTimeout(async () => {
				if (this.$refs.form.validate()) {
                    let finalEmailAddress = []

                    finalEmailAddress = this.options.length > 0 ? this.options.map((o) => { return o.text }) : []                

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
            let { id, report_recipients } = payload
            this.editedItemData.isSending = true

            try {
                let payload = { id, report_recipients }
                await this.sendReportSchedule(payload)
                this.editedItemData.isSending = false
                this.notificationMessage('The report has successfully sent to the emails.')
                this.close()
            } catch (e) {
                this.editedItemData.isSending = false
                this.notificationError(e)
            }
        },
        findFrequency(freq) {
            if (freq !== null) {
                let findOption = _.find(this.frequency, (e) => (e.id === freq))

                if (findOption !== undefined) { return findOption.text } 
                else { return '' }
            }
        },
        close() {
            this.$refs.form.resetValidation()
            this.$emit('close')
            this.clearErrors()
        },
    },
    mounted() {},
    updated() {}
}
</script>

<style lang="scss">
@import '../../../assets/scss/pages_scss/report/sendReportDialog.scss';
@import '../../../assets/scss/inputs.scss';
</style>