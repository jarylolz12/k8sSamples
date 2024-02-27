<template>
   <div>
      <div :class="'justmodal-shadow '+( open ? 'active' : '' )"></div>
      <div :class="'justmodal-wrapper '+( open ? 'active' : '' )" @click="open=false">
         <div class="justmodal-container" @click.stop>
            <svg xmlns="http://www.w3.org/2000/svg" style="height: 24px;" viewBox="0 0 320 512" class="justmodal-close" @click="open=false">
               <path
                  d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"
               />
            </svg>
            <div class="justmodal-header-wrapper">
               <div class="justmodal-header"><h3 class="mb00">{{ title }}</h3></div>
            </div>
            <div class="justmodal-content">
               <form action="/nova-vendor/customer-statement/save" method="post" @submit.prevent="save(true)" ref="statementForm">
                  <div class="flex justify-start">
                     <div class="flex-initial" style="width: 45%; padding: 5px;">
                        <date-picker v-model="formData.start_date" valueType="format"></date-picker>
                     </div>
                     <div class="flex-initial" style="width: 45%; padding: 5px;">
                        <date-picker v-model="formData.end_date" valueType="format"></date-picker>
                     </div>
                     <div class="flex-initial" style="width: 10%; padding: 5px;">
                        <a href="#" @click.prevent="fetchInvoiceData()" class="btn btn-default btn-primary flex items-center" dusk="create-button" style="height: 2.12rem;">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" width="20" height="20">
                              <path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z" />
                           </svg>
                        </a>
                     </div>
                  </div>
                  <div class="flex justify-start">
                     <div class="flex-auto flex items-center justify-center" style="padding: 5px; height: 80px;">
                        <div class="text-center">
                           <p class="mb-2">{{ moneyFormat(formData.opening_balance,formData.currency_code) }}</p>
                           <h4>OPENING BALANCE</h4>
                        </div>
                     </div>
                     <div class="flex-auto flex items-center justify-center" style="padding: 5px; height: 80px;">
                        <div class="text-center">
                           <p class="mb-2">{{ moneyFormat(formData.total_amount,formData.currency_code) }}</p>
                           <h4>INVOICE AMOUNT</h4>
                        </div>
                     </div>
                     <div class="flex-auto flex items-center justify-center" style="padding: 5px; height: 80px;">
                        <div class="text-center">
                           <p class="mb-2">{{ moneyFormat(formData.total_paid_amount,formData.currency_code) }}</p>
                           <h4>AMOUNT PAID</h4>
                        </div>
                     </div>
                     <div class="flex-auto flex items-center justify-center" style="padding: 5px; height: 80px;">
                        <div class="text-center">
                           <p class="mb-2">{{ moneyFormat(formData.closing_balance,formData.currency_code) }}</p>
                           <h4>CLOSING BALANCE</h4>
                        </div>
                     </div>
                  </div>
                  <div class="flex mt-6">
                     <table class="table custom-table" style="width:100%">
                        <thead>
                           <tr>
                              <th></th>
                              <th>Date</th>
                              <th>Document</th>
                              <th>Value</th>
                              <th>Paid</th>
                              <th>Balance</th>
                           </tr>
                        </thead>
                        <tbody class="mb-3">
                           <tr v-if="isInvoiceLoading">
                              <td colspan="6">Loading...</td>
                           </tr>
                           <tr v-for="(item,index) in formData.invoices" :key="index" v-else>
                              <th>
                                 <input type="checkbox" @change="updateInvoiceSelected(index)" :checked="item.selected">
                              </th>
                              <td>{{ formatDateTime(item.due_date,'YYYY-MM-DD') }}</td>
                              <td>Invoice:{{ item.invoice_num }}</td>
                              <td style="text-align: right;">{{ moneyFormat(item.total_amount,formData.currency_code) }}</td>
                              <td style="text-align: right;">{{ moneyFormat(item.total_paid_amount,formData.currency_code) }}</td>
                              <td style="text-align: right;">{{ moneyFormat(item.opening_balance,formData.currency_code) }}</td>
                           </tr>
                        </tbody>
                        <tfoot>
                           <tr>
                              <td colspan="2"></td>
                              <td class="text-right" style="font-weight:900">
                                 <p><b>Total:</b></p>
                              </td>
                              <td style="border-left: none !important; border-right: none !important; text-align: right;">
                                 <p>
                                    <b>{{ moneyFormat(formData.amount,formData.currency_code) }}</b>
                                 </p>
                              </td>
                              <td style="border-left: none !important; border-right: none !important; text-align: right;">
                                 <p>
                                    <b>{{ moneyFormat(formData.total_paid_amount,formData.currency_code) }}</b>
                                 </p>
                              </td>
                              <td style="text-align: right;">
                                 <p>
                                    <b> {{ moneyFormat(formData.amount_due,formData.currency_code) }}</b>
                                 </p>
                              </td>
                           </tr>
                        </tfoot>
                     </table>
                  </div>
                  <div class="flex mt-6">
                     <div class="flex-initial">
                        <button @click.prevent="save(true)" class="btn btn-default btn-primary inline-flex items-center m-5" dusk="create-button" style="height: 2.12rem;" :disabled="!formData.customer || formData.customer == '' || isSaving || isInvoiceLoading">
                           {{ isSaving ? 'saving...' : 'Save and Send' }}
                        </button>
                        <button @click.prevent="save(false)" class="btn btn-default btn-primary inline-flex items-center m-5" dusk="create-button" style="height: 2.12rem;" :disabled="!formData.customer || formData.customer == '' || isSaving || isInvoiceLoading">
                           {{ isSaving ? 'saving...' : 'Save' }}
                        </button>
                        <button @click.prevent="open = false" class="btn btn-default btn-outlined inline-flex items-center m-5" dusk="create-button" style="height: 2.12rem;border: 1px solid #ccc;color: #242424;text-shadow: none;">
                           Cancel
                        </button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <MessageModal ref="MessageModal"/>
   </div>
</template>

<style lang="scss" scoped>
   .font900{ font-weight:900!important;}
   .mx-datepicker {
      width: 100%;
   }
   .justmodal-shadow {
      position: fixed;
      top: 0px;
      left: 0px;
      width: 100%;
      height: 100vh;
      background: rgba(0, 0, 0, 0.5);
      z-index: 99;
      display: none;
   }
   .justmodal-shadow.active {
      display: block !important;
   }
   .justmodal-wrapper {
      position: fixed;
      width: 100%;
      height: 100vh;
      z-index: -999;
      top: 0px;
      left: 0px;
      align-items: center;
      justify-content: center;
      display: flex !important;
      opacity: 0;
      transition: all 0.3s ease;
      -webkit-transition: all 0.3s ease;
      -moz-transition: all 0.3s ease;
      transform: translate3d(0, 10px, 0);
      -webkit-transform: translate3d(0, 10px, 0);
      -moz-transform: translate3d(0, 10px, 0);
      visibility: hidden;
   }
   .justmodal-wrapper.active {
      z-index: 100 !important;
      opacity: 1 !important;
      transform: translate3d(0, 0px, 0) !important;
      -webkit-transform: translate3d(0, 0px, 0) !important;
      -moz-transform: translate3d(0, 0px, 0) !important;
      visibility: visible !important;
   }
   .justmodal-container {
      min-height: 60px;
      max-height: 100%;
      background: #fff;
      width: 800px;
      padding: 24px 0px;
      position: relative;
      -webkit-box-shadow: 0 10px 40px -6px rgba(0, 0, 0, 0.1);
      -moz-box-shadow: 0 10px 40px -6px rgba(0, 0, 0, 0.1);
      -o-box-shadow: 0 10px 40px -6px rgba(0, 0, 0, 0.1);
      box-shadow: 0 10px 40px -6px rgba(0, 0, 0, 0.1);
      .justmodal-header {
         padding: 0px 24px 8px 24px;
         border-bottom: 1px solid #ededed;
         margin-bottom: 24px;
         h5 {
            margin-top: 0px !important;
            margin-bottom: 0px !important;
         }
      }
      .justmodal-content {
         padding: 0px 24px;
         min-height: 30px;
         max-height: 87vh;
         overflow-y: auto;
         overflow-x: hidden;
      }
   }
   .justmodal-close {
      position: absolute;
      top: 16px;
      right: 16px;
      font-size: 16px;
      z-index: 10;
      opacity: 0.5;
      color: #555;
      cursor: pointer;
      &:hover {
         opacity: 1 !important;
      }
   }
   @media (max-width: 767px) {
      .justmodal-container {
         width: 90% !important;
      }
      #app-loader-content {
         width: 95% !important;
      }
   }
   @media (min-width: 768px) and (max-width: 1024px) {
      .justmodal-container {
         width: 500px !important;
      }
   }
</style>

<script>
   import moment from "moment";
   import DatePicker from "vue2-datepicker";
   import "vue2-datepicker/index.css";
   import MessageModal from './MessageModal.vue';
   import { debounce } from './../utility.js';

   export default {
      components: { DatePicker,MessageModal },
      data() {
         return {
            title: "",
            open: false,
            isEditMode: false,
            status: '',
            formData: {
               company: false,
               statement_real_id: false,
               statement_id: false,
               type: "open item",
               contact_id: false,
               is_send: 0,
               customer: false,
               customer_data: false,
               currency_code: "",
               start_date: moment().startOf("month").format("YYYY-MM-DD"),
               end_date: moment().format("YYYY-MM-DD"),
               invoices: [],
               amount: 0,
               amount_due: 0,
               total_amount: 0,
               total_paid_amount: 0,
               opening_balance: 0,
               closing_balance: 0,
            },
            isInvoiceLoading: true,
            select_all: 1,
            type: "",
            types: ["open item"], // 'balance forward','open item','Transaction statement'
            isSaving: false,
            invoice_pagination: {
               page: 1,
               limit: 100,
               searchText: "",
            },
         };
      },
      watch: {
         open(isOpen) {
            let _this = this;

            if (!isOpen || ( isOpen && !this.isEditMode ) ) {
               this.formData.statement_id = false;
               this.formData.invoices = [];
               this.formData.amount = 0;
               this.formData.amount_due = 0;
               this.formData.total_amount = 0;
               this.formData.total_paid_amount = 0;
               this.formData.opening_balance = 0;
               this.formData.closing_balance = 0;

               setTimeout(()=>{
                  _this.formData.start_date =  moment().startOf('month').format('YYYY-MM-DD');
                  _this.formData.end_date = moment().format('YYYY-MM-DD');
                  _this.formData.type = 'open item';
               },500);
            }

            if (isOpen && !this.isEditMode ) {
               this.fetchFinancialDate()
               .then(()=>{
                  _this.fetchInvoiceData();
               });
            }

            if( !isOpen ){
               this.isSaving = false;
               this.isInvoiceLoading = false;
               this.$emit('close',(new Date).getTime());
            }
         },
         'invoice_pagination.searchText': debounce(function() {
            this.fetchInvoiceData();
         }, 300),
         totalAmount(v){
            this.formData.amount = v;
            this.formData.total_amount = v;
         },
         totalAmountDue(v){
            this.formData.amount_due = v;
         },
         totalPaidAmount(v){
            this.formData.total_paid_amount = v;
         },
         closingBalance(v){
            this.formData.closing_balance = v;
         }
      },
      computed: {
         totalAmount(){
            let _this = this;
            return this.formData.invoices.filter( i => i.selected ).map( i => ( _this.isEmpty(i.total_amount) ? 0 : parseFloat(i.total_amount) ) ).reduce( (a,b) => a+b, 0);
         },
         totalAmountDue(){
            return this.totalAmount - this.totalPaidAmount;
         },
         totalPaidAmount(){
            let _this = this;
            return this.formData.invoices.filter( i => i.selected ).map( i => ( _this.isEmpty(i.total_paid_amount) ? 0 : parseFloat(i.total_paid_amount) ) ).reduce( (a,b) => a+b, 0);
         },
         closingBalance(){
            return (this.formData.opening_balance + this.totalAmount) - (this.totalPaidAmount);
         },
      },
      methods: {
         updateInvoiceSelected(index){
            if( event.target.checked ){
               this.formData.invoices[index].selected = true;
            }else{
               this.formData.invoices[index].selected = false;
            }
         },
         async fetchInvoiceData() {

            this.isInvoiceLoading = true;

            try {
               const { data } = await axios.get('/nova-vendor/customer-statement/customer-invoices/'+this.formData.customer.qbo_id+'?page='+this.invoice_pagination.page+'&limit='+this.invoice_pagination.limit+'&search='+(this.invoice_pagination.searchText || '')+'&start_date='+this.formData.start_date+'&end_date='+this.formData.end_date);

               this.formData.invoices = data.message.map(item=>{
                  item.selected = true;
                  return item;
               });

               this.getOpeningBalance(this.formData.customer.id,{start_date:this.formData.end_date});

               this.isInvoiceLoading = false;
            } catch (error) {
               console.log(error);
               const { data } = error.response || { data: {} };
               this.$toasted.show( (data.message || 'Failed to get opening balance, please try again.'), { type: 'error' })
               this.isInvoiceLoading = false;
            }
         },
         async getOpeningBalance(id,params){

            this.isInvoiceLoading = true;
            try {

               const { data } = await axios.post('/nova-vendor/customer-statement/opening-balance/get',{
                  start_date: params.start_date,
                  end_date: params.end_date,
                  contact_id: this.formData.customer.qbo_id
               });

               this.formData.opening_balance = data.balance;
               this.isInvoiceLoading = false;
            } catch (error) {
               console.log(error);
               const { data } = error.response || { data: {} };
               this.$toasted.show( (data.message || 'Failed to get opening balance, please try again.'), { type: 'error' })
               this.isInvoiceLoading = false;
            }
         },
         async save(is_send) {
            if( !this.formData.customer ){
               this.$refs.MessageModal.$data.type = 'error';
               this.$refs.MessageModal.$data.text = 'Customer not found!';
               this.$refs.MessageModal.$data.open = true;
               this.$refs.MessageModal.$data.disable_confirm_button = true;
               this.$refs.MessageModal.$data.disable_cancel_button = true;
               return;
            }

            if( this.isEmpty(this.formData.customer.primary_email) ){
               this.$refs.MessageModal.$data.type = 'error';
               this.$refs.MessageModal.$data.text = 'No email available!';
               this.$refs.MessageModal.$data.open = true;
               this.$refs.MessageModal.$data.disable_confirm_button = true;
               this.$refs.MessageModal.$data.disable_cancel_button = true;
               return;
            }

            if (this.isSaving) {
               return;
            }

            this.formData.is_send = is_send ? 1 : 0;
            this.isSaving = true;

            try {
               let formdata = JSON.parse(JSON.stringify(this.formData));
               formdata.invoices = formdata.invoices.filter( i => i.selected );

               const { data } = await axios.post('/nova-vendor/customer-statement/statement/save',{
                  ...formdata
               });

               this.$toasted.show( (data.message || 'Statement has been created.'), { type: 'success' });
               this.$refs.statementForm.reset();
               this.isSaving = false;
               this.open = false;

            } catch (error) {
               console.log(error);
               const { data } = error.response || { data: {} };

               this.$toasted.show( (data.message || 'Could not save the statement.'), { type: 'error' });
               this.isSaving = false;
            }
            
         },
         async fetchFinancialDate(){
            if( this.isEditMode ) return;

            try {
               const { data } = await axios.get('/nova-vendor/customer-statement/financial-date/get');

               this.formData.start_date = data.message;
            } catch (error) {
               console.log(error);
               const { data } = error.response || { data: {} };
               this.$toasted.show( (data.message || 'Failed to get financial date, please try again.'), { type: 'error' })
            }
         }
      },
      mounted(){},
   };
</script>
