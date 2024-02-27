<template>
    <div class="tabBlock w-full">
        <div v-if="qboCompanies.length > 0">
            <ul class="tabBlock-tabs">
                <li
                 v-for="(company, index) in qboCompanies"
                 :key="index"
                 :aria-setsize="qboCompanies.length"
                 :aria-posinet="index + 1"
                >
                    <a
                     href="javascript:void(0);"
                     class="tabBlock-tab"
                     :class="activeTab === index ? 'is-active' : ''"
                     :aria-selected="activeTab === index"
                     @click="changeTab(index)"
                    >
                        {{ company.company_name }}
                    </a>
                </li>
            </ul>
            <div class="tabBlock-content">
                <div
                v-for="(company, index) in qboCompanies"
                :key="index"
                :aria-current="activeTab ===  index"
                class="tabBlock-pane"
                v-show="activeTab === index"
                >
                    <detail-tab-content
                     ref="tabContent"
                     :tabRealmId="company.company_realm_id"
                     :invoices="invoices"
                     :bills="bills"
                     :projected_profit="projected_profit"
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

import DetailTabContent from "./DetailTabContent.vue";
export default {
    name:"DetailTabs",
    props: ["resourceId","qboCompanies","qboCompanyInfo"],
    components:{
        DetailTabContent,
    },
    data(){
        return {
            activeTab: 0,
            invoices:[],
            bills:[],
            projected_profit : ''
        };
    },
    computed:{
        //
    },
    created(){
        //
    },
    mounted(){
        this.fetchInvoices();
        this.fetchBills();
        this.fetchProjectedProfit();
    },

    methods: {
        changeTab: function(tabIndexValue) {
            this.activeTab = tabIndexValue;
        },
        
        fetchInvoices: function(){
            axios.get("/custom/invoices/by-shipment/"+this.resourceId)
            .then(response => {
                if(response.data.success){
                    const responseData = response.data.results;
                    if(responseData.length > 0){
                        this.invoices = responseData;
                    }
                }
            })
            .catch(e => {
                console.log(e);
            })
        },

        fetchBills: function(){
            axios.get("/custom/bill/by-shipment/"+this.resourceId)
            .then(response => {
                if(response.data.success){
                    const responseData = response.data.results;
                    if(responseData.length > 0){
                        this.bills = responseData;
                    }
                }
            })
            .catch(e => {
                console.log(e);
            });
        },
        fetchProjectedProfit(){
            let _this = this;

            axios.get("/custom/projected-profit/by-shipment/"+this.resourceId)
            .then(res => {
                _this.projected_profit = res.data.message;
            })
            .catch(e => {
                if( e ) console.log(e);
            });
        }

    },
    watch:{
        //
    },
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
