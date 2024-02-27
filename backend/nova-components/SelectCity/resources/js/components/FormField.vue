<template>
<div class="flex border-b border-40">
  <div class="w-1/5 px-8 py-6">
    <label for="line2" class="inline-block text-80 pt-2 leading-tight">
      City
    </label>
  </div>
  <div class="py-6 px-8 w-1/2">
    <v-select :options="options" @open="loadCities()" :value="selected" @option:selected="optionSelected" placeholder="Select city" v-model="selected">
      <template #search="{attributes, events}">
        <input 
          class="vs__search"
          v-bind="attributes"
          v-on="events"
        />
      </template>
    </v-select>
  </div>
</div>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import Cities from './../state_cities.json';

export default {
  mixins: [FormField, HandlesValidationErrors],
  props: ['resourceName', 'resourceId', 'field'],
  components: { vSelect },
  data(){
    return {
      options:[],
      selected: this.field.selected
    }
  },
  methods: {
    optionSelected(v){
      document.querySelector('body').setAttribute('selected-city',v);
    },
    loadCities(){
      let selected_country = document.querySelector('body').getAttribute('selected-country');
      let _this = this;

      fetch(this.field.api_url+selected_country.replace('"','').replace(' ','_').toLowerCase())
      .then(response => response.json())
      .then(data => {
        _this.options = data.map( i => i.name ).filter((x, i, a) => a.indexOf(x) == i);
      })
      .catch(err=>{
        if( err ) console.log(err);
      });
    },
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || ''
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, this.selected);
    },
  },
  mounted(){
    document.querySelector('body').setAttribute('selected-city',this.field.selected);
  }
}
</script>
