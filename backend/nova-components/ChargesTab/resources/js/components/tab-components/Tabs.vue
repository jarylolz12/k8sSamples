<template>
    <div class="tabBlock w-full">
        <div v-if="qboCompanies.length > 0">
            <ul class="tabBlock-tabs">
                <li
                 v-for="(company, index) in qboCompanies"
                 :key="company.company_realm_id"
                 :aria-setsize="qboCompanies.length"
                 :aria-posinet="index + 1"
                >
                    <a
                     href="javascript:void(0);"
                     class="tabBlock-tab"
                     :class="activeTab === company.company_realm_id ? 'is-active' : ''"
                     :aria-selected="activeTab === company.company_realm_id"
                     @click="changeTab(company.company_realm_id)"
                    >
                        {{ company.company_name }}
                    </a>
                </li>
            </ul>
            <div class="tabBlock-content">
                <div
                v-for="(company, index) in qboCompanies"
                :key="company.company_realm_id"
                :aria-current="activeTab === company.company_realm_id"
                class="tabBlock-pane"
                v-show="activeTab === company.company_realm_id"
                >
                    <tab-content
                     ref="tabContent"
                     :realmId="company.company_realm_id"
                     :companyInfo="company"
                     @openEditInvoiceModal="openEditInvoiceModal"
                     :index="index"
                    />
                </div>
                <hr />
                <!-- <div class="tabBlock-pane">
                
                </div> -->
            </div>
        </div>
        <div v-else>
            <center>
                <label  class="font-semibold text-lg">{{loadingText}}</label>
            </center>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import TabContent from './TabContent.vue';
import { mapGetters } from 'vuex'

export default {
    name:"Tabs",
    props: [],
    data(){
        return {
            activeTab: null,
            qboCompanies: [],
            loadingText:"Fetching QuickBooks Accounts, please wait..."
        };
    },
    computed:{
        ...mapGetters({
            showEditInvoiceModal: 'showEditInvoiceModal',
            showDeleteInvoiceModal: 'showDeleteInvoiceModal',
            showEditBillModal: 'showEditBillModal',
            showDeleteBillModal: 'showDeleteBillModal',
        })
    },
    components:{
        TabContent,
    },
    created(){
        this.getShiflOffices();
    },
    mounted(){
        const state = this.$store.state.chargesTab;
        this.activeTab = state.qboCompanyInfo.realm_id;
    },

    methods: {
        changeTab: function(tabIndexValue) {
            this.activeTab = tabIndexValue;
        },
        openEditInvoiceModal() {
            this.$emit('openEditInvoiceModal')
        },
        getShiflOffices: function(){
            axios.get('/custom/qbo/get-companies')
            .then( response =>{
                if(response.data.length > 0){ 
                    this.qboCompanies = response.data;
                    this.$store.commit('updateQBOCompanyAccount_CT',response.data)
                }else{
                    this.loadingText = "No associated QuickBooks Accounts."
                }
            })
            .catch( e =>{
                console.log(e);
            })
        },

    },
    watch:{
        /*
        showEditInvoiceModal(newVal,oldVal){
            if(newVal === true){
                this.$emit('openEditInvoiceModal');
            }else{
                this.$emit('closeEditInvoiceModal');
            }   
        },*/
        showDeleteInvoiceModal(newVal,oldVal){
            if(newVal === true){
                this.$emit('openDeleteInvoiceModal');
            }else{
                this.$emit('closeDeleteInvoiceModal');
            }  
        },
        showEditBillModal(newVal,oldVal){
            if(newVal === true){
                this.$emit('openEditBillModal');
            }else{
                this.$emit('closeEditBillModal');
            }  
        },
        showDeleteBillModal(newVal,oldVal){
            if(newVal === true){
                this.$emit('openDeleteBillModal');
            }else{
                this.$emit('closeDeleteBillModal');
            }  
        }
    }
};
</script>

<style lang="scss" scoped>
*,
::before,
::after {
  box-sizing: border-box;
}

body {
  color: #222;
  font-family: "Source Sans Pro", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
  line-height: 1.5;
  margin: 0 auto;
  max-width: 50rem;
  padding: 2.5rem 1.25rem;
}
  
/* ===================================================== */
  
$primary: #03a9f4;
$primary-faint: mix($primary, #fff, 50%);
$border: #d8d8d8;
$var90: #3c4b5f;
  
.tabBlock {
  margin: 0 0 0rem;
  
  &-tabs {
    list-style: none;
    margin: 0;
    padding: 0;
    z-index: 999;
    
    &::after {
      clear: both;
      content: "";
      display: table;
    }
  }
  
  &-tab {
    background-color: #fff;
    border-color: #ffffff;
    border-left-style: solid;
    border-top: solid;
    border-width: 2px;
    color: $var90;
    cursor: pointer;
    display: flex;
    align-items: center;
    font-weight: 600;
    float: left;
    padding: 0.625rem 1.25rem;
    position: relative;
    transition: 0.1s ease-in-out;
    text-decoration: none;
    
    &:last-of-type {
      border-right-style: solid;
    }
    
    &::before,
    &::after {
      content: "";
      display: block;
      height: 4px;
      position: absolute;
      transition: 0.1s ease-in-out;
    }
    
    &::before {
      background-color: #ffffff;
      left: -2px;
      right: -2px;
      top: -2px;
    }
    
    &::after {
      background-color: transparent;
      bottom: -2px;
      left: 0;
      right: 0;
    }
    
    &:hover,
    &:focus {
      color: $primary;
    }
    
    @media screen and (min-width: 700px) {
      padding-left: 2.5rem;
      padding-right: 2.5rem;
    }
    
    &.is-active {
      position: relative;
      color: $var90;
      z-index: 1;
      font-weight: 700;
      
      &::before {
        background-color: #ffffff;
        
      }
      
      &::after {
        background-color: #fff;
        border-bottom: 2px solid $primary;
      }
    }
    
    svg {
      height: 1.2rem;
      width: 1.2rem;
      margin-right: 0.5rem;
      pointer-events: none;
      fill: currentcolor;
    }
  }
  
  &-content {
    background-color: #fff;
    // border: 2px solid $border;
    padding-top: 1.25rem;
    //padding-bottom: 1.25rem;
  
    a {
      color: dodgerblue;
      font-weight: 700;
      transition: color 200ms ease;

      &:hover,
      &:focus {
        color: orangered;
      }
    }
  
    hr {
    //   margin: 1.5rem 0;
      margin-top: 1.5rem;
      border: 1px solid transparent;
      border-radius: 50%;
      border-top-color: $border;
    }
  }
}
</style>
