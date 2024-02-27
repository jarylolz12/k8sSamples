<template>
    <div>
        <!-- <modal id="meModal" @modal-close="handleClose" style="height: 80%; width: 100%">
            <div
                @submit.prevent="handleSave"
                slot-scope="props"
                class="bg-white rounded-lg shadow-lg"
                style="width: 100%; height: 100%; overflow: scroll;"
            >
                <slot> -->
                    <div class="p-8 dx-viewport" style="height: 80%; width: 100%;">
                        <!-- <heading :level="2" class="mb-6">{{
                            __("Add Invoice")
                        }}</heading> -->
                        <div class="flex fiftyleft">
                            <label>QB Customer: </label>
                            <!-- <select class='form-control' v-model='qbCustId'>
                                <option value='0' >Select</option>
                                <option v-for='customer in customers' :key="customer.Id" :value='customer.Id'>{{ customer.FullyQualifiedName }}</option>
                            </select> -->
                            <customer-select
                                :services="services"
                                :customers="customers"
                                :resourceId="resourceId"
                                @clicked="onClickSelect"
                                @changed="onChangeSelect"
                            >
                            </customer-select>
                        </div>

                        <div class="flex fiftyright">
                            <label>Customer Email: </label> <input class="input-bordered" type="email" v-model='qbCustEmail'>
                        </div>

                        <br /><br /><br />

                        <div class="flex fiftyleft">
                            <label>Invoice #:</label>
                            <input class="input-bordered" type="text" v-model='qbInvNum'>
                        </div>

                        <div class="flex fiftyright">
                            <label>Billing Address: </label>
                            <textarea class="input-bordered" v-model='qbCustBillAdd'></textarea>
                        </div>

                        <br /><br /><br />

                        <div class="flex fiftyleft">
                            <label>Terms: </label>
                            <select v-model='qbTermId'>
                                <option value="0">--Select--</option>
                                <option v-for='term in terms' :key="term.Id" :value='term.Id'>{{ term.Name }}</option>
                            </select>
                        </div>

                        <div class="flex fiftyright">
                            <label>Invoice Date: </label> <input class="input-bordered" type="date" v-model='qbInvDate'>
                        </div>

                        <br /><br /><br />

                        <div class="flex fiftyleft">
                            <label>Due Date: </label> <input class="input-bordered" type="date" v-model='qbInvDueDate'>
                        </div>

                        <br /><br />

                        <lines-grid
                        :services="services"
                        :customers="customers"
                        :resourceId="resourceId"
                        @clicked="onClickGrid"
                        >
                        </lines-grid>

                        <!-- <button
                            type="button"
                            data-testid="cancel-button"
                            dusk="cancel-general-button"
                            @click.prevent="handleClose"
                            class="btn-default"
                            style="border:0 solid var(--black); background-color:var(--primary); color:var(--white); line-height: 2.25rem; text-shadow: 0px 1px 2px; box-sizing: inherit;"
                        >
                            {{ __("Cancel") }}
                        </button> -->

                        <button
                            type="button"
                            ref="addSaveButton"
                            data-testid="add-save-button"
                            dusk="add-save-add-invoice-button"
                            @click.prevent="handleSave"
                            class="btn-default"
                            style="border:0 solid var(--black); background-color:var(--primary); color:var(--white); line-height: 2.25rem; text-shadow: 0px 1px 2px; box-sizing: inherit;"
                        >
                            {{ __("Save") }}
                        </button>

                    </div>

                        <!-- <button id="confirm-delete-button" ref="confirmButton" data-testid="confirm-button" type="submit" class="btn btn-default btn-danger">{{ __(uppercaseMode) }}</button> -->

                <!-- </slot> -->

                <!-- <div class="bg-30 px-6 py-3 flex" style="height: 20%">
                    <div class="ml-auto">
                        <button
                            type="button"
                            data-testid="cancel-button"
                            dusk="cancel-general-button"
                            @click.prevent="handleClose"
                            class="btn text-80 font-normal h-9 px-3 mr-3 btn-link"
                        >
                            {{ __("Cancel") }}
                        </button>

                        <button
                            type="button"
                            ref="addSaveButton"
                            data-testid="add-save-button"
                            dusk="add-save-add-invoice-button"
                            @click.prevent="handleSave"
                            class="btn text-80 font-normal h-9 px-3 mr-3 btn-link"
                        >
                            {{ __("Save") }}
                        </button>

                        <button id="confirm-delete-button" ref="confirmButton" data-testid="confirm-button" type="submit" class="btn btn-default btn-danger">{{ __(uppercaseMode) }}</button> -->
                    <!--</div>
                </div> -->
            <!-- </div>
        </modal> -->
    </div>
</template>

<script>
import axios from 'axios';
import createAuthRefreshInterceptor from 'axios-auth-refresh';
//import { items } from './data.js';
/* import {AgGridVue} from "@ag-grid-community/vue";
import { ClientSideRowModelModule } from '@ag-grid-community/client-side-row-model'; */
import LinesGrid from './LinesGrid.vue';
import CustomerSelect from './CustomerSelect.vue';

import JQuery from 'jquery'
window.$ = JQuery

export default {
    name: "AddInvoiceModal",
    components: {
        LinesGrid,
        CustomerSelect
    },
    props: ['services', 'customers', 'terms', 'resourceId'],
    data() {
        return {
            events: [],
            items: [], //orderDataSource.slice(0),
            /* rowSelection: null, */
            qbCustId: 0,
            qbCustEmail: '',
            qbInvNum: '',
            qbCustBillAdd: '',
            qbInvDate: "",
            qbInvDueDate: "",
            qbTermId: 0,
            viewLines: false,
            errrors: [],
            gridValues: []
        };
    },
    created() {
        //
    },
    beforeMount() {
        //console.log(this.customers);
    },
    methods: {
        handleClose() {
            this.$emit("close");
        },
        onClickGrid (value) {
            //console.log(value) // someValue
            this.gridValues = value
        },
        onClickSelect (value) {
            /* console.log('Modal:'+value) */ // someValue
            this.qbCustId = value
        },
        onChangeSelect (value) {
            console.log(value) // someValue
            /* this.qbCustId = value[0] */
        },
        handleSave() {
            console.log('Modal:'+this.qbCustId)
            axios.post("/custom/qbo/invoices/add", {
                qbCustId: this.qbCustId,
                qbCustEmail: this.qbCustEmail,
                qbInvNum: this.qbInvNum,
                qbCustBillAdd: this.qbCustBillAdd,
                qbInvDate: this.qbInvDate,
                qbInvDueDate: this.qbInvDueDate,
                qbTermId: this.qbTermId,
                qbLines: this.gridValues,
                resourceId: this.resourceId
            })
            .then(response => {
                this.$emit("addSave");
                this.viewLines = true;
                let invoiceData = response.data;
                console.log(response.data);
                if (response.data == "success") {
                    console.log(response.data);
                    Nova.success('Invoice Saved Successfully!');
                }else{
                    Nova.error(response.data);
                }
            })
            .catch(e => {
                console.log(e)
                Nova.error(event);
                this.errors.push(e)
                if (response.data.message == 'Unauthenticated.' || response.message == 'Unauthenticated.'){
                    location.reload(true);
                }
            })
            //console.log(this.gridValues)
        },
        changeStyle() {
            $('#meModal > div:nth-child(1)').css('height' , '100%');
            $('#meModal > div:nth-child(1) > div:nth-child(1)').css('width' , '60%');
        },
        getAccessToken() {
            return localStorage.getItem('token');
        },
        mapCustomers() {
            const obj = {}

            for(let  i = 0 ; i < customers.length; i++){
                let name = customers[i].ID
                obj[customers[i].ID]=customers[i].Name;
            }

            var customerValues = this.extractValues(obj);
        }
    },

    /**
     * Mount the component.
     */
    mounted() {
    }
};

</script>

<style scoped>
    .input-bordered {
        border-width: 1px;
        border-color: black;
        width: auto;
    }

    .fiftyleft {
        float: left;
        /* width: 39% */
        font-size: 12px;
    }

    .fiftyright {
        float: right;
       /*  width: 39% */
       font-size: 12px;
    }

</style>
