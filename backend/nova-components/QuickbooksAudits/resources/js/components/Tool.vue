<template>
    <div>
        <heading class="mb-6">Quickbooks Audits</heading>

        <card v-if="qboCompanyInfo != null" class="flex flex-col items-center py-6 px-8" style="min-height: 300px">
            <div class="flex w-full">
                <label v-if="qboCompanyInfo !== null && qboCompanyInfo.CompanyName !== null" style="font-style: italic; font-size: 1rem; margin-left:auto; color:#16A34A">You are connected to Quickbooks {{qboCompanyInfo.CompanyName}}</label>
            </div>
            <!-- <div v-if="qboCompanyInfo != null"> You are connected to Quickbooks Company: {{qboCompanyInfo.CompanyName}} </div> -->
            <div class="flex w-full mt-6">
                <div class="flex-1 pr-4">
                    <div class="flex items-center">
                        <label class="text-lg mr-4 font-semibold">From:</label>
                        <input v-model="dateFrom" class="flex-1 form-control form-input form-input-bordered" type="date" style="border-radius:0px;">  
                    </div>
                </div>
                 <div class="flex-1 pl-4">
                    <div class="flex items-center">
                        <label class="text-lg mr-4 font-semibold">To:</label>
                        <input v-model="dateTo" class="flex-1 form-control form-input form-input-bordered" type="date" style="border-radius:0px;">  
                    </div>
                </div>
                <div class="flex-1"></div>
                <div class="flex-1"></div>
            </div>
                <!-- <div class="flex-1">
                    <div class="flex-nowrap">                        
                        <label class="">To:</label>
                        <input v-model="dateTo" class="w-full form-control form-input form-input-bordered" type="date" style="border-radius:0px;">  
                    </div>
                </div> -->
            <div class="flex w-full mt-6 pt-4">
                <div class="flex-1 mr-2">
                    <invoices-mismatch
                    :dateFrom="dateFrom"
                    :dateTo="dateTo"
                    />
                </div>
                <div class="flex-1 ml-2 mt-6">
                    <bills-mismatch
                    :dateFrom="dateFrom"
                    :dateTo="dateTo"
                    />
                </div>
            </div>
        </card>
        <card v-else class="flex flex-col items-center py-6 px-8" style="min-height: 300px">
            <h3>You are not connected, please connect to Quickbooks.</h3>
        </card>
    </div>
</template>

<script>

import CdcInvoice from './CdcInvoice';
import InvoicesMismatch from './Invoice/InvoicesMismatch.vue';
import BillsMismatch from './Bill/BillsMismatch';
export default {
    metaInfo() {
        return {
          title: 'QuickbooksAudits',
        }
    },
    components:{
        CdcInvoice,
        InvoicesMismatch,
        BillsMismatch,
    },

    data(){
        return{
            qboCompanyInfo:null,
            dateFrom:'',
            dateTo:'',
        }
    },
    created(){
        axios.get("/nova-vendor/quickbooks-audits/get-qbo-company-info")
        .then( response =>{
            if (response.data.success === true) {
                this.qboCompanyInfo =  response.data.result;
            }
        })
        .catch(e =>{
            console.log(e)
        })
    },
    mounted() {
        this.dateFrom =  new Date().toISOString().slice(0,10);
        this.dateTo =  new Date().toISOString().slice(0,10);
    },
    methods:{

    }
}
</script>

<style>
    .modal {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.65);
        z-index: 23;
    }

    .modal__body {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);

        display: flex;
        flex-direction: column;
        width:50%;
        min-height: 200px;

        background: #fff;
        border-radius: 4px;
        padding: 20px;
    }

    .modal__content {
        flex: 1 0 90%;
    }

    .modal__footer {
        flex: 0 0 10%;
        display: flex;
        justify-content: center;
    }
    .vgt-wrap{
        width: 100%;
    }
</style>
