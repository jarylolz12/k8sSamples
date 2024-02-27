<template>
	<div class="drayage-desktop-wrapper">
		<!-- <v-toolbar class="header_top" style="background:none !important" flat>
            <v-toolbar-title class="titleDrayage">Drayage</v-toolbar-title>
            <v-spacer></v-spacer>				
		</v-toolbar> -->

		<div class="drayage-desktop-header pb-4">
			<h2 class="drayage-title">Drayage</h2>
		</div>

		<div class="drayage-desktop-body">
			<v-data-table
				:headers="headers"
				:items="dataitemheader"
				class="drayage-table elevation-0"
				v-bind:class="{
                    'no-data-table': (typeof dataitemheader !== 'undefined' && dataitemheader.length === 0),
                }"
				hide-default-footer
				:page.sync="page"
				:items-per-page="itemsPerPage"
				:search="search"
				@page-count="pageCount = $event"
				:loading="false">

				<template v-slot:[`header.terminal.address`]="{ header }">
					{{ header.text }}<i class="classsort"></i>
				</template>

				<template v-slot:[`header.dispatch_schedule_legs`]="{ header }">
					{{ header.text }}<i class="classsort"></i>
				</template>

				<template v-slot:top>
					<v-toolbar height="auto" class="headerTable">
						<v-spacer></v-spacer>
						<div class="drayageDropdownstatus">
							<v-autocomplete
								v-model="inititaldriverSelectFilter"
								:items="dropdowndrayage"
								color="blue"
								item-text="name"
								item-value="id"
								size="14px"
								class="dropdownstatus"
								width="334px"
								hide-details="auto"
								height="40px">

								<template slot="item" slot-scope="{ item }">
									<template v-if="typeof item == 'object'">
										<div 
											class="listDropdown" 
											style="width:100%" 
											v-if="checkifselected(item)">
											<span style="font-size:14px">
												{{ item.name }}
											</span>
											<img
												class="checkdropdown"
												style="width:14px;"
												src="@/assets/icons/CheckMark.svg"
											/>
										</div>
										<div class="listDropdown" v-else>
											{{ item.name }}
										</div>
									</template>
								</template>
							</v-autocomplete>
						</div>

						<div class="searchtab" 
							v-if="(typeof dataitemheader !== 'undefined' && dataitemheader.length > 0)">
							<Search
								width="240px"
								placeholder="Search Drayage"
								className="search"
								:inputData.sync="search" />
						</div>

						<v-btn class="mr-2 btnDownloadreport" color="white" elevation="0" height="40px">
							<img src="@/assets/icons/downloadreport.svg" />
							<span @click="reportdownload" class="downloadReport" style="letter-spacing: 0 !important;">
								Download Report
							</span>
						</v-btn>
					</v-toolbar>
				</template>

				<template v-slot:body="{ items }">
					<tbody v-if="items.length > 0">
						<tr 
                            class="rowclass" 
                            v-for="(itemdata,index)  in items" 
                            :key="index" 
                            @click="rowDetails(itemdata)"
                            >
							<td 
								style="padding-top:12px;" 
								class="referrence"
								>
								<p class="mb-0"> 
									<span 
										v-html="check_ref_containers(itemdata)">
									</span><br /> 
								</p>
							</td>
							<td style="">
								<span class="commonRuleS">
									{{ itemdata.mbl_num }}
								</span>
							</td>
							<td>
								<span class="commonRuleS">
									{{ itemdata.trucker.name }}
								</span>
							</td>
							<td class="sizetops" style="text-align:right">
								<span 
									class="commonRule"
									>
									{{ getsize(itemdata) }}
								</span>
							</td>
							<td class="schedule" style="padding-right: 0px">
								{{ calldata(itemdata, 1) }}
							</td>
							<td class="pickupon" style="padding-right: 0px">
								{{ calldata(itemdata, 0) }}
							</td>
							<td 
								class="statusDrayage_" 
								style="padding-left:0px; 
									   padding-right: 0px">                                
                                <span 
									class="AvailableStatus 
										   status_drayage" 
									v-if="itemdata.statusrow == 'in yard full'"
									>
									In Yard Full
								</span>
                                <span 
									class="AvailableStatus 
										   status_drayage" 
									v-if="itemdata.statusrow == 'scheduled'"
									>
									Available
								</span> 
                                 <span 
								 	class="AvailableStatus 
										   status_drayage" 
									v-if="itemdata.statusrow === 'dropped empty'"
									>
									Drop Empty
								</span> 
                                <span 
									class="PendingAvailabilityStatus 
										   status_drayage" 
									v-if="itemdata.statusrow == 'pending availability'"
									>
									Pending Availability
								</span>
                                <span 
									class="PendingAvailabilityStatus 
										   status_drayage" 
									v-if="itemdata.statusrow == 'pending billing'"
									>
									Pending Billing
								</span>
                                <span 
									class="DeliveredStatus 
										   status_drayage 
										   font-medium" 
									v-if="itemdata.statusrow == 'completed'"
									>
									Delivered
								</span>

                                <span 
									class="DeliveredStatus 
										   status_drayage 
										   font-medium" 
									v-if="itemdata.statusrow == 'cancelled'"
									>
									Cancelled
								</span>                               
							</td>
						</tr>
					</tbody>

					<tbody v-else>
						<tr class="nodatatable">
							<td class="" style="align:center; background:none;" colspan="7">
								<div style="text-align:center" class="no-data-wrapper">
									<div v-if="isfetching === 0">
                                        <img src="@/assets/icons/Truck Filled.svg" height="50px" width="50px" />

										<div class="searchnoresearch" v-if="search !== ''">
											<h2 style="font-size: 20px;">No Matching Result</h2>
											<p>
												We couldn’t find any product
												that matches your search
												term.<br />
												Have you spelled it correctly?
											</p>
										</div>

                                        <div v-else>
                                            <h2 style="font-size: 20px;">No Available Drayage</h2>
                                        </div>
									</div>

									<div v-else>
										<v-progress-circular
											:size="40"
											color="#0171a1"
											indeterminate>
										</v-progress-circular>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</template>
			</v-data-table>
			<PaginationComponent 
            :totalPage.sync="pageCount"
            :currentPage.sync="page"
            @handlePageChange="handlePageChange"
            :isMobile="isMobile" />
			<template>
				<div class="text-center ma-2">
					<v-snackbar v-model="snackbar" color="#16B442">
						<div style="display:flex">
							<img src="@/assets/icons/Check Mark.svg" />
							<span style="margin-left:5px">
								{{ text }}
							</span>
						</div>
					</v-snackbar>
				</div>
			</template>

			<div>
				<ModalDetails
					:items="itemsdetails"
					@datastatus="hideshow"
					:defaultmodal="defaultmodal" />
			</div>
		</div>

		<!-- <PaginationComponent 
            :totalPage.sync="pageCount"
            :currentPage.sync="page"
            @handlePageChange="handlePageChange"
            :isMobile="isMobile" /> -->

		<!-- <Pagination
			v-if="typeof dataitemheader !== 'undefined' && dataitemheader.length > 0"
			:pageData.sync="page"
			:lengthData.sync="pageCount" /> -->			
	</div>
</template>

<script>
import { mapGetters } from "vuex";
import axios from "axios";
const poBaseUrl = process.env.VUE_APP_TRUCKING_URL;
import Search from "../../Search2.vue";
// import Pagination from "../../Pagination.vue";
import globalMethods from "../../../utils/globalMethods";
import ModalDetails from "./modaldetails";
import PaginationComponent from '../../PaginationComponent/PaginationComponent.vue'

// import datatest from "./data.js"

import _ from "lodash";

// import moment from "moment";
// import jQuery from "jquery";

export default {
	name: "DrayageDesktopTable",
	props: ['itemdata','isfetching','centralcustomer','centraldata'],   
	components: {
		Search,
		// Pagination,	
        ModalDetails,
		PaginationComponent	
	},    
    async mounted(){
        // console.log(this.centraldata);
		this.$store.dispatch("page/setPage", "drayage");
	},
	data: () => ({
		snackbar: false,
		text: " Drayage report successfully downloaded.",
		timeout: 50,
		dataheaderstore: [],
		defaultmodal: false,
		itemsdetails: [], // items
		datatable: [],
		dropdowndrayage: [
			{ id: 1, name: "All Status", selected: "ako" },
			{ id: 2, name: "Available", selected: "c" },
			{ id: 3, name: "Delivered", selected: "3" },
			{ id: 4, name: "Pending Availability", selected: "e" },
			{ id: 5, name: "In Yard Full", selected: "c" },
			{ id: 6, name: 'Dropped empty',selected:"c"},
			{ id: 7, name: 'Cancelled',selected:"c"},
			{ id: 8, name: 'Pending Billing',selected:"c"},
			{ id: 9, name: 'At The Customer',selected:"c"}			
		],
		whatclick: true,
		refresh: 1,
		inititaldriverSelectFilter: 1,
		menu: false,
		alert: 1,
		itemsData: [],
		dialog: false,
		dialogData: [],
		page: 1,
		pageCount: 0,
		itemsPerPage: 20,
		search: "",
		isMobile: false
	}),
	computed: {
		...mapGetters(["getUser", "getLoadingUserDetails"]),
		dataitemheader: {
			get() {
				var s = [];
				var st = [];

				if (typeof this.itemdata != "undefined") {
					var data = [];
					for (const [key, value] of Object.entries(this.itemdata)) {
						if (value.length) {
							Object.values(value).map(function(f) {								
								data = f;
								if(key=='pending_availibility'){
									data.statusrow = 'pending availability';
								}else{
									data.statusrow = (key.toLowerCase()).replaceAll('_', ' ');
								}
                                var parr     = JSON.parse(data.dispatch_schedule)               
                                var getar    = (parr.legs)
                                if(getar.length>0){
                                    let newsta = getar.shift();
                                    data.dispatch_schedule_legs = newsta.address;   
                                }else{
                                    data.dispatch_schedule_legs = '--'; 
                                }     

								data.shifl_container =
									data.shifl_ref +
									"-" +
									data.trucker_container;
								s.push(data);	
							});

						}
					}
				}

				var getids = this.dropdowndrayage.find((e) => {
					return e.id == this.inititaldriverSelectFilter;
				});

				if (
					typeof getids != "undefined" &&
					this.inititaldriverSelectFilter != 1
				) {
					var statuslabel = getids.name.toLowerCase();
					
					if (statuslabel == "delivered") {
						statuslabel = "completed";
					}

					const getstatus = _.filter(Object.values(s), function(o) {				
						return o.statusrow == statuslabel;
					});
				
					if (getstatus.length === 0) {
						return st;
					} else {
						return getstatus;
					}
				}
				return s;
			},
		},		
		headers() {
			return [
				{
					text: "Ref#/Container ID",
					align: "left",
					sortable: true,
					value: "shifl_container",
					width: "13%",
					fixed: true,
				},
				{
					text: "MBL#",
					align: "start",
					sortable: false,
					value: "mbl_num",
					width: "5%",
					fixed: true,
				},
				{
					text: "Trucker Handling",
					align: "start",
					sortable: false,
					value: "trucker.name",
					width: "15%",
					fixed: true,
				},
				{
					text: "Size",
					align: "right",
					sortable: false,
					value: "container_size.name",
					width: "4%",
					fixed: true,
				},
				{
					text: "Pickup On",
					align: "start",
					sortable: true,
					value: "terminal.address",
					width: "22%",
					fixed: true,
				},
				{
					text: "Delivery On",
					align: "start",
					sortable: true,
					value: "dispatch_schedule_legs",
					width: "15%",
					fixed: true,
				},
				{
					text: "",
					align: "center",
					sortable: false,
					value: "Status",
					width: "15%",
					fixed: true,
				},
			];
		},
	},
	watch: {},
	methods: {
		// getCompanyKey(){
		//     let user = JSON.parse(this.getUser)
		//     return user.customers_api.find(c=>c.id == user.default_customer_id).company_key
		// },
		getsize(itemdata){
			if(itemdata.container_size !=null){			
				return itemdata.container_size.name
			} return '--';
		},
		calldata(item, target) {			
			if(!_.isEmpty(item) ) {		
				if (target == 1) {	
					if(!_.isEmpty(item.terminal))				
					return item.terminal.name;
				} else {
					let parr     = JSON.parse(item.dispatch_schedule)               
					let getar    = (parr.legs)
					if(getar.length>0){
						let newsta = getar.shift();
						return newsta.address;   
					}else{
						return '--';
					}     
				}
			}
			return '--';
		},
		check_ref_containers(item) {
			var var3 = item.shifl_container;
			const str = var3.split("-");
			if (typeof str[0] != "undefined" && typeof str[1] != "undefined")
				return (
					'<span class="ref font-medium">' +
					str[0] +
					'</span><br><span class="containerID">' +
					str[1] +
					"</span>"
				);
		},
		hideshow(status) {
			if (!status) this.defaultmodal = false;
		},
		rowDetails(item) {
			this.itemsdetails = item;
			this.defaultmodal = true;
		},
		checkifselected(item) {
			if (item.id == this.inititaldriverSelectFilter) {
				return true;
			}
			return false;
		},
		async reportdownload() {
			axios({
				url:
					`${poBaseUrl}/customer/download/report/` +
					this.centralcustomer,
				method: "GET",
				responseType: "blob",
			}).then((response) => {
				window.URL = window.URL || window.webkitURL;
				const link = document.createElement("a");
				link.href = window.URL.createObjectURL(
					new Blob([response.data], {
						type: "application/octet-stream",
					})
				);
				link.setAttribute("download", "customer_report.xlsx");
				document.body.appendChild(link);
				link.click();
				document.body.removeChild(link);
				this.snackbar = true;
			});
		},
		...globalMethods,
		handlePageChange() {}
	},
};
</script>

<style lang="scss">
.dropdownstatus {
	.v-input__control {
		.v-input__slot {
			.v-select__slot {
				.v-input__append-inner {
					.v-input__icon--append i {
						background: url("../../../assets/icons/Chevron Down.svg")
							no-repeat !important;
						font-size: 26px !important;
						height: 17px;
						width: 17px;
						display: block;
						position: relative;
						top: 1px;
						left: -1px;
					}
				}
			}
		}
	}
}

.dropdownstatus {
	.v-input__control {
		.v-input__slot {
			.v-select__slot {
				.v-input__append-inner {
					.v-input__icon--append i::before {
						content: "";
					}
				}
			}
		}
	}
}

.classsort {
	background: url("../../../assets/icons/Sort%20Ascending.svg") no-repeat !important;
	margin-left: 4px;
	width: 21px;
	height: 17px;
	display: block;
	position: relative;
	top: 3px;
	left: 1px;
	float: right;
}
.classsort:hover {
	opacity: 0.4;
}

.mdi-arrow-up {
	display: none !important;
}

.drayage-desktop-wrapper {
	height: 100%;
	background-color: #fff;

	.drayage-desktop-header {
		background-color: #f1f6fa !important;
	}

	.drayage-desktop-body {
		height: calc(100vh - 222px);

		.drayage-table {
			.headerTable {
				border-top: none !important;
			}

			.v-data-table__wrapper {
				table {
					thead {
						tr {
							th {
								height: 40px;
								font-size: 12px !important;
								text-transform: none !important;
								padding: 8px 12px !important;

								&:first-child {
									padding-left: 16px !important;
								}
							}
						}
					}

					tbody {
						tr {
							td {
								border-width: 1px !important;
								padding: 8px 12px !important;

								&:first-child {
									padding-left: 16px !important;
								}
							}
						}

						.nodatatable {
							td {
								border: none !important;
								border-width: 1px !important;

								.no-data-wrapper {
									padding-top: 65px;
								}
							}
						}
					}
				}
			}

			&.no-data-table {
				.v-data-table__wrapper {
					table {
						thead {
							display: none;
						}

						tbody {
							.no-data-wrapper {
								padding-top: 65px;
							}
						}
					}
				}
			}
		}
	}

	.pagination-wrapper {
		border: none;
    	border-top: 1px solid #ebf2f5!important;
	}
}
</style>
