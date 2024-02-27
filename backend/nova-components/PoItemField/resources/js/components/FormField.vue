<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
      <div class="w-full" :id="`po-item-field-${item.item ? item.item.id : null}`" v-show="selectedItems.length > 0" v-for="(item,key) in selectedItems" :key="key">
        <div class="w-4/5">
          <div>
          <div class="w-full">
              <label>Select Item:</label>
            </div>
            <multiselect
              :id="`po-item-field-select-${key}`"
              v-model="item.item"
              label="item_num"
              track-by="id"
              :options="itemsOption"
              :allow-empty="false"
            ></multiselect>
          </div>
          <div class="mt-2">
            <div class="w-full">
              <label>Quantity:</label>
            </div>
            <input type="number" v-model="item.quantity" class="w-full form-control form-input form-input-bordered" />
          </div>
          <div>
            <button  @click.prevent="removeFieldGroup(item.item,key)" class="mt-2 btn btn-sm btn-default btn-danger inline-flex items-center relative">Remove</button>
          </div>
        </div>
        <br>
      </div>   
      <div id="shipment-supplier-group-submit-group" :class="selectedItems.length > 0 ? 'mt-2' : '' ">
        <button @click.prevent="addFieldGroup()" class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group">Add Item</button>
      </div>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import axios from 'axios';

export default {
  mixins: [FormField, HandlesValidationErrors],
  props: ['resourceName', 'resourceId', 'field'],

  data: function(){
    return{
      customer:null,
      customer_id:null,
      customerItems:[],
      selectedItems: (this.field.value == '' || this.field.value == null) ? [] : JSON.parse(this.field.value),
      itemFields: []
    }
  },
  
  computed: {
    itemsOption : function(){
      if(this.selectedItems.length > 0 ){
        let selected = _.reject(this.selectedItems,{item:null});
        const options = _.differenceWith(this.customerItems,
          _.map(selected,
            ({ item: id }) => id), (a, b) => a.id === b.id
        );  
        return options;
      }
      return this.customerItems;
    }
  },

  methods: {
    //Set the initial, internal value for the field.
    setInitialValue() {
      this.value = this.field.value || ''
    },

    //Fill the given FormData object with the field's internal value.
    fill(formData) {
      if(this.selectedItems.length > 0){
        let getFieldValue = _.filter(this.selectedItems, function(c){
          if(c.item !== null && c.quantity !== null)
          if(c.item !== null || c.quantity !== null)
          return c;
        })
        console.log(getFieldValue)
        formData.append(this.field.attribute, JSON.stringify(getFieldValue));
       
      }else{
        formData.append(this.field.attribute, this.value || '')
      }
    },

    handleChange(value) {
			this.value = value
		},

    addFieldGroup(){
      if(this.customer){
        let hasEmptyVal = _.filter(this.selectedItems,{item:null}).length > 0;
        if(hasEmptyVal === false){
          this.selectedItems.push({
            quantity:null,
            item:null
          })
          this.value = JSON.stringify(this.selectedItems)
        }
      }
    },

    removeFieldGroup(item,key){
      if(item){
        let filteredItemFields = _.filter(this.selectedItems, c => c.item.id != item.id);
        this.selectedItems = filteredItemFields;
      }
    }
  },

  created: function(){

  },

  mounted(){
    Nova.$on('nova-belongsto-depend-customer', data => { this.customer = data});
    
    if(this.resourceId){
      console.log(this.resourceId);
      axios.get('/custom/purchase-order-by-id/'+this.resourceId,{
          headers: {
            'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
            'Accept': 'application/json'
          }
        })
        .then(res => {
          let customer = res.data.results ? res.data.results[0].customer : '';
          console.log(customer)
          this.customer = {value:customer};
        })
        .catch(err=>{
          console.log(err)
        })
    }
    
  },

  updated: function(){

  },
  
  watch: {
    customer: function(newVal,OldVal){
      if(newVal.value.id){
        axios.get('/custom/items-by-customer/'+newVal.value.id,{
          headers: {
            'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
            'Accept': 'application/json'
          }
        })
        .then(res => {
          if(!this.resourceId){
            this.selectedItems = [];
            this.itemFields = [];
          }
          let responseResults = _.map(res.data.results,c => (_.pick(c,['id','item_num'])));
          this.customerItems = res.data ? responseResults : [];
        })
        .catch(err=>{
          console.log(err)
        })
      }
    },
  }
}


/**** TODOS ****/
/**** hide remove button until an item is selected ****/
/**** Quantity is required before adding new selection field ****/
</script>

<style scoped>

</style>
