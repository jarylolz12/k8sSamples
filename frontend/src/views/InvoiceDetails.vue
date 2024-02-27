<template>
    <v-container fluid>        
        <div class="shipment-details-wrapper">
            <v-row>
                <v-col class="first-col" sm="12" md="12">
                    <div class="details-breadcrumbs">
                        <router-link to="/invoices" class="shipment-link">
                            Invoices
                        </router-link>

                        <span class="right-chevron"> 
                            <img src="../assets/images/right_chevron.svg" alt="" srcset="" width="7px"> 
                        </span>

                        <p class="shipment-ref">
                            #example
                        </p>
                    </div>
                    <div id="top-header" v-resize="onResize">
                        <div class="head-wrapper">
                            <div class="reference-status">
                                <h2>Invoice
                                    #Example
                                </h2>
                                <!-- <v-chip
                                class="ml-2 open"
                                >
                                    Open
                                </v-chip> -->
                                <!-- <v-chip
                                class="ml-2 paid"
                                    
                                    
                                >
                                    Paid
                                </v-chip> -->
                                <v-chip
                                class="ml-2 partially-paid"
                                >
                                    Partially Paid
                                </v-chip>
                                <!-- <v-chip
                                class="ml-2 overdue"
                                >
                                   Overdue
                                </v-chip> -->
                            </div>
                            <div>
                                <v-btn elevation="0" class="btn-white mx-1" @click.stop="addSupplier">
                                        Edit Invoice
                                </v-btn>
                                <v-btn class="btn-blue mx-1" @click.stop="recievepayment">
                                    <div class="white--text text-capitalize">
                                         Receive Payment
                                    </div>
                                </v-btn>
                            </div>

                        </div> 
                    </div>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                <div class="documents-wrapper" style="background:white; border: 1px solid #E1ECF0; position:relative; border-radius: 4px;">
                    <div class="pa-5">
                        <template>
                            <v-form>
                                <v-container>
                                <v-row>
                                    <v-col
                                    cols="4"
                                    >
                                    <p class="p1 mb-1">
                                        Customer
                                    </p>
                                    <p class="p2">Opulent Operators</p>
                                    <p class="p1 mb-1 mt-3">
                                        REFERENCE
                                    </p>
                                    <p class="p3">#SHIFL187958</p>
                                    </v-col>

                                    <v-col
                                    cols="4"
                                    >
                                        <p class="p1 mb-1">
                                            BILLING ADDRESS
                                        </p>
                                        <p class="p2" style="line-height: 20px; width: 165px; height: 59.22px;">Main Warehouse
                                            55 Bergenline Ave Unit C
                                            Westwood NJ 07675</p>
                                    </v-col>

                                    <v-col
                                    cols="4"
                                    
                                    >
                                        <div class="d-flex flex-row align-end">
                                            <div class="d-inline pr-2">
                                                <p class="p1">
                                                    ISSUE DATE
                                                </p>
                                            </div>
                                            <div class="d-inline pl-2">
                                                <p class="p2">Dec. 3, 2021</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row align-end">
                                            <div class="d-inline pr-2">
                                                <p class="p1">
                                                    DUE DATE
                                                </p>
                                            </div>
                                            <div class="d-inline pl-5">
                                                <p class="p2 ">Dec. 15, 2021</p>
                                            </div>
                                        </div>
                                    </v-col>
                                </v-row>
                                </v-container>
                            </v-form>
                        </template>
                    </div>
                    <v-tabs class="customTab" height="64px" style="border-top: 2px solid #E1ECF0; border-bottom: 1px solid #E1ECF0;">
                        <v-tab v-for="n in tabs" :key="n" v-on:click="setMainTab(n)" :class="n">
                            {{ n }}
                        </v-tab>
                    </v-tabs>
                    

                    <div v-if="currentTab == 'Summary'">
                        <v-row no-gutters>
                            <v-col lg="10">
                                <template>
                                    <div class="supplier-content-wrapper">
                                        <v-data-table
                                            :headers="headers"
                                            mobile-breakpoint="769"
                                            :items="invoiceDetails"
                                            class="suppliers-table"
                                            :class="invoiceDetails !== null && invoiceDetails.length !== 'undefined' && invoiceDetails.length !== 0 ? '' : 'no-data-table'"
                                            :search="search"
                                            :page.sync="page"
                                            :items-per-page="itemsPerPage"
                                            @page-count="pageCount = $event"
                                            hide-default-footer
                                            fixed-header>


                                            <template v-slot:[`item.product_service`]="{ item }">
                                                <div class="email-wrapper" v-if="item.product_service !== ''">
                                                    <div class="email-container">
                                                        <p class="mb-0" style="white-space: nowrap">{{ item.product_service }}</p>
                                                    </div>
                                                </div>

                                                <div v-if="item.product_service == ''">
                                                    <p class="mb-0" style="color: #4a4a4a;">--</p>
                                                </div>
                                            </template>

                                            <template v-slot:[`item.hsn_sac`]="{ item }">
                                                <p class="mb-0">{{ item.hsn_sac !== '' ? item.hsn_sac : '--' }}</p>
                                            </template> 

                                            <template v-slot:[`item.description`]="{ item }">
                                                <p class="mb-0">{{ item.description !== '' ? item.description : '--' }}</p>
                                            </template> 

                                            <template v-slot:[`item.qty`]="{ item }">				
                                                <div class="mb-0">
                                                    {{item.qty}}
                                                </div>
                                            </template>
                                            <template v-slot:[`item.rate`]="{ item }">
                                                <p class="mb-0">{{ item.rate !== '' ? item.rate : '--' }}</p>
                                            </template> 
                                            <template v-slot:[`item.amount`]="{ item }">
                                                <p class="mb-0">{{ item.amount !== '' ? item.amount : '--' }}</p>
                                            </template> 
                                            <template v-slot:[`item.tax`]="{ item }">
                                                <p class="mb-0" style="white-space: nowrap">{{ item.tax !== '' ? item.tax : '--' }}</p>
                                            </template> 

                                            <template v-slot:no-data>
                                                <div class="no-data-preloader mt-4" v-if="getSuppliersLoading">
                                                    <v-progress-circular
                                                        :size="40"
                                                        color="#0171a1"
                                                        indeterminate
                                                        v-if="getSuppliersLoading">
                                                    </v-progress-circular>
                                                </div>
                                            </template>
                                        </v-data-table>
                                    </div>
                                </template>
                                <v-divider></v-divider>
                                <v-row>
                                    <v-spacer>

                                    </v-spacer>
                                    <v-col cols="4">
                                        <template>
                                            <v-simple-table>
                                                <template v-slot:default>
                                                <tbody>
                                                    <tr>
                                                    <td class="text-right">Subtotal</td>
                                                    <td class="text-right">$7061.25</td>
                                                    </tr>
                                                    <tr>
                                                    <td class="text-right">Tax</td>
                                                    <td class="text-right">$1172.56</td>
                                                    </tr>
                                                    <tr>
                                                    <td style="border-bottom: none !important;" class="font-weight-bold text-right">Total</td>
                                                    <td style="border-bottom: none !important;" class="font-weight-bold text-right ">$8233.81</td>
                                                    </tr>
                                                    <!-- <tr>
                                                    <td style="border-bottom: none !important;" class="font-weight-bold">Amount Recieved</td>
                                                    <td style="border-bottom: none !important;" class="font-weight-bold">$1500.00</td>
                                                    </tr> -->
                                                    <tr>
                                                    <td style="border-bottom: none !important;" class="font-weight-bold text-right">Balance Due</td>
                                                    <td style="border-bottom: none !important;" class="font-weight-bold text-right">$8233.31</td>
                                                    </tr>
                                                </tbody>
                                                </template>
                                            </v-simple-table>
                                        </template>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col lg="2" style="border-top: 1px solid #E1ECF0; border-left: 2px solid #E1ECF0;">
                                <h4 class="activity mt-4 ml-3">INVOICE ACTIVITY</h4>
                                <!-- <template>
                                    <v-stepper
                                    class="ma-0 pa-0 elevation-0"
                                        v-model="e6"
                                        vertical
                                    >
                                        <v-stepper-step
                                        :complete="e6 > 1"
                                        @click="e6 =2"
                                        step="1"
                                        >
                                        <h4 class="step">Opened</h4>
                                        <small class="step-subheading">12/03/2021</small>
                                        </v-stepper-step>
                                        <v-stepper-content step="1">
                                            <v-card
                                                flat
                                                class=""
                                                height="8px"
                                            >
                                            
                                            </v-card>
                                        </v-stepper-content>

                                        <v-stepper-step
                                        :complete="e6 > 2"
                                        step="2"
                                        @click="e6 = 3"
                                        >
                                        <h4 class="step-disabled"> Sent</h4>
                                        </v-stepper-step>

                                        <v-stepper-content step="2">
                                            <v-card
                                                flat
                                                class=""
                                                height="8px"
                                            >
                                            </v-card>
                                        </v-stepper-content>
                                        <v-stepper-step
                                        :complete="e6 > 3"
                                        @click="e6 =4"
                                        step="3"
                                        >
                                        <h4 class="step-disabled"> Sent</h4>
                                        </v-stepper-step>
                                        <v-stepper-content step="3">
                                            <v-card
                                                flat
                                                class=""
                                                height="8px"
                                            >
                                            </v-card>
                                        </v-stepper-content>

                                        <v-stepper-step step="4" 
                                        >
                                        <h4 class="step-disabled"> Sent</h4>
                                        </v-stepper-step>

                                    </v-stepper>
                                </template> -->
                                <template>
                                    <v-timeline
                                    align-top
                                    dense
                                    
                                    >
                                    <v-timeline-item
                                        align-top
                                        fill-dot
                                        color="#0171A1"
                                        small
                                        style="padding-left:0px !important;"
                                    >
                                        <template v-slot:icon>
                                        <img src="../assets/icons/radio.svg"> 
                                        </template>
                                        <h4 class="step">Opened</h4>
                                        <small class="step-subheading">12/03/2021</small>
                                        
                                    </v-timeline-item>
                                    <v-timeline-item
                                    align-top
                                        fill-dot
                                        class="white--text"
                                        color="#0171A1"
                                        small
                                    >
                                        <template v-slot:icon>
                                        <img src="../assets/icons/radio2.svg"> 
                                        </template>
                                        <h4 class="step-disabled">Sent</h4>
                                        <small class="step-subheading">06/29/2021</small>
                                       
                                    </v-timeline-item>
                                    <v-timeline-item
                                        fill-dot
                                        class="white--text"
                                        color="#0171A1"
                                        small
                                    >
                                        <template v-slot:icon>
                                        <img src="../assets/icons/radio2.svg"> 
                                        </template>
                                        <h4 class="step-disabled">Paid</h4>
                                        <small class="step-subheading">
                                            06/30/2021 
                                            $1,200.00 out of $6,210.00
                                        </small>
                                        <br>
                                        <small class="view-payment">
                                            View Payment
                                        </small>
                                    </v-timeline-item>
                                    <v-timeline-item
                                        fill-dot
                                        class=""
                                        color="#0171A1"
                                        small
                                    >
                                        <template v-slot:icon>
                                        <img src="../assets/icons/radio2.svg"> 
                                        </template>
                                        <h4 class="step-disabled">Deposited</h4>
                                        <small class="step-subheading">
                                            07/01/2021 
                                            $800.00 out of $5,010.00
                                        </small>
                                        <br>
                                        <small class="view-payment">
                                            View Payment
                                        </small>
                                    </v-timeline-item>

                                    </v-timeline>
                                </template>
                            </v-col>
                        </v-row>

                    </div>
                    <div v-if="currentTab == 'Payments'">
                        <div style="height:441px;">
                            <!-- <div class="pa-15">
                                <h4 class="text-center">
                                    No Payments
                                </h4>
                                <p class="text-center">No payments has been made for this invoice yet.</p>
                            </div> -->
                            <template>
                                <div class="supplier-content-wrapper">
                                    <v-data-table
                                        :headers="headers_payments"
                                        mobile-breakpoint="769"
                                        :items="paymentDetails"
                                        class="suppliers-table"
                                        :class="paymentDetails !== null && paymentDetails.length !== 'undefined' && paymentDetails.length !== 0 ? '' : 'no-data-table'"
                                        :search="search"
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
                                            <p class="mb-0">{{ item.payment !== '' ? item.payment : '--' }}</p>
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
                                            <div class="no-data-preloader mt-4" v-if="getSuppliersLoading">
                                                <v-progress-circular
                                                    :size="40"
                                                    color="#0171a1"
                                                    indeterminate
                                                    v-if="getSuppliersLoading">
                                                </v-progress-circular>
                                            </div>
                                        </template>
                                    </v-data-table>
                                    <v-divider></v-divider>
                                </div>
                            </template>
                            
                        </div>
                        <!-- <ShipmentDocuments :getDetails="getShipmentDetails" /> -->
                    </div>
                    </div>
                </v-col>
            </v-row>
        </div>
        <template>
            <v-dialog v-model="dialog" max-width="718px" content-class="add-supplier-dialog" v-resize="onResize" :retain-focus="false">
                <v-card class="add-supplier-card">
                    <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                        <v-card-title>
                            <!-- <span class="headline">Recieve Payment</span> -->
                            <span class="headline">Edit Payment</span>

                            <button icon dark class="btn-close" @click="close">
                                <v-icon>mdi-close</v-icon>
                            </button>
                        </v-card-title>

                        <v-card-text>
                            <v-row class="mt-1">
                                <v-col cols="6" >
                                    <div class="d-flex flex-row align-end">
                                        <div class="d-inline pr-2">
                                            <p class="p1-modal">
                                                INVOICE #
                                            </p>
                                        </div>
                                        <div class="d-inline pl-5">
                                            <p class="p2-modal ">1000148</p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row align-end">
                                        <div class="d-inline pr-2">
                                            <p class="p1-modal">
                                                REFERENCE
                                            </p>
                                        </div>
                                        <div class="d-inline pl-5">
                                            <p class="p2-modal-blue">#SHIFL187958</p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row align-end">
                                        <div class="d-inline pr-2">
                                            <p class="p1-modal">
                                                ISSUE DATE
                                            </p>
                                        </div>
                                        <div class="d-inline pl-5">
                                            <p class="p2-modal ">Dec 3, 2021</p>
                                        </div>
                                    </div>
                                </v-col>
                                <v-col cols="6">
                                    <div class="d-flex flex-row align-end">
                                        <div class="d-inline pr-2">
                                            <p class="p1-modal">
                                                DUE DATE
                                            </p>
                                        </div>
                                        <div class="d-inline pl-5">
                                            <p class="p2-modal ">Dec 15, 2021</p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row align-end">
                                        <div class="d-inline pr-2">
                                            <p class="p1" style="font-weight: 600; font-size: 14px;">
                                                BALANCE DUE
                                            </p>
                                        </div>
                                        <div class="d-inline pl-5">
                                            <p class="p2 ">$8,233.31</p>
                                        </div>
                                    </div>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" sm="12" md="12" lg="5" class="pb-0 pt-0 mt-0">
                                    <label class="text-item-label">Payment Date</label>
                                    <v-text-field 
                                        placeholder="Select Payment Date" 
                                        outlined 
                                        class="text-fields"
                                        
                                        :rules="rules"
                                        hide-details="auto">
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" lg="5" class="pb-0">
                                    <label class="text-item-label">Payment Method</label>
                                    <v-text-field 
                                        placeholder="Select Payment Method" 
                                        outlined 
                                        class="text-fields"
                                        
                                        :rules="rules"
                                        hide-details="auto">
                                    </v-text-field>
                                </v-col>
                                <v-spacer></v-spacer>
                            </v-row>
                            <v-row>
                                <v-col cols="12" sm="12" md="12" lg="5" class="pb-0">
                                    <label class="text-item-label">Deposit To</label>
                                    <v-text-field 
                                        placeholder="Select Payment Date" 
                                        outlined 
                                        class="text-fields"
                                        
                                        :rules="rules"
                                        hide-details="auto">
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" lg="5" class="pb-0">
                                    <label class="text-item-label ">Amount Recieved</label>
                                    <v-text-field 
                                        placeholder="Select Payment Method" 
                                        outlined 
                                        class="text-fields text-right"
                                        
                                        :rules="rules"
                                        hide-details="auto">
                                    </v-text-field>
                                </v-col>
                                <v-spacer></v-spacer>
                            </v-row>
                            <v-row>
                                <v-col cols="12" sm="12" md="12" lg="6" class="pb-0">
                                    <label class="text-item-label">Attachment</label>
                                    <v-row class="d-flex flex-column py-2 mt-1" dense align="center" justify="center" style="border: 2px dashed #eee; border-radius: 5px;">
                                        <p class="" style="color:#B4CFE0">
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
                                <v-col cols="12" sm="12" md="12" lg="6" class="pb-0">
                                    <label class="text-item-label">Note</label>
                                    <v-textarea
                                        height="95px"
                                        class="text-fields"
                                        outlined
                                        name="input-7-4"
                                        placeholder="Type notes here" 
                                        
                                        :rules="rules"
                                        hide-details="auto">
                                    </v-textarea>
                                </v-col>
                            </v-row>
                        </v-card-text>

                        <v-card-actions style="border-top:0px !important;">
                            <v-btn class="btn-blue" text @click="save">
                            Save
                            </v-btn>

                            <v-btn class="btn-white" text @click="close" v-if="!isMobile">
                                Cancel
                            </v-btn>
                        </v-card-actions>
                    </v-form>
                </v-card>
            </v-dialog>
        </template>
    </v-container>
    
</template>

<script>
// import ShipmentInfo from "@/components/ShipmentInfo.vue";
// import ShipmentDocuments from "@/components/ShipmentDocuments.vue";
import { mapActions, mapGetters } from "vuex";
import moment from 'moment'
import _ from 'lodash'

export default {
    components: {
        // ShipmentInfo,
        // ShipmentDocuments,
    },
    data: () => ({
        e6: 1,
        page:1,
        pageCount: 0,
        itemsPerPage: 35,
        dialog:false,
        valid: true,
        headers_payments: [
            {
                text: "PAYMENT DATE",
                align: "start",
                sortable: false,
                value: "payment_date",
                width: "25%", 
                fixed: true
                
            },
            {
                text: "PAYMENT METHOD",
                align: "start",
                value: "payment_method",
                sortable: false,
                width: "20%", 
                fixed: true
            },
            {
                text: "DEPOSIT TO",
                align: "start",
                value: "deposit_to",
                sortable: false,
                width: "25%", 
                fixed: true
            },
            {
                text: "ORIGINAL AMOUNT",
                align: "start",
                value: "original_amount",
                sortable: false,
                width: "20%", 
                fixed: true
            },
            {
                text: "PAYMENT",
                align: "center",
                value: "payment",
                sortable: false,
                width: "10%", 
                fixed: true
            },
            {
                text: "OPEN BALANCE",
                align: "center",
                value: "open_balance",
                sortable: false,
                width: "10%", 
                fixed: true
            },
            {
                text: "",
                align: "center",
                value: "edit",
                sortable: false,
                width: "10%", 
                fixed: true
            },
        ],
        paymentDetails: [
          {
            payment_date: 'June 30, 2021',
            payment_method: 'Cash',
            deposit_to: '',
            original_amount: '$6,210.00',
            payment: '$1,200.00',
            open_balance: '$5,010.00',
          },
          {
            payment_date: 'July 1, 2021',
            payment_method: 'Cash',
            deposit_to: '',
            original_amount: '$5,010.00',
            payment: '$800.00',
            open_balance: '$4,210.00',
          }, 
        ],
        headers: [
            {
                text: "PRODUCT/SERVICE",
                align: "start",
                sortable: false,
                value: "product_service",
                width: "25%", 
                fixed: true
            },
            {
                text: "HSN/SAC",
                align: "start",
                value: "hsn_sac",
                sortable: false,
                width: "20%", 
                fixed: true
            },
            {
                text: "DESCRIPTION",
                align: "start",
                value: "description",
                sortable: false,
                width: "25%", 
                fixed: true
            },
            {
                text: "QTY",
                align: "start",
                value: "qty",
                sortable: false,
                width: "20%", 
                fixed: true
            },
            {
                text: "RATE",
                align: "center",
                value: "rate",
                sortable: false,
                width: "10%", 
                fixed: true
            },
            {
                text: "AMOUNT",
                align: "center",
                value: "amount",
                sortable: false,
                width: "10%", 
                fixed: true
            },
            {
                text: "TAX",
                align: "center",
                value: "tax",
                sortable: false,
                width: "10%", 
                fixed: true
            },
        ],
        invoiceDetails: [
          {
            product_service: 'Commission and brokerage',
            hsn_sac: 996111,
            description: 'USD 0.20 X 3989 KG, EX-74.37',
            qty: 1,
            rate: '$5,830.50',
            amount: '$5,830.50',
            tax:'18.0% GST',
          },
          {
            product_service: 'Commission and brokerage',
            hsn_sac: 996112,
            description: 'USD 0.20 X 3989 KG, EX-74.37',
            qty: 1,
            rate: '$1,230.75',
            amount: '$1,230.75',
            tax: '10.0% GST',
          },
        ],
        tabs: ["Summary", "Payments"],
        tabs2: ["Shipments"],
        currentTab: "Summary",
        currentTabMileStone : "Shipments",        
        isMobile: false,
        shipment_id: null,
        title: '',
        detailsLoading: true,
        location_to_with_details: null,
        shipment_status: null,
        term_id: null,
        items: [
            {
                text: "Shipments",
                disabled: false,
                href: "/shipments",
            },
            {
                text: '#',
                disabled: true,
                href: "breadcrumbs_link_1",
            },
        ],
        fetchMilestoneLoading: true,
        mbl_num: '',
        shipment_mode: '',
        shipment_type: ''
    }),
    methods:{
        close(){
            this.dialog=false;
        },
        recievepayment(){
            this.dialog=true;
        },
        ...mapActions(["fetchShipmentDetails", "fetchMilestones", "setMilestonesNewLoading", "setMilestonesOtherEvents", "fetchTerms", "fetchContainers"]),
        setMainTab(value){
            this.currentTab = value
        },
        setMilestoneTab(value){
            this.currentTabMileStone = value
        },
        onResize() {
            if (window.innerWidth < 960) {
                this.isMobile = true;
            } else {
                this.isMobile = false;
            }
        },
        isDisabled(value) {
            if(value == 'Container' && this.shipment_status == 'Pending Approval') {
                this.isDisabled = true
            }
        },
        async loadShipmentMetaData() {
            this.setMilestonesOtherEvents([])
            this.setMilestonesNewLoading(true)
            
            // this.fetchTerms()
            // this.fetchContainers()
            
            try {                
                await this.fetchShipmentDetails(this.shipment_id)
                this.detailsLoading = false
                this.title = this.getShipmentDetails.shifl_ref

                let etaDetails = this.getShipmentDetails.eta !== null 
                                ? ', ETA ' + moment.utc(this.getShipmentDetails.eta).format('MMM DD, YYYY') 
                                : ''
                let to_name = this.getShipmentDetails.location_to_name !== null && this.getShipmentDetails.location_to_name !== ""
                                ? this.getShipmentDetails.location_to_name : "No Name"
                this.location_to_with_details = to_name !== "No Name" ? `${to_name}${etaDetails}` : ''
                this.mbl_num = this.getShipmentDetails.mbl_num !== null ? this.getShipmentDetails.mbl_num : null

                // get shipment mode
                this.getShipmentDetails.schedules_group_bookings = 
                    typeof this.getShipmentDetails.schedules_group_bookings !== 'undefined' &&
                    this.getShipmentDetails.schedules_group_bookings !== '' &&
                    this.getShipmentDetails.schedules_group_bookings !== null &&
                    JSON.parse(this.getShipmentDetails.schedules_group_bookings.length) > 0
                    ? JSON.parse(this.getShipmentDetails.schedules_group_bookings)
                    : []

                if (this.getShipmentDetails.schedules_group_bookings !== 'undefined' && this.getShipmentDetails.schedules_group_bookings !== '') {
                    this.shipment_mode = (typeof _.find(this.getShipmentDetails.schedules_group_bookings, e => (e.is_confirmed==1))!=='undefined') ? _.find(this.getShipmentDetails.schedules_group_bookings, e => (e.is_confirmed==1)).mode : null  
                    
                    this.shipment_type = (typeof _.find(this.getShipmentDetails.schedules_group_bookings, e => (e.is_confirmed==1))!=='undefined') ? _.find(this.getShipmentDetails.schedules_group_bookings, e => (e.is_confirmed==1)).type : null  
                }

                // get shipment status
                const setTo = moment.utc(moment()).format('YYYY-MM-DD HH:mm:ss')
                const setFrom = moment
                    .utc(moment(this.getShipmentDetails.eta))
                    .format('YYYY-MM-DD HH:mm:ss')

                // check if the eta is already past 10 days
                let eta_diff_from_today = moment.duration(
                    moment(setTo).diff(setFrom)
                )

                // check now how many days since eta
                let eta_diff_from_today_as_days = eta_diff_from_today.asDays()

                if (this.getShipmentDetails.shipment_status !== 'Completed') {
                    if (this.getShipmentDetails.booking_confirmed === 0 && this.getShipmentDetails.cancelled === 0) {
                        this.shipment_status = 'Pending Approval'
                    }

                    if (this.getShipmentDetails.booking_confirmed === 1) {
                        if (this.getShipmentDetails.status_v2 !== null) {
                            if (this.getShipmentDetails.status_v2 == 'In transit - hold') {
                                this.shipment_status = 'In Transit - Pending Release'
                            } else {                                
                                this.shipment_status = this.getShipmentDetails.status_v2
                            }
                        } else {
                            if (moment(this.getShipmentDetails.etd).format('x') > moment().format('x')) {
                                this.shipment_status = 'Awaiting Departure'
                            }
            
                            if (moment(this.getShipmentDetails.etd).format('x') < moment().format('x')) {
                                this.shipment_status = 'In Transit'
                            }
                        }
                    }

                    if (parseInt(eta_diff_from_today_as_days) >= 10) {
                        if (this.getShipmentDetails.status_v2 !== null) {
                            this.shipment_status = this.getShipmentDetails.status_v2
                        } else {
                            this.shipment_status = 'Completed'
                        }
                    }
                }
            } catch(e) {
                console.log(e)
                this.detailsLoading = false
            }

            try {
                if (this.mbl_num !== '') {
                    await this.fetchMilestones(this.mbl_num)                
                    this.fetchMilestoneLoading = false
                } else {
                    this.setMilestonesNewLoading(false)
                }
            } catch (e) {
                console.log(e)
                this.setMilestonesNewLoading(false)
                this.fetchMilestoneLoading = false
            }

            //set current page
            this.$store.dispatch("page/setPage", "shipments")
        },
    },
    async mounted() {
        this.shipment_id = this.$route.params.id
        this.loadShipmentMetaData()
    },
    async updated() {
        if (this.shipment_id!==this.$route.params.id) {
            this.shipment_id = this.$route.params.id
            this.loadShipmentMetaData()
        }
    },
    created() {},
    computed: {
        ...mapGetters(["getShipmentDetails", "getShipmentDetailsLoading", "getMilestonesNewLoading", "getShipmentTerms", "getShipmentContainerSizes"]),
    },
    watch: {
        title() {
            this.items[1].text = this.title
        }
    }
};
</script>

<style lang="scss">
.p1{
font-size: 12px;
font-weight: 500;
color: #819FB2;
text-transform: uppercase;
}
.p1-modal{
font-weight: 600;
font-size: 10px;
color: #819FB2;
text-transform: uppercase;
}
.p2-modal{
    font-weight: 500;
    font-size: 12px;
    color: #4A4A4A;
}
.p2-modal-blue{
    font-weight: 400;
    font-size: 12px;
    color: #0171A1;
;
}
.p2{
    font-size: 14px;
    color: #4A4A4A;
}
.p3{
    font-size: 14px;
    text-transform: uppercase;
    color: #0171A1;
}
.activity{
    font-weight: 600;
    font-size: 14px;
    line-height: 20px;
    /* or 143% */
    color: #4A4A4A;
}
.step{
    font-weight: 600;
    font-size: 14px;
    line-height: 20px;
    /* or 143% */


    color: #0B6084;
}
.step-disabled{
    font-weight: 600;
    font-size: 14px;
    line-height: 20px;
    /* or 143% */


    color: #B4CFE0;
}
.step-subheading{
    font-weight: 500;
    font-size: 10px;
    line-height: 20px;
    /* or 200% */


    color: #819FB2 !important;
}


.v-timeline:before{
    content: "";
    height: 68% !important;
    position: absolute !important;
    top: auto !important;
    bottom: auto !important;
}
.v-application--is-ltr .v-timeline--dense:not(.v-timeline--reverse):before {
    left: 29px !important;
    right: initial;
}
.v-timeline-item__divider {
    position: relative;
    min-width: 60px !important;
    display: flex;
    align-items: center;
    justify-content: center;
}
.v-timeline--dense .v-timeline-item {
    flex-direction: row-reverse !important;
    justify-content: start;
}
.v-chip{
    font-weight: 500 !important;
    min-width: 69px;
    min-height: 30px;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;

}
.paid{
    background: #EBFAEF !important;
    border: 1px solid rgba(73, 175, 65, 0.12) !important;
    box-sizing: border-box !important;
    border-radius: 99px !important;
    color: #2DC48E !important;
    }
.partially-paid{
    color: #2DC48E !important;
    background: #FFFFFF !important;
    border: 1px solid #EBF2F5 !important;
    box-sizing: border-box !important;
    border-radius: 99px !important;
}
.open{
    color: #4A4A4A !important;
    background: #FFFFFF !important;
    border: 1px solid #EBF2F5 !important;
    box-sizing: border-box !important;
    border-radius: 99px !important;
}
.overdue{
    color: #F93131 !important;
    background: #FFFFFF !important;
    /* Grey BG */

    border: 1px solid #EBF2F5 !important;
    box-sizing: border-box !important;
    border-radius: 99px !important;
}
// .v-data-table-header tr th {
//     color: #819FB2 !important;
//     font-size: 12px;
//     text-transform: uppercase;
//     font-family: 'Inter-Regular', sans-serif;
//     padding: 11px 20px;
//     white-space: nowrap;
// }
.text-item-label{
    font-size: 10px !important;
}
.view-payment{
    font-weight: 600;
font-size: 10px;
line-height: 20px;
/* identical to box height, or 200% */
color: #039DDC;
}
@import '../assets/scss/pages_scss/invoice/invoice.scss';
@import '../assets/scss/pages_scss/invoice/invoiceDetails.scss';
@import '../assets/scss/buttons.scss';
@import '../assets/css/dialog_styles/dialogHeader.css';
@import '../assets/css/dialog_styles/dialogBody.css';
@import '../assets/css/dialog_styles/dialogFooter.css';
@import '../assets/css/dialog_styles/deleteDialog.css';
</style>
