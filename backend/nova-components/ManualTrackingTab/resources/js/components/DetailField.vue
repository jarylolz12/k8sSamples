<template>
<div>
    <div class="flex justify-between	" v-if="!not_tracking && is_admin">
        <heading class="mb-6 mx-6"></heading>
        <button  @click="goToEditView" class="cursor-pointer dim inline-block text-primary font-bold" data-testid="edit-resource" dusk="edit-resource-button" title="Edit">
            <u> Open in Manual Tracking Tool </u>
        </button>
    </div>
    <card v-if="!loading" style="zoom: 0.9">

        <modal class="bg-none" v-if="showConfirmationBox">
          <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width: 460px;">
            <div class="p-8">
              <h2 class="mb-6 text-90 font-normal text-xl">Confirmation</h2>
              <p class="text-80 leading-normal">Are You sure ?</p>
            </div>
            <div class="border-t border-50 px-6 py-3 ml-auto flex items-center" style="min-height: 70px; flex-direction: row-reverse;">
              <a class="cursor-pointer btn text-80 font-normal px-3 mr-3 btn-link" style="order: 2;" @click="not_tracking = true; showConfirmationBox = false">Cancel</a>
              <span>
                <span class="text-primary font-bold cursor-pointer" @click="handleUndoNotTrackingConfirmation"> OK </span>
              </span>
            </div>
          </div>
        </modal>

        <modal class="bg-none" v-if="showBox" >
            <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="min-width: 50vw; padding: 20px 30px">
                <div class="container">

                    <div class="flex border-b border-40">
                        <div class="py-4 px-8 ">
                            <div class="pt-2">
                                <div class="text-xl py-2 font-bold"> Why are you turning off manual tracking for this shipment? </div>
                                Please make sure your answer is clear and in full sentences. This explanation will be going to other parts
                                of operations.
                            </div>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/4 px-8 py-4">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                Notes
                            </label>
                        </div>
                        <div class="py-4 px-8 w-3/4">
                            <div class="pt-2">
                                <textarea rows="5" placeholder="Notes" class="w-full form-control form-input form-input-bordered py-3 h-auto" spellcheck="false" v-model="not_tracking_notes" ></textarea>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: flex-end" class="px-8">
                        <button  class="btn-sm btn-default btn-info ml-2" style="font-size: 14px; line-height: 2rem; background: #e3e8ee;" @click="showBox = false; not_tracking = false">
                            <span style="color: red">
                                Cancel
                            </span>
                        </button>
                        <button class="btn-sm btn-default btn-primary ml-2" style="font-size: 14px; line-height: 2rem;"  align="right" @click="saveNotes()">
                            <span>
                              Ok
                            </span>
                        </button>
                    </div>

                </div>
            </div>
        </modal>

        <div class="flex border-b border-40" >
            <div class="w-1/4 px-8 py-6">

            </div>
            <div class="py-6 px-8 w-3/4 flex justify-between">
                <div>

                </div>

                <div class="flex" v-show="not_tracking && is_admin">
                    <label style="margin: 10px 0px 5px 16px; font-weight: 800px">
                        <span style="margin-top: 15px"> Not Tracking </span>
                    </label>
                    <label class="switch" style="zoom: 0.75 !important; margin: 10px 20px 5px 13px;">
                        <input type="checkbox" v-model="not_tracking" @change="notTracking">
                        <span class="slider round"></span>
                    </label>
                </div>
                <!---->
            </div>
        </div>
        <section v-show="not_tracking" class="flex justify-center">
            <div class="container p-8 text-90 m-6" style="border: 1px solid #eee; width: 800px">
                <div>
                    <div class="py-2 text-2xl">We are not manually Tracking this Shipment.</div>
                    <div class="py-1" style="font-size: 18px">Here’s Why,</div>
                </div>
                <div class="py-6 ml-8">
                    <div v-for="note in not_tracking_notes_logs" class="border-b border-40 py-4">
                          <div class="py-1" style="font-size: 17px; white-space: pre-line"> {{ note.note || '__' }}</div>
                          <div class="mt-2 text-right" style="font-size: 14.5px; font-weight: bold">	&#8618; Halted manual tracking at {{ note.written_at }} - <a :href="'mailto:' + note.written_by"> {{ note.written_by }} </a> </div>
                    </div>
                </div>
            </div>
        </section>

        <section v-show="!not_tracking">
            <div class="flex border-b border-40" v-if='is_tracking_shipment'>
                <div class="w-1/4 px-8 py-6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Customer
                    </label>
                </div>
                <div class="py-6 px-8 w-3/4">
                    <div class="pb-2">
                        <div class="cursor-pointer dim inline-block text-primary font-bold" @click="goToCustomerView(customer.id)"> {{ customer.company_name }} </div> <br>
                    </div>
                    <li v-for="email in customer.emails" >
                        {{ email.email }}
                    </li>

                    <div v-if="!customer.emails || customer.emails == 0"> __ </div>

                    <!---->
                </div>
            </div>
            <div class="flex border-b border-40">
                <div class="w-1/4 px-8 py-6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Reasons
                    </label>
                </div>
                <div class="py-6 px-8 w-3/4">
                    <li v-if="reasons.is_empty_mbl" >
                        MBL is Empty
                    </li>
                    <li v-if="reasons.is_lcl_type" >
                        Type is LCL
                    </li>
                    <li v-if="reasons.is_rail_shipment" >
                        Shipment is a Rail Shipment
                    </li>
                    <li v-if="reasons.is_tracking_shipment" >
                        Shipment is a Tracking Shipment
                    </li>
                    <li v-if="!reasons.is_tracking_with_t49" >
                        Not Tracking with Terminal49
                    </li>
                    <div v-if="reasons.length == 0"> __ </div>

                    <!---->
                </div>
            </div>

            <div class="flex border-b border-40">
                <div class="w-1/4 px-8 py-6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Type
                    </label>
                </div>
                <div class="py-6 px-8 w-3/4">
                    <input type="text" disabled  class="w-full form-control form-input form-input-bordered" v-model="type" v-if="type">
                    <div v-if="!type" style="color: rgb(239 68 68)">
                      Please note, due to the fact that this shipment does not have the type specified - we cannot display all the fields that must be tracked. Please Find out what this shipment's type is and Update it
                    </div>
                    <!---->
                </div>
            </div>

            <div class="flex border-b border-40">
                <div class="w-1/4 px-8 py-6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Last Updated At
                    </label>
                </div>
                <div class="py-6 px-8 w-3/4">
                    <input type="text" disabled  class="w-full form-control form-input form-input-bordered" v-model="last_updated_at" >
                    <!---->
                </div>
            </div>

            <div class="flex border-b border-40">
                <div class="w-1/4 px-8 py-6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Last Updated By
                    </label>
                </div>
                <div class="py-6 px-8 w-3/4">
                    <input type="text" disabled  class="w-full form-control form-input form-input-bordered" v-model="last_updated_by" >
                    <!---->
                </div>
            </div>

            <div class="flex border-b border-40">
                <div class="w-1/4 px-8 py-6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Terminal
                    </label>
                </div>
                <div class="py-6 px-8 w-3/4">
                    <input type="text" disabled  class="w-full form-control form-input form-input-bordered" v-model="terminal" >
                    <!---->
                </div>
            </div>

            <div class="flex border-b border-40">
                <div class="w-1/4 px-8 py-6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Container Count
                    </label>
                </div>
                <div class="py-6 px-8 w-3/4">
                    <div class="pt-2">
                        {{ isEmpty(container_count) ? '__' : container_count }}
                    </div>
                </div>
            </div>
            <div class="flex border-b border-40">
                <div class="w-1/4 px-8 py-6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Notes
                    </label>
                </div>
                <div class="py-6 px-8 w-3/4">
                    <div class="pb-2" style="white-space: pre-line">
                        {{ notes }}
                    </div>
                </div>
            </div>

            <div class="flex border-b border-40">
                <div class="w-1/4 px-8 py-6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        ETD
                    </label>
                </div>
                <div class="py-6 px-8 w-3/4">
                    <div class="pt-2">
                        {{ isEmpty(etd) ? '__' : formatDate(etd) }}
                    </div>
                </div>
            </div>

            <div class="flex border-b border-40">
                <div class="w-1/4 px-8 py-6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        ETA
                    </label>
                </div>
                <div class="py-6 px-8 w-3/4">
                    <div class="pt-2">
                        {{ isEmpty(eta) ? '__' : formatDate(eta) }}
                    </div>
                </div>
            </div>



            <div class="flex mt-4" v-if="type != 'Air' && type">
                <div class="w-1/4 px-8 py-8">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Containers
                    </label>
                </div>
                <div class="w-3/4 px-8 py-4">
                    <div class="border-b border-400 mb-5" v-if="containers && containers.length > 0" v-for="container in containers">

                        <div class="py-4">
                            <label style="font-weight: bold;">Container </label>
                        </div>
                        <div class="flex border-b border-40">
                            <div class="w-1/4 py-4 ">
                                <h4 class="font-normal text-80">Container Num#</h4>
                            </div>
                            <div class="w-3/4 py-4 break-words">
                                <p class="text-90">
                                    {{ isEmpty(container.container_num) ? '__' : container.container_num}}
                                </p>
                            </div>
                        </div>


                        <div class="flex border-b border-40">
                            <div class="w-1/4 py-4 ">
                                <h4 class="font-normal text-80">Empty out</h4>
                            </div>
                            <div class="w-3/4 py-4 break-words">
                                <p class="text-90">
                                    {{ isEmpty(container.empty_out) ? '__' : container.empty_out}}
                                </p>
                            </div>
                        </div>


                        <div class="flex border-b border-40">
                            <div class="w-1/4 py-4 ">
                                <h4 class="font-normal text-80">Full In</h4>
                            </div>
                            <div class="w-3/4 py-4 break-words">
                                <p class="text-90">
                                    {{ isEmpty(container.pod_full_in_at) ? '__' : container.pod_full_in_at}}
                                </p>
                            </div>
                        </div>

                        <div class="flex border-b border-40">
                            <div class="w-1/4 py-4 ">
                                <h4 class="font-normal text-80">Vessel Loaded</h4>
                            </div>
                            <div class="w-3/4 py-4 break-words">
                                <p class="text-90">
                                    {{ isEmpty(container.vessel_loaded) ? '__' : container.vessel_loaded}}
                                </p>
                            </div>
                        </div>

                        <div class="flex border-b border-40" v-if="type == 'FCL'">
                            <div class="w-1/4 py-4 ">
                                <h4 class="font-normal text-80">Discharged</h4>
                            </div>
                            <div class="w-3/4 py-4 break-words">
                                <p class="text-90">
                                    {{ isEmpty(container.pod_discharged_at) ? '__' : container.pod_discharged_at}}
                                </p>
                            </div>
                        </div>

                        <div class="flex border-b border-40" v-if="type == 'FCL'">
                            <div class="w-1/4 py-4 ">
                                <h4 class="font-normal text-80">Last Free Day</h4>
                            </div>
                            <div class="w-3/4 py-4 break-words">
                                <p class="text-90">
                                    {{ isEmpty(container.pickup_lfd) ? '__' : container.pickup_lfd}}
                                </p>
                            </div>
                        </div>

                        <div class="flex border-b border-40" v-if="type == 'FCL'">
                            <div class="w-1/4 py-4 ">
                                <h4 class="font-normal text-80">Full Out</h4>
                            </div>
                            <div class="w-3/4 py-4 break-words">
                                <p class="text-90">
                                    {{ isEmpty(container.pod_full_out_at) ? '__' : container.pod_full_out_at}}
                                </p>
                            </div>
                        </div>

                        <div class="flex border-b border-40" v-if="type == 'FCL'">
                            <div class="w-1/4 py-4 ">
                                <h4 class="font-normal text-80">Empty In</h4>
                            </div>
                            <div class="w-3/4 py-4 break-words">
                                <p class="text-90">
                                    {{ isEmpty(container.pod_empty_returned_at) ? '__' : container.pod_empty_returned_at}}
                                </p>
                            </div>
                        </div>



                        <div class="flex border-b border-20" v-if="type == 'FCL'">
                            <div class="w-1/4 py-4 ">
                                <h4 class="font-normal text-80">Holds at POD Terminal</h4>
                            </div>
                            <div class="w-3/4 py-4 break-words">

                                <div v-if="!isEmpty(container.holds_at_pod_terminal)">
                                    <div v-for="hold in container.holds_at_pod_terminal" class="holds-at-pod-terminal">
                                        <div>
                                            <label> Type </label>
                                            <label> {{ isEmpty(hold.name) ? '__' : hold.name}} </label>
                                        </div>
                                        <div>
                                            <label> Status </label>
                                            <label> {{ isEmpty(hold.status) ? '__' : hold.status}} </label>
                                        </div>
                                        <div>
                                            <label> Description </label>
                                            <label> {{ isEmpty(hold.description) ? '__' : hold.description}} </label>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    N/A
                                </div>
                            </div>
                        </div>

                        <div class="flex border-b border-20" v-if="type == 'FCL'">
                            <div class="w-1/4 py-4 ">
                                <h4 class="font-normal text-80">Fees at POD Terminal</h4>
                            </div>
                            <div class="w-3/4 py-4 break-words">

                                <div v-if="!isEmpty(container.fees_at_pod_terminal)">
                                    <div v-for="fee in container.fees_at_pod_terminal" class="holds-at-pod-terminal">
                                        <div>
                                            <label> Type </label>
                                            <label> {{ isEmpty(fee.type) ? '__' : fee.type}} </label>
                                        </div>
                                        <div>
                                            <label> Amount </label>
                                            <label> {{ isEmpty(fee.amount) ? '__' : fee.amount}} </label>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    N/A
                                </div>
                            </div>
                        </div>

                    </div>

                    <div v-if="!(containers && containers.length > 0)" class="pt-3"> No containers Available </div>

                </div>


            </div>

            <div class="flex border-b border-40" v-if="type != 'FCL' && type">
                <div class="w-1/4 px-8 py-6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Available
                    </label>
                </div>
                <div class="py-6 px-8 w-3/4">
                    <div class="pt-2">
                        {{ isEmpty(available) ? '__' : formatDate(available) }}
                    </div>
                </div>
            </div>


            <div class="flex border-b border-40" v-if="type != 'FCL' && type">
                <div class="w-1/4 px-8 py-6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Last Free Day
                    </label>
                </div>
                <div class="py-6 px-8 w-3/4">
                    <div class="pt-2">
                        {{ isEmpty(lfd) ? '__' : formatDate(lfd) }}
                    </div>
                </div>
            </div>

            <div class="flex border-b border-40" v-if="type != 'FCL' && type">
                <div class="w-1/4 px-8 py-6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Picked Up
                    </label>
                </div>
                <div class="py-6 px-8 w-3/4">
                    <div class="pt-2">
                        {{ isEmpty(picked_up) ? '__' : formatDate(picked_up) }}
                    </div>
                </div>
            </div>
        </section>
    </card>

    <card v-else class="flex border-b border-40 p-8">
        <loader class="text-60 text-center" />
    </card>
</div>
</template>

<script>

import axios from 'axios'
import _ from "lodash";
import moment from 'moment-timezone'

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data() {
        return {
            etd: null,
            eta: null,
            container_count: null,
            containers: {},
            pickerFormat: 'Y-m-d',
            errorMessages: {},
            loading: false,
            shifl_ref: '',
            picked_up: null,
            available: null,
            lfd: null,
            type: null,
            is_tracking_shipment: false,
            last_updated_at: '',
            last_updated_by: '--',
            terminal: '--',
            notes: '__',
            reasons: [],
            customer: null,
            showBox: false,
            not_tracking_notes: '',
            not_tracking_notes_logs: [],
            not_tracking: false,
            logged_in_user: null,
            showConfirmationBox: false,
            is_admin: false
        }
    },
    mounted() {
        //
    },
    created() {
        this.loading = true
        axios.get("/nova-vendor/untracked-shipments/shipments/" + this.$route.params.resourceId)
            .then(res => {
                // console.log(res.data)

                let cgu = res.data.containers_group_untracked // cgu - containers_group_untracked
                let count = 0
                try {
                    count = cgu.containers.length
                } catch (e) {
                    console.log(e)
                }


                this.containers = res.data.containers_group_bookings.map((container, key) => {
                    // console.log(key)
                    return {
                        container_num: container.container_num,
                        empty_out: cgu.containers && count > key ? cgu.containers[key].empty_out : null,
                        pod_full_in_at: cgu.containers && count > key ? cgu.containers[key].pod_full_in_at : null,
                        vessel_loaded: cgu.containers && count > key ? cgu.containers[key].vessel_loaded : null,
                        pod_discharged_at: cgu.containers && count > key ? cgu.containers[key].pod_discharged_at : null,
                        pickup_lfd: cgu.containers && count > key ? cgu.containers[key].pickup_lfd : null,
                        pod_full_out_at: cgu.containers && count > key ? cgu.containers[key].pod_full_out_at : null,
                        pod_empty_returned_at: cgu.containers && count > key ? cgu.containers[key].pod_empty_returned_at : null,
                        holds_at_pod_terminal: cgu.containers && count > key ? cgu.containers[key].holds_at_pod_terminal : [],
                        fees_at_pod_terminal: cgu.containers && count > key ? cgu.containers[key].fees_at_pod_terminal : []
                    }
                })
                this.etd = res.data.etd
                this.eta = res.data.eta
                this.shifl_ref = res.data.shifl_ref
                this.container_count = cgu.container_count
                this.notes = cgu.notes ? cgu.notes : '__'
                this.loading = false
                this.available = cgu.available || null
                this.lfd = cgu.lfd || null
                this.picked_up = cgu.picked_up || null
                this.not_tracking_notes_logs = cgu.not_tracking_notes_logs || []
                this.type = res.data.type
                this.is_tracking_shipment = res.data.is_tracking_shipment
                this.last_updated_at = res.data.untracked_tool_last_updated_at
                this.last_updated_by = res.data.untracked_tool_last_updated_by ? res.data.untracked_tool_last_updated_by.email : '--'
                this.terminal = res.data.terminal ? res.data.terminal.name : '--'
                this.reasons = res.data.reasons
                this.customer = res.data.customer
                this.not_tracking = res.data.not_tracking
                this.logged_in_user = res.data.logged_in_user
                this.is_admin = res.data.is_admin
            })
            .catch(err => {
                this.loading = false
                console.log(err)
            })
    },
    methods: {
        goToCustomerView(id){
          window.open(window.location.origin + '/administrator/resources/customers/' + id, '_blank')
        },
        goToEditView() {
            window.open(window.location.origin + `/administrator/untracked-shipments/${this.$route.params.resourceId}`, '_blank')
        },
        isEmpty(data) {
            return _.isEmpty(data)
        },
        formatDate(date) {
            // return (new)
            return new Date(date).toLocaleDateString('en-US')
        },
        notTracking(e){
            // make the shipment not tracking
            if(e.target.checked)
                this.showBox = true
            else
                this.showConfirmationBox = true
        },
        saveNotes(){

            let data = {
                containers_group_untracked: {
                    containers: this.containers,
                    container_count: this.container_count,
                    available: this.available,
                    lfd: this.lfd,
                    picked_up: this.picked_up,
                    notes: this.notes == '__' ? '' : this.notes,
                    not_tracking_notes_logs: this.not_tracking_notes_logs,
                },
                not_tracking : this.not_tracking
            }


            axios.post("/nova-vendor/untracked-shipments/shipments/" + this.$route.params.resourceId + '/save-notes', data)
                .then(res => {
                    if(res.status == 200)
                        if(this.not_tracking)
                            Nova.warning(
                                'Shipment is Marked as "Not Tracking"!'
                            )
                        else{
                            Nova.success(
                                'Successfully Unmarked!'
                            )
                            this.not_tracking_notes = ''
                        }

                        this.showBox = false
                }).catch(err => {
                    Nova.error(
                        'Something went wrong! Please reload and try again later.'
                    )
                    this.showBox = false
                })
        },
        handleUndoNotTrackingConfirmation(){
            this.showConfirmationBox = false
            this.saveNotes();
        }
    }
}
</script>

<style>
/* Scoped Styles */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #ffcc00;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
