<template>
    <v-dialog v-model="dialogCreate" max-width="560px" content-class="email-dialog" :retain-focus="false" @click:outside="close">
        <v-card class="email-dialog-card">
            <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                <v-card-title>
                    <span class="headline">Email PO to Vendor</span>

                    <button icon dark class="btn-close" @click="close">
                        <v-icon>mdi-close</v-icon>
                    </button>
                </v-card-title>

                <v-card-text>
                    <v-row>
                        <v-col cols="12" sm="12" class="pb-0">
                            <div class="vendor-wrapper">
                                <div class="vendor-details">
                                    <p class="email-title">VENDOR</p>
                                    <p class="email-data" v-html="getVendor(emailItem.po.supplier_id)"></p>
                                </div>
                            </div>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" sm="12">
                            <div class="email-wrapper">
                                <div class="email-details mb-3">
                                    <p class="email-title">EMAIL</p>
                                    <div class="tags-email-wrapper mb-1">
                                        <vue-tags-input
                                            hide-details="auto"
                                            :rules="arrayNotEmptyRules"
                                            :tags="options"
                                            :add-on-blur=true
                                            :add-on-key="[13, ',']"
                                            :validation="tagsValidation"
                                            v-model="poEmailAddress"
                                            placeholder="e.g. name@email.com"
                                            @tags-changed="newTags => {
                                                this.options = newTags
                                                this.tagsInput.touched = true
                                                this.tagsInput.hasError = (this.options.length > 0) ? false : true
                                                let el = this.documentProto.getElementsByClassName('ti-input')[0]
                                                if (typeof el!=='undefined') {
                                                    if (this.tagsInput.hasError)
                                                        el.classList.add('ti-new-tag-input-error')
                                                    else
                                                        el.classList.remove('ti-new-tag-input-error')
                                                }
                                            }"
                                        />
                                    </div>

                                    <div v-if="tagsInput.touched" class="v-text-field__details">
                                        <div class="v-messages theme--light error--text" role="alert">
                                            <div class="v-messages__wrapper">
                                                <div v-if="(options.length > 0) && poEmailAddress!==''" class="v-messages__message">
                                                    {{
                                                        tagsInput.errorMessage
                                                    }}
                                                </div>

                                                <div v-if="options.length == 0 && poEmailAddress!==''" class="v-messages__message">
                                                    {{
                                                        tagsInput.errorMessage
                                                    }}
                                                </div>
                                                <div v-if="options.length == 0 && poEmailAddress==''" class="v-messages__message">
                                                    Please provide at least 1 valid email address.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <span class="separate-info">Separate multiple email addresses with comma</span>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions class="po-button-actions">
                    <button class="btn-blue" @click="sendVendorEmail()" :disabled="getEmailLoading">
                        <span>
                            {{ getEmailLoading ? 'Sending Email...' : 'Send Email' }}
                        </span>
                    </button>

                    <button class="btn-white" @click="close">
                        Cancel
                    </button>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import globalMethods from '../../../utils/globalMethods'
import _ from 'lodash'
import VueTagsInput from '@johmun/vue-tags-input';
import jQuery from 'jquery'

export default {
    name: 'POCreateDialog',
    props: ['dialog', 'editedItems', 'editedIndex', 'isMobile', 'currentpo_id'],
    components: { 
        VueTagsInput
    },
    data: () => ({
        valid: true,
        options: [],
        tagsValidation: [
            {
                classes: 't-new-tag-input-text-error',
                rule: (/^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/),
                disableAdd: true
            }
        ],
        documentProto: document,
        tagsInput: {
            touched: false,
            hasError: false,
            errorMessage: 'Please confirm the entered email address by pressing the "Enter" or "," key in your keyboard.'
        },
        poEmailAddress: '',
        arrayNotEmptyRules: [
            (v) => !!v || "Email is required",
            (v) => /.+@.+/.test(v) || "E-mail must be valid",
            () => this.optionsFiltered.length > 0 || "Make sure to supply at least 1 email." 
        ],
    }),
    computed: {
        ...mapGetters({
            getEmailLoading: 'po/getEmailLoading',
            getVendorLists: 'po/getVendorLists',
        }),
        dialogCreate: {
            get() {
                return this.dialog
            },
            set (value) {
                this.$emit('update:dialog', value)
            }
        },
        emailItem: {
            get() {
                return this.editedItems
            },
            set(value) {
                this.$emit('update:editedItems', value)
            }
        },
        addEmailTemplate() {
            let { emails, po } = this.emailItem

            return {
                emails,
                id: po.id
            }
        },
        optionsFiltered: {
            get() {
                let validEmailAddress = []

                if (this.emailItem.emails !== null && this.emailItem.emails.length > 0) {                    
                    this.emailItem.emails.map(wea => {
                        if (wea!==null) {
                            validEmailAddress.push({text: wea,tiClasses:["ti-valid"]})
                        }
                    })
                }

                return validEmailAddress
            },
            set(value) {
                this.options = value
            }            
        },
    },
    watch: {
        tagsInput(value) {
            if ( value.hasError) {
                jQuery('.ti-input').addClass('ti-new-tag-input-error')
            } else {
                jQuery('.ti-input').removeClass('ti-new-tag-input-error')
            }
        },
        dialogCreate(value, oldValue) {
            this.tagsInput.hasError = false
            this.tagsInput.touched = false

            jQuery('.ti-input').removeClass('ti-new-tag-input-error')
            if (typeof el!=='undefined') {
                let el = document.getElementsByClassName('ti-input')[0]
                el.classList.remove('ti-new-tag-input-error')
            }           
            
            if ( !value ) {
                this.options = []
                this.poEmailAddress = ''                

            } else if(value && !oldValue) {
                // if (this.editedIndex==-1) {
                //     this.options = []
                // } else {
                //     let validEmailAddress = []
                //     if (this.emailItem.emails !== null && this.emailItem.emails.length > 0) {
                        
                //         this.emailItem.emails.map(wea => {
                //             if (wea!==null) {
                //                 validEmailAddress.push({text: wea,tiClasses:["ti-valid"]})
                //             }
                //         })
                //     }
                //     this.options = validEmailAddress    
                // }     
                
                let validEmailAddress = []
                if (this.emailItem.emails !== null && this.emailItem.emails.length > 0) {
                    
                    this.emailItem.emails.map(wea => {
                        if (wea!==null) {
                            validEmailAddress.push({text: wea,tiClasses:["ti-valid"]})
                        }
                    })
                }
                this.options = validEmailAddress
            }
        }
    },
    methods: {
        ...mapActions({
            sendEmail: 'po/sendEmail',
            getPo: 'po/getPo',
        }),
        ...globalMethods,
        generateErrorMessage() {
            this.tagsInput.hasError = (this.options.length > 0) ? false : true
            if (this.tagsInput.hasError)
                jQuery('.ti-input').addClass('ti-new-tag-input-error')
            else
                jQuery('.ti-input').removeClass('ti-new-tag-input-error')
        },
        getVendor(id) {
            if ( Array.isArray(this.getVendorLists) && this.getVendorLists.length > 0) {
                let findVendor = _.find(this.getVendorLists, (e => e.id === id))
                if ( typeof findVendor!=='undefined' ) {
                    return findVendor.company_name
                }
            }
            return '--'
        },
        sendVendorEmail() {
            if (!this.tagsInput.touched)
                this.tagsInput.touched = true

            this.$refs.form.validate()
            this.tagsInput.hasError = (this.options.length > 0) ? false : true
    
            this.generateErrorMessage()
            
            setTimeout(async () => {
                if (this.$refs.form.validate()) {
                    let isValid = false
                    var re = /\S+@\S+\.\S+/                    
                        
                    if (this.poEmailAddress !== '') {
                        if (re.test(this.poEmailAddress)) {
                            isValid = true
                        } else {
                            isValid = false
                        }
                    } else {
                        isValid = true
                    }
                    
                    if (!this.tagsInput.hasError && isValid) {
                        try {
                            jQuery('.ti-new-tag-input').trigger(
                                jQuery.Event( 'keyup', { keyCode: 13, which: 13 } )
                            )

                            let finalEmailAddress = (this.options.length > 0) ? this.options.map(o => {
                                return o.text
                            }) : []

                            let addEmailTemplate = this.addEmailTemplate
                            addEmailTemplate.emails = finalEmailAddress

                            await this.sendEmail(addEmailTemplate)                 
                            this.notificationMessage('PO has been emailed to the vendor.')

                            if (typeof this.currentpo_id !== 'undefined' && this.currentpo_id !== '' 
                                && this.currentpo_id !== '') {
                                await this.getPo(this.currentpo_id) 
                            }           
                                                
                            this.close()
                        } catch (e) {
                            this.notificationError(e)
                            this.close()
                        }
                    }                    
                } 
            }, 200) 
        },
        close() {
            this.$refs.form.resetValidation()
            this.$emit('close')
        }
    },
    async mounted() {},
    updated() {
        if (typeof this.$refs.form !== 'undefined') {
            if (typeof this.$refs.form.resetValidation() !== 'undefined') {
                this.$refs.form.resetValidation()
            }
        }
    }
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/po/poEmailDialog.scss';
</style>
