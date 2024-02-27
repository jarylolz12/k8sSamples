<!-- @format -->

<template>
	<div class="archived-shipment-wrapper">
    <p class="font-weight-regular" style="color: #0171A1; cursor: pointer; width: 8%;" @click="clickBack()">
      <span class="mdi mdi-chevron-left"></span> Go Back
    </p>
		<div id="shipments_header">
      <div class="filters-wrapper">
          <v-row>
            <v-col class="pl-6">
              <div class="font-weight-black">Archive</div>
            </v-col>
            <v-col class="pr-6">
              <div class="search-wrapper" style="float: right;">
                <img src="@/assets/images/search-icon.svg" alt="search icon">
                <input
                  class="search" 
                  type="text"
                  id="search-input"
                  placeholder="Search Archived"
                  v-model.trim="search"
                  @input="handleSearch()"
                  autocomplete="off"
                  ref="shipmentSearchInput" />
              </div>
            </v-col>
        </v-row>
      </div>
    </div>

    <v-data-table
      :headers="headersPendingQuote"
      :items="getArchivedData"
      mobile-breakpoint="769"
      :page="1"
      item-key="name"
      class="archived-header archived-shipment-header"
      hide-default-footer
      ref="my-table"
    >

      <template v-slot:no-data>
        <div
          class="no-data-preloader"
          v-if="getArchivedBookingsLoading"
        >
          <v-progress-circular :size="40" color="#0171a1" indeterminate>
          </v-progress-circular>
        </div>
      </template>

      <template v-slot:[`item.ReferenceID`]="{ item }">
        <div class="k-flex k-justify-between">
          <div class="k-default-color">{{ item.ReferenceID }}</div>
        </div>
      </template>

      <template v-slot:[`item.updated_at`]="{ item }">
				<div class="k-flex k-justify-between">
					<div style="color: #6D858F;">{{ beautify(item.updated_at) }}</div>
				</div>
			</template>

      <template v-slot:[`item.location_from_name`]="{ item }">
        <div class="k-flex k-justify-between">
          <div class="k-default-color">{{ item.location_from_name }}</div>
        </div>
      </template>

      <template v-slot:[`item.location_to_name`]="{ item }">
        <div class="k-flex k-justify-between">
          <div class="k-default-color">{{ item.location_to_name }}</div>
        </div>
      </template>

      <template v-slot:[`item.Suppliers`]="{ item }">
				<div class="supplier-desktop">
					<span v-for="(name, index) in item.Suppliers" :key="index">
						<span>{{ name }}</span
						><span v-if="index + 1 < item.Suppliers.length">, </span>
					</span>
          
					<span v-if="item.Suppliers == null || item.Suppliers.length == 0">
						<span>N/A</span>
					</span>
				</div>
			</template>

      <template v-slot:[`item.mode`]="{ item }">
				<div
					v-if="item.multipleModes.length > 0"
					class="pending-quote-actions-wrapper shipment-type k-relative"
				>
					<div class="k-pr-5 k-flex k-items-center k-pl-12">
						<span
							class="k-flex k-mr-4"
							:key="k"
							v-for="(m, k) in item.multipleModes"
						>
							<span class="mr-1">
								<img
									v-if="m === 'ship'"
									src="@/assets/icons/ocean.svg"
									width="20px"
									height="20px"
								/>
								<img
									v-if="m === 'air'"
									src="@/assets/icons/airplane.svg"
									width="20px"
									height="20px"
								/>
								<img
									v-if="m === 'truck'"
									src="@/assets/icons/truck.svg"
									width="20px"
									height="20px"
								/>
							</span>
						</span>
            <img v-if="item.type == 'LCL'" src="@/assets/images/small-container.svg" width="20px" height="20px" />
            <img v-if="item.type == 'Air'" src="@/assets/images/airplane-shipment.svg" width="20px" height="20px" />
            <img v-if="item.type == 'FCL'" src="@/assets/images/big-container.svg" width="20px" height="20px" />
            <span style="padding-bottom:2px;" v-if="item.type == 'FCL' && item.container_num_list.length !== 0">
                ({{ item.container_num_list.length }})
            </span>
					</div>
				</div>

				<div v-else class="k-flex k-items-center justify-center">
					<div class="k-flex">
						<span>N/A</span>
					</div>
				</div>
			</template>

      <template v-slot:[`item.PO`]="{ item }">
				<div v-if="1 == 2" class="mobile-pos">
					<div
						v-if="item.po_list !== null && item.po_list.length > 0"
						style="width: 100%; display:flex;"
					>
						<p>
							<span v-for="(name, index) in item.po_list" :key="index">
								PO #{{ name
								}}<template v-if="index + 1 < item.po_list.length">, </template>
							</span>
						</p>
					</div>

					<div v-if="item.po_list == null">
						<p>N/A</p>
					</div>
				</div>
				<div class="k-flex k-justify-between">
					<div class="k-default-color">
						{{
							item.po_list !== null && item.po_list.length > 0
								? item.po_list.join(", ")
								: "N/A"
						}}
					</div>
				</div>
			</template>

      <template v-slot:[`item.actions`]="{ item }">
        <div class="shipment-action-button d-flex align-end justify-end mr-1">
        <button class="btn-white" style="height: 35px !important;" @click="shipmentUnArchive(item.id)">Unarchive</button>
        <!-- <div class="three-dots-wrapper">
          <v-menu
            offset-y
            bottom
            left
            content-class="pending-dropdown-container"
          > -->
            <!-- <template v-slot:activator="{ on, attrs }">
              <button v-bind="attrs" v-on="on" class="shipment-three-dots-container">
                <custom-icon
                  iconName="three-dots"
                  color="#0171A1"
                  width="11"
                  height="3"
                />
              </button>
            </template>
            <v-list> -->
              <!-- <v-list-item>
                <v-list-item-title class="k-fw-sm k-font-inter-regular">
                  Edit Shipment
                </v-list-item-title>
              </v-list-item>
              <v-list-item>
                <v-list-item-title class="k-fw-sm k-font-inter-regular k-red">
                  Request Cancellation
                </v-list-item-title>
              </v-list-item> -->
            <!-- </v-list> -->
          <!-- </v-menu> -->
        <!-- </div> -->
        </div>     
      </template>

		</v-data-table>

    <ConfirmDialog :dialogData.sync="unarchiveAlert">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>UnArchive Shipment?</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					Are you sure, Want to unacrhive this Shipment?
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" text @click="confirmUnArchive()" :disabled="getUnArchiveShipmentLoading">
          <span v-if="getUnArchiveShipmentLoading">Yes, Confirming...</span>
					<span v-else>Yes, Confirm</span>
				</v-btn>
				<v-btn class="btn-white" text @click="cancelUnArchive()" :disabled="getUnArchiveShipmentLoading">
					No, Cancel
				</v-btn>
			</template>
		</ConfirmDialog>

	</div>
</template>

<style scoped>
  @import "../../../assets/scss/pages_scss/shipment/archivedShipmentTable.scss";
</style>
<script>
import { mapActions, mapGetters } from "vuex";
import moment from "moment";
// import CustomIcon from "../../Icons/CustomIcon";
import ConfirmDialog from "@/components/Dialog/GlobalDialog/ConfirmDialog.vue";
import globalMethods from '@/utils/globalMethods';

export default {
	name: "ArchivedShipment",
  props: ['selectedTab'],
	components: {
		// CustomIcon,
    ConfirmDialog,
	},
	data () {
      return {
        itemsPerPage: 50,
        headersPendingQuote: [
          {
            text: "Reference",
            align: "start",
            sortable: false,
            value: "ReferenceID",
            fixed: true,
            class: "pending-quote-table-header",
            width: "12%",
          },
          {
            text: "Updated By & At",
            align: "start",
            sortable: false,
            value: "updated_at",
            fixed: true,
            class: "pending-quote-table-header",
            width: "18%",
          },
          {
            text: "From",
            value: "location_from_name",
            sortable: false,
            fixed: true,
            class: "pending-quote-table-header",
            width: "12%",
          },
          {
            text: "To",
            value: "location_to_name",
            sortable: false,
            fixed: true,
            class: "pending-quote-table-header",
            width: "12%",
          },
          {
            text: "Suppliers",
            value: "Suppliers",
            sortable: false,
            fixed: true,
            class: "pending-quote-table-header",
            width: "18%",
          },
          {
            text: "Mode/Type",
            align: "center",
            value: "mode",
            sortable: false,
            fixed: true,
            class: "pending-quote-table-header",
            width: "6%",
          },
          {
            text: "PO",
            value: "PO",
            sortable: false,
            fixed: true,
            class: "pending-quote-table-header",
            width: "14%",
          },
          {
            text: "",
            align: "center",
            sortable: false,
            value: "actions",
            width: "5%",
            fixed: true,
          },
        ],
        search: "",
        unarchiveAlert: false,
        unarchiveShipmentId: false,
      }
  },
  async mounted() {
    let payload = {
      search: "",
      tab: this.selectedTab
    }
    this.fetchArchivedBookings(payload)
  },
  computed: {
    ...mapGetters([
			"getArchivedBookingsLoading",
			"getArchivedBookings",
			"getUnArchiveShipmentLoading",
		]),
    getArchivedData() {
      return this.getArchivedBookings ? this.getArchivedBookings.shipments : [];
    }
  },
	methods: {
    ...globalMethods,
    ...mapActions([
      "fetchArchivedBookings",
      "unArchiveShipment",
      "fetchPendingQuoteShipments",
      "fetchDraftShipments",
      "fetchShipments",
    ]),
    clickBack() {
			this.$emit("hideArchivedShipment");
		},
    beautify(date) {
			return date !== null ? moment.utc(date).format("MMM DD, YYYY") : "N/A";
		},
    async handleSearch() {
        clearTimeout(this.typingTimeout)
        this.typingTimeout = setTimeout(() => {
            let payload = { 
                search: this.search,
                tab: this.selectedTab
            }
            this.searchPendingData = this.search
            this.fetchArchivedBookings(payload)
        },800)
    },
    shipmentUnArchive(shipmentId) {
      this.unarchiveAlert = true;
      this.unarchiveShipmentId = shipmentId;
    },
    confirmUnArchive() {
      let payloadObject = {'shipmentId': this.unarchiveShipmentId, 'type': this.selectedTab}      
      this.unArchiveShipment(payloadObject)
        .then((response) => {
          this.unarchiveAlert = false;
          this.notificationErrorCustom(response.data.message);
          let payload = {
            search: "",
            tab: this.selectedTab
          }
          let payloadShipments = {
            page: 1,
            order: 'asc',
          }
          this.fetchArchivedBookings(payload);
          this.fetchPendingQuoteShipments(1);
          this.fetchDraftShipments(1);
          this.fetchShipments(payloadShipments);
        })
        .catch((e) => {
          console.log(e);
          this.unarchiveAlert = false;
          this.notificationErrorCustom('SOMETHING WENT WRONG!');
          let payload = {
            search: "",
            tab: this.selectedTab
          }
          this.fetchArchivedBookings(payload)
        })
    },
    cancelUnArchive() {
      this.unarchiveAlert = false;
      this.unarchiveShipmentId = "";
    }
	}
};
</script>
