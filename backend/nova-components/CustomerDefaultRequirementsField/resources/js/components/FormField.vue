<template>
    <div class="flex border-b border-40" resource-id="resourceId">
        <div class="w-1/5 px-8 py-6">
            <label for="custom_notes" class="inline-block text-80 pt-2 leading-tight">
                Default Requirements

                <!---->
            </label>
        </div>
        <div class="py-6 px-8 w-4/5">
            <div class="flex">
                <div class="w-1/6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Ocean Freight
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('ocean_freight_yes')" :checked="customer_default_requirements.ocean_freight_yes==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Yes
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('ocean_freight_no')" :checked="customer_default_requirements.ocean_freight_no==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        No
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('ocean_freight_sometimes')" :checked="customer_default_requirements.ocean_freight_sometimes==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Sometimes
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('ocean_freight_dont_know')" :checked="customer_default_requirements.ocean_freight_dont_know==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Don't know
                    </label>
                </div>
            </div>
            <div class="flex">
                <div class="w-1/6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Trucking at Final Destination
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('trucking_destination_yes')" :checked="customer_default_requirements.trucking_destination_yes==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Yes
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('trucking_destination_no')" :checked="customer_default_requirements.trucking_destination_no==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        No
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('trucking_destination_sometimes')" :checked="customer_default_requirements.trucking_destination_sometimes==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Sometimes
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('trucking_destination_dont_know')" :checked="customer_default_requirements.trucking_destination_dont_know==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Don't know
                    </label>
                </div>
            </div>
            <div class="flex">
                <div class="w-1/6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Pier Pass
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('pier_pass_yes')" :checked="customer_default_requirements.pier_pass_yes==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Yes
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('pier_pass_no')" :checked="customer_default_requirements.pier_pass_no==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        No
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('pier_pass_sometimes')" :checked="customer_default_requirements.pier_pass_sometimes==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Sometimes
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('pier_pass_dont_know')" :checked="customer_default_requirements.pier_pass_dont_know==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Don't know
                    </label>
                </div>
            </div>
            <div class="flex">
                <div class="w-1/6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Customs Clearance
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('customs_clearance_yes')" :checked="customer_default_requirements.customs_clearance_yes==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Yes
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('customs_clearance_no')" :checked="customer_default_requirements.customs_clearance_no==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        No
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('customs_clearance_sometimes')" :checked="customer_default_requirements.customs_clearance_sometimes==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Sometimes
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('customs_clearance_dont_know')" :checked="customer_default_requirements.customs_clearance_dont_know==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Don't know
                    </label>
                </div>
            </div>

            <div class="flex">
                <div class="w-1/6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Duty Layout
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('duty_layout_yes')" :checked="customer_default_requirements.duty_layout_yes==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Yes
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('duty_layout_no')" :checked="customer_default_requirements.duty_layout_no==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        No
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('duty_layout_sometimes')" :checked="customer_default_requirements.duty_layout_sometimes==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Sometimes
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('duty_layout_dont_know')" :checked="customer_default_requirements.duty_layout_dont_know==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Don't know
                    </label>
                </div>
            </div>
            <div class="flex">
                <div class="w-1/6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        ISF
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('isf_yes')" :checked="customer_default_requirements.isf_yes==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Yes
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('isf_no')" :checked="customer_default_requirements.isf_no==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        No
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('isf_sometimes')" :checked="customer_default_requirements.isf_sometimes==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Sometimes
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('isf_dont_know')" :checked="customer_default_requirements.isf_dont_know==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Don't know
                    </label>
                </div>
            </div>

            <div class="flex">
                <div class="w-1/6">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Trucking at Initial Location
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1"  @click="tick('trucking_at_initial_location_yes')" :checked="customer_default_requirements.trucking_at_initial_location_yes==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Yes
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1" @click="tick('trucking_at_initial_location_no')" :checked="customer_default_requirements.trucking_at_initial_location_no==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        No
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1"  @click="tick('trucking_at_initial_location_sometimes')" :checked="customer_default_requirements.trucking_at_initial_location_sometimes==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Sometimes
                    </label>
                </div>
                <div class="w-1/6">
                    <input type="checkbox" class="checkbox mx-1"  @click="tick('trucking_at_initial_location_dont_know')" :checked="customer_default_requirements.trucking_at_initial_location_dont_know==1"/>
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Don't know
                    </label>
                </div>
            </div>
<!--            <input type="hidden" id="customer_default_requirements" dusk="customer_default_requirements" :value="JSON.stringify(customer_default_requirements)" />-->
        </div>
    </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [FormField, HandlesValidationErrors],
        data() {
          return {
              customer_default_requirements: (this.field.value=='' || this.field.value==null) ?
                  {
                      "ocean_freight_yes":0,
                      "ocean_freight_no":0,
                      "ocean_freight_sometimes":0,
                      "ocean_freight_dont_know":1,

                      "trucking_destination_yes": 0,
                      "trucking_destination_no": 0,
                      "trucking_destination_sometimes": 0,
                      "trucking_destination_dont_know":1,

                      "pier_pass_yes":0,
                      "pier_pass_no":0,
                      "pier_pass_sometimes":0,
                      "pier_pass_dont_know":1,

                      "customs_clearance_yes": 0,
                      "customs_clearance_no": 0,
                      "customs_clearance_sometimes": 0,
                      "customs_clearance_dont_know":1,

                      "duty_layout_yes": 0,
                      "duty_layout_no": 0,
                      "duty_layout_sometimes": 0,
                      "duty_layout_dont_know":1,

                      "isf_yes": 0,
                      "isf_no": 0,
                      "isf_sometimes": 0,
                      "isf_dont_know":1,

                      "trucking_at_initial_location_yes": 0,
                      "trucking_at_initial_location_no": 0,
                      "trucking_at_initial_location_sometimes": 0,
                      "trucking_at_initial_location_dont_know":1,

                  } : JSON.parse(this.field.value)
        }
    },

  props: ['resourceName', 'resourceId', 'field'],

  methods: {
      tick(getKey) {

          /** ocean_freight **/
          if(getKey == 'ocean_freight_yes'){
              if(this.customer_default_requirements.ocean_freight_yes != 0){
              }else{
                  this.customer_default_requirements.ocean_freight_no = 0;
                  this.customer_default_requirements.ocean_freight_sometimes = 0;
                  this.customer_default_requirements.ocean_freight_dont_know = 0;
              }
          }else if(getKey == 'ocean_freight_no'){
              if(this.customer_default_requirements.ocean_freight_no != 0){
              }else{
                  this.customer_default_requirements.ocean_freight_yes = 0;
                  this.customer_default_requirements.ocean_freight_sometimes = 0;
                  this.customer_default_requirements.ocean_freight_dont_know = 0;
              }
          }else if(getKey == 'ocean_freight_sometimes'){
              if(this.customer_default_requirements.ocean_freight_sometimes != 0){
              }else{
                  this.customer_default_requirements.ocean_freight_yes = 0;
                  this.customer_default_requirements.ocean_freight_no = 0;
                  this.customer_default_requirements.ocean_freight_dont_know = 0;
              }
          }else if(getKey == 'ocean_freight_dont_know'){
              if(this.customer_default_requirements.ocean_freight_dont_know != 0){
              }else{
                  this.customer_default_requirements.ocean_freight_yes = 0;
                  this.customer_default_requirements.ocean_freight_no = 0;
                  this.customer_default_requirements.ocean_freight_sometimes = 0;
              }
          }

          /** trucking_destination **/
          if(getKey == 'trucking_destination_yes'){
              if(this.customer_default_requirements.trucking_destination_yes != 0){
              }else{
                  this.customer_default_requirements.trucking_destination_no = 0;
                  this.customer_default_requirements.trucking_destination_sometimes = 0;
                  this.customer_default_requirements.trucking_destination_dont_know = 0;
              }
          }else if(getKey == 'trucking_destination_no'){
              if(this.customer_default_requirements.trucking_destination_no != 0){
              }else{
                  this.customer_default_requirements.trucking_destination_yes = 0;
                  this.customer_default_requirements.trucking_destination_sometimes = 0;
                  this.customer_default_requirements.trucking_destination_dont_know = 0;
              }
          }else if(getKey == 'trucking_destination_sometimes'){
              if(this.customer_default_requirements.trucking_destination_sometimes != 0){
              }else{
                  this.customer_default_requirements.trucking_destination_yes = 0;
                  this.customer_default_requirements.trucking_destination_no = 0;
                  this.customer_default_requirements.trucking_destination_dont_know = 0;
              }
          }else if(getKey == 'trucking_destination_dont_know'){
              if(this.customer_default_requirements.trucking_destination_dont_know != 0){
              }else{
                  this.customer_default_requirements.trucking_destination_yes = 0;
                  this.customer_default_requirements.trucking_destination_no = 0;
                  this.customer_default_requirements.trucking_destination_sometimes = 0;
              }
          }

          /** pier_pass **/
          if(getKey == 'pier_pass_yes'){
              if(this.customer_default_requirements.pier_pass_yes != 0){
              }else{
                  this.customer_default_requirements.pier_pass_no = 0;
                  this.customer_default_requirements.pier_pass_sometimes = 0;
                  this.customer_default_requirements.pier_pass_dont_know = 0;
              }
          }else if(getKey == 'pier_pass_no'){
              if(this.customer_default_requirements.pier_pass_no != 0){
              }else{
                  this.customer_default_requirements.pier_pass_yes = 0;
                  this.customer_default_requirements.pier_pass_sometimes = 0;
                  this.customer_default_requirements.pier_pass_dont_know = 0;
              }
          }else if(getKey == 'pier_pass_sometimes'){
              if(this.customer_default_requirements.pier_pass_sometimes != 0){
              }else{
                  this.customer_default_requirements.pier_pass_yes = 0;
                  this.customer_default_requirements.pier_pass_no = 0;
                  this.customer_default_requirements.pier_pass_dont_know = 0;
              }
          }else if(getKey == 'pier_pass_dont_know'){
              if(this.customer_default_requirements.pier_pass_dont_know != 0){
              }else{
                  this.customer_default_requirements.pier_pass_yes = 0;
                  this.customer_default_requirements.pier_pass_no = 0;
                  this.customer_default_requirements.pier_pass_sometimes = 0;
              }
          }

          /** customs_clearance **/
          if(getKey == 'customs_clearance_yes'){
              if(this.customer_default_requirements.customs_clearance_yes != 0){
              }else{
                  this.customer_default_requirements.customs_clearance_no = 0;
                  this.customer_default_requirements.customs_clearance_sometimes = 0;
                  this.customer_default_requirements.customs_clearance_dont_know = 0;
              }
          }else if(getKey == 'customs_clearance_no'){
              if(this.customer_default_requirements.customs_clearance_no != 0){
              }else{
                  this.customer_default_requirements.customs_clearance_yes = 0;
                  this.customer_default_requirements.customs_clearance_sometimes = 0;
                  this.customer_default_requirements.customs_clearance_dont_know = 0;
              }
          }else if(getKey == 'customs_clearance_sometimes'){
              if(this.customer_default_requirements.customs_clearance_sometimes != 0){
              }else{
                  this.customer_default_requirements.customs_clearance_yes = 0;
                  this.customer_default_requirements.customs_clearance_no = 0;
                  this.customer_default_requirements.customs_clearance_dont_know = 0;
              }
          }else if(getKey == 'customs_clearance_dont_know'){
              if(this.customer_default_requirements.customs_clearance_dont_know != 0){
              }else{
                  this.customer_default_requirements.customs_clearance_yes = 0;
                  this.customer_default_requirements.customs_clearance_no = 0;
                  this.customer_default_requirements.customs_clearance_sometimes = 0;
              }
          }

          /** duty_layout **/
          if(getKey == 'duty_layout_yes'){
              if(this.customer_default_requirements.duty_layout_yes != 0){
              }else{
                  this.customer_default_requirements.duty_layout_no = 0;
                  this.customer_default_requirements.duty_layout_sometimes = 0;
                  this.customer_default_requirements.duty_layout_dont_know = 0;
              }
          }else if(getKey == 'duty_layout_no'){
              if(this.customer_default_requirements.duty_layout_no != 0){
              }else{
                  this.customer_default_requirements.duty_layout_yes = 0;
                  this.customer_default_requirements.duty_layout_sometimes = 0;
                  this.customer_default_requirements.duty_layout_dont_know = 0;
              }
          }else if(getKey == 'duty_layout_sometimes'){
              if(this.customer_default_requirements.duty_layout_sometimes != 0){
              }else{
                  this.customer_default_requirements.duty_layout_yes = 0;
                  this.customer_default_requirements.duty_layout_no = 0;
                  this.customer_default_requirements.duty_layout_dont_know = 0;
              }
          }else if(getKey == 'duty_layout_dont_know'){
              if(this.customer_default_requirements.duty_layout_dont_know != 0){
              }else{
                  this.customer_default_requirements.duty_layout_yes = 0;
                  this.customer_default_requirements.duty_layout_no = 0;
                  this.customer_default_requirements.duty_layout_sometimes = 0;
              }
          }





          /** isf **/
          if(getKey == 'isf_no'){
              if(this.customer_default_requirements.isf_no != 0){
              }else{
                  this.customer_default_requirements.isf_yes = 0;
                  this.customer_default_requirements.isf_sometimes = 0;
                  this.customer_default_requirements.isf_dont_know = 0;
              }
          }else if(getKey == 'isf_yes'){
              if(this.customer_default_requirements.isf_yes != 0){
              }else{
                  this.customer_default_requirements.isf_no = 0;
                  this.customer_default_requirements.isf_sometimes = 0;
                  this.customer_default_requirements.isf_dont_know = 0;
              }
          }else if(getKey == 'isf_sometimes'){
              if(this.customer_default_requirements.isf_sometimes != 0){
              }else{
                  this.customer_default_requirements.isf_yes = 0;
                  this.customer_default_requirements.isf_no = 0;
                  this.customer_default_requirements.isf_dont_know = 0;
              }
          }else if(getKey == 'isf_dont_know'){
              if(this.customer_default_requirements.isf_dont_know != 0){
              }else{
                  this.customer_default_requirements.isf_yes = 0;
                  this.customer_default_requirements.isf_no = 0;
                  this.customer_default_requirements.isf_sometimes = 0;
              }
          }

          /** trucking_at_initial_location **/
          if(getKey == 'trucking_at_initial_location_no'){
              if(this.customer_default_requirements.trucking_at_initial_location_no != 0){
              }else{
                  this.customer_default_requirements.trucking_at_initial_location_yes = 0;
                  this.customer_default_requirements.trucking_at_initial_location_sometimes = 0;
                  this.customer_default_requirements.trucking_at_initial_location_dont_know = 0;
              }
          }else if(getKey == 'trucking_at_initial_location_yes'){
              if(this.customer_default_requirements.trucking_at_initial_location_yes != 0){
              }else{
                  this.customer_default_requirements.trucking_at_initial_location_no = 0;
                  this.customer_default_requirements.trucking_at_initial_location_sometimes = 0;
                  this.customer_default_requirements.trucking_at_initial_location_dont_know = 0;
              }
          }else if(getKey == 'trucking_at_initial_location_sometimes'){
              if(this.customer_default_requirements.trucking_at_initial_location_sometimes != 0){
              }else{
                  this.customer_default_requirements.trucking_at_initial_location_yes = 0;
                  this.customer_default_requirements.trucking_at_initial_location_no = 0;
                  this.customer_default_requirements.trucking_at_initial_location_dont_know = 0;
              }
          }else if(getKey == 'trucking_at_initial_location_dont_know'){
              if(this.customer_default_requirements.trucking_at_initial_location_dont_know != 0){
              }else{
                  this.customer_default_requirements.trucking_at_initial_location_yes = 0;
                  this.customer_default_requirements.trucking_at_initial_location_no = 0;
                  this.customer_default_requirements.trucking_at_initial_location_sometimes = 0;
              }
          }

          this.customer_default_requirements[getKey] = (this.customer_default_requirements[getKey]==1) ? 0 : 1
      },
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
        this.value = (this.field.value=='' || this.field.value==null) ?
            {
                "ocean_freight_yes":0,
                "ocean_freight_no":0,
                "ocean_freight_sometimes":0,
                "ocean_freight_dont_know":1,

                "trucking_destination_yes": 0,
                "trucking_destination_no": 0,
                "trucking_destination_sometimes": 0,
                "trucking_destination_dont_know":1,

                "pier_pass_yes":0,
                "pier_pass_no":0,
                "pier_pass_sometimes":0,
                "pier_pass_dont_know":1,

                "customs_clearance_yes": 0,
                "customs_clearance_no": 0,
                "customs_clearance_sometimes": 0,
                "customs_clearance_dont_know":1,

                "duty_layout_yes": 0,
                "duty_layout_no": 0,
                "duty_layout_sometimes":0,
                "duty_layout_dont_know":1,

                "isf_yes": 0,
                "isf_no": 0,
                "isf_sometimes": 0,
                "isf_dont_know":1,

                "trucking_at_initial_location_yes": 0,
                "trucking_at_initial_location_no": 0,
                "trucking_at_initial_location_sometimes": 0,
                "trucking_at_initial_location_dont_know":1,

            } : JSON.parse(this.field.value)
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
        formData.append(this.field.attribute, JSON.stringify(this.customer_default_requirements) || JSON.stringify(
            {
                "ocean_freight_yes":0,
                "ocean_freight_no":0,
                "ocean_freight_sometimes":0,
                "ocean_freight_dont_know":0,

                "trucking_destination_yes": 0,
                "trucking_destination_no": 0,
                "trucking_destination_sometimes": 0,
                "trucking_destination_dont_know":0,

                "pier_pass_yes":0,
                "pier_pass_no":0,
                "pier_pass_sometimes":0,
                "pier_pass_dont_know":0,

                "customs_clearance_yes": 0,
                "customs_clearance_no": 0,
                "customs_clearance_sometimes": 0,
                "customs_clearance_dont_know":0,

                "duty_layout_yes": 0,
                "duty_layout_no": 0,
                "duty_layout_sometimes":0,
                "duty_layout_dont_know":0,

                "isf_yes": 0,
                "isf_no": 0,
                "isf_sometimes": 0,
                "isf_dont_know":1,

                "trucking_at_initial_location_yes": 0,
                "trucking_at_initial_location_no": 0,
                "trucking_at_initial_location_sometimes": 0,
                "trucking_at_initial_location_dont_know":1,
            }))
    },
  },
}
</script>
