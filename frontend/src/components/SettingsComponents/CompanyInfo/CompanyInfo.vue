<template>
	<div class="company-profile-content">
    	<v-row>
			<v-col cols="12" sm="12">
				<div class="company-profile-title-container mb-1">
					<h3 class="company-profile-title">Company Information</h3>
					<button
						class="btn-white float-right"
						@click="openEditCompanyInfoDialog"
						:disabled="getLoadingUserDetails" >
						Edit Profile
					</button>
				</div>
			</v-col>
		</v-row>

		<v-row v-if="getLoadingUserDetails" justify="center" class="mb-5">
			<div class="preloader">
				<v-progress-circular :size="40" color="#0171a1" indeterminate>
				</v-progress-circular>
			</div>
		</v-row>

		<v-row class="mb-2" v-if="!getLoadingUserDetails">
			<v-col cols="12" sm="4" class="company-profile-col first">
				<v-row>
					<v-col cols="12" sm="4" class="company-profile-label">
						Company Name
					</v-col>
					<v-col cols="12" sm="8">
						<p class="company-profile-value">
							{{ companyDetail.company_name ? companyDetail.company_name : "N/A" }}
						</p>
					</v-col>
				</v-row>

				<v-row class="mt-0">
					<v-col cols="12" sm="4" class="company-profile-label">
						Company Key
					</v-col>
					<v-col cols="12" sm="8">
						<p class="company-profile-value" v-if="companyDetail.company_key">
							<input
								type="hidden"
								id="company-key"
								:value="companyDetail.company_key" />
								{{ companyDetail.company_key }}
								<span
									style="vertical-align: middle"
									class="pl-10 pointer"
									@click.stop.prevent="copyTestingCode">
								<img
									width="15px"
									src="../../../assets/icons/copy-to-clipboard.svg"
								/>
							</span>
						</p>
						<p class="company-profile-value" v-if="!companyDetail.company_key">
							{{ companyDetail.company_key ? companyDetail.company_key : "N/A" }}
						</p>
					</v-col>
				</v-row>
			</v-col>

			<v-col cols="12" sm="4" class="company-profile-col">
				<v-row>
					<v-col cols="12" sm="4" class="company-profile-label">
						Phone Number
					</v-col>
					<v-col cols="12" sm="8">
						<p class="company-profile-value">
						{{
							companyDetail.phone
							? (companyDetail.phone.substr(0, 2) == "+1" ? "" : "+1 ") +
								companyDetail.phone
							: "N/A"
						}}
						</p>
					</v-col>
				</v-row>

				<v-row class="mt-0">
					<v-col cols="12" sm="4" class="company-profile-label">
						Email Address
					</v-col>
					<v-col cols="12" sm="8">
						<div v-for="(item, index) in companyDetail.emails" :key="index">
							<p class="company-profile-value" v-if="item.email">
								{{ item.email }}
							</p>
						</div>
						<p class="company-profile-value"
							v-if="!companyDetail.emails || companyDetail.emails.length == 0" >
							N/A
						</p>
					</v-col>
				</v-row>
			</v-col>
			
			<v-col cols="12" sm="4" class="company-profile-col">
				<v-row class="address-top-margin">
					<v-col cols="12" sm="3" class="company-profile-label"> Address </v-col>
					<v-col cols="12" sm="9">
						<p class="company-profile-value">
							{{ companyDetail.address ? companyDetail.address : ""
							}}{{ companyDetail.city ? `, ${companyDetail.city}` : ""
							}}{{ companyDetail.state ? `, ${companyDetail.state}` : ""
							}}{{ companyDetail.zipcode ? `, ${companyDetail.zipcode}` : ""
							}}{{ companyDetail.country ? `, ${companyDetail.country}` : "" }}
						</p>
					</v-col>
				</v-row>
			</v-col>
		</v-row>

		<!-- <div class="row">
			<v-divider class="mt-5"></v-divider>
		</div> -->

		<v-snackbar v-model="snackbarFlag" :timeout="snackbarTimeout">
			Company key copied to clipboard.
		</v-snackbar>

		<EditCompanyInfoDialog
			:editDialog.sync="editDialog"
			:editedItemData.sync="editedItem"
			@close="close" />
	</div>
</template>

<script>
import { mapGetters } from "vuex";
import EditCompanyInfoDialog from "../Dialog/EditCompanyInfoDialog.vue";

export default {
	name: "CompanyInfo",
	props: ["companyDetail"],
	components: {
		EditCompanyInfoDialog,
	},
	computed: {
		...mapGetters({
			getLoadingUserDetails: "getLoadingUserDetails",
		}),
	},
	data() {
		return {
			snackbarTimeout: 5000,
			snackbarFlag: false,
			editDialog: false,
			editedItem: Object.assign({}, this.companyDetail),
		};
	},
  watch: {
    getLoadingUserDetails() {
      this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.companyDetail);
			});
    }
  },
	methods: {
		openEditCompanyInfoDialog() {
			this.editDialog = true;
      this.editedItem = Object.assign({}, this.companyDetail);
		},
		close() {
			this.editDialog = false;
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.companyDetail);
			});
		},
		copyTestingCode() {
			let testingCodeToCopy = document.querySelector("#company-key");
			testingCodeToCopy.setAttribute("type", "value");
			testingCodeToCopy.select();
			if (document.execCommand("copy")) {
				this.snackbarFlag = true;
			}

			/* unselect the range */
			testingCodeToCopy.setAttribute("type", "hidden");
			window.getSelection().removeAllRanges();
		},
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/buttons.scss";
</style>
