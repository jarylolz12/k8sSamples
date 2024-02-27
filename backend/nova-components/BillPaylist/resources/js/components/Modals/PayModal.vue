<template>
    <div @click="closeModal" class="modal-overlay">
        <div :class="`modal pay-modal ${payClass}`">
              <h6 class="text-white py-16">Bill Payment for #{{item.bill_id}}</h6>
            <slot name="content" v-bind:getItem="item"></slot>
            <div v-if="!paying">
                <button @click="$emit('confirm')" class="btn btn-default bill-paylist-btn-pay text-white py-0 leading-3 flex">
                  <div class="bill-paylist-btn-pay-first-div">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                  </div>
                  <div>Finish and Pay</div>
                </button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    components:{},
    props: ['modal', 'title', 'item', 'paying', 'payClass'],
    mounted() {},
    methods: {
        closeModal(e) {
          let currentSelector = e.target
          if(!currentSelector.classList.contains('bill-pay-radio') && !currentSelector.classList.contains('label-pay-radio') && !currentSelector.classList.contains('modal')) {
            this.$emit('update:payClass', 'slide-out-elliptic-top-bck')
            setTimeout(() => {
              this.$emit('update:payClass', '')
              this.$emit('update:modal', false) 
            },750)
            
          }
        }
    }
}
</script>
<style scoped>
.pay-modal {
  font-family: 'Montserrat', sans-serif;
}
.modal-overlay {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  display: flex;
  justify-content: center;
  transition: 0.3s background-color;
  background-color: #000000da;
  z-index: 100;
}

.modal {
  text-align: center;
  background-color: rgb(23,26,61);
  padding-top: 25px;
  border-radius: 20px;
  padding-bottom: 25px;
  height: 350px;
  box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
}


.modal p {
    padding-left: 16px;
    padding-right: 16px;
}
.close {
  margin: 10% 0 0 16px;
  cursor: pointer;
}

.close-img {
  width: 25px;
}

.check {
  width: 150px;
}

h6 {
  font-weight: 500;
  padding-top: 16px;
  padding-bottom: 16px;
  color: white;
}

p {
  font-size: 16px;
  margin: 20px 0;
}

button {
  width: 150px;
  height: 40px;
  color: white;
  font-size: 14px;
  border-radius: 16px;
  margin-top: 50px;
}

.method-option-row {
  justify-content: space-between;
  border-bottom: 2px solid rgb(33,36,74);
}

.bill-paylist-btn-pay {
  background: rgb(19,193,191);
  color: white;
  width: 65%;
  margin: 0px auto;
  justify-content: center;
  margin-top: 32px;
  box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
}

.bill-paylist-btn-pay:hover {
  background: rgb(12 122 121);
}
.billpaylist-icon-container {
  background: white;
  height: 25px;
  border-radius: 4px;
  margin-top: 18px;
  padding: 3px;
  margin-right: 16px;

}
.bill-paylist-btn-pay-first-div {
  padding-top: 3px;
  padding-right: 6px;
}
.bill-paylist-btn-pay-first-div svg {
  height: 17px;
}

.label-pay-radio {
  margin-top: -2px;
}
.label-pay-radio::before {
  content: "";
  display: block;
  width: 20px;
  height: 20px;
  background-color: rgb(41,44,81);
  border: 3px solid rgb(64,67,100);
  border-radius: 20px;
  margin-right: 10px;
}
.bill-pay-radio {
  width: 1px;
  height: 1px;
}

.pay-options-container {
    width: 80%;
    background: rgb(30,33,72);
    padding: 15px;
    display: block;
    margin: 0px auto;
    box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
}

.bill-pay-radio:checked+.label-pay-radio::before { 
  content: "";
  display: block;
  width: 20px;
  height: 20px;
  background-color: rgb(18,193,191);
  border: 3px solid rgb(115,220,219);
  border-radius: 20px;
}


@media (min-width: 100px) {
  .modal {
    width: 352px;
    margin-top: 32%;
  }
  h6 {
    font-size: 20px;
  }
}
@media (min-width: 576px) { 
  .modal {
    width: 500px;
    margin-top: 10%;
  }

  h6 {
    font-size: 28px;
  }
}

@media (min-width: 768px) {

}

@media (min-width: 992px) {
  .modal {
    width: 600px;  
  }
}

@media (min-width: 1200px) {


}
</style>