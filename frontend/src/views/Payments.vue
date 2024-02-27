<template>
    <v-container fluid>        
        <div class="payment-details-wrapper">
            <v-row>
                <v-col class="first-col pb-0 pt-5" sm="12" md="12">
                    <div id="top-header">
                        <div class="head-wrapper pb-2">
                            <div class="">
                                <h2>
                                    Recieve Payments
                                </h2>
                            </div>
                            <div class="d-flex flex-row align-end">
                                <div class="pr-2">
                                    <p class="p1 mb-0 text-capitalize">
                                        Amount Recieved:
                                    </p>
                                </div>
                                <div class="pl-5">
                                    <p class="p2 mb-0">$0</p>
                                </div>
                            </div>
                        </div> 
                    </div>
                </v-col>
            </v-row>
            <v-row>
                <v-col class="pt-0"> 
                    <div class="documents-wrapper" style="background:white; border: 1px solid #E1ECF0; position:relative; border-radius: 4px;">
                        <v-tabs class="customTab" height="64px" style="border-bottom: 1px solid #E1ECF0;">
                            <v-tab v-for="n in tabs" :key="n" v-on:click="setMainTab(n)" :class="n">
                                {{ n }}
                            </v-tab>
                        </v-tabs>
                        <div v-if="currentTab == 'Summary'">
                            <v-form ref="form" class="pa-4" action="#" @submit.prevent="submit">
                                <v-row>
                                    <v-col cols="12" sm="12" md="12" lg="3" class="pb-0">
                                        <label class="text-item-label">Customer</label>
                                        <v-select
                                        append-icon="mdi-chevron-down"
                                        
                                        outlined
                                        :items="items"
                                        :rules="rules"
                                        placeholder="Select Customer" 
                                        
                                        class="text-fields"
                                        data-vv-name="select"
                                        hide-details="auto">
                                        required
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="12" lg="3" class="pb-0">
                                        <label class="text-item-label">Customer Emails...</label>
                                        <v-text-field 
                                            placeholder="Add customer emails..." 
                                            outlined 
                                            class="text-fields"
                                            
                                            :rules="rules"
                                            hide-details="auto">
                                        </v-text-field>
                                    </v-col>
                                    <v-spacer></v-spacer>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" sm="12" md="12" lg="3" class="pb-0">
                                        <label class="text-item-label">Payment Date</label>
                                        <v-text-field 
                                            placeholder="Select Payment Date" 
                                            outlined 
                                            
                                            class="text-fields"
                                            type="date"
                                            :rules="rules"
                                            hide-details="auto">
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="12" lg="3" class="pb-0">
                                        <label class="text-item-label ">Refrence No.</label>
                                        <v-text-field 
                                            placeholder="Enter reference no." 
                                            outlined 
                                            class="text-fields"
                                            type="number"
                                            :rules="rules"
                                            hide-details="auto">
                                        </v-text-field>
                                    </v-col>
                                    <v-spacer></v-spacer>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" sm="12" md="12" lg="3" class="pb-0">
                                        <label class="text-item-label">Payment Method</label>
                                        <v-select
                                        append-icon="mdi-chevron-down"
                                        
                                        outlined
                                        :items="items"
                                        :rules="rules"
                                        placeholder="Select Payment Method" 
                                        
                                        class="text-fields"
                                        data-vv-name="select"
                                        hide-details="auto">
                                        required
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="12" lg="3" class="pb-0">
                                        <label class="text-item-label ">Deposit To</label>
                                        <v-select
                                        append-icon="mdi-chevron-down"
                                        
                                        outlined
                                        :items="items"
                                        :rules="rules"
                                        placeholder="Choose Account" 
                                        
                                        class="text-fields"
                                        data-vv-name="select"
                                        hide-details="auto">
                                        required
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="12" lg="3" class="pb-0">
                                        <label class="text-item-label ">Amount Recieved</label>
                                        <v-text-field 
                                            placeholder="0" 
                                            outlined 
                                            class="text-fields text-right"
                                            :rules="rules"
                                            hide-details="auto">
                                        </v-text-field>
                                    </v-col>
                                    <v-spacer></v-spacer>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" sm="12" md="12" lg="4" class="pb-0">
                                        <label class="text-item-label">Note</label>
                                        <v-textarea
                                            height="110px"
                                            class="text-fields"
                                            outlined
                                            name="input-7-4"
                                            placeholder="Type notes here" 
                                            
                                            :rules="rules"
                                            hide-details="auto">
                                        </v-textarea>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="12" lg="4" class="pb-0">
                                        <label class="text-item-label">Attachment</label>
                                        <v-row class="d-flex flex-column py-4 mt-1" dense align="center" justify="center" style="border: 2px dashed #eee; border-radius: 5px;">
                                            <p style="color:#B4CFE0">
                                                Browse or Drop Files
                                            </p>
                                            <v-btn   
                                                class="text-capitalize elevation-0"
                                                style="font-weight:600 !important; background-color:white; border: 1px solid #B4CFE0;color: #0171A1;"
                                                @click="onButtonClick"
                                                >
                                                <img src="../assets/icons/upload.svg" alt="" width="20px" height="20px">
                                                Upload
                                            </v-btn>
                                            <input
                                                ref="uploader"
                                                class="d-none"
                                                type="file"
                                                accept="image/*"
                                                @change="onFileChanged"
                                            >
                                        </v-row>
                                    </v-col>   
                                </v-row>
                                <v-row class="mt-15" style="border-top: 2px solid #E1ECF0;">
                                    <v-col class="d-flex flex-row justify-end">
                                        <v-btn class="btn-blue mr-3" text @click="save">
                                        Save
                                        </v-btn>
                                        <v-btn class="btn-blue mr-3" text>
                                        Next
                                        </v-btn>
                                        <v-btn class="btn-white" text @click="close">
                                            Cancel
                                        </v-btn>
                                    </v-col> 
                                </v-row>
                            </v-form>
                        </div>
                        <div v-if="currentTab == 'Invoices'">
                            <v-row class="payment-container" >
                                <v-col>
                                    <template>
                                        <div class="">
                                            <h3 class="outstanding pb-4">outstanding transactions</h3>
                                            <v-data-table
                                                :headers="headers_outstanding"
                                                mobile-breakpoint="769"
                                                v-model="selected"
                                                :single-select="singleSelect"
                                                show-select
                                                :items="paymentOutstanding"
                                                item-key="description"
                                                class="payments-table"
                                                :class="paymentOutstanding !== null && paymentOutstanding.length !== 'undefined' && paymentOutstanding.length !== 0 ? '' : 'no-data-table'"
                                                :page.sync="page"
                                                :items-per-page="itemsPerPage"
                                                @page-count="pageCount = $event"
                                                hide-default-footer
                                                fixed-header>

                                                <template v-slot:[`item.payment_date`]="{ item }">
                                                    <div class="email-wrapper" v-if="item.payment_date !== ''">
                                                        <div class="email-container">
                                                            <p class="mb-0" style="white-space: nowrap">{{ item.payment_date }}</p>
                                                        </div>
                                                    </div>

                                                    <div v-if="item.payment_date == ''">
                                                        <p class="mb-0" style="color: #4a4a4a;">--</p>
                                                    </div>
                                                </template>

                                                <template v-slot:[`item.payment_method`]="{ item }">
                                                    <p class="mb-0">{{ item.payment_method !== '' ? item.payment_method : '--' }}</p>
                                                </template> 

                                                <template v-slot:[`item.deposit_to`]="{ item }">
                                                    <p class="mb-0">{{ item.deposit_to !== '' ? item.deposit_to : '--' }}</p>
                                                </template> 

                                                <template v-slot:[`item.original_amount`]="{ item }">				
                                                    <div class="mb-0">
                                                        <p class="mb-0">{{ item.original_amount !== '' ? item.original_amount : '--' }}</p>
                                                    </div>
                                                </template>
                                                <template v-slot:[`item.payment`]="{ item }">
                                                    <v-text-field 
                                                        v-model=item.payment
                                                        outlined 
                                                        class="text-fields my-2"
                                                        :rules="rules"
                                                        hide-details="auto">
                                                    </v-text-field>
                                                </template> 
                                                <template v-slot:[`item.open_balance`]="{ item }">
                                                    <p class="mb-0" style="white-space: nowrap">{{ item.open_balance !== '' ? item.open_balance : '--' }}</p>
                                                </template> 
                                                <template v-slot:[`item.edit`]="{ item }">
                                                    <v-btn v-if=!item.edit
                                                        class=" rounded"
                                                        fab
                                                        dark
                                                        small
                                                        outlined
                                                        tile
                                                        @click="recievepayment"
                                                        color="primary"
                                                        v-bind="attrs"
                                                        v-on="on"
                                                        >
                                                        <img src="../assets/icons/edit-blue.svg" alt="">
                                                    </v-btn>
                                                </template> 

                                                <template v-slot:no-data>
                                                    <div class="no-data-preloader mt-4" v-if="getpaymentsLoading">
                                                        <v-progress-circular
                                                            :size="40"
                                                            color="#0171a1"
                                                            indeterminate
                                                            v-if="getpaymentsLoading">
                                                        </v-progress-circular>
                                                    </div>
                                                </template>
                                            </v-data-table>
                                            <v-divider></v-divider>
                                        </div>
                                    </template>
                                    <v-row>
                                        <v-spacer>

                                        </v-spacer>
                                        <v-col cols="4">
                                            <template>
                                                <v-simple-table>
                                                    <template v-slot:default>
                                                    <tbody>
                                                        <tr>
                                                        <td style="border-bottom: none !important;" class="font-weight-bold text-right">Amount to Apply</td>
                                                        <td style="border-bottom: none !important;" class="font-weight-bold text-right ">$1500.00</td>
                                                        </tr>
                                                        <!-- <tr>
                                                        <td style="border-bottom: none !important;" class="font-weight-bold">Amount Recieved</td>
                                                        <td style="border-bottom: none !important;" class="font-weight-bold">$1500.00</td>
                                                        </tr> -->
                                                        <tr>
                                                        <td style="border-bottom: none !important;" class="font-weight-bold text-right">Amount to Credit</td>
                                                        <td style="border-bottom: none !important;" class="font-weight-bold text-right">$0</td>
                                                        </tr>
                                                    </tbody>
                                                    </template>
                                                </v-simple-table>
                                            </template>
                                                <!-- <v-col class="d-flex flex-row justify-end"> -->
                                        </v-col>
                                    </v-row>
                                    

                                    <v-row>
                                        <v-col class="d-flex flex-row justify-end">
                                            <v-btn class="btn-white" text @click="close">
                                            Clear Payment
                                        </v-btn>
                                        </v-col>
                                    </v-row>
                                    <!-- <v-row>
                                        <v-col cols="12" sm="12" md="12" lg="4" class="pb-0">
                                            <label class="text-item-label">Note</label>
                                            <v-textarea
                                                height="100px"
                                                class="text-fields"
                                                outlined
                                                name="input-7-4"
                                                placeholder="Type notes here" 
                                                
                                                :rules="rules"
                                                hide-details="auto">
                                            </v-textarea>
                                        </v-col>
                                        <v-col cols="12" sm="12" md="12" lg="4" class="pb-0">
                                            <label class="text-item-label">Attachment</label>
                                            <v-row class="d-flex flex-column py-2 mt-1" dense align="center" justify="center" style="border: 2px dashed #eee; border-radius: 5px;">
                                                <p style="color:#B4CFE0">
                                                    Browse or Drop Files
                                                </p>
                                                <v-btn   
                                                    class="text-capitalize elevation-0"
                                                    style="font-weight:600 !important; background-color:white; border: 1px solid #B4CFE0;color: #0171A1;"
                                                    @click="onButtonClick"
                                                    >
                                                    <img src="../assets/icons/upload.svg" alt="" width="20px" height="20px">
                                                    Upload
                                                </v-btn>
                                                <input
                                                    ref="uploader"
                                                    class="d-none"
                                                    type="file"
                                                    accept="image/*"
                                                    @change="onFileChanged"
                                                >
                                            </v-row>
                                        </v-col>   
                                    </v-row> -->
                                    <v-row class="mt-15" style="border-top: 2px solid #E1ECF0;">
                                    <v-col class="d-flex flex-row justify-end">
                                        <v-btn class="btn-blue mr-3" text @click="save">
                                        Save All
                                        </v-btn>
                                        <v-btn class="btn-blue mr-3" text>
                                        Previous
                                        </v-btn>
                                        <v-btn class="btn-white" text @click="close">
                                            Cancel
                                        </v-btn>
                                    </v-col> 
                                </v-row>
                                </v-col>   
                            </v-row>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </div>
    </v-container>
    
</template>
<script>
// import globalMethods from '../utils/globalMethods'
export default {
    name: "Invoices",
	components: {
        // globalMethods,
	},
    data: () => ({
    date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        page:1,
        selected: [],
        tabs: ["Summary", "Invoices"],
        currentTab: "Summary",
        select: null,
        singleSelect: false,
        items: [
            'Item 1',
            'Item 2',
            'Item 3',
            'Item 4',
        ],
        headers_outstanding: [
            {
                text: "DESCRIPTION",
                align: "start",
                sortable: false,
                value: "description",
                width: "25%", 
                fixed: true
            },
            {
                text: "DUE DATE",
                align: "start",
                value: "due_date",
                sortable: false,
                
                fixed: true
            },
            {
                text: "ORIGINAL AMOUNT",
                align: "start",
                value: "original_amount",
                sortable: false,
                
                fixed: true
            },
            {
                text: "OPEN BALANCE",
                align: "start",
                value: "open_balance",
                sortable: false,
                
                fixed: true
            },
            {
                text: "PAYMENT",
                align: "end",
                value: "payment",
                sortable: false,
                
                fixed: true
            },
        ],
        paymentOutstanding: [
          {
            description: 'Invoice #900675 (12/15/21)',
            due_date: '12/15/21',
            original_amount: '$1500.00',
            open_balance: '$1500.00',
            payment: '$1500.00',
          },
          {
            description: 'Invoice #900676 (12/25/21)',
            due_date: '12/25/21',
            original_amount: '$12500.50',
            open_balance: '$12500.50',
            payment: '$12500.50',
          }, 
        ],
    }),
    methods: {
        // ...globalMethods,
        setMainTab(value){
            this.currentTab = value
        },
         onButtonClick() {
            this.isSelecting = true
            window.addEventListener('focus', () => {
                this.isSelecting = false
            }, { once: true })

            this.$refs.uploader.click()
        },
        onFileChanged(e) {
            this.selectedFile = e.target.files[0]
            
            // do something
        },
    },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "payments")   
    },
};

</script>
<style scoped lang="scss">
.p1{
    font-weight: 600;
    font-size: 16px;
    line-height: 30px;
    /* identical to box height, or 187% */
    color: #819FB2;
}
.p2{
    font-weight: 600;
    font-size: 20px;
    line-height: 30px;
    /* identical to box height, or 150% */
    color: #4A4A4A;
}
.outstanding{
    font-weight: 500;
    font-size: 18px;
    line-height: 18px;
    /* or 100% */

    text-transform: uppercase;

    color: #819FB2;
}
.h-color{
    background-color: #F7F7F7 !important;
}
// .v-data-table-header tr th {
//     color: #819FB2 !important;
//     font-size: 12px;
//     text-transform: uppercase;
//     font-family: 'Inter-Regular', sans-serif;
//     padding: 11px 20px;
//     white-space: nowrap;
// }
@import '../assets/scss/pages_scss/payments/payments.scss';
@import '../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../assets/scss/buttons.scss';
</style>