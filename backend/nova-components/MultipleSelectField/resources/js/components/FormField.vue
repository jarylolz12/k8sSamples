<template>

  <div class="flex border-b border-40">
      <div class="w-1/5 px-8 py-6">
        <slot>
          <h4 class="font-normal text-80">{{ field.name }}</h4>
        </slot>
      </div>
      <div id="kenetashi-multiple-select-container" class="py-6 px-8 w-1/2 break-words">
        <v-select :selectable="(option) => validateOption(option)" taggable push-tags multiple v-model="value" :options="options"  @input="setSelected" @option:deselected="deselected"></v-select>
        <div v-if="entity=='customer' || entity=='office'" class="text-error pt-2">
            <span v-bind:key="key" v-for="(error,key) in errors">
                {{
                    typeof error[field.attribute]!=='undefined' ? error[field.attribute][0] : ''
                }}
            </span>
        </div>
        <input :id="field.attribute" type="hidden" v-model="value" />
      </div>
    </div>
</template>
<style scoped type="text/css">
</style>
<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import _ from 'lodash'
import jQuery from 'jquery'
import {
  mapActions,
  mapGetters
} from 'vuex'


export default {
    mixins: [FormField, HandlesValidationErrors],
    props: ['resourceName', 'resourceId', 'field'],
    data() {

        return {
            options: (typeof this.field.options!=='undefined') ? this.field.options : [],
            emailsValue: [],
            bookingEmailsValue: []
        }
    },
    computed: {
    entity() {
        return this.field.entity
    },
    emails() {

        //emails container
        let setEmails = []
        
        //first get the value
        let {
            value,
            emailsValue,
            bookingEmailsValue
        } = this

        if ( Array.isArray(value) ) {
            value.map(sv => {
                let setValue = ''
                if ( typeof sv.email!=='undefined')
                    setValue = sv.email
                else
                    setValue = sv

                if ( setEmails.indexOf(setValue)==-1)
                    setEmails.push(sv)
            })
        }

        if ( this.field.attribute == 'booking_and_updated_emails' || this.field.attribute == 'all_email_emails') {
            emailsValue.map(ev => {
                let setValue = ''
                if ( typeof ev.email!=='undefined' )
                    setValue = ev.email
                else
                    setValue = ev
                if ( setEmails.indexOf(setValue)==-1)
                    setEmails.push(setValue)
            })

            if ( this.field.attribute == 'booking_and_updated_emails' ) {

                if ( Array.isArray(bookingEmailsValue) ) {
                    bookingEmailsValue.map(ev => {
                        let setValue = ''
                        if ( typeof ev.email!=='undefined' )
                            setValue = ev.email
                        else
                            setValue = ev
                        if ( setEmails.indexOf(setValue)==-1)
                            setEmails.push(setValue)
                    })
                }
                
            }
        }

        return setEmails
    }
  },
  async mounted() {
    if ( this.entity === 'customer' || this.entity === 'office' ) {

        //delete multiselect
        Nova.$on('delete-multi-select', args => {
            let { field, value } = args;

            if (field === 'all_email_emails' && this.field.attribute !== field) {

                let getValue = this.value || [];
                let newValue = getValue.filter(gv => gv !== value);
                
                if ( this.field.attribute === 'booking_and_updated_emails' || this.field.attribute === 'booking_confirmation_emails') {
                    
                    let nonShifl = false
                    newValue.map(nv => {
                        let domain = nv.split('@')[1].split('.')[0].toLowerCase();
                        if ( domain!='shifl' ) {
                            nonShifl = true
                        }
                    })

                    if ( !nonShifl ) {
                        newValue = []
                    }
                }

                this.value = newValue;
            }

        })

        //update multiselect
        Nova.$on('update-multi-select', args => {
            let {
                field,
                value
            } = args

            let except_fields = ['agent_booking_updated_emails', 'agent_booking_confirmation_emails']

            if ( field === 'all_email_emails' && this.field.attribute!= field && except_fields.indexOf(this.field.attribute)==-1) {
                let getValue = ( this.value==null || this.value == '') ? [] : this.value
                value.some(v => {
                    if ( this.validateOption(v) ) {
                        let findValue = _.findIndex(getValue, e => (e == v))
                        if ( findValue == -1 ) {
                            getValue.push(v)
                            jQuery('.vs__selected').css('backgroundColor','blue !important')
                        }
                    }
                })


                if ( this.field.attribute === 'booking_and_updated_emails' || this.field.attribute === 'booking_confirmation_emails' ) {
                    let getEmailValue = document.getElementById('all_email_emails').value
                    getEmailValue = getEmailValue.split(',')

                    let hasNonShifl = false
                    getValue.some(gv => {
                        let domain = gv.split('@')[1].split('.')[0].toLowerCase()
                        if ( domain !='shifl' ) {
                            hasNonShifl = true
                        }
                    })
                    if ( getEmailValue.length > 0 && hasNonShifl ) {
                        
                        getEmailValue.map(gev => {
                            let findValue = _.findIndex(getValue, e => (e == gev))
                            if ( findValue == -1 ) {
                                getValue.push(gev)
                            }
                        })
                    }    
                }
                
                this.value = getValue
                
            } else {
                
            }
        })


        if ( this.field.attribute == 'booking_and_updated_emails' || this.field.attribute == 'all_email_emails' && (this.field.entity =='customer' || this.field.entity == 'office')) {
            try {
                

                let output = (this.field.entity=='customer') ? await this.fetchCustomerItem({
                    id: this.field.id
                }) : await this.fetchOfficeItem({
                    id: this.field.id
                })

                let {
                    data
                } = output

                if ( typeof data!=='undefined' ) {
                    this.bookingEmailsValue = data.item.booking_email_recipients
                    this.emailsValue = data.item.emails
                }
                    
                
                this.value = this.emails

            } catch(e) {
                console.log('err', e)
            }
        }


    }
    
  },
  methods: {
    ...mapActions({
        fetchCustomerItem: 'customer/fetchItem',
        fetchOfficeItem: 'office/fetchItem'
    }),
    setSelected(value) {
        if ( this.entity === 'customer' ) {
            Nova.$emit('update-multi-select', {
                field: this.field.attribute,
                value
            })
        }else if( this.entity === 'office') {
            Nova.$emit('update-multi-select', {
                field: this.field.attribute,
                value
            })
        }
    },
    deselected(value) {

        if ( this.entity === 'customer'  || this.entity === 'office' ) {
            if ( this.field.attribute!='all_email_emails' ) {
                let getEmailValue = document.getElementById('all_email_emails').value
                if ( getEmailValue!=='' && getEmailValue!==null ) {
                    getEmailValue = getEmailValue.split(',')
                    let findValue = _.findIndex(getEmailValue, e => (e == value))
                    if ( findValue!==-1 ) {
                        this.value.push(value)
                    }
                }
            } else {
                Nova.$emit('delete-multi-select', {
                    field: this.field.attribute,
                    value
                })
            }
        }
        
    },
    validateOption(value) {
        
        if ( this.entity === 'customer' || this.entity === 'office' ) {
            let allValues = this.value!==null ? this.value : []
            //set an email pattern
            let pattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/
            if ( pattern.test(value) ) {

                if ( this.field.attribute === 'booking_and_updated_emails' || this.field.attribute === 'booking_confirmation_emails' ) {

                    if ( allValues.length > 0 ) {
                        return allValues.some(av => {
                            const domain = av.split('@')[1].split('.')[0].toLowerCase();
                            return domain !== 'shifl'
                        })
                    } else {
                        const domain = value.split('@')[1].split('.')[0].toLowerCase()
                        return domain !== 'shifl'
                    }

                } else {

                    if ( this.field.attribute === 'agent_booking_updated_emails' || this.field.attribute === 'agent_booking_confirmation_emails') {
                        let tempDomain = value.split('@')[1].split('.')[0].toLowerCase()
                        let extension = value.split('@')[1].split('.')[1].toLowerCase()
                        return (tempDomain == 'shifl' && (extension === 'cn' || extension ==='com')) ? true : false
                    }

                    return true
                }


            } else {
                return false
            }
        } else {
            return true
        }
    },
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
        this.value = Array.isArray(this.field.value)
                ? this.field.value
                : JSON.parse(this.field.value)

    
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
        //formData.append(this.field.attribute, this.value || '')
        formData.append(this.field.attribute, JSON.stringify(this.value))
    },
  },
}
</script>
