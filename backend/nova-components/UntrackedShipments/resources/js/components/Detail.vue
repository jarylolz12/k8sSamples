<template>
<div>
    <div class="flex justify-between	">
        <heading class="mb-6">Manual Tracking Shipment Details | Shifl Ref# {{ shifl_ref }} | {{ is_tracking_shipment ? 'External' : 'Internal' }}</heading>
        <button @click="goToEditView" class="btn btn-default btn-icon bg-primary" data-testid="edit-resource" dusk="edit-resource-button" title="Edit">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current text-white" style="margin-top: -2px; margin-left: 3px;">
                <path
                  d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z">
                </path>
            </svg>
        </button>
    </div>
    <card v-if="!loading" style="zoom: 0.9">

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

                <div class="flex" v-show="!not_tracking">
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
                        <div class="flex border-b border-40">
                            <div class="w-1/4 py-4 ">
                                <h4 class="font-normal text-80">ETD ({{ getTerminalById(selected_schedule ? selected_schedule.location_from : null).name }})</h4>
                            </div>
                            <div class="w-3/4 py-4 break-words">
                                <p class="text-90">
                                    {{ isEmpty(etd) ? '__' : formatDate(etd) }}
                                </p>
                            </div>
                        </div>
                        <div class="flex border-b border-40">
                            <div class="w-1/4 py-4 ">
                                <h4 class="font-normal text-80">ETA ({{ getTerminalById(selected_schedule ? selected_schedule.location_to : null).name }})</h4>
                            </div>
                            <div class="w-3/4 py-4 break-words">
                                <p class="text-90">
                                    {{ isEmpty(eta) ? '__' : formatDate(eta) }}
                                </p>
                            </div>
                        </div>
                        <section v-for="transShipment in transShipments"  :key="Date()">
                            <div class="flex border-b border-40">
                                <div class="w-1/4 py-4 ">
                                    <h4 class="font-normal text-80">ETD ({{ getTerminalById(transShipment.location_from).name }})</h4>
                                </div>
                                <div class="w-3/4 py-4 break-words">
                                    <p class="text-90">
                                        {{ isEmpty(transShipment.etd) ? '__' : formatDate(transShipment.etd) }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex border-b border-40" >
                                <div class="w-1/4 py-4 ">
                                    <h4 class="font-normal text-80">ETA ({{ getTerminalById(transShipment.location_to).name }})</h4>
                                </div>
                                <div class="w-3/4 py-4 break-words">
                                    <p class="text-90">
                                        {{ isEmpty(transShipment.eta) ? '__' : formatDate(transShipment.eta) }}
                                    </p>
                                </div>
                            </div>
                        </section>

                        <div class="flex border-b border-40" v-if="type == 'FCL'">
                            <div class="w-1/4 py-4 ">
                                <h4 class="font-normal text-80">Available For Pickup</h4>
                            </div>
                            <div class="w-3/4 py-4 break-words">
                                <p class="text-90">
                                    {{ isEmpty(container.available_for_pickup) ? '__' : container.available_for_pickup}}
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
import 'vue-good-table/dist/vue-good-table.css'
import {
    VueGoodTable
} from 'vue-good-table'
import moment from 'moment-timezone'
import axios from 'axios'
import _ from "lodash";

export default {
    metaInfo() {
        return {
            title: 'UntrackedShipments',
        }
    },
    // add to component
    components: {
        VueGoodTable,
    },
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
            terminalRegions: [],
            selected_schedule: {},
            transShipments: []
        }
    },
    mounted() {
        //
        this.setTerminalRegions()
    },
    created() {
        this.loading = true
        axios.get("/nova-vendor/untracked-shipments/shipments/" + this.$route.params.id)
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
                        available_for_pickup: cgu.containers && count > key ? cgu.containers[key].available_for_pickup === true || cgu.containers[key].available_for_pickup === false ? '' : cgu.containers[key].available_for_pickup : null,
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
                this.selected_schedule = this.getSelectedSchedule(res.data.schedules_group_bookings)
                this.checkForTransShipment()
            })
            .catch(err => {
                this.loading = false
                console.log(err)
            })

    },
    methods: {
        checkForTransShipment(){
            if(!_.isEmpty(this.selected_schedule) && this.selected_schedule.mode == 'Ocean' && !_.isEmpty(this.selected_schedule.legs) && this.selected_schedule.legs.length > 0){
                let transShipments = []
                for(let i =0; i<this.selected_schedule.legs.length; i++){
                    if(this.selected_schedule.legs[i].mode != 'Ocean') return
                    try {
                      if(i > 0 && _.isEmpty(this.selected_schedule.legs[i].location_from) && !_.isEmpty(this.selected_schedule.legs[i-1].location_to))
                        this.selected_schedule.legs[i].location_from = this.selected_schedule.legs[i-1].location_to
                      else if(i == 0 && _.isEmpty(this.selected_schedule.legs[i].location_from) && !_.isEmpty(this.selected_schedule.location_to))
                        this.selected_schedule.legs[i].location_from = this.selected_schedule.location_to
                    } catch (e) {
                      console.log(e)
                    }

                    transShipments.push({
                        eta: this.selected_schedule.legs[i].eta,
                        etd: this.selected_schedule.legs[i].etd,
                        location_from: this.selected_schedule.legs[i].location_from,
                        location_to: this.selected_schedule.legs[i].location_to,
                    })
                }
                this.transShipments = transShipments
            }
        },
        getTerminalById(id){
            if(id){
                let terminal = this.terminalRegions.find(e => e.id == id)
                if(terminal) return terminal;
            }
            return { name: ' __ ' }
        },
        getSelectedSchedule(schedules){
            if(!schedules || schedules.length == 0) return {}
            for(let i = 0; i< schedules.length; i++){
                if(schedules[i].is_confirmed) return schedules[i];
            }
            return {}
        },
        setTerminalRegions() {
            //get all terminal regions
            fetch(`/custom/terminal-regions`, {
                token: this.token
            })
            .then(this.handleResponse)
            .then(response => {
                let { results } = response;
                if (typeof results !== "undefined") {
                    let newTerminalRegions = [];

                    for (var x = 0; x < results.length; x++) {
                        newTerminalRegions.push({
                            name: results[x].name,
                            id: results[x].id
                        });
                    }
                    this.terminalRegions = newTerminalRegions;
                }
            });
        },
        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text);
                return data;
            });
        },
        goToCustomerView(id){
          window.open(window.location.origin + '/administrator/resources/customers/' + id, '_blank')
        },
        goToEditView() {
            this.$router.push(`/untracked-shipments/${this.$route.params.id}/edit`)
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
                this.saveNotes()
        },
        saveNotes(){
            if(this.showBox){
                this.not_tracking_notes_logs.push({
                    note: this.not_tracking_notes,
                    written_by : this.logged_in_user,
                    written_at : moment().tz('America/New_York').format('L LT')
                })
            }

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


            axios.post("/nova-vendor/untracked-shipments/shipments/" + this.$route.params.id + '/save-notes', data)
                .then(res => {
                    if(res.status == 200)
                        if(this.not_tracking){
                            Nova.warning(
                                'Shipment is Marked as "Not Tracking"!'
                            )
                            this.$router.push('/untracked-shipments/')
                        }
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
