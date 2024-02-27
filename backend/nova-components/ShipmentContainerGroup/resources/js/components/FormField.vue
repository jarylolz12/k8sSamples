<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <input
                :id="field.attribute"
                type="hidden"
                class="w-full form-control form-input form-input-bordered"
                :class="errorClasses"
                :placeholder="field.name"
                v-model="value"
            />
            <div :id="`shipment-container-group-${item.id}`" v-for="(item,key) in formGroups" class="shipment-container-group" style="border-bottom: 1px solid #eef1f4 !important;">
                <div>
                    <label>Container #</label>
                    <input type="text" v-model="item.container_num" class="w-full form-control form-input form-input-bordered" />
                </div>
                <div>
                    <label>Sizes</label>
                    <select :id="`shipment-container-${key}`" v-model="item.size" class="w-full form-control form-select form-select-bordered" >
                        <option value="">Select Sizes</option>
                        <option v-show="sizes.length>0" v-for="option in sizes" :value="option.id">{{option.name}}</option>
                    </select>
                </div>
                <div>
                    <label>Volume(Number of CBM)</label>
                    <input type="text" v-model="item.cbm" class="w-full form-control form-input form-input-bordered" />
                </div>
                <div>
                    <label>Weight(Number of KG)</label>
                    <input type="text" v-model="item.kg" class="w-full form-control form-input form-input-bordered" />
                </div>
                <div>
                    <label>Carton Count</label>
                    <input type="text" v-model="item.cartons" class="w-full form-control form-input form-input-bordered" />
                </div>
                <div>
                    <button class="btn btn-default btn-danger inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="removeGroup(item,key)" >Remove Container</button>
                </div>
            </div>

            <div >
                <button class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="addGroup()" >Add Container</button>
            </div>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors, InteractsWithDates } from 'laravel-nova'
import jQuery from 'jquery';
export default {
    mixins: [FormField, HandlesValidationErrors, InteractsWithDates],
    props: ['resourceName', 'resourceId', 'field'],
    data: function() {
        return {
            errors: {},
            is_create: true,
            has_submitted: false,
            sizes: [],
            formGroups: (this.field.value=='' || this.field.value==null) ? [] : JSON.parse(this.field.value)
        }
    },
    computed: {
        token() {
            return jQuery('meta[name="csrf-token"]').attr('content')
        }
    },
    mounted() {

        //set default selected schedules
       let getContainers = (this.field.value=='' || this.field.value==null) ? [] : JSON.parse(this.field.value);
       this.is_create = (this.field.value=='' || this.field.value==null) ? true : false;

        fetch(`${this.field.baseUrl}/custom/container-sizes`,{
            token: this.token
        }).then(this.handleResponse)
        .then( response => {
            let { results } = response 
            if (typeof results!=='undefined') {
                this.sizes = results
            }
        })
       /*
       if ( typeof jquery=='undefined' ) {
        var jqueryLibraryUrl = 'https://code.jquery.com/jquery-3.4.1.min.js';
        s.setAttribute('src',jqueryLibraryUrl);
       }

       h.appendChild(s);

       s.addEventListener('load',() => {



        /*
        $('body').on('click','button[dusk="create-button"]',(e) => {
            if(!this.has_submitted) {
                //e.preventDefault();
                this.value = JSON.stringify(this.formGroups);
                this.has_submitted = true;
                //$(this).click();
                $('button[dusk="create-button"]').click();
            }else {
                $('button[dusk="create-button"]').click();
            }

        });

        $('body').on('click','button[dusk="update-and-continue-editing-button"]',(e) => {
            if(!this.has_submitted) {
                //e.preventDefault();
                this.value = JSON.stringify(this.formGroups);
                this.has_submitted = true;
                //$(this).click();
                $('button[dusk="update-and-continue-editing-button"]').click();
            }else {
                $('button[dusk="update-and-continue-editing-button"]').click();
            }
        });

        $('body').on('click','button[dusk="update-button"]',(e) => {


            if(!this.has_submitted) {
                //e.preventDefault();
                this.value = JSON.stringify(this.formGroups);
                this.has_submitted = true;
                //$(this).click();
                $('button[dusk="update-button"]').click();

            }else {
                $('button[dusk="update-button"]').click();
            }
        });*/

      // });
    },
    computed: {
        token() {
            return jQuery('meta[name="csrf-token"]').attr('content');
        },
        firstDayOfWeek() {
            return this.field.firstDayOfWeek || 0
        },
        placeholder() {
          return this.field.placeholder || moment().format(this.format)
        },
        format() {
          return this.field.format || 'YYYY-MM-DD'
        },
        pickerFormat() {
          return this.field.pickerFormat || 'Y-m-d'
        },
    },
    methods: {
        removeGroup(item,key) {
            jQuery(`#shipment-container-group-${key}`).remove();
            var filterFormGroups = _.filter(this.formGroups,o => (o.id!=item.id));
            this.errors[key] = false;
            this.formGroups = filterFormGroups;
            this.value = JSON.stringify(filterFormGroups);
        },
        closeModal() {
          this.scheduleModal = false;
        },
        openModal() {
            this.scheduleModal = true;
        },
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
        },
        addGroup() {
            //this.current_id = parseInt(new Date().getTime());
            //let setId = (this.formGroups.length>0) ? this.formGroups[this.formGroups.length - 1].id + 1 : 0;

            let setId = new Date().getTime();

            this.formGroups.push({
                id: new Date().getTime(),
                container_num: '',
                size: '',
                cbm: '',
                kg: '',
                cartons: ''
            });

            this.value = JSON.stringify(this.formGroups);

        },
        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
           // formData.append(this.field.attribute, this.value || '')
            if(this.formGroups.length>0) {
                formData.append(this.field.attribute, JSON.stringify(this.formGroups));
            }else {
                formData.append(this.field.attribute, '[]');
            }
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },
        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text)
                return data
            })
        },
    },
}
</script>
