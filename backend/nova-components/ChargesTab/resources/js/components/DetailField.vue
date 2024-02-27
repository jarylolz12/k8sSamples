<template>
    <div class="border-b border-40">
        <div class="flex w-full">
            <label v-if="qboCompanyInfo !== null" style="font-style: italic; font-size: 0.8rem; margin-left:auto; color:#16A34A">You are connected to Quickbooks {{qboCompanyInfo.company.CompanyName}}</label>
            <!-- <label v-else style="font-style: italic; font-size: 0.8rem; margin-left:auto; color:red">{{qboConStatText}}</label> -->
        </div>
        <div v-if="qboCompanies.length > 0 && qboCompanyInfo !== null">
            <detail-tabs
             :resourceId="resourceId"
             :qboCompanyInfo="qboCompanyInfo"
             :qboCompanies="qboCompanies"
            />
        </div>
        <div v-else>
            <center>
                <label  class="font-semibold text-lg">{{loadingText}}</label>
            </center>
        </div>
        
    </div>
</template>

<script>

import DetailTabs from './DetailFieldComponents/DetailTabs.vue';

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    components:{
        DetailTabs
    },
    data(){
        return{
            qboCompanies: [],
            qboCompanyInfo: null,
            loadingText:"Fetching QuickBooks Accounts, please wait...",
            qboConStatText: 'Checking connection status...',
            invoices:[],
            bills:[],
        }
    },
    computed: {
        profit: function(){
            return this.income - this.totalBillAmount;
        }
    },
    mounted(){
        this.getQBOCompanyInfo();
        this.getQBOCompanies();
        console.log('merge 01-26-2023')
    },
    methods:{
        getQBOCompanyInfo: function(){
            axios.get("/custom/qbo/get-company-info-v2")
            .then( response =>{
                if (response.data.success === true) {
                    this.$store.commit('updateQBOCompanyInfo_CT',response.data.result);
                    this.qboCompanyInfo = response.data.result;
                }else{
                    this.qboConStatText = "You are not connected to QuickBooks.";
                    this.loadingText = 'Failed to get QuickBooks information. Please connect to QuickBooks to continue access to Charges.';
                }
            })
            .catch(e =>{
                this.qboConStatText = "You are not connected to QuickBooks."
                console.log(e);
                this.loadingText = 'Failed to get QuickBooks information. Please connect to QuickBooks to continue access to Charges.';
            });
        },
        getQBOCompanies: function(){
            axios.get('/custom/qbo/get-companies')
            .then( response =>{
                if(response.data.length > 0){ 
                    this.qboCompanies = response.data;
                }else{
                    this.loadingText = "No associated QuickBooks Accounts."
                }
            })
            .catch( e =>{
                console.log(e);
            })
        },
    }
}
</script>
