<template>
    <div>
        <scac-list-table :rows="scacList" @toggleScacModal="toggleScacModal" v-if="showScacModal"> </scac-list-table>

        <div v-if="(!loading && !data_available) || !field.value">
            <div class="flex border-b border-40 justify-center">
                <div class="py-4 break-words" style="max-width: 650px;">
                    <div class="text-xl py-2 font-bold text-90">We are not able to Connect to Shifl Automatic Tracking</div>
                    <div class="py-2">This could be because the MBL inputted is incorrect or because we do not support the carrier of this shipment. </div>
                    <p class="text-90 py-2">
                        These are the Carrier we Support.
                        <span class="px-2" style="cursor: pointer; color: rgb(41, 153, 241)" @click="toggleScacModal">
                            Supported Carriers

                        </span>
                       
                    </p>

                    <button
                        class="btn btn-primary p-3"
                        @click.prevent="addThisShipment"
                        :disabled="isLoading"
                     >
                      Add Shipment to Our Automated Tracking
                    </button>
                </div>

            </div>
        </div>

        <div v-if="loading">
            <div class="flex border-b border-40">
                <loader class="text-60 text-center" />
            </div>
        </div>

        <div v-if="!loading && data_available">    

              <div class="flex border-b border-40 ">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Bill of Lading Number</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words flex justify-between">
                      <p class="text-90">
                          {{ isEmpty(attributes.bill_of_lading_number) ? "N/A" : attributes.bill_of_lading_number  }}
                          <span class="px-2" style="cursor: pointer; color: rgb(41, 153, 241)" @click="addAll" v-if="field.defaultFields.loginEmail === 'mary@shifl.com'">
                            Add All Shipments 
                        </span>
                      </p>
                  </div>
              </div>


              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Shipping Line SCAC</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.shipping_line_scac) ? "N/A" : attributes.shipping_line_scac  }}
                      </p>
                  </div>
              </div>

              
              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Shipping Line Name</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.shipping_line_name) ? "N/A" : attributes.shipping_line_name  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Shipping Line Short Name</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.shipping_line_short_name) ? "N/A" : attributes.shipping_line_short_name  }}
                      </p>
                  </div>
              </div>


              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Created At</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.created_at) ? "N/A" : formatDate(attributes.created_at)  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Port of Lading Locode</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.port_of_lading_locode) ? "N/A" : attributes.port_of_lading_locode  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Port of Lading Name</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.port_of_lading_name) ? "N/A" : attributes.port_of_lading_name  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Port of Discharge Locode</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.port_of_discharge_locode) ? "N/A" : attributes.port_of_discharge_locode  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Port of Discharge Name</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.port_of_discharge_name) ? "N/A" : attributes.port_of_discharge_name  }}
                      </p>
                  </div>
              </div>


              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">POD Terminal Name</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.pod_terminal_name) ? "N/A" : attributes.pod_terminal_name  }}
                      </p>
                  </div>
              </div>


              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">POD Terminal Frims Code</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.pod_terminal_frims_code) ? "N/A" : attributes.pod_terminal_frims_code  }}
                      </p>
                  </div>
              </div>


              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Destination Terminal Name</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.destination_terminal_name) ? "N/A" : attributes.destination_terminal_name  }}
                      </p>
                  </div>
              </div>


              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Destination Terminal Firms Code</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.destination_terminal_frims_code) ? "N/A" : attributes.destination_terminal_frims_code  }}
                      </p>
                  </div>
              </div>


              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">POD Vessel Name</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.pod_vessel_name) ? "N/A" : attributes.pod_vessel_name  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">POD Vessel IMO</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.pod_vessel_imo) ? "N/A" : attributes.pod_vessel_imo  }}
                          <a v-if="!isEmpty(attributes.pod_vessel_imo)" class="no-underline  ml-2 hover:underline font-bold" style="color: #2999F1" target="_blank" :href="'https://www.vesselfinder.com/?imo='+ attributes.pod_vessel_imo">
                              AIS
                          </a>
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">POD Voyage Number</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.pod_voyage_number) ? "N/A" : attributes.pod_voyage_number  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Destination Locode</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.destination_locode) ? "N/A" : attributes.destination_locode  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Destination Name</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.destination_name) ? "N/A" : attributes.destination_name  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Destination Timezone</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.destination_timezone) ? "N/A" : attributes.destination_timezone  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Destination ATA at</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.destination_ata_at) ? "N/A" : formatDate(attributes.destination_ata_at)  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Destination ETA at</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.destination_eta_at) ? "N/A" : formatDate(attributes.destination_eta_at)  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Port of Lading ETD at</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.pol_etd_at) ? "N/A" : formatDate(attributes.pol_etd_at)  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Port of Lading ATD at</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.pol_atd_at) ? "N/A" : formatDate(attributes.pol_atd_at)  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Port of Lading Timezone</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.pol_timezone) ? "N/A" : attributes.pol_timezone  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">POD ETA at</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.pod_eta_at) ? "N/A" : formatDate(attributes.pod_eta_at)  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">POD ATA at</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.pod_ata_at) ? "N/A" : formatDate(attributes.pod_ata_at)  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">POD Timezone</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.pod_timezone) ? "N/A" : attributes.pod_timezone  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Line tracking last attempted at</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.line_tracking_last_attempted_at) ? "N/A" : formatDate(attributes.line_tracking_last_attempted_at)  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Line tracking last succeeded at</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.line_tracking_last_succeeded_at) ? "N/A" : formatDate(attributes.line_tracking_last_succeeded_at)  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Line tracking stopped at</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.line_tracking_stopped_at) ? "N/A" : formatDate(attributes.line_tracking_stopped_at)  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Line tracking stopped reason</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.line_tracking_stopped_reason) ? "N/A" : attributes.line_tracking_stopped_reason  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Port of Lading</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.port_of_lading) ? "N/A" : attributes.port_of_lading  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Port of Discharge</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.port_of_discharge) ? "N/A" : attributes.port_of_discharge  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">POD Terminal</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.pod_terminal) ? "N/A" : attributes.pod_terminal  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Destination</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.destination) ? "N/A" : attributes.destination  }}
                      </p>
                  </div>
              </div>

              <div class="flex border-b border-40">
                  <div class="w-1/4 py-4 ">
                      <h4 class="font-normal text-80">Terminal Destination</h4>
                  </div>
                  <div class="w-3/4 py-4 break-words">
                      <p class="text-90">
                          {{ isEmpty(attributes.destination_terminal) ? "N/A" : attributes.destination_terminal  }}
                      </p>
                  </div>
              </div>
              <!-- ///////// -->
             
              <!-- //////// -->
              <div class="flex border-t border-400 mt-4">
                    <div class="py-4">
                        <label style="font-weight: bold;">Containers Section </label>
                    </div>
              </div>
            
            <div class="mb-5" v-for="container in containers">
              <div class="flex flex-wrap">
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Container Num#</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90" style="font-weight: bold;">
                                    {{ isEmpty(container.number) ? "N/A" : container.number  }}
                                </p>

                            </div>
                            
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Picked Up At</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.picked_up_at) ? "N/A" : formatDate(container.picked_up_at)  }}
                                </p>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="flex flex-wrap">
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Delivered At</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.delivered_at) ? "N/A" : formatDate(container.delivered_at)  }}
                                </p>

                            </div>
                            
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Seal Number</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.seal_number) ? "N/A" : container.seal_number  }}
                                </p>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap">
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Chassis Number</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.chassis_number) ? "N/A" : container.chassis_number  }}
                                </p>

                            </div>
                            
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Equipment Height</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.equipment_height) ? "N/A" : container.equipment_height  }}
                                </p>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap">
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Equipment Length</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.equipment_length) ? "N/A" : container.equipment_length  }}
                                </p>

                            </div>
                            
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Equipment Type</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.equipment_type) ? "N/A" : container.equipment_type  }}
                                </p>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="flex flex-wrap">
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Current Status</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.current_status) ? "N/A" : container.current_status  }}
                                </p>

                            </div>
                            
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Current Status At</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.current_status_at) ? "N/A" : formatDate(container.current_status_at)  }}
                                </p>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="flex flex-wrap">
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Current Ref. Number</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.customer_ref_number) ? "N/A" : container.customer_ref_number  }}
                                </p>

                            </div>
                            
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Delivery Appointment At</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.delivery_appointment_at) ? "N/A" : formatDate(container.delivery_appointment_at)  }}
                                </p>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="flex flex-wrap">
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Delivery Appointment On</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.delivery_appointment_on) ? "N/A" : formatDate(container.delivery_appointment_on)  }}
                                </p>

                            </div>
                            
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Drayed By Shifl</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.drayed_by_shifl) ? "N/A" : container.drayed_by_shifl  }}
                                </p>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="flex flex-wrap">
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Empty Returned At</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.empty_returned_at) ? "N/A" : formatDate(container.empty_returned_at)  }}
                                </p>

                            </div>
                            
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Empty Pickdrop Location</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.empty_pickdrop_location) ? "N/A" : container.empty_pickdrop_location  }}
                                </p>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="flex flex-wrap">
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Last Status Refresh At</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.last_status_refresh_at) ? "N/A" : formatDate(container.last_status_refresh_at)  }}
                                </p>

                            </div>
                            
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Notes</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.notes) ? "N/A" : formatDate(container.notes)  }}
                                </p>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="flex flex-wrap">
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Tare Weight</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.tare_weight) ? "N/A" : container.tare_weight  }}
                                </p>

                            </div>
                            
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Weight</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.weight) ? "N/A" : container.weight  }}
                                </p>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="flex flex-wrap">
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Pickup Appointment At</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.pickup_appointment_at) ? "N/A" : formatDate(container.pickup_appointment_at)  }}
                                </p>

                            </div>
                            
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="flex border-b border-40">
                            <div class="w-3/4 py-4 ">
                                <h4 class="font-normal text-80">Pickup Appointment On</h4>
                            </div>
                            <div class="w-3/4 py-4 flex justify-between mr-4">
                                <p class="text-90">
                                    {{ isEmpty(container.pickup_appointment_on) ? "N/A" : formatDate(container.pickup_appointment_on)  }}
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="w-4/4 py-4 mb-5">
                    <section>
                        <div class="w-1/4 py-5">
                            <h4 class="font-bold text-80">Cargo Shipments Events</h4>
                        </div>
                        <!--  -->
                        <!-- This example requires Tailwind CSS v2.0+ -->
                        <div class="flex flex-col py-8">
                            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50 border-b border-grey-400">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider	">
                                                        Event
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                                        Voyage
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                                        Location
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                                        Actual At
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                                        Estimated
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                                        Estimated At
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200" v-if="container.cargo_shipment_events && Object.keys(container.cargo_shipment_events).length > 0">
                                                <tr v-for="event in container.cargo_shipment_events">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ event.event || '__'}}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ event.voyage_num || '__'}}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ event.location || '__'}}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ event.actual_at || '__' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ event.estimated || '__' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ event.estimated_at || '__' }}
                                                    </td>

                                                </tr>

                                                <!-- More people... -->
                                            </tbody>
                                        </table>

                                        <div v-if="isEmpty(container.cargo_shipment_events)" class="text-center" style="padding: 5px">
                                            <div v-if="milestones_loading">
                                                <!-- Fetching Raw Milestones Data... -->
                                                <loader class="text-60" />

                                            </div>
                                            <div v-else>
                                                No Cargo Shipment Data available
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </section>
                </div>
            </div>



        </div>
      
    </div>
</template>

<script>
import _ from "lodash";
import ScacListTable  from "./modals/ScacListTable.vue";

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    components: {
        ScacListTable
    },
    data() {
        return {
            loading: false,
            milestones_loading: false,
            attributes: {},
            containers: {},
            showScacModal: false,
            scacList: {},
            isLoading: false,
            isLoadingAdd: false,
            centralUrl: 'https://us-central1-fifth-compiler-357712.cloudfunctions.net/nbl_function2/api',
            page: 1,
            isLast: false,
            shipmentData: {
                data: []
            },
        }
    },
    computed: {
        data_available() {
            return !(_.isEmpty(this.attributes))
        },
        token() {
            return document.querySelector('meta[name="csrf-token"]').content

        }
    },
    async created(){
        if(!this.isEmpty(this.field.value)){
            this.loading = true;
            await axios.get(`${this.centralUrl}/v3/shipments/?bl=`+this.field.value)
                .then(async res => {
                    if(res.status === 200){
                        this.attributes = res.data.data.attributes
                        this.containers = res.data.data.relationships.containers.data
                        this.updateShipment(1, res.data.id)
                    }

                    this.loading = false;

                }).catch(err => {
                    this.loading = false
                    console.log(err)
                })
        }
        await axios.get(`${this.centralUrl}/v2/tracking_requests/shipping_lines_trackable`)
            .then(async res => {
                if(res.status === 200){
                    this.scacList = res.data
                }

                this.loading = false;

            }).catch(err => {
                this.loading = false
                console.log(err)
            })
    },  
    methods: {
        isEmpty(data) {
            return _.isEmpty(data)
        },
        formatDate(date) {
            return new Date(date).toDateString()
        },
        toggleScacModal(){
            this.showScacModal = !this.showScacModal;
        },
        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text)
                return data
            })
        },
        updateShipment(id, requestId){
            try {
                this.isLoadingAdd = true
                
                const params = {
                    requestId: requestId,
                    id: id
                };
                const { data } = Nova.request().post(
                    `/custom/our-automated-tracking/update/${this.field.defaultFields.id}`,
                    params
                );
                if(typeof data !== 'undefined'){
                    console.log('update-shipment ', data);
                }
            } catch (error) {
                console.log('error ', error)
            }
        },
        async addThisShipment(){

        let data = {
            request_number: this.field.defaultFields.mbl_num,
            scac: this.field.defaultFields.scac,
            request_type: 'bill_of_lading'
        }

        await fetch(`${this.centralUrl}/v2/tracking_requests`, {
                method: "POST",
                headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                },
                body: JSON.stringify(data),
            })
            .then(this.handleResponse)
            .then(response => {
                
                if ( typeof response !=='undefined' && typeof response.errors!=='undefined' ) {
                    let getKeys = Object.keys(response.errors)
                    getKeys.map(k =>{
                        if( typeof response.errors[k] !== 'undefined' && typeof response.errors[k].code !== 'undefined'){
                            let code = response.errors[k].code
                            if(code == 'duplicate'){
                                this.updateShipment(2, code);
                                this.$toasted.show(response.errors[k].detail, {
                                    type: "success"
                                });
                            }else{
                                this.$toasted.show(response.errors[k].detail, {
                                    type: "error"
                                });
                            }
                            console.log('getkeys', response);
                        }  

                    })
                }
                
                if ( typeof response !=='undefined' && typeof response.status!=='undefined' ) {
                    if(response.status === 'pending' || response.status === 'created'){
                        this.updateShipment(1, response.id);
                        this.$toasted.show('This shipment is already added on our automated tracking with a status:'+response.status, {
                                type: "success"
                        });
                        location.reload(true);
                    }
                }
            
            }).catch(e => {
                console.log('e', e)
            })    

        },
        async addAll(){
            try {
                this.isLoadingAdd = true
                
                const { data } = await Nova.request().get(
                    `/custom/our-automated-tracking?page=${this.page}&isLast=${this.isLast}`
                );
                console.log('addAll:data', data)

                if(typeof data !== 'undefined'){

                    if(data.next_page_url !== null){
                       this.page++;
                       this.addAll();
                    }
                    if(this.page == data.last_page - 1){
                        this.isLast = true
                    }  

                    if(this.page == data.last_page){
                        this.isLoading = false
                        this.isLast = false
                    }
                    
                }
                if(typeof data.status !== 'undefined'){
                    console.log('addAll:status' , data.status)    
                }
                
            } catch (error) {
                console.log('error ', error)
            }
        },

    }
}
</script>
