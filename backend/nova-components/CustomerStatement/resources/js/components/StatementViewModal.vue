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
               <div class="flex justify-start">
                  <div class="flex-auto flex items-center justify-center" style="padding: 5px;">
                     <div>
                        <p class="mb-2 font900">START DATE</p>
                        <p>{{ formData.start_date }}</p>
                     </div>
                  </div>
                  <div class="flex-auto flex items-center justify-center" style="padding: 5px;">
                     <div>
                        <p class="mb-2 font900">END DATE</p>
                        <p>{{ formData.end_date }}</p>
                     </div>
                  </div>
                  <div class="flex-auto flex items-center justify-center" style="padding: 5px;">
                     <div>
                        <p class="mb-2 font900">STATUS</p>
                        <p>{{ status }}</p>
                     </div>
                  </div>
               </div>
               <div class="flex justify-start">
                  <div class="flex-auto flex items-center justify-center" style="padding: 5px; height: 80px;">
                     <div class="text-center">
                        <p class="mb-2">{{ moneyFormat(formData.opening_balance,formData.currency_code) }}</p>
                        <h4>OPENING BALANCE</h4>
                     </div>
                  </div>
                  <div class="flex-initial flex items-center justify-center" style="padding: 5px; height: 80px;width:20px;">
                     <label>+</label>
                  </div>
                  <div class="flex-auto flex items-center justify-center" style="padding: 5px; height: 80px;">
                     <div class="text-center">
                        <p class="mb-2">{{ moneyFormat(formData.total_amount,formData.currency_code) }}</p>
                        <h4>INVOICE AMOUNT</h4>
                     </div>
                  </div>
                  <div class="flex-initial flex items-center justify-center" style="padding: 5px; height: 80px;width:20px;">
                     <label>-</label>
                  </div>
                  <div class="flex-auto flex items-center justify-center" style="padding: 5px; height: 80px;">
                     <div class="text-center">
                        <p class="mb-2">{{ moneyFormat(formData.total_paid_amount,formData.currency_code) }}</p>
                        <h4>AMOUNT PAID</h4>
                     </div>
                  </div>
                  <div class="flex-initial flex items-center justify-center" style="padding: 5px; height: 80px;width:20px;">
                     <label>=</label>
                  </div>
                  <div class="flex-auto flex items-center justify-center" style="padding: 5px; height: 80px;">
                     <div class="text-center">
                        <p class="mb-2">{{ moneyFormat(formData.closing_balance,formData.currency_code) }}</p>
                        <h4>CLOSING BALANCE</h4>
                     </div>
                  </div>
               </div>
               <div class="flex mt-6">
                  <table class="table custom-table" style="width: 100%;">
                     <thead>
                        <tr>
                           <th>Date</th>
                           <th>Document</th>
                           <th>Value</th>
                           <th>Paid</th>
                           <th>Balance</th>
                        </tr>
                     </thead>
                     <tbody class="mb-3">
                        <tr v-show="isInvoiceLoading">
                           <td colspan="5">Loading...</td>
                        </tr>
                        <tr v-show="!isInvoiceLoading" v-for="(item,index) in formData.invoices" :key="index">
                           <td>{{ formatDateTime(item.due_date,'YYYY-MM-DD') }}</td>
                           <td>Invoice:{{ item.invoice_num }}</td>
                           <td style="text-align: right;">{{ moneyFormat(item.total_amount,formData.currency_code) }}</td>
                           <td style="text-align: right;">{{ moneyFormat(item.total_paid_amount,formData.currency_code) }}</td>
                           <td style="text-align: right;">{{ moneyFormat(item.opening_balance,formData.currency_code) }}</td>
                        </tr>
                     </tbody>
                     <tfoot>
                        <tr>
                           <td></td>
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
                     <button href="#" @click.prevent="editStatement()" class="btn btn-default btn-primary inline-flex items-center m-5" dusk="create-button" style="height: 2.12rem;" :disabled="isInvoiceLoading">
                        Edit
                     </button>
                     <button href="#" @click.prevent="deleteStatement()" class="btn btn-default btn-danger inline-flex items-center m-5" dusk="create-button" style="height: 2.12rem;" :disabled="isInvoiceLoading">
                        Delete
                     </button>
                     <button @click.prevent="open = false" class="btn btn-default btn-outlined inline-flex items-center m-5" dusk="create-button" style="height: 2.12rem; border: 1px solid #ccc; color: #242424; text-shadow: none;">
                        Cancel
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>

<style lang="scss" scoped>
   .font900{ font-weight:900!important;}
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

   export default {
      data() {
         return {
            title: "",
            open: false,
            type: '',
            status: '',
            formData: {
               statement_real_id: false,
               statement_id: false,
               type: "open item",
               contact_id: false,
               is_send: 0,
               customer_data: false,
               customer: "",
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
            isDeleteLoading: false,
            invoice_pagination: {
               page: 1,
               limit: 100,
               searchText: "",
            },
         };
      },
      watch: {
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
         },
         'formData.customer_data'(v){
            this.formData.currency_code = v.currency_code;
         }
      },
      computed: {
         totalAmount(){
            let _this = this;
            return this.formData.invoices.map( i => ( _this.isEmpty(i.total_amount) ? 0 : parseFloat(i.total_amount) ) ).reduce( (a,b) => a+b, 0);
         },
         totalAmountDue(){
            return this.totalAmount - this.totalPaidAmount;
         },
         totalPaidAmount(){
            let _this = this;
            return this.formData.invoices.map( i => ( _this.isEmpty(i.total_paid_amount) ? 0 : parseFloat(i.total_paid_amount) ) ).reduce( (a,b) => a+b, 0);
         },
         closingBalance(){
            return (this.formData.opening_balance + this.totalAmount) - (this.totalPaidAmount);
         }
      },
      methods: {
         editStatement(){
            this.$emit('edit_statement_action',this.formData);
            this.open = false;
         },
         deleteStatement(){
            this.$emit('delete_statement_action',this.formData);
            this.open = false;
         },
         async fetchInvoiceData() {

            if (!this.open || this.isInvoicesLoading ) {
               return;
            }

            this.isInvoicesLoading = true;

            try {
               const { data } = await axios.get('/nova-vendor/customer-statement/customer-invoices/'+this.formData.customer.qbo_id+'?page='+this.invoice_pagination.page+'&limit='+this.invoice_pagination.limit+'&search='+(this.invoice_pagination.searchText || '')+'&start_date='+this.formData.start_date+'&end_date='+this.formData.end_date);

               this.formData.invoices = data.message;

               this.getOpeningBalance(this.formData.customer.id,{start_date:this.formData.start_date});

               this.isInvoicesLoading = false;
            } catch (error) {
               console.log(error);
               const { data } = error.response || { data: {} };
               this.$toasted.show( (data.message || 'Failed to get opening balance, please try again.'), { type: 'error' })
               this.isInvoicesLoading = false;
            }
         },
         async getOpeningBalance(id,params){

            this.isInvoicesLoading = true;
            try {

               const { data } = await axios.get('/nova-vendor/customer-statement/opening-balance/get',{
                  start_date: params.start_date,
                  end_date: params.end_date,
                  contact_id: this.formData.customer.qbo_id
               });

               this.formData.opening_balance = data.balance;
               this.isInvoicesLoading = false;
            } catch (error) {
               console.log(error);
               const { data } = error.response || { data: {} };
               this.$toasted.show( (data.message || 'Failed to get opening balance, please try again.'), { type: 'error' })
               this.isInvoicesLoading = false;
            }
         },
      },
      mounted() {},
   };
</script>
