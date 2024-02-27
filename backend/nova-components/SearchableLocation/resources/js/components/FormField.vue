<template>
  <div>
    <v-select :options="options"></v-select>
    <!-- <div class="searchable-wrapper">
      <a href="#" class="_trigger" @click.prevent="open=true">{{ getSelectedName }}</a>
      <input
        :id="field.name"
        type="hidden"
        v-model="selected"
      />
      <div class="_dropdown">
        <input class="_input" v-model="keyword" @keydown="onKeyDown()">
        <ul>
          <li v-for="(item,index) in new_options" :key="index" :class="item.selected?'active':''">
            <a href="#" @click.prevent="options[index].selected=true">{{ item.name}}</a>
          </li>
        </ul>
      </div>
    </div> -->
  </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

function debounce(func, wait, immediate) {

  let timeout;

  return function() {
    let context = this, args = arguments;
    let later = function() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    }

    let callNow = immediate && !timeout;

    clearTimeout(timeout);  

    timeout = setTimeout(later, wait);

    if (callNow) func.apply(context, args);
  }
}

export default {
  mixins: [FormField, HandlesValidationErrors],
  props: ['resourceName', 'resourceId', 'field'],
  components: { vSelect },
  data(){
    return{
      selected: '',
      new_options: [],
      keyword: '',
      options: [],
      placeholder: 'Click to select'
    }
  },
  watch: {
    options(){
      this.selected = this.options.findIndex( i => i.selected ) ? this.options.find( i => i.selected ).value : '';
    },
    selected(v){
      window['selected_'+this.field.type] = v;
    }
  },
  computed: {
    getSelectedName(){
      return this.options.findIndex( i => i.selected ) ? this.options.find( i => i.selected ).name : this.placeholder;
    }
  },
  methods: {
    onKeyDown: debounce(function(){
      let searchKeys = ["name"] 

      console.log(search(this.options, ["name"], this.keyword));
    },800),
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
      formData.append(this.field.attribute, this.value || '')
    },
  },
  mounted(){

    if( this.field.data ){
      this.options = this.field.data;
    }

    if( this.options ){
      this.options = this.options.map( i =>{
        return {
          name: i.name,
          value: i.name
        }
      });
    }

    this.new_options = this.options;

    this.placeholder = this.field.placeholder ? this.field.placeholder : this.placeholder;

  }
}
</script>
