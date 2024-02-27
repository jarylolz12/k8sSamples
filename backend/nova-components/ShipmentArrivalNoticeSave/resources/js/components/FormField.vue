<template>
<div>
		<Confirmation
			v-if="showConfirmationModal"
			:subject="subject"
			:body="body"
			:cancel="cancel"
			:override="override"
			:question_mark="questionMark"
			:overrideConfirm="overrideConfirm"
			@onCancelled="showConfirmationModalToggle"
			@onOverride="onOverride"
			@onQuestionMark="imShowToggle"
			@onOverrideConfirmation="onOverrideConfirmation"
		></Confirmation>

		<InfoModal
			v-if="imShow"
			:subject="imSubject"
			:body="imBody"
			@imOnCancelled="imShowToggle"
		></InfoModal>

    <div class="flex border-b border-40">
        <div class="w-1/5 px-8 py-6">
            <label for="terminal" class="inline-block text-80 pt-2 leading-tight">
                Terminal in form
            </label>
        </div>
        <div class="py-6 px-8 w-1/2">
            <div :class="`${typeof errors.terminal!='undefined' ? 'border-custom-danger' : ''}`">
                <v-select v-model="terminal" :reduce="terminal => terminal.value" :value="terminal" :options="options" label="label" :filter="terminalSearch" ></v-select>
            </div>
            <!---->
            <input type="hidden" id="terminal" dusk="terminal" name="terminal" :value="terminal" />
            <div v-show="typeof errors.terminal!='undefined'" class="help-text help-text mt-2">
                <div style="color: red;" v-for="value in errors.terminal">
                    {{ value }}
                </div>
            </div>
        </div>
    </div>

    <div class="flex border-b border-40">
        <div class="w-1/5 px-8 py-6">
            <label for="terminal" class="inline-block text-80 pt-2 leading-tight">
                Estimated Time of Arrival
            </label>
        </div>
        <div class="py-6 px-8 w-1/2">
          <date-time-picker
              class="w-full form-control form-input form-input-bordered mb-1"
              :value="eta"
              :dateFormat="'Y-m-d'"
              :placeholder="'__'"
              :enable-time="false"
              :enable-seconds="false"
              :first-day-of-week="0"
              disabled = true
            />

        </div>
    </div>

    <div class="flex border-b border-40">
        <div class="w-1/5 px-8 py-6">
            <label for="terminal" class="inline-block text-80 pt-2 leading-tight">
                Carrier Arrival Notice Eta
            </label>
        </div>
        <div class="py-6 px-8 w-1/2">
          <date-time-picker
              class="w-full form-control form-input form-input-bordered mb-1"
              :value="carrier_arrival_notice_eta"
              @change="(value) => {handleCANEtaChange(value)}"
              :dateFormat="'Y-m-d'"
              :placeholder="'YYYY-MM-DD'"
              :enable-time="false"
              :enable-seconds="false"
              :first-day-of-week="0"
              :class="`${ (typeof errors.carrier_arrival_notice_eta!='undefined') ? 'form-input-bordered border-danger' : ''}`"
            />
          <div v-show="(typeof errors.carrier_arrival_notice_eta!='undefined')" class="help-text error-text text-danger mt-2">
            {{errors['carrier_arrival_notice_eta']}}
          </div>
        </div>
    </div>

    <div class="flex border-b border-40">
        <div class="w-1/5 px-8 py-6">
            <label for="carrier_arrival_notice_firms" class="inline-block text-80 pt-2 leading-tight">
                Carrier Arrival Notice Firms
            </label>
        </div>
        <div class="py-6 px-8 w-1/2">
            <div :class="`${typeof errors.terminalBottom!='undefined' ? 'border-custom-danger' : ''}`">
                <v-select v-model="carrier_arrival_notice_firms" :reduce="carrier_arrival_notice_firms => carrier_arrival_notice_firms.value" :value="carrier_arrival_notice_firms" :options="options" label="label" :filter="terminalSearch" ></v-select>
            </div>
            <!---->
            <input type="hidden" id="carrier_arrival_notice_firms" dusk="carrier_arrival_notice_firms" name="carrier_arrival_notice_firms" :value="carrier_arrival_notice_firms" />
            <div v-show="typeof errors.terminalBottom!='undefined'" class="help-text help-text mt-2">
                <div style="color: red;" v-for="value in errors.terminalBottom">
                    {{ value }}
                </div>
            </div>
        </div>
    </div>

    <div class="flex border-b border-40">
        <div class="w-1/5 px-8 py-6">
            <label for="terminal" class="inline-block text-80 pt-2 leading-tight">
                Customs Broker
            </label>
        </div>
        <div class="py-6 px-8 w-1/2">
            <div :class="`${typeof errors.customs_broker!='undefined' ? 'border-custom-danger' : ''}`">
                <v-select v-model="customs_broker" :reduce="customs_broker => customs_broker.value" :value="customs_broker" :options="optionsCustomsBroker" label="label"  ></v-select>
            </div>
            <!---->
            <input type="hidden" id="customs_broker" dusk="customs_broker" name="customs_broker" :value="customs_broker" />
            <div v-show="typeof errors.customs_broker!='undefined'" class="help-text help-text mt-2">
                <div style="color: red;" v-for="value in errors.customs_broker">
                    {{ value }}
                </div>
            </div>
        </div>
    </div>

    <div v-show="resourceId!='' && resourceId!=null" class="flex border-b border-40">
        <div class="w-1/5 px-8 py-6">
        </div>
        <div class="py-6 px-8 w-1/2">
            <button v-show="loading" disabled class="btn btn-default btn-primary inline-flex items-center relative" dusk="create-button">
                <span class="">
                    Saving...
                </span>
            </button>
            <button v-show="!loading" @click="e => saveArrivalNotice(e)" class="btn btn-default btn-primary inline-flex items-center relative" dusk="create-button">
                <span class="">
                    Save
                </span>
            </button>
        </div>
    </div>
</div>
</template>
<style type="text/css">
.border-custom-danger {
    border: 1px solid var(--danger) !important;
    border-radius: .5rem;
}
</style>
<script>
import {
    FormField,
    HandlesValidationErrors
} from 'laravel-nova'
import jQuery from 'jQuery'
import _ from 'lodash'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import Confirmation from './modals/Confirmation.vue'
import InfoModal from './modals/InfoModal.vue'

export default {
    mixins: [FormField, HandlesValidationErrors],
    props: ['resourceName', 'resourceId', 'field', 'panels'],
		components: {
				Confirmation,
				InfoModal
		},
    data() {
        return {
            resourceUrlName: null,
            terminal: null,
            carrier_arrival_notice_eta: null,
            carrier_arrival_notice_firms: null,
            customs_broker: null,
            eta : null,
            loading: false,
            loadingText: 'Saving',
            buttonText: 'Save',
            terminal: null,
            terminalBottom: null,
            id: null,
            options: [],
            optionsCustomsBroker: [],
            errors: {
                terminal: '',
                terminalBottom: '',
                carrier_arrival_notice_eta: '',
                carrier_arrival_notice_firms: '',
                customs_broker: ''
            },
						subject : "",
						body: "",
						cancel : false,
						override: false,
						imShow: false,
						showConfirmationModal: false,
						imSubject: "",
						imBody: "",
						lclSubject1 : "Firms Incorrect!",
						lclSubject2: "For your Info.",
						lclBody2: `Please understand, there is a reason why we do not use the firms code from the status tab in the case of an LCL (Less Container load). This is because with LCL’s, the port is not the final location. Generally the final location and port is one and the same. A shipment comes in - becomes available - and we pick it up . Simple.<br>
											But this is not the case with an LCL Shipment. When an importer has goods that cannot fill an entire container he shares one container with other importers. So when it arrives at the port, none of the contributing importers have a right over the container. It must first go to a warehouse to be split up. Only then can each importer pick up their goods.<br>
											Thus the final location is not the port/terminal but rather the warehouse.<br>
											This means we cannot use the Terminal from the Status Tab - even though it is the port of arrival.`,
						fclSubject: `Firms Incorrect!`,
						type: "",
						proceedSave: false,
						terminal49_terminal: null,
						overrideConfirmationSubject : `Override Confirmed with Manager?`,
						overrideConfirmationBody: `Are you sure? <br> Please confirm with your manager before Overriding.`,
						overrideConfirm: false,
						e: null

        }
    },
    updated() {},
    async mounted() {
        let arrPath = window.location.pathname.split("/");
        this.resourceUrlName = arrPath[3];
        this.terminal = this.field.terminal_id
        this.carrier_arrival_notice_eta = this.field.carrier_arrival_notice_eta
        this.carrier_arrival_notice_firms = this.field.carrier_arrival_notice_firms
        this.customs_broker       = this.field.customs_broker_id
        this.optionsCustomsBroker = this.field.customs_broker
        this.eta = this.field.eta
        this.id = this.field.id
        this.options = this.field.terminals
        this.type    = this.field.type
        this.optionsTerminal = this.field.terminals
        this.terminal49_terminal = this.field.terminal49_terminal
				console.log(this.field)
    },
		computed:{
			lclBody1 (){
					return `This is Shipment is an LCL (less container load) and the Terminal/Pickup Location you are inputting, <b>${this.getSelectedTerminal.label} (${this.getSelectedTerminal.firms_code})</b> matches the Terminal/Pickup Location, <b> ${this.terminal49_terminal.name || ''} (${this.terminal49_terminal.firms_code}) </b> on the status tab. Are you inputting the port of arrival or are you inputting the correct final destination warehouse firms code that is necessary for an LCL Shipment?
									<br/> Please double-check as you may have to wait for the Carrier Arrival Notice's firms code.`
			},
			fclBody(){
					return `This is Shipment is an FCL (full container load) and the Terminal/Pickup Location you are inputting, <b> ${this.getSelectedTerminal.label}(${this.getSelectedTerminal.firms_code})</b> does <b>not</b> match the Terminal/Pickup Location, <b> ${this.terminal49_terminal.name || ''} (${this.terminal49_terminal.firms_code}) </b>  in T49/Status.
									<br> <br>Are you inputting the Correct Port?
									<br>Please double-check.`
			},
			getSelectedTerminal(){
					return this.field.terminals.find(t => (t.value == this.terminal)) || {
						label: '',
						value: '',
						firms_code: ''
					}
			},
			shouldCheckTerminalVerification(){
					console.log(this.getSelectedTerminal,this.terminal)
					if(this.terminal49_terminal && this.getSelectedTerminal.firms_code != ''){
						if((this.type == 'LCL' && this.getSelectedTerminal.firms_code == this.terminal49_terminal.firms_code) || (this.type == 'FCL' && this.getSelectedTerminal.firms_code != this.terminal49_terminal.firms_code )){
							return true
						}
					}
					return false
			},
			questionMark(){
				return this.type == 'LCL' && !this.overrideConfirm
			}
		},
    methods: {
        saveArrivalNotice(e) {
						this.e = e
            e.preventDefault()
            if (!this.loading) {
                this.errors = {}
                this.loading = true
                this.buttonText = 'Saving...'
                const token = jQuery('meta[name="csrf-token"]').attr('content')
                const requestOptions = {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        terminal: this.terminal,
                        carrier_arrival_notice_eta : this.carrier_arrival_notice_eta,
                        carrier_arrival_notice_firms: this.carrier_arrival_notice_firms,
                        customs_broker: this.customs_broker,
                        _token: token,
                        id: this.id
                    })
                }

								if(this.proceedSave || !this.shouldCheckTerminalVerification){
									fetch(`${this.field.baseUrl}/custom/terminals/save`, requestOptions)
                    .then(this.handleResponse)
                    .then(response => {
                        let {
                            status,
                            messages
                        } = response

                        if (status == 'ok') {
                            Nova.success(
                                'Arrival Notice successfully updated.'
                            )
                            console.log(messages)
                            if (!_.isEmpty(messages) && !_.isEmpty(messages['terminal']) && messages['terminal'].length > 0) {
                                console.log(messages['terminal'])
                                messages['terminal'].forEach((item, i) => {
                                    Nova.error(
                                        item
                                    )
                                });
                            }

                            this.$router.push(`/resources/${this.resourceUrlName}/${this.resourceId}?tab=arrival-notice`)


                        } else {
                            Nova.error(this.__('There was a problem submitting the form.'))
                            this.errors = messages
                        }
                        this.loading = false
                        this.buttonText = 'Save'
                    })
								}else{
									this.showConfirmationModalToggle(true)
									this.loading = false
								}


						}


        },
        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text)
                return data
            })
        },

        terminalSearch(options, search) {
            return options.filter(op => {
                if(op.label && op.firms_code){
                        return op.label.toLowerCase().indexOf(search.toLowerCase()) > -1 || op.firms_code.toLowerCase().indexOf(search.toLowerCase()) > -1
                }else {
                        return false
                }
            })
         },

        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append('terminal', this.terminal || '')
            // formData.append(this.field.attribute, this.value || '')
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },


        handleCANEtaChange(value){
          this.carrier_arrival_notice_eta = value
        },
				onOverride(value){
					this.showConfirmationModal= false
					this.subject = this.overrideConfirmationSubject
					this.body = this.overrideConfirmationBody
					this.overrideConfirm = true
					this.override = false
					this.showConfirmationModal = true
				},
				onQuestionMark(value){

				},
				imShowToggle(value){
					if(this.type == "LCL"){
						this.imSubject = this.lclSubject2
						this.imBody = this.lclBody2
					}
					this.imShow = value

				},
				showConfirmationModalToggle(value){
					if(this.type == "LCL"){
						this.subject = this.lclSubject1
						this.body = this.lclBody1
					}else{
						this.subject = this.fclSubject
						this.body = this.fclBody
					}
					this.override = true
					this.cancel = true
					this.overrideConfirm = false
					this.showConfirmationModal = value
				},
				onOverrideConfirmation(value){
					this.proceedSave = true
					this.saveArrivalNotice(this.e)
				}

    },
}
</script>
