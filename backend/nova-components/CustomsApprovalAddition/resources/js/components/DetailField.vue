<template>
    <div class="flex border-b border-40 -mx-6 px-6">
        <div class="w-1/4 py-4">
            <h4 class="font-normal text-80">{{ field.name }}</h4>
        </div>
        <div class="flex w-3/4 py-4 break-words flex-col">
            <div class="w-full flex flex-row py-2">
                <div class="w-1/2">
                    Get Documents From
                </div>
                <div class="flex flex-row">
                    <div v-if="field.data.documents_from==null || field.data.documents_from==''" class="mr-2">
                        <font-awesome-icon color="#e74444" icon="fa-solid fa-info-circle" />
                    </div>
                    <div>
                    {{
                        getValue('documents_from', field.data.documents_from)
                    }}    
                    </div>
                </div>
            </div>
            <div class="w-full flex flex-row py-2">
                <div class="w-1/2">
                    Approval Requirements
                </div>
                <div class="flex flex-row">
                    <div v-if="field.data.approval_requirements==null || field.data.approval_requirements==''" class="mr-2">
                        <font-awesome-icon color="#e74444" icon="fa-solid fa-info-circle" />
                    </div>
                    {{
                        getValue('approval_requirements', field.data.approval_requirements)
                    }}
                </div>
            </div>
            <div class="w-full flex flex-row py-2">
                <div class="w-1/2">
                    Approved HS Codes
                </div>
                <div class="flex flex-row">
                    <div v-if="field.data.approved_hs_codes==null || field.data.approved_hs_codes==''" class="mr-2">
                        <font-awesome-icon color="#e74444" icon="fa-solid fa-info-circle" />
                    </div>
                    {{
                        field.data.approved_hs_codes=='' || field.data.approved_hs_codes==null ? 'Not set.' : field.data.approved_hs_codes
                    }}
                </div>
            </div>
        </div>
  </div>
</template>

<script>
import _ from 'lodash'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faInfoCircle } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'


export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data:() => ({
        documents_from_options: [],
        approval_requirements_options: []
    }),
    components: {
        FontAwesomeIcon
    },
    methods: {
        getValue(keyName, value) {
            if ( value!='' && value!==null ) {
                if ( keyName == 'documents_from' ) {
                    let findValue = _.find(this.documents_from_options, e => (e.id == value))
                    return  ( typeof findValue=='undefined') ? 'Not set.' : findValue.label
                } else {
                    let findValue = _.find(this.approval_requirements_options, e => (e.id == value))
                    return  ( typeof findValue=='undefined') ? 'Not set.' : findValue.label
                }       
            } else {
                return 'Not set.'
            }      
        }
    },
    created() {
        library.add(faInfoCircle)
    },
    mounted() {
        this.approval_requirements_options = this.field.approval_requirements_options
        this.documents_from_options = this.field.documents_from_options
    }
}
</script>
