<template>
    <div class="modal-overlay" style="justify-content: center !important;">
        <div :class="`modal bg-white rounded-lg shadow-lg`" style="background-color: white!important;">
            <div v-if="itemData.is_confirmed == 1 && validateBuyRates(freightPortToPort) && agentEmail">
                <div class="p-8">
                    <heading :level="2" class="mb-6">{{ __('Add Email Recipients.') }}</heading>
                    <p class="text-80 leading-normal">{{__('Input email separated by comma.')}}</p>
                    <input-tag add-tag-on-blur="true" v-model="tags"></input-tag>
                </div>
                <div class="p-8">
                    <drag-n-drop-input
                        :buttonState="buttonState"
                        :componentIndex="1"
                        @getAttachements="getAttachements"
                    />
                </div>
                <div class="px-6 py-3">
                    <div class="ml-auto">
                        <div>
                            <label>{{__('Recipients')}} </label>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-3 text-left">
                    <div class="ml-auto">
                        <div>
                            <label>User: </label>
                            <span>{{ this.userEmail }}</span>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-3 text-left">
                    <div class="ml-auto">
                        <div>
                            <label>Agent: </label>
                            <span>{{ this.agentEmail }}</span>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-3 text-left">
                    <div class="ml-auto">
                        <div>
                            <label>Booking: </label>
                            <span v-for="(item,key) in shiflOfficeFromDisplay">{{ item }}, </span>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-3 text-left">
                    <div class="ml-auto">
                        <div>
                            <label>Operation: </label>
                            <span v-for="(item,key) in shiflOfficeFromOperationDisplay">{{ item }}, </span>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-3 text-left">
                    <div class="ml-auto">
                        <div>
                            <label>CC: </label>
                            <span v-for="(item,key) in ccEmails">{{ item }}, </span>
                        </div>
                    </div>
                </div>
                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button type="button" data-testid="cancel-button" dusk="cancel-general-button" @click.prevent="handleClose" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}</button>
                        <button type="button" data-testid="add-button" dusk="add-general-button" @click.prevent="handleConfirm" class="btn text-80 font-normal h-9 px-3 mr-3 btn-primary text-white">{{__('Send')}}</button>
                    </div>
                </div>
            </div>
            <div v-else-if="itemData.is_confirmed == 0">
                <div style="padding: 10px 0px 30px 0px">
                    {{ __('Please select the schedule.') }}
                </div>
                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button type="button" data-testid="cancel-button" dusk="cancel-general-button" @click.prevent="handleClose" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Ok')}}</button>
                    </div>
                </div>
            </div>
            <div v-else-if="itemData.is_confirmed == 1 && !validateBuyRates(freightPortToPort)">
                <div style="padding: 10px 0px 30px 0px">
                    {{ __('Please input the freight port to port services in buy rates.') }}
                </div>
                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button type="button" data-testid="cancel-button" dusk="cancel-general-button" @click.prevent="handleClose" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Ok')}}</button>
                    </div>
                </div>
            </div>
            <div v-else-if="agentEmailMessage">
                <div style="padding: 10px 0px 30px 0px">
                    {{ agentEmailMessage }}
                </div>
                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button type="button" data-testid="cancel-button" dusk="cancel-general-button" @click.prevent="handleClose" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Ok')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios';
import InputTag from 'vue-input-tag'
import jQuery from "jquery";
import DragNDropInput from '../DragNDropInput.vue';
export default {
    props: ['currentSelected', 'freightPortToPort', 'shiflOfficeFrom', 'userEmail', 'item', 'baseUrl'],
    data() {
        return {
            tags: [],
            shiflOfficeFromDisplay: [],
            shiflOfficeFromOperationDisplay: [],
            shiflOfficeFromEmails:[
                {
                    officeName: 'Shifl USA',
                    emails:['pamela@shifl.com', 'shia@shifl.com']
                },
                {
                    officeName: 'Shifl China',
                    emails:['Cathy@shifl.cn', 'Mark@shifl.cn', 'pricing@shifl.cn']
                },
                {
                    officeName: 'Shifl Vietnam',
                    emails:['Joy@shifl.com', 'Leonardo@shifl.com']
                },
                {
                    officeName: 'Shifl India',
                    emails:['Renu@shifl.com', 'Prateek@shifl.com']
                },
                {
                    officeName: 'Shifl Malaysia',
                    emails:['pamela@shifl.com', 'shia@shifl.cn']
                }
            ],
            shiflOfficeFromOperationEmails:[
                {
                    officeName: 'Shifl USA',
                    emails:['pamela@shifl.com', 'shia@shifl.com']
                },
                {
                    officeName: 'Shifl China',
                    emails:['eric@shifl.cn', 'harry@shifl.cn']
                },
                {
                    officeName: 'Shifl Vietnam',
                    emails:['Joy@shifl.com', 'Leonardo@shifl.com']
                },
                {
                    officeName: 'Shifl India',
                    emails:['Renu@shifl.com', 'Prateek@shifl.com']
                },
                {
                    officeName: 'Shifl Malaysia',
                    emails:['gnanavel@shifl.com']
                }
            ],
            ccEmails:['chana@shifl.com', 'shia@shifl.com'],
            agentEmail: '',
            buttonState: {
                isLoading: false,
                isShow: false
            },
            attachments: [],
            shipmentDocs: [],
            agentEmailMessage: 'Please select Agent.',
            itemData: {},
        };
    },
    computed: {
        token() {
            return jQuery('meta[name="csrf-token"]').attr('content')
        }
    },
    components: {
        InputTag,
        DragNDropInput
    },
    name: "AddEmailsModal",
    methods: {
        handleClose() {
            this.$emit('close')
        },
        handleConfirm() {
            this.$emit('confirm',
                {
                    emails: this.tags,
                    userEmail: this.userEmail,
                    agentEmail: this.agentEmail,
                    shiflOfficeFromDisplay: this.shiflOfficeFromDisplay,
                    ccEmails: this.ccEmails,
                    attachments: this.attachments,
                }
            )
        },
        validateBuyRates(data){
            if(data !== undefined){
                const jsonObj = data;
                if(jsonObj){
                    if(jsonObj.length > 0){
                        const freightBuyRates =  this.searchByKey("Freight (port to port)", jsonObj);
                        return freightBuyRates.length > 0 ? true : false;
                    }
                }
            }
            return false;
        },
        searchByKey(key, inputArray) {
            const newArray = [];
            for (let i=0; i < inputArray.length; i++) {
                if (inputArray[i].service_name === key) {
                    newArray.push(inputArray[i]);
                }
            }
            return newArray;
        },
        getAttachements: function(value) {
            this.attachments = value;
        },
    },
    watch: {
        item: function (newVal, oldVal) {
            this.itemData = newVal;
            if(this.itemData.agent_id != undefined) {
                axios.get(`${this.baseUrl}/custom/get-booking-agent-email/${this.itemData.agent_id}`, {
                    token: this.token
                }).then(response => {
                    if (response.status == 200) {
                        this.agentEmail = response.data.data;
                        this.agentEmailMessage = ""
                        let agent_emails = response.data.emails.map( i => i.email );
                        this.ccEmails = [...this.ccEmails,...agent_emails];
                    }
                    else if(response.status == 204) {
                        this.agentEmail = ''
                        this.agentEmailMessage = "Please select agent."
                    }
                    else if(response.status == 202) {
                        this.agentEmail = '';
                        this.agentEmailMessage = 'Agent have not valid email';
                    }
                })
            }
            const filterOfficeEmails = this.shiflOfficeFromEmails.find(office => office.officeName == this.shiflOfficeFrom.name)
            if(filterOfficeEmails){
                this.shiflOfficeFromDisplay = filterOfficeEmails.emails
            }
            const filterOfficeOperationEmails = this.shiflOfficeFromOperationEmails.find(office => office.officeName == this.shiflOfficeFrom.name)
            if(filterOfficeOperationEmails){
                this.shiflOfficeFromOperationDisplay = filterOfficeOperationEmails.emails
            }
        },
    },

    /**
     * Mount the component.
     */
    mounted() {}
}
</script>

<style scoped>
    .modal-overlay {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center important!;
        transition: 0.3s background-color;
        background-color: #000000da;
        z-index: 100;
        overflow-y: auto;
    }
    .modal {
        left:auto;
        min-width: 460px;
        width: auto;
        text-align: center;
        padding-top: 25px;
        border-radius: 20px;
        padding-bottom: 25px;
        height: fit-content;
        box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
    }
</style>
