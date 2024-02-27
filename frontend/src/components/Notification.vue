<!-- @format -->

<template>
    <v-container class="notification-container">
        <special-dialog
            :title="specialNotificationTitle"
            :message="specialNotificationMessage"
            :show="getSpecialDialog"
            @close="closeSpecialDialog"
        />
        <!-- <v-row justify="end align-center"> -->
        <v-row
            :class="
                !isMobile ? 'row-desktop account-side-main-container' : 'row-mobile'
            "
        >
            <!-- <v-badge :content="messages" :value="messages" color="green" overlap>
                <img src="@/assets/images/notification-icon.svg" alt="" width="18" height="18" v-if="!isMobile">   
                <img src="@/assets/images/mobile-notif.png" alt="" v-if="isMobile" class="mobile-notif" />
            </v-badge> -->

            <v-menu
                :content-class="`account-side-main-menu-container ${customers.length > 0 ? '' : 'no-customers-container'}`"
                offset-y
                v-if="!isMobile">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn v-bind="attrs" v-on="on" class="account-side-btn">
                        <!--<v-avatar v-if="!isMobile" size="35">
                            <v-img src="https://cdn.vuetifyjs.com/images/john.png"></v-img>
                            <v-icon small class="mr-2"> mdi-account </v-icon>
                        </v-avatar> -->
                        <div v-if="!isMobile" class="account-side-container">
                            <div class="account-side-avatar">
                                <img src="../assets/images/account-menus/avatar.png" />
                            </div>
                            <div class="account-side-names">
                                <div class="account-side-name">
                                    {{ (getLoadingUserDetails) ? '' : defaultCustomerEntity.name }}
                                </div>
                                <div class="account-side-undername">
                                    {{
                                        (!getLoadingUserDetails) ? 
                                        defaultCustomerEntity.address == "" ||
                                        defaultCustomerEntity.address == null
                                            ? "No address found."
                                            : defaultCustomerEntity.address
                                        : ''
                                    }}
                                </div>
                                <!-- <div class="account-side-undername">
                                    <input type="hidden" id="testing-code" :value="(!getLoadingUserDetails && defaultCustomerEntity.company_key)? defaultCustomerEntity.company_key : ''">
                                    {{ companyKey }}
                                    <span 
                                        style="z-index: 99999999999; vertical-align: middle;"
                                        @click.stop.prevent="copyTestingCode"
                                        v-if="!getLoadingUserDetails && defaultCustomerEntity.company_key"
                                        >
                                        <img width="15px" src="../assets/icons/copy-to-clipboard.svg" />
                                    </span>
                                </div> -->
                            </div>
                            <div
                                style="display: flex; justify-content: center; align-items: center; padding-left: 11px;"
                            >
                                <v-icon
                                    v-if="attrs['aria-expanded'] == 'true'"
                                    small
                                    color="#0171A1"
                                    >mdi-chevron-up</v-icon
                                >
                                <v-icon
                                    v-if="attrs['aria-expanded'] == 'false'"
                                    small
                                    color="#0171A1"
                                    >mdi-chevron-down</v-icon
                                >
                            </div>
                        </div>
                    </v-btn>
                </template>

                <v-card class="mx-auto account-side-card" min-width="240">
                    <v-card-title class="account-side-content-item-title" v-if="customers.length > 0">
                        Accounts
                    </v-card-title>

                    <div class="item-separator" v-if="customers.length > 0"></div>

                    <v-list class="account-side-list-container pt-0" :class="customers.length > 0 ? '' : 'no-customers'">
                        <!-- <div></div> -->

                        <!-- <v-list-item-title class="account-side-content-item-title" v-if="customers.length > 0">
                            Accounts
                        </v-list-item-title> -->

                        <v-list-item
                            @click="selectCustomer(customer)"
                            :key="key"
                            v-for="(customer, key) in customers"
                            :class="
                                `account-side-list-items ${
                                    customer.is_selected == 1
                                        ? 'account-side-list-items-selected'
                                        : ''
                                }`
                            "
                        >
                            <div class="account-side-avatar">
                                <img src="../assets/images/account-menus/avatar.png" />
                            </div>
                            <v-list-item-content>
                                <v-list-item-title class="account-side-list-item-title">
                                    <div class="account-customer-name" :class="customer.is_selected == 1 ? 'current-selected-customer' : ''">
                                        {{ customer.name }}
                                    </div>
                                    <!-- <div
                                        class="current-selected"
                                        v-if="customer.is_selected == 1"
                                    >
                                        (Current)
                                    </div> -->
                                </v-list-item-title>
                                <v-list-item-subtitle>
                                    {{ customer.company_name }}
                                </v-list-item-subtitle>
                                <v-list-item-subtitle style="padding-left: 2px;">
                                    Key: {{ customer.company_key }}
                                </v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>

                        <!-- <v-list-item class="item-separator" v-if="customers.length > 0"></v-list-item> -->

                        <!-- <v-list-item class="logout-item-container" @click="userLogout">
                            <v-list-item-content>
                                <v-list-item-title class="account-side-menu-items logout-item">
                                    Log out
                                </v-list-item-title>
                            </v-list-item-content>
                            <div class="d-flex align-items-center">
                                <img src="../assets/images/account-menus/signout.svg" alt="Sign Out" width="18px" height="18px">
                            </div>
                        </v-list-item> -->
                    </v-list>

                    <div class="item-separator" v-if="customers.length > 0"></div>

                    <v-card-actions class="logout-item-container px-4">
                        <button class="btn-logout-user d-flex justify-space-between align-center" style="width: 100%;" @click="userLogout">
                            <span class="logout-item">Log out</span>

                            <div class="d-flex align-items-center">
                                <img src="../assets/images/account-menus/signout.svg" alt="Sign Out" width="18px" height="18px">
                            </div>
                        </button>                        
                    </v-card-actions>
                </v-card>
            </v-menu>
        </v-row>
		<v-snackbar v-model="snackbarFlag" :timeout="snackbarTimeout">
			Company key copied to clipboard.
		</v-snackbar>
    </v-container>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap");
.account-side-list-container::-webkit-scrollbar {
    width: 6px;
}
    
/* Track */
.account-side-list-container::-webkit-scrollbar-track {
    background: #f1f1f1; 
}
    
/* Handle */
.account-side-list-container::-webkit-scrollbar-thumb {
    background: #e2e2e2; 
}
    
/* Handle on hover */
.account-side-list-container::-webkit-scrollbar-thumb:hover {
    background: #e2e2e2; 
}
.account-side-list-container {
    padding-bottom: 0;
    max-height: 390px;
    /* overflow-y: scroll; */
    overflow-y: auto;
}
.account-side-list-container.no-customers {
    padding-top: 0 !important;
}
.account-side-list-item-title {
    display: flex;
}
.account-side-list-item-title > div.current-selected {
    padding-left: 8px;
    color: #0171a1;
    /* font-weight: 600; */
    font-family: "Inter-Medium", sans-serif;
}
.account-side-list-item-title .account-customer-name {
    font-family: "Inter-Medium", sans-serif;
    color: #4a4a4a !important;
}
.account-side-list-item-title .account-customer-name.current-selected-customer {
    font-family: "Inter-Medium", sans-serif;
    color: #0171a1 !important;
}
.v-list-item__subtitle {
    font-family: "Inter-Regular", sans-serif;
    color: #6d858f !important;
}
.account-side-btn {
    background-color: #ffffff !important;
    filter: none;
    box-shadow: none;
    border-radius: 4px;
    padding: 8px !important;
    height: auto !important;
    border: 1px solid #ebf2f5 !important;
}
.account-side-btn:hover {
    background-color: #ffffff !important;
}
.item-separator {
    background-color: #EBF2F5;
    height: 1px;
    width: 100%;
    min-height: 1px;
}
.account-side-main-menu-container {
    /* border-radius: 0px !important; */
    border-radius: 4px !important;
    background-color: transparent !important;
    background: transparent !important;
    left: inherit !important;
    right: 24px !important;
    /* min-width: 22% !important; */
    min-width: 300px !important;
    width: 300px;
}
.account-side-main-menu-container.no-customers-container {
    width: 240px !important;
    min-width: unset !important;
}
.account-side-content-item-title {
    color: #819fb2;
    font-family: "Inter", sans-serif;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 16px;
    /* padding: 5px 16px 10px; */
    /* padding-left: 16px; */
    text-transform: uppercase;
}
.account-side-card {
    border-radius: 0px !important;
}
.account-side-container {
    display: flex;
    flex-direction: row;
    width: 100%;
}
.account-side-avatar img {
    width: 32px;
    height: auto;
}
.account-side-avatar {
    margin-right: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.account-side-names {
    display: flex;
    flex-direction: column;
    letter-spacing: 0;
}
.account-side-menu-items {
    color: #4a4a4a;
    font-family: "Inter", sans-serif;
    font-size: 14px;
    font-weight: 500;
    text-transform: none;
}
.account-side-names > div {
    width: 100%;
    font-family: "Inter", sans-serif;
    text-align: left;
}
.account-side-names .account-side-name {
    font-size: 14px;
    color: #4a4a4a;
    font-weight: 600;
    text-transform: none;
    max-width: 160px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.account-side-names .account-side-undername {
    color: #6d858f;
    font-size: 12px;
    font-weight: 400;
    text-transform: none;
    text-align: left;
    /* max-width: 100px; */
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    max-width: 160px;
    padding-top: 2px;
}
.account-side-list-items {
    padding-left: 16px;
    cursor: pointer;
}
.account-side-list-items:hover,
.account-side-main-items:hover,
.logout-item-container:hover {
    background: #f1f6fa;
}
.account-side-list-items-selected {
    background: #f1f6fa;
}
.logout-item-container,
.account-side-main-items {
    cursor: pointer;
}
.logout-item-container {
    min-height: 52px;
}
.row-desktop {
    justify-content: flex-end;
    align-items: center;
}
.mobile-notif {
    margin-top: 4px;
    width: 18px;
}
.avatar-button {
    padding: 0 !important;
    min-width: 35px !important;
    width: 35px !important;
    border-radius: 50% !important;
    margin: 0 5px 0 25px !important;
    background-color: #fff !important;
}
.btn-logout {
    background-color: transparent !important;
    box-shadow: none !important;
    text-transform: capitalize !important;
    letter-spacing: 0;
    font-weight: 600;
}
.avatar-button .v-btn__content .v-avatar i {
    font-size: 20px !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    margin: 0 !important;
}
.loading-special {
    height: 32px;
    width: 32px;
    position: absolute;
    z-index: 1001;
    left: 50%;
    top: 50%;
}
.logout-item {
    color: #f93131;
    font-family: 'Inter-Medium', sans-serif;
    font-size: 14px;
}
.logout-item-icon {
    background-image: url("../assets/images/account-menus/logout.png");
    background-repeat: no-repeat;
    background-size: cover;
    width: 18px;
    height: 13px;
}
.account-side-main-container {
    padding-right: 8px !important;
}
</style>

<script>
import { mapActions, mapGetters } from "vuex";
import SpecialDialog from "./Dialog/SpecialDialog";
import _ from "lodash";
export default {
    name: "Notification",
    props: ["isMobile"],
    components: {
        SpecialDialog,
    },
    data() {
        return {
            specialNotificationTitle: "Special Notification",
            specialNotificationMessage:
                "Refreshing the page in 5 seconds in order to update changes in your account.",
            messages: 0,
            items: [{ title: "Logout" }],
            snackbarTimeout: 5000,
            snackbarFlag: false,
        };
    },
    methods: {
        //
        ...mapActions(["logout", "updateCustomerPreference", "closeSpecialDialog"]),
        userLogout() {
            this.$store.dispatch("page/setTab", 1);
            this.logout();
        },
        selectCustomer({ id: customer_id }) {
            let userDetails = JSON.parse(localStorage.getItem("shifl.user_details"))
            if ( customer_id !== userDetails.default_customer_id ) {
                if (typeof userDetails === "object") {
                    localStorage.setItem(
                        "shifl.user_details",
                        JSON.stringify({
                            ...userDetails,
                            default_customer_id: customer_id,
                        })
                    );
                }
                this.updateCustomerPreference(customer_id)
                    .then((response) => {
                        if (typeof response.status !== "undefined") {
                            if (response.status == "ok") {
                                let currentUrl = window.location.pathname;
                                if (this.$route.name === 'PO Details') {
                                    window.location.href = '/pos';
                                } else if (this.$route.name === 'Shipment Details') {
                                    window.location.href = '/shipments';
                                } else if (this.$route.name === 'Inventory Inbound View' || 
                                    this.$route.name ==='Inventory Outbound View') {
                                    window.location.href = '/inventory?tab=Products';
                                } else if (this.$route.name === 'AddUserGroup') {
                                    window.location.href = '/settings?tab=users';
                                } else {
                                    window.location.href = currentUrl;
                                }
                            }
                        }
                    })
                    .catch((e) => {
                        console.log(e);
                    });    
            }
            
        },
        copyTestingCode () {
            let testingCodeToCopy = document.querySelector('#testing-code');
            testingCodeToCopy.setAttribute('type', 'text');
            testingCodeToCopy.select();
            if(document.execCommand('copy')){
                this.snackbarFlag = true;
            }
            
            /* unselect the range */
            testingCodeToCopy.setAttribute('type', 'hidden');
            window.getSelection().removeAllRanges();
        },
    },
    computed: {
        ...mapGetters(["getSpecialDialog", "getLoadingUserDetails"]),
        defaultCustomerEntity() {
            if (this.customers.length > 0) {
                return _.find(this.customers, {
                    is_selected: 1,
                });
            } else {
                let getUser = this.$store.getters.getUser;
                let getCustomers =
                typeof getUser.customers_api !== "undefined"
                    ? getUser.customers_api
                    : JSON.parse(getUser);
                return {
                    name: getCustomers.name,
                    address: getCustomers.email,
                };
            }
        },
        defaultCustomer() {
            let getUser = this.$store.getters.getUser;
            getUser = typeof getUser === "string" && JSON.parse(getUser);
            return getUser.default_customer_id;
        },
        email() {
            let getUser = this.$store.getters.getUser;
            return typeof getUser.name !== "undefined"
                ? getUser.email
                : JSON.parse(getUser).email;
        },
        name() {
            let getUser = this.$store.getters.getUser;
            return typeof getUser.name !== "undefined"
                ? getUser.name
                : JSON.parse(getUser).name;
        },
        customers() {
            let getUser = this.$store.getters.getUser;
            let getCustomers =
                typeof getUser.customers_api !== "undefined"
                    ? getUser.customers_api
                    : JSON.parse(getUser).customers_api;
            if (typeof getCustomers !== "undefined" && getCustomers.length > 0) {
                getCustomers.map((customer, key) => {
                    (getCustomers[key].is_selected =
                        this.defaultCustomer !== null && this.defaultCustomer == customer.id
                            ? 1
                            : 0),
                        (getCustomers[key].name = customer.company_name),
                        (getCustomers[key].company_name =
                            customer.address !== null && customer.address !== ""
                                ? customer.address
                                : "No address found.");
                });
                let findSelected = _.find(getCustomers, { is_selected: 1 });
                if (typeof findSelected == "undefined") getCustomers[0].is_selected = 1;
                getCustomers = _.orderBy(getCustomers, ["is_selected"], ["desc"]);
                return getCustomers;
            }
            return [];
        },
        companyKey(){
            if(!this.getLoadingUserDetails){
                if(this.defaultCustomerEntity.company_key == "" || this.defaultCustomerEntity.company_key == null){
                    return 'No key found';
                }else{
                    return 'Key: '+ this.defaultCustomerEntity.company_key 
                }
            }
            return '';
        },
    },
    mounted() {},
};
</script>
