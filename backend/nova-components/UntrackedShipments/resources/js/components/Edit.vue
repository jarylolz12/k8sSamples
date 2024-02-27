<template>
<div>
    <heading class="mb-6">Manual Tracking Shipment Details | Shifl Ref# {{ shifl_ref }} | {{ is_tracking_shipment ? 'External' : 'Internal' }}</heading>

    <card v-if="!loading" style="zoom: 0.9">

        <div class="flex border-b border-40" v-if='is_tracking_shipment'>
            <div class="w-1/5 px-8 py-6">
                <label class="inline-block text-80 pt-2 leading-tight">
                    Customer
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
              <div class="pb-2">
                  <div class="cursor-pointer dim inline-block text-primary font-bold" @click="goToCustomerView( customer ? customer.id : null)"> {{ customer ? customer.company_name : '__' }} </div> <br>
              </div>
              <li v-for="email in customer.emails" >
                  {{ email.email }}
              </li>

              <div v-if="!customer || !customer.emails || customer.emails == 0"> __ </div>

                <!---->
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/5 px-8 py-6">
                <label class="inline-block text-80 pt-2 leading-tight">
                    Reasons
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
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
            <div class="w-1/5 px-8 py-6">
                <label class="inline-block text-80 pt-2 leading-tight">
                    Type
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
                <input type="text" disabled  class="w-full form-control form-input form-input-bordered" v-model="type" v-if="type">
                <div v-if="!type" style="color: rgb(239 68 68)">
                  Please note, due to the fact that this shipment does not have the type specified - we cannot display all the fields that must be tracked. Please Find out what this shipment's type is and Update it
                </div>
                <!---->
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/5 px-8 py-6">
                <label class="inline-block text-80 pt-2 leading-tight">
                    Last Updated At
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
                <input type="text" disabled  class="w-full form-control form-input form-input-bordered" v-model="last_updated_at">
                <!---->
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/5 px-8 py-6">
                <label class="inline-block text-80 pt-2 leading-tight">
                    Last Updated By
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
                <input type="text" disabled  class="w-full form-control form-input form-input-bordered" v-model="last_updated_by">
                <!---->
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/5 px-8 py-6">
                <label class="inline-block text-80 pt-2 leading-tight">
                    Terminal
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
                <input type="text" disabled  class="w-full form-control form-input form-input-bordered" v-model="terminal">
                <!---->
            </div>
        </div>


        <div class="flex border-b border-40">
            <div class="w-1/5 px-8 py-6">
                <label class="inline-block text-80 pt-2 leading-tight">
                    Container count
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
                <input type="number" placeholder="Container Count" min="0" class="w-full form-control form-input form-input-bordered" v-model="container_count">
                <!---->
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/5 px-8 py-6">
                <label class="inline-block text-80 pt-2 leading-tight">
                    Notes
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
                <!---->
                <textarea rows="5" placeholder="Notes" class="w-full form-control form-input form-input-bordered py-3 h-auto " v-model="notes" spellcheck="false"></textarea>
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/5 px-8 py-6">

                <label class="inline-block text-80 pt-2 leading-tight">
                    ETD
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
                <date-time-picker class="w-full form-control form-input form-input-bordered" :value="etd" @change="value => { handleChange(value,'etd')}" :dateFormat="pickerFormat" placeholder="ETD" :enable-time="false" :enable-seconds="false"
                  :class="`${ (typeof errorMessages.etd!='undefined') ? 'form-input-bordered border-danger' : ''}`" />
                <div v-show="(typeof errorMessages.etd!='undefined')" class="help-text error-text text-danger mt-2">
                    {{errorMessages['etd']}}
                </div>
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/5 px-8 py-6">

                <label class="inline-block text-80 pt-2 leading-tight">
                    ETA
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
                <date-time-picker class="w-full form-control form-input form-input-bordered" :value="eta" @change="value => { handleChange(value,'eta')}" :dateFormat="pickerFormat" placeholder="ETA" :enable-time="false" :enable-seconds="false"
                  :class="`${ (typeof errorMessages.eta!='undefined') ? 'form-input-bordered border-danger' : ''}`" />
                <div v-show="(typeof errorMessages.eta!='undefined')" class="help-text error-text text-danger mt-2">
                    {{errorMessages['eta']}}
                </div>
            </div>
        </div>




        <div class="flex border-b border-40" v-if="type != 'Air' && type" >
            <div class="w-1/5 px-8 py-6">
                <label class="inline-block text-80 pt-2 leading-tight">
                    Containers
                </label>
            </div>
            <!-- // containers -->
            <div class="py-6 px-8 w-4/5" >
                <div v-if="containers && containers.length > 0" class="border-b border-50" v-for="container,key in containers" :key="container.id">

                    <div class="flex border-b border-40">
                        <div class="w-1/5 px-8 py-6">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                Container#
                            </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="text" placeholder="Container#" :value="container.container_num" disabled class="w-full form-control form-input form-input-bordered">
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/5 px-8 py-6">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                Empty Out
                            </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <date-time-picker class="w-full form-control form-input form-input-bordered" :value="container.empty_out" @change="value => {handleContainerChange(value,key,'empty_out')}" :dateFormat="pickerFormat" placeholder="Empty out"
                              :enable-time="false" :enable-seconds="false" />
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/5 px-8 py-6">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                Full in
                            </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <date-time-picker class="w-full form-control form-input form-input-bordered" :value="container.pod_full_in_at" @change="value => {handleContainerChange(value,key,'pod_full_in_at')}" :dateFormat="pickerFormat"
                              placeholder="Full In" :enable-time="false" :enable-seconds="false" />
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/5 px-8 py-6">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                  Vessel Loaded
                            </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <date-time-picker class="w-full form-control form-input form-input-bordered" :value="container.vessel_loaded" @change="value => {handleContainerChange(value,key,'vessel_loaded')}" :dateFormat="pickerFormat"
                              placeholder="Vessel Loaded" :enable-time="false" :enable-seconds="false" />
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/5 px-8 py-6">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                  ETD ({{ getTerminalById(selected_schedule ? selected_schedule.location_from : null).name }})
                            </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                          <date-time-picker class="w-full form-control form-input form-input-bordered" :value="etd" @change="value => { handleChange(value,'etd')}" :dateFormat="pickerFormat" placeholder="ETD" :enable-time="false" :enable-seconds="false"
                            :class="`${ (typeof errorMessages.etd!='undefined') ? 'form-input-bordered border-danger' : ''}`" />
                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/5 px-8 py-6">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                  ETA ({{ getTerminalById(selected_schedule ? selected_schedule.location_to : null).name }})
                            </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                          <date-time-picker class="w-full form-control form-input form-input-bordered" :value="eta" @change="value => { handleChange(value,'eta')}" :dateFormat="pickerFormat" placeholder="ETA" :enable-time="false" :enable-seconds="false"
                            :class="`${ (typeof errorMessages.eta!='undefined') ? 'form-input-bordered border-danger' : ''}`" />
                        </div>
                    </div>

                    <section v-for="transShipment in transShipments"  :key="Date()">
                        <div class="flex border-b border-40">
                            <div class="w-1/5 px-8 py-6">
                                <label class="inline-block text-80 pt-2 leading-tight">
                                      ETD ({{ getTerminalById(transShipment.location_from).name }})
                                </label>
                            </div>
                            <div class="py-6 px-8 w-1/2">
                              <date-time-picker class="w-full form-control form-input form-input-bordered" :value="transShipment.etd"  :dateFormat="pickerFormat" placeholder="ETD" disabled/>
                            </div>
                        </div>
                        <div class="flex border-b border-40">
                            <div class="w-1/5 px-8 py-6">
                                <label class="inline-block text-80 pt-2 leading-tight">
                                      ETA ({{ getTerminalById(transShipment.location_to).name }})
                                </label>
                            </div>
                            <div class="py-6 px-8 w-1/2">
                              <date-time-picker class="w-full form-control form-input form-input-bordered" :value="transShipment.eta" :dateFormat="pickerFormat" placeholder="ETA" disabled/>
                            </div>
                        </div>
                    </section>

                    <div class="flex border-b border-40" v-if="type == 'FCL'">
                        <div class="w-1/5 px-8 py-6">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                Discharged
                            </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <date-time-picker class="w-full form-control form-input form-input-bordered" :value="container.pod_discharged_at" @change="value => {handleContainerChange(value,key,'pod_discharged_at')}" :dateFormat="pickerFormat"
                              placeholder="Discharged" :enable-time="false" :enable-seconds="false" />
                        </div>
                    </div>

                    <div class="flex border-b border-40" v-if="type == 'FCL'">
                        <div class="w-1/5 px-8 py-6">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                Available for Pickup
                            </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <date-time-picker class="w-full form-control form-input form-input-bordered" :value="container.available_for_pickup" @change="value => {handleContainerChange(value,key,'available_for_pickup')}" :dateFormat="pickerFormat"
                              placeholder="Last Free Day" :enable-time="false" :enable-seconds="false" />
                        </div>
                    </div>

                    <div class="flex border-b border-40" v-if="type == 'FCL'">
                        <div class="w-1/5 px-8 py-6">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                Last Free Day
                            </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <date-time-picker class="w-full form-control form-input form-input-bordered" :value="container.pickup_lfd" @change="value => {handleContainerChange(value,key,'pickup_lfd')}" :dateFormat="pickerFormat"
                              placeholder="Last Free Day" :enable-time="false" :enable-seconds="false" />
                        </div>
                    </div>

                    <div class="flex border-b border-40" v-if="type == 'FCL'">
                        <div class="w-1/5 px-8 py-6">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                Pickup Appointment At
                            </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <date-time-picker class="w-full form-control form-input form-input-bordered" :value="container.pickup_appointment_at" @change="value => {handleContainerChange(value,key,'pickup_appointment_at')}" :dateFormat="pickerFormat"
                              placeholder="Pickup Appointment At" :enable-time="false" :enable-seconds="false" />
                        </div>
                    </div>

                    <div class="flex border-b border-40" v-if="type == 'FCL'">
                        <div class="w-1/5 px-8 py-6">

                            <label class="inline-block text-80 pt-2 leading-tight">
                                Full Out
                            </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <date-time-picker class="w-full form-control form-input form-input-bordered" :value="container.pod_full_out_at" @change="value => {handleContainerChange(value,key,'pod_full_out_at')}" :dateFormat="pickerFormat"
                              placeholder="Full Out" :enable-time="false" :enable-seconds="false" />
                        </div>
                    </div>

                    <div class="flex border-b border-40" v-if="type == 'FCL'">
                        <div class="w-1/5 px-8 py-6">

                            <label class="inline-block text-80 pt-2 leading-tight">
                                Empty In
                            </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <date-time-picker class="w-full form-control form-input form-input-bordered" :value="container.pod_empty_returned_at" @change="value => {handleContainerChange(value,key,'pod_empty_returned_at')}" :dateFormat="pickerFormat"
                              placeholder="Empty In" :enable-time="false" :enable-seconds="false" />
                        </div>
                    </div>

                    <div class="flex border-b border-40" v-if="type == 'FCL'">
                        <div class="w-1/5 px-8 py-6">

                            <label class="inline-block text-80 pt-2 leading-tight">
                                Holds at Pod Terminal
                            </label>
                        </div>

                        <div class="py-6 px-2 w-1/2" v-if="!container.holds_at_pod_terminal || !(container.holds_at_pod_terminal.length > 0)">
                            <div class=" border-b border-40 mx-4">
                                <div class="py-2 px-4 ">
                                    <button class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" @click="addHolds(key,0)">Add Holds</button>
                                </div>
                            </div>
                        </div>

                        <div v-else class="w-1/2 py-6">
                            <div class="py-2 px-2 w-full" v-for="hold,hold_key in container.holds_at_pod_terminal">
                                <div class=" border-b border-40 mx-4">
                                    <div class="w-1/5 px-4 pb-2">
                                        <label class="inline-block text-80 pt-2 leading-tight">
                                            Type
                                        </label>
                                    </div>
                                    <div class="py-2 px-4 ">
                                        <select class="w-full form-control form-input form-input-bordered" v-model="hold.name">
                                            <option value="customs"> Customs </option>
                                            <option value="freight"> Freight </option>
                                            <option value="USDA"> USDA </option>
                                            <option value="TMF"> TMF </option>
                                            <option value="other"> Other </option>
                                        </select>
                                    </div>
                                </div>

                                <div class=" border-b border-40 mx-4">
                                    <div class="w-1/5 px-4 pb-2">
                                        <label class="inline-block text-80 pt-2 leading-tight">
                                            Status
                                        </label>
                                    </div>
                                    <div class="py-2 px-4 ">
                                        <select class="w-full form-control form-input form-input-bordered" v-model="hold.status">
                                            <option value="pending"> Pending </option>
                                            <option value="hold"> Hold </option>
                                        </select>
                                    </div>
                                </div>

                                <div class=" border-b border-40 mx-4">
                                    <div class="w-1/5 px-4 pb-2">
                                        <label class="inline-block text-80 pt-2 leading-tight">
                                            Description
                                        </label>
                                    </div>
                                    <div class="py-2 px-4 ">
                                        <input type="text" placeholder="Description" class="w-full form-control form-input form-input-bordered" v-model="hold.description" />
                                    </div>
                                </div>

                                <div class=" border-b border-40 mx-4">
                                    <div class="py-2 px-4 ">
                                        <button class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" @click="addHolds(key,hold_key)">Add Holds</button>
                                        <button class="btn btn-default btn-danger inline-flex items-center relative mr-3 shipment-add-group" @click="removeHolds(key,hold_key)">Remove Holds</button>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="flex border-b border-40" v-if="type == 'FCL'">
                        <div class="w-1/5 px-8 py-6">

                            <label class="inline-block text-80 pt-2 leading-tight">
                                Fees at Pod Terminal
                            </label>
                        </div>
                        <div class="py-6 px-2 w-1/2" v-if="!container.fees_at_pod_terminal || !(container.fees_at_pod_terminal.length > 0)">
                            <div class=" border-b border-40 mx-4">
                                <div class="py-2 px-4 ">
                                    <button class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" @click="addFees(key,0)">Add Fees</button>
                                </div>
                            </div>
                        </div>

                        <div v-else class="w-1/2 py-6">
                            <div class="py-6 px-2 w-full" v-for="fee, fee_key in container.fees_at_pod_terminal">

                                <div class=" border-b border-40 mx-4">
                                    <div class="w-1/5 px-4 pb-2">
                                        <label class="inline-block text-80 pt-2 leading-tight">
                                            Type
                                        </label>
                                    </div>
                                    <div class="py-2 px-4 ">
                                        <select class="w-full form-control form-input form-input-bordered" v-model="fee.type">
                                            <option value="demurrage"> Demurrage </option>
                                            <option value="exam"> Exam </option>
                                            <option value="total"> Total </option>
                                            <option value="other"> Other </option>
                                        </select>
                                    </div>
                                </div>

                                <div class=" border-b border-40 mx-4">
                                    <div class="w-1/5 px-4 pb-2">
                                        <label class="inline-block text-80 pt-2 leading-tight">
                                            Amount
                                        </label>
                                    </div>
                                    <div class="py-2 px-4 ">
                                        <input type="Number" placeholder="Amount" class="w-full form-control form-input form-input-bordered" v-model="fee.amount" />
                                    </div>
                                </div>

                                <div class=" border-b border-40 mx-4">
                                    <div class="py-2 px-4 ">
                                        <button class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" @click="addFees(key,fee_key)">Add Fees</button>
                                        <button class="btn btn-default btn-danger inline-flex items-center relative mr-3 shipment-add-group" @click="removeFees(key,fee_key)">Remove Fees</button>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                </div>

                <div v-if="!(containers && containers.length > 0)" class="pt-3"> No containers Available </div>
            </div>


        </div>


        <div class="flex border-b border-40" v-if="type != 'FCL' && type">
            <div class="w-1/5 px-8 py-6">

                <label class="inline-block text-80 pt-2 leading-tight">
                    Available
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
                <date-time-picker class="w-full form-control form-input form-input-bordered" :value="available" @change="value => { handleChange(value,'available')}" :dateFormat="pickerFormat" placeholder="Available" :enable-time="false" :enable-seconds="false"
                  :class="`${ (typeof errorMessages.available!='undefined') ? 'form-input-bordered border-danger' : ''}`" />
                <div v-show="(typeof errorMessages.available!='undefined')" class="help-text error-text text-danger mt-2">
                    {{errorMessages['available']}}
                </div>
            </div>
        </div>

        <div class="flex border-b border-40" v-if="type != 'FCL' && type">
            <div class="w-1/5 px-8 py-6">

                <label class="inline-block text-80 pt-2 leading-tight">
                    Last Free Day
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
                <date-time-picker class="w-full form-control form-input form-input-bordered" :value="lfd" @change="value => { handleChange(value,'lfd')}" :dateFormat="pickerFormat" placeholder="Last Free Day" :enable-time="false" :enable-seconds="false"
                  :class="`${ (typeof errorMessages.lfd!='undefined') ? 'form-input-bordered border-danger' : ''}`" />
                <div v-show="(typeof errorMessages.lfd!='undefined')" class="help-text error-text text-danger mt-2">
                    {{errorMessages['lfd']}}
                </div>
            </div>
        </div>

        <div class="flex border-b border-40" v-if="type != 'FCL' && type">
            <div class="w-1/5 px-8 py-6">

                <label class="inline-block text-80 pt-2 leading-tight">
                    Picked Up
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
                <date-time-picker class="w-full form-control form-input form-input-bordered" :value="picked_up" @change="value => { handleChange(value,'picked_up')}" :dateFormat="pickerFormat" placeholder="Picked Up" :enable-time="false" :enable-seconds="false"
                  :class="`${ (typeof errorMessages.picked_up!='undefined') ? 'form-input-bordered border-danger' : ''}`" />
                <div v-show="(typeof errorMessages.picked_up!='undefined')" class="help-text error-text text-danger mt-2">
                    {{errorMessages['picked_up']}}
                </div>
            </div>
        </div>


        <div class="flex justify-end w-4/5">
              <button class="btn btn-default btn-primary inline-flex items-center relative my-8 mr-2" @click="save(false)">
                  <span>
                      Save & Mark Tracked
                  </span>
              </button>
              <button class="btn btn-default btn-primary inline-flex items-center relative my-8 " @click="save(true)">
                  <span>
                      Save
                  </span>
              </button>
        </div>


    </card>

    <card v-else class="flex border-b border-40 p-8">
        <loader class="text-60 text-center" />
    </card>




</div>
</template>

<script>
import axios from 'axios'
import jQuery from "jquery"

export default {
    // add to component
    components: {},
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
            type: null,
            picked_up: null,
            available: null,
            lfd: null,
            is_tracking_shipment: false,
            last_updated_at: '',
            last_updated_by: '--',
            terminal: '--',
            notes: '',
            reasons: [],
            customer: null,
            not_tracking_notes_logs: {},
            last_marked_not_tracking_at: null,
            not_tracking: false,
            terminalRegions: [],
            selected_schedule: {},
            transShipments: []
        };
    },
    mounted() {
        //
        this.setTerminalRegions()
    },
    computed : {
        token() {
            return jQuery('meta[name="csrf-token"]').attr("content");
        }
    },
    created() {
        this.loading = true
        axios.get("/nova-vendor/untracked-shipments/shipments/" + this.$route.params.id)
            .then(res => {
                // console.log(res.data)

                let cgu = res.data.containers_group_untracked // cgu - containers_group_untracked
                let count = 0;
                try {
                    count = cgu.containers.length
                } catch (e) {
                    console.log(e)
                }

                this.containers = res.data.containers_group_bookings.map((container, key) => {
                    // console.log('hello', cgu)
                    // console.log(key)
                    return {
                        container_num: container.container_num,
                        empty_out: cgu.containers && count > key ? cgu.containers[key].empty_out : null,
                        pod_full_in_at: cgu.containers && count > key ? cgu.containers[key].pod_full_in_at : null,
                        vessel_loaded: cgu.containers && count > key ? cgu.containers[key].vessel_loaded : null,
                        pod_discharged_at: cgu.containers && count > key ? cgu.containers[key].pod_discharged_at : null,
                        available_for_pickup: cgu.containers && count > key ? cgu.containers[key].available_for_pickup === true || cgu.containers[key].available_for_pickup === false ? '' : cgu.containers[key].available_for_pickup : null,
                        pickup_lfd: cgu.containers && count > key ? cgu.containers[key].pickup_lfd : null,
                        pickup_appointment_at: cgu.containers && count > key ? cgu.containers[key].pickup_appointment_at : null,
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
                this.notes = cgu.notes ? cgu.notes : ''
                this.loading = false
                this.type = res.data.type
                this.available = cgu.available || null
                this.lfd = cgu.lfd || null
                this.picked_up = cgu.picked_up || null
                this.not_tracking_notes_logs = cgu.not_tracking_notes_logs || {}
                this.last_marked_not_tracking_at = cgu.last_marked_not_tracking_at || null
                this.is_tracking_shipment = res.data.is_tracking_shipment
                this.last_updated_at = res.data.untracked_tool_last_updated_at
                this.last_updated_by = res.data.untracked_tool_last_updated_by ? res.data.untracked_tool_last_updated_by.email : '--'
                this.terminal = res.data.terminal ? res.data.terminal.name : '--'
                this.reasons = res.data.reasons
                this.customer = res.data.customer
                this.not_tracking = res.data.not_tracking
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
            return { name: '' }
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
          if(id)
            window.open(window.location.origin + '/administrator/resources/customers/' + id, '_blank')
        },
        save(shouldGoTodetail) {
            let data = {
                containers_group_untracked: {
                    containers: this.containers,
                    container_count: this.container_count,
                    available: this.available,
                    lfd: this.lfd,
                    picked_up: this.picked_up,
                    notes: this.notes,
                    not_tracking_notes_logs: this.not_tracking_notes_logs,
                },
                etd: this.etd,
                eta: this.eta,
                markAsTracked: !shouldGoTodetail
            }
            // console.log(data)

            axios.post("/nova-vendor/untracked-shipments/shipments/" + this.$route.params.id, data)
                .then(res => {
                    Nova.success(
                        "Untracked Shipment Saved Successfully!"
                    )
                    if(shouldGoTodetail){
                      this.$router.push('/untracked-shipments/'+ this.$route.params.id)
                    }else{
                      this.$router.push('/untracked-shipments/')
                    }
                }).catch(err => {
                    console.log(err)
                })
        },
        handleContainerChange(value, key, attribute) {
            this.containers[key][attribute] = value
        },
        handleChange(value, field) {
            this[field] = value
        },
        addHolds(key, hold_key) {
            this.containers[key].holds_at_pod_terminal.splice(hold_key + 1, 0, {
                name: 'customs',
                status: 'pending',
                description: null
            })
        },
        removeHolds(key, hold_key) {
            this.containers[key].holds_at_pod_terminal.splice(hold_key, 1)
        },
        addFees(key, fee_key) {
            this.containers[key].fees_at_pod_terminal.splice(fee_key + 1, 0, {
                type: 'demurrage',
                amount: 0,
            })
        },
        removeFees(key, fee_key) {
            this.containers[key].fees_at_pod_terminal.splice(fee_key, 1)
        }
    }
}
</script>

<style>
/* Scoped Styles */
</style>
