<template>
<!-- <panel-item :field="field" /> -->
<div>
    <scac-list-table :rows="field.scacList" @toggleScacModal="toggleScacModal" v-if="showScacModal"> </scac-list-table>

    <div v-if="(!loading && !data_available) || !field.value">

        <div class="flex border-b border-40 justify-center">

            <div class=" py-4 break-words" style="max-width: 650px;">
                <div class="text-xl py-2 font-bold text-90"> We are not able to Connect to T49 Tracking </div>
                <div class="py-2"> This could be bceasue the MBL inputted is incorrect or because we do not support the carrier of this shipment. </div>
                <p class="text-90 py-2">
                    These are the Carrier we Support.
                    <span class=" px-2" style="cursor: pointer; color: rgb(41, 153, 241)" @click="toggleScacModal">
                        Supported Carriers
                    </span>
                </p>
            </div>
        </div>
    </div>
    <div v-if="loading">

        <div class="flex border-b border-40">
            <!-- <div class="w-1/4 py-4 ">

                <h4 class="font-normal text-80"> </h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                <p class="text-90">
                    Fetching Shipment's data ...

                </p>
            </div> -->
            <loader class="text-60 text-center" />
        </div>
    </div>

    <div v-if="!loading && data_available">

        <div class="flex border-b border-40 ">
            <div class="w-1/4 py-4 ">
                <h4 class="font-normal text-80">BOL Num#</h4>
            </div>
            <div class="w-3/4 py-4 break-words flex justify-between">
                <p class="text-90">
                    {{ isEmpty(attributes.bill_of_lading_number) ? "N/A" : attributes.bill_of_lading_number  }}
                </p>
                <button class="btn btn-default bg-primary text-white btn-icon" @click="resync">
                    <span v-if="!is_resyncing">
                        Resync
                    </span>
                    <span v-else>
                        <loader class="text-60 text-white" />
                    </span>
                </button>
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/4 py-4 flex">
                <h4 class="font-normal text-80">SCAC</h4>
                <span class=" px-2" style="cursor: pointer" @click="toggleScacModal">
                  <svg  fill="rgb(41, 153, 241)" viewBox="0 0 24 24" width="20px" height="20px">
                    <path d="M 12 2 C 6.4889971 2 2 6.4889971 2 12 C 2 17.511003 6.4889971 22 12 22 C 17.511003 22 22 17.511003 22 12 C 22 6.4889971 17.511003 2 12 2 z M 12 4 C 16.430123 4 20 7.5698774 20 12 C 20 16.430123 16.430123 20 12 20 C 7.5698774 20 4 16.430123 4 12 C 4 7.5698774 7.5698774 4 12 4 z M 11 7 L 11 9 L 13 9 L 13 7 L 11 7 z M 11 11 L 11 17 L 13 17 L 13 11 L 11 11 z"/>
                  </svg>
                </span>
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
                <h4 class="font-normal text-80">POL Locode</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                <p class="text-90">
                    {{ isEmpty(attributes.port_of_lading_locode) ? "N/A" : attributes.port_of_lading_locode  }}
                </p>
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/4 py-4 ">
                <h4 class="font-normal text-80">POL Name</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                <p class="text-90">
                    {{ isEmpty(attributes.port_of_lading_name) ? "N/A" : attributes.port_of_lading_name  }}
                </p>
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/4 py-4 ">
                <h4 class="font-normal text-80">POD Locode</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                <p class="text-90">
                    {{ isEmpty(attributes.port_of_discharge_locode) ? "N/A" : attributes.port_of_discharge_locode  }}
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
                <h4 class="font-normal text-80">Destination ATA</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                <p class="text-90">
                    {{ isEmpty(attributes.destination_ata_at) ? "N/A" : formatDate(attributes.destination_ata_at)  }}
                </p>
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/4 py-4 ">
                <h4 class="font-normal text-80">Destination ETA</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                <p class="text-90">
                    {{ isEmpty(attributes.destination_eta_at) ? "N/A" : formatDate(attributes.destination_eta_at)  }}
                </p>
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/4 py-4 ">
                <h4 class="font-normal text-80">POL ETD</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                <p class="text-90">
                    {{ isEmpty(attributes.pol_etd_at) ? "N/A" : formatDate(attributes.pol_etd_at)  }}
                </p>
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/4 py-4 ">
                <h4 class="font-normal text-80">POL ATD</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                <p class="text-90">
                    {{ isEmpty(attributes.pol_atd_at) ? "N/A" : formatDate(attributes.pol_atd_at)  }}
                </p>
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/4 py-4 ">
                <h4 class="font-normal text-80">POL Timezone</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                <p class="text-90">
                    {{ isEmpty(attributes.pol_timezone) ? "N/A" : attributes.pol_timezone  }}
                </p>
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/4 py-4 ">
                <h4 class="font-normal text-80">POD ETA</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                <p class="text-90">
                    {{ isEmpty(attributes.pod_eta_at) ? "N/A" : formatDate(attributes.pod_eta_at)  }}
                </p>
            </div>
        </div>

        <div class="flex border-b border-40">
            <div class="w-1/4 py-4 ">
                <h4 class="font-normal text-80">POD ATA</h4>
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
                <h4 class="font-normal text-80">Line tracking last attemted at</h4>
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
        <!-- ///////// -->
        <section class="flex border-t border-400 mt-4">
            <div class="w-1/4 py-8">
                <h4 class="font-bold text-80">Eta Changes Log</h4>
            </div>
            <div class=" py-4">
                <div class=" mb-5" v-if="etaLogs && Object.keys(etaLogs).length > 0">
                    <div class="flex mt-4" v-for="eta in etaLogs">
                        <div class="flex">

                            <span class="pr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>

                            <h4 class=" font-normal text-80 mt-1">Eta Was updated from - {{ formatDate(eta.old_eta) || "Null" }} to - {{ formatDate(eta.eta) || "Null" }} ~ at {{ formatTimestamp(eta.created_at) }}</h4>

                        </div>
                    </div>
                </div>

                <div class=" mb-4" v-else>

                    <div class="mt-4">
                        <span class="pr-3" v-if="!etaLogs_loading">
                            No Eta change Logs available yet.
                        </span>
                        <span class="pr-3" v-else>
                            <loader class="text-60" />
                        </span>
                    </div>


                </div>
            </div>

        </section>

        <section class="flex border-t border-400 mt-4">
            <div class="w-1/4 py-8">
                <h4 class="font-bold text-80">Change Log</h4>
            </div>
            <div class="flex flex-col w-3/4">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 border-b border-grey-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider	">
                                            Field Name
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                            Container No#
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                            Old Value
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                            Current Value
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                            Changed At
                                        </th>

                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" v-if="changelogs && Object.keys(changelogs).length > 0">
                                    <tr v-for="changelog in changelogs">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ changelog.field_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ changelog.container_num }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" >
                                            {{ ['available_for_pickup'].includes(changelog.field_name) ? (changelog.boolval ? 'No' : 'Yes') : '__' }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ ['available_for_pickup'].includes(changelog.field_name) ? (changelog.boolval ? 'Yes' : 'No') : '__' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ changelog.created_at }}
                                        </td>

                                    </tr>

                                    <!-- More people... -->
                                </tbody>
                            </table>

                            <div v-if="isEmpty(changelogs)" class="text-center" style="padding: 5px">
                                <div v-if="changelogs_loading">
                                    <!-- Fetching Standard Milestones Data... -->
                                    <loader class="text-60" />
                                </div>
                                <div v-else>
                                    No Changelogs Available
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </section>
        <!-- //////// -->
        <div class="flex border-t border-400 mt-4">
            <div class="w-1/4 py-8">
                <h4 class="font-bold text-80">Containers</h4>
            </div>
            <div class="w-3/4 py-4">
                <div class="border-b border-400 mb-5" v-for="container in containers">
                    <div class="py-4">
                        <label style="font-weight: bold;">Container </label>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">Container Num#</h4>
                        </div>
                        <div class="w-3/4 py-4 flex justify-between mr-4">
                            <p class="text-90">
                                {{ isEmpty(container.attributes.number) ? "N/A" : container.attributes.number  }}

                            </p>
                            <div class="flex border-b border-40" v-if="field.is_admin">
                              <div class="px-4">
                                <label for="so_released" class="inline-block text-800 pt-2 leading-tight ">
                                    Ignore Demurrage
                                </label>
                              </div>
                              <div>
                                <input  type="checkbox" class="checkbox mt-2 mr-4" :checked="ignoreDemurrageCheckBox(container.attributes.number)" @change="ignoreDemurrage(container.attributes.number)" style="cursor: pointer" />
                              </div>
                            </div>

                        </div>
                    </div>


                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">Seal Number</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words">
                            <p class="text-90">
                                {{ isEmpty(container.attributes.seal_number) ? "N/A" : container.attributes.seal_number  }}

                            </p>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">Created At</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words">
                            <p class="text-90">
                                {{ isEmpty(container.attributes.created_at) ? "N/A" : formatDate(container.attributes.created_at)  }}

                            </p>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">Equipment Type</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words">
                            <p class="text-90">
                                {{ isEmpty(container.attributes.equipment_type) ? "N/A" : container.attributes.equipment_type  }}

                            </p>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">Equipment length</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words">
                            <p class="text-90">
                                {{ isEmpty(container.attributes.equipment_length) ? "N/A" : container.attributes.equipment_length  }}

                            </p>
                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class=" w-1/4 py-4 ">
                            <h4 class="font-normal text-80">Equipment Height</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words ">
                            <p class="text-90 ">
                                {{ isEmpty(container.attributes.equipment_height) ? "N/A" : container.attributes.equipment_height  }}

                            </p>
                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">Weight in lbs</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words">
                            <p class="text-90">
                                {{ isEmpty(container.attributes.weight_in_lbs) ? "N/A" : container.attributes.weight_in_lbs  }}

                            </p>
                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">Fees at POD Terminal</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words">

                            <div v-if="!isEmpty(container.attributes.fees_at_pod_terminal)">
                                <div v-for="fee in container.attributes.fees_at_pod_terminal" class="holds-at-pod-terminal">
                                    <div>
                                        <label> Type </label>
                                        <label> {{ fee.type }} </label>
                                    </div>
                                    <div>
                                        <label> Amount </label>
                                        <label> {{ fee.amount }} </label>
                                    </div>

                                </div>
                            </div>
                            <div v-else>
                                N/A
                            </div>

                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">Holds at POD Terminal</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words">

                            <div v-if="!isEmpty(container.attributes.holds_at_pod_terminal)">
                                <div v-for="hold in container.attributes.holds_at_pod_terminal" class="holds-at-pod-terminal">
                                    <div>
                                        <label> Type </label>
                                        <label> {{ hold.name }} </label>
                                    </div>
                                    <div>
                                        <label> Status </label>
                                        <label> {{ hold.status }} </label>
                                    </div>
                                    <div>
                                        <label> Description </label>
                                        <label> {{ hold.description }} </label>
                                    </div>

                                </div>
                            </div>
                            <div v-else>
                                N/A
                            </div>




                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">Pickup LFD</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words">
                            <p class="text-90">
                                {{ isEmpty(container.attributes.pickup_lfd) ? "N/A" : container.attributes.pickup_lfd  }}

                            </p>
                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">Pickup Appointment at</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words">
                            <p class="text-90">
                                {{ isEmpty(container.attributes.pickup_appointment_at) ? "N/A" : formatDate(container.attributes.pickup_appointment_at)  }}

                            </p>
                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">Availability Known</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words">
                            <p class="text-90">
                                {{ isEmpty(container.attributes.availability_known) ? "N/A" : container.attributes.availability_known  }}

                            </p>
                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">Available for pickup</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words">
                            <p class="text-90">
                                {{ isEmpty(container.attributes.available_for_pickup) ? "N/A" : container.attributes.available_for_pickup  }}

                            </p>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">POD arrived at</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words">
                            <p class="text-90">
                                {{ isEmpty(container.attributes.pod_arrived_at) ? "N/A" : formatDate(container.attributes.pod_arrived_at)  }}

                            </p>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">POD discharged at</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words">
                            <p class="text-90">
                                {{ isEmpty(container.attributes.pod_discharged_at) ? "N/A" : formatDate(container.attributes.pod_discharged_at)  }}

                            </p>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">Final destination full out at</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words">
                            <p class="text-90">
                                {{ isEmpty(container.attributes.final_destination_full_out_at) ? "N/A" : formatDate(container.attributes.final_destination_full_out_at)  }}

                            </p>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">POD full out at</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words">
                            <p class="text-90">
                                {{ isEmpty(container.attributes.pod_full_out_at) ? "N/A" : formatDate(container.attributes.pod_full_out_at)  }}

                            </p>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/4 py-4 ">
                            <h4 class="font-normal text-80">Empty terminated at</h4>
                        </div>
                        <div class="w-3/4 py-4 break-words">
                            <p class="text-90">
                                {{ isEmpty(container.attributes.empty_terminated_at) ? "N/A" : formatDate(container.attributes.empty_terminated_at)  }}

                            </p>
                        </div>
                    </div>



                    <section>
                        <div class="w-1/4 py-8">
                            <h4 class="font-bold text-80">POD Terminal</h4>
                        </div>

                        <div v-if="terminals[container.id]">
                            <div class="flex border-b border-40">
                                <div class="w-1/4 py-4 ">
                                    <h4 class="font-normal text-80">NickName</h4>
                                </div>
                                <div class="w-3/4 py-4 break-words">
                                    <p class="text-90">
                                        {{ terminals[container.id].attributes.nickname || "__" }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex border-b border-40">
                                <div class="w-1/4 py-4 ">
                                    <h4 class="font-normal text-80">Firms Code</h4>
                                </div>
                                <div class="w-3/4 py-4 break-words">
                                    <p class="text-90">
                                        {{ terminals[container.id].attributes.firms_code || "__" }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex border-b border-40">
                                <div class="w-1/4 py-4 ">
                                    <h4 class="font-normal text-80">Name</h4>
                                </div>
                                <div class="w-3/4 py-4 break-words">
                                    <p class="text-90">
                                        {{ terminals[container.id].attributes.name || "__" }}
                                    </p>
                                </div>
                            </div>

                        </div>
                        <div v-else class="text-center" style="padding: 5px">
                            <div v-if="terminal_loading">
                                <loader class="text-60" />
                            </div>
                            <div v-else>
                                No Terminal Data avilable.
                            </div>
                        </div>

                        <!--  -->
                    </section>




                    <section>
                        <div class="w-1/4 py-8">
                            <h4 class="font-bold text-80">Milestones (STANDARD)</h4>
                        </div>
                        <!--  -->
                        <!-- This example requires Tailwind CSS v2.0+ -->
                        <div class="flex flex-col">
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
                                                        Locode
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                                        Voyage
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                                        Date
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                                        Timezone
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200" v-if="standard_events && Object.keys(standard_events).length > 0">
                                                <tr v-for="standard_event in standard_events[container.id].data">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ standard_event.attributes.event ? standard_event.attributes.event.replace("container.transport.","") : '__'}}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ standard_event.attributes.location_locode || '__'}}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ standard_event.attributes.voyage_number || '__'}}
                                                    </td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ standard_event.attributes.timestamp || '__' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ standard_event.attributes.timezone || '__' }}
                                                    </td>

                                                </tr>

                                                <!-- More people... -->
                                            </tbody>
                                        </table>

                                        <div v-if="isEmpty(standard_events)" class="text-center" style="padding: 5px">
                                            <div v-if="standard_events_loading">
                                                <!-- Fetching Standard Milestones Data... -->
                                                <loader class="text-60" />
                                            </div>
                                            <div v-else>
                                                No Standard Milestone Data avilable
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--  -->
                    </section>

                    <section>
                        <div class="w-1/4 py-8">
                            <h4 class="font-bold text-80">Milestones (RAW)</h4>
                        </div>
                        <!--  -->
                        <!-- This example requires Tailwind CSS v2.0+ -->
                        <div class="flex flex-col">
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
                                                        Details
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                                        Vessel
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                                        IMO
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                                        Voyage
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                                        Location
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                                        Locode
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                                        Date
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold	 text-gray-500 uppercase tracking-wider">
                                                        Estimated
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200" v-if="raw_events && Object.keys(raw_events).length > 0">
                                                <tr v-for="raw_event in raw_events[container.id].data">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ raw_event.attributes.event || '__'}}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ raw_event.attributes.original_event || '__'}}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ raw_event.attributes.vessel_name || '__'}}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ raw_event.attributes.vessel_imo || '__'}}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ raw_event.attributes.voyage_number || '__'}}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ raw_event.attributes.location_name || '__'}}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ raw_event.attributes.location_locode || '__'}}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ raw_event.attributes.actual_on || raw_event.attributes.actual_at || '__' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ raw_event.attributes.estimated_at || '__' }}
                                                    </td>

                                                </tr>

                                                <!-- More people... -->
                                            </tbody>
                                        </table>

                                        <div v-if="isEmpty(raw_events)" class="text-center" style="padding: 5px">
                                            <div v-if="milestones_loading">
                                                <!-- Fetching Raw Milestones Data... -->
                                                <loader class="text-60" />

                                            </div>
                                            <div v-else>
                                                No Raw Milestone Data avilable
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--  -->
                    </section>




                </div>
            </div>

        </div>


    </div>

</div>
</template>

<script>
import axios from 'axios'
import _ from "lodash";
import ScacListTable from './modals/ScacListTable.vue'

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    components: {
        ScacListTable
    },
    data() {
        return {
            loading: false,
            milestones_loading: false,
            standard_events_loading: false,
            terminal_loading: false,
            etaLogs_loading: false,
            changelogs_loading: false,
            is_resyncing: false,
            attributes: {},
            containers: {},
            raw_events: {},
            standard_events: {},
            terminals: {},
            etaLogs: {},
            changelogs: {},
            ignore_demurrage: {},
            showScacModal: false

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
    async created() {
        // show the loading text
        // console.log(this.field)
        if (!this.isEmpty(this.field.value)) {
            this.loading = true;

            await axios.get('/custom-api/shipments/status/terminal49/' + this.field.value)
                .then(async res => {
                    if (res.status === 200) {
                        this.attributes = JSON.parse(res.data.attributes)
                        this.containers = JSON.parse(res.data.containers)
                        this.terminals = JSON.parse(res.data.terminals)
                        this.ignore_demurrage = JSON.parse(res.data.ignore_demurrage || '{}')
                        // console.log(this.ignore_demurrage)
                    }
                    // console.log(this.attributes)
                    // hide the loading text
                    this.loading = false

                    var container_ids = [];

                    this.containers.map(el => {
                        container_ids.push(el.id)
                    })

                    if (!_.isEmpty(container_ids)) {

                        this.milestones_loading = true
                        this.standard_events_loading = true

                        //
                        if (_.isEmpty(this.etaLogs)) {
                            this.etaLogs_loading = true
                            // eta change logs
                            await axios.get("/custom-api/shipments/status/eta_logs/" + this.field.value)
                                .then(res => {
                                    this.etaLogs = res.data
                                    this.etaLogs_loading = false
                                    // console.log(this.etaLogs);
                                }).catch(err => {
                                    this.etaLogs_loading = false
                                    console.log(err)
                                })
                        }

                        //
                        if (_.isEmpty(this.changelogs)) {
                            this.changelogs_loading = true
                            // eta change logs
                            await axios.get("/custom-api/shipments/status/changelogs/" + this.field.value)
                                .then(res => {
                                    this.changelogs = res.data
                                    this.changelogs_loading = false
                                    // console.log(this.changelogs);
                                }).catch(err => {
                                    this.changelogs_loading = false
                                    console.log(err)
                                })
                        }

                        if (_.isEmpty(this.terminals)) {
                            this.terminal_loading = true;
                            // fetch terminal data
                            await axios.post('/custom-api/shipments/status/terminal49/terminals', {
                                    token: this.token,
                                    mbl_num: this.field.value
                                })
                                .then(res => {
                                    this.terminals = res.data
                                    this.terminal_loading = false

                                    // console.log("terminals", this.terminals);
                                }).catch(err => {
                                    this.terminal_loading = false
                                    console.log(err);
                                })
                        }

                        // fetch standard events
                        await axios.post('/custom-api/shipments/status/terminal49/standard_events', {
                                token: this.token,
                                container_ids: container_ids
                            })
                            .then(res => {
                                this.standard_events = res.data
                                this.standard_events_loading = false

                                // console.log("standard", this.standard_events);
                            }).catch(err => {
                                this.milestones_loading = false
                                console.log(err);
                            })

                        // fetch raw events
                        await axios.post('/custom-api/shipments/status/terminal49/raw_events', {
                                token: this.token,
                                container_ids: container_ids
                            })
                            .then(res => {

                                this.raw_events = res.data

                                this.milestones_loading = false

                                // console.log(this.raw_events)

                            }).catch(err => {
                                this.milestones_loading = false
                                console.log(err)
                            })



                    }



                }).catch(err => {
                    this.loading = false
                    console.log(err)
                })
        }


    },
    methods: {
        isEmpty(data) {
            return _.isEmpty(data) && typeof data != 'boolean'
        },
        formatDate(date) {
            // return (new)
            return new Date(date).toDateString()
        },
        async resync() {
            this.is_resyncing = true


            await axios.post('/custom-api/shipments/status/terminal49/resync', {
                    token: this.token,
                    mbl_num: this.field.value
                })
                .then(async (res) => {
                    Nova.success(
                        this.__("Resync Successful!", {
                            resource: this.resourceId
                        })
                    );
                    await this.loadData()
                    this.is_resyncing = false
                })
                .catch(err => {
                    Nova.error(
                        this.__("Resync Failed!", {
                            resource: this.resourceId
                        })
                    );
                    this.is_resyncing = false
                    console.log(err)

                })

        },
        async loadData() {

            if (!this.isEmpty(this.field.value)) {
                this.loading = true;

                await axios.get('/custom-api/shipments/status/terminal49/' + this.field.value)
                    .then(async res => {
                        if (res.status === 200)  {

                            this.attributes = JSON.parse(res.data.attributes)
                            this.containers = JSON.parse(res.data.containers)
                            this.terminals = JSON.parse(res.data.terminals)
                            this.ignore_demurrage = JSON.parse(res.data.ignore_demurrage || '{}')

                            // console.log(res.data)
                        }
                        // console.log(this.attributes)
                        // hide the loading text
                        this.loading = false

                        var container_ids = [];

                        this.containers.map(el => {
                            container_ids.push(el.id)
                        })

                        if (!_.isEmpty(container_ids)) {

                            this.milestones_loading = true
                            this.standard_events_loading = true

                            //
                            if (_.isEmpty(this.etaLogs)) {
                                this.etaLogs_loading = true
                                // eta change logs
                                await axios.get("/custom-api/shipments/status/eta_logs/" + this.field.value)
                                    .then(res => {
                                        this.etaLogs = res.data
                                        this.etaLogs_loading = false
                                        // console.log(this.etaLogs);
                                    }).catch(err => {
                                        this.etaLogs_loading = false
                                        console.log(err)
                                    })
                            }

                            if (_.isEmpty(this.terminals)) {
                                this.terminal_loading = true;
                                // fetch terminal data
                                await axios.post('/custom-api/shipments/status/terminal49/terminals', {
                                        token: this.token,
                                        mbl_num: this.field.value
                                    })
                                    .then(res => {
                                        this.terminals = res.data
                                        this.terminal_loading = false

                                        // console.log("terminals", this.terminals);
                                    }).catch(err => {
                                        this.terminal_loading = false
                                        console.log(err);
                                    })
                            }

                            // fetch standard events
                            await axios.post('/custom-api/shipments/status/terminal49/standard_events', {
                                    token: this.token,
                                    container_ids: container_ids
                                })
                                .then(res => {
                                    this.standard_events = res.data
                                    this.standard_events_loading = false

                                    // console.log("standard", this.standard_events);
                                }).catch(err => {
                                    this.milestones_loading = false
                                    console.log(err);
                                })

                            // fetch raw events
                            await axios.post('/custom-api/shipments/status/terminal49/raw_events', {
                                    token: this.token,
                                    container_ids: container_ids
                                })
                                .then(res => {

                                    this.raw_events = res.data

                                    this.milestones_loading = false

                                    // console.log(this.raw_events)

                                }).catch(err => {
                                    this.milestones_loading = false
                                    console.log(err)
                                })



                        }



                    }).catch(err => {
                        this.loading = false
                        console.log(err)
                    })
            }


        },

        formatTimestamp(timestamp) {
            return new Date(timestamp).toLocaleString()
        },
        ignoreDemurrage(number){
            this.ignore_demurrage[number] = !this.ignore_demurrage[number]
             axios.post('/custom-api/shipments/status/terminal49/'+ this.field.value + '/ignore_demurrage', {
                token: this.token,
                ignore_demurrage: JSON.stringify(this.ignore_demurrage)
            }).then(res => {
                if(res.status == 200){
                    this.loadData()
                }
            }).catch(err => {
                console.log(err)
            })
        },
        ignoreDemurrageCheckBox(number){
            return this.ignore_demurrage[number] || false
        },
        toggleScacModal(){
            this.showScacModal = !this.showScacModal;
        }


    }
}
</script>
