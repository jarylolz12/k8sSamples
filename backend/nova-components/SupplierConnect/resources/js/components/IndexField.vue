<template>
    <div>
        <svg class="svg-icon"
             style="width: 1em; height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;"
             v-tooltip.top-end="'Connect'" width="30" @click="connectToCustomer(true)"
             v-if="field.connectedCustomerId === 0"
             viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M582.239997 663.142968c-56.713997 0-113.426994-21.589999-156.609992-64.772997-11.728999-11.726999-11.728999-30.792998 0-42.520998 11.728999-11.726999 30.791998-11.726999 42.520998 0 62.910997 62.909997 165.212992 62.909997 228.121989 0L914.94998 337.169984c62.910997-62.909997 62.910997-165.211992 0-228.121989-62.907997-62.909997-165.210992-62.909997-228.119989 0L502.313 293.565986c-11.728999 11.727999-30.792998 11.727999-42.521998 0-11.726999-11.727999-11.726999-30.792998 0-42.520998L644.308994 66.527997c86.364996-86.364996 226.858989-86.364996 313.163985 0 86.304996 86.363996 86.363996 226.857989 0 313.163985L738.794989 598.368971c-43.183998 43.183998-99.898995 64.772997-156.612992 64.772997L582.239997 663.141968zM221.386014 1023.99995c-56.714997 0-113.429994-21.591999-156.611992-64.772997-86.363996-86.364996-86.363996-226.856989 0-313.163985l218.678989-218.678989c86.364996-86.364996 226.858989-86.364996 313.162985 0 11.727999 11.727999 11.727999 30.791998 0 42.520998-11.727999 11.726999-30.792998 11.726999-42.518998 0-62.910997-62.909997-165.213992-62.909997-228.122989 0L107.29402 688.583966c-62.909997 62.907997-62.909997 165.212992 0 228.120989 62.908997 62.907997 165.211992 62.907997 228.121989 0l184.516991-184.517991c11.728999-11.728999 30.793998-11.728999 42.520998 0 11.728999 11.726999 11.728999 30.790998 0 42.520998L377.938007 959.226953C334.755009 1002.407951 278.040011 1023.99995 221.325014 1023.99995L221.386014 1023.99995z"/>
        </svg>

        <span class="mr-6">{{field.customerName}}</span>
        <svg class="svg-icon"
             style="width: 1.3em;height: 1.3em;vertical-align: middle;fill: currentColor;overflow: hidden;"
             v-tooltip.top-end="'Disconnect'" width="30" @click="disConnectToCustomer(true)"
             v-if="field.connectedCustomerId !== 0"
             viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M832.64 191.36a216.192 216.192 0 0 0-306.048 0L429.696 288.384l51.008 51.008 96.896-96.896c53.76-53.824 144.64-59.52 204.032 0 59.52 59.52 53.76 150.144 0 203.968l-96.96 96.896 51.136 51.072 96.896-96.896a216.576 216.576 0 0 0-0.128-306.048z m-386.112 590.272c-53.824 53.76-144.64 59.52-204.032 0a143.616 143.616 0 0 1 0-204.032l96.896-96.896-51.072-51.072L191.36 526.464a216.192 216.192 0 0 0 0 305.92 216.32 216.32 0 0 0 305.92 0l96.96-96.832-51.008-51.008-96.768 97.024z m-186.24-572.224a8 8 0 0 0-11.264 0l-39.68 39.616a8 8 0 0 0 0 11.264l554.432 554.432a8 8 0 0 0 11.328 0l39.616-39.68a8 8 0 0 0 0-11.264L260.288 209.408z"/>
        </svg>

        <div v-show="showModal" @click="closeModal" class="modal-overlay">
            <div class="modal">
                <h6 class="text-white py-16">{{ title }}</h6>
                <div class="flex flex-col button-modal-container">
                    <div class="button-main-container">
                        <label class="select-label">
                            Select Customer
                        </label>
                        <div class="model-select">
                            <model-select :options="field.customers"
                                          v-model="item"
                                          placeholder="select item">
                            </model-select>
                        </div>
                        <button @click="connectToBuyer"
                                class="btn btn-default bg-success text-white py-0 leading-3 btn-yes">
                            <span class="btn-yes" v-if="!sending">Connect</span>
                            <span v-if="sending">Connecting...</span>
                        </button>
                        <button @click="closeModal" class="btn btn-default bg-danger text-white py-0 leading-3 btn-no">
                        <span>
                            Cancel
                        </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .modal-overlay {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        transition: 0.3s background-color;
        background-color: rgba(0, 0, 0, 0.6);
        z-index: 100;
    }

    .model-select {
        padding: 0 25px 20px;
    }

    .modal {
        text-align: center;
        background-color: white;
        margin-top: 10%;
        border-radius: 20px;
        height: 248px;
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

    .select-label {
        text-align: left;
        width: 100%;
        display: inline-block;
        margin: 0 28px 5px;
    }

    .form-control {
        width: 90%;
        display: inline-block;
        border: 2px solid #c8c8c8;
        height: 46px;
        border-radius: 16px;
        padding: 0 10px;
        margin: 0 20px 20px;
    }

    .button-main-container {
        margin-top: 0px;
    }

    .button-main-container button {
        box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
    }

    h6 {
        font-weight: 500;
        font-size: 28px;
        border-top-left-radius: 17px;
        border-top-right-radius: 17px;
        padding-top: 16px;
        padding-bottom: 16px;
        background-color: #fff;
        color: #000000;
    }

    p {
        font-size: 16px;
        margin: 20px 0;
    }

    .button-modal-container {
        padding-top: 15px;
    }


    @media (min-width: 100px) {
        .modal {
            width: 85%;
        }
    }

    @media (min-width: 576px) {
        .modal {
            width: 360px;
        }
    }

    @media (min-width: 768px) {
        .modal {
            width: 455px;
        }

    }

    @media (min-width: 992px) {

    }

    @media (min-width: 1200px) {

    }

</style>
<script>
    import jQuery from "jquery";
    import {VTooltip} from 'v-tooltip'
    import {ModelSelect} from 'vue-search-select'

    export default {
        props: ['resource', 'resourceName', 'resourceId', 'field', 'supplierId', 'customerId', 'connectedCustomerId', 'customerName'],
        data() {
            return {
                sending: false,
                showModal: false,
                title: 'Connect to Customer',
                field: false,
                customer: '',
                supplierId: 0,
                customerId: 0,
                connectedCustomerId: 0,
                customerName: '',
                item: {
                    value: '',
                    text: ''
                }
            }
        },
        computed: {
            token() {
                return jQuery('meta[name="csrf-token"]').attr("content");
            }
        },
        mounted() {

        },
        methods: {
            closeModal(e) {
                let currentSelector = e.target
                if (!currentSelector.classList.contains('btn-yes')) {
                    this.showModal = false
                }

                if (currentSelector.classList.contains('search')) {
                    this.showModal = true
                }

                if (currentSelector.classList.contains('form-control')) {
                    this.showModal = true
                }

                if (currentSelector.classList.contains('btn-no')) {
                    this.showModal = false
                }
            },
            disConnectToCustomer(id) {
                const params = {
                    id: this.field.supplierId,
                    buyerId: this.field.buyerId,
                    customerId: this.field.connectedCustomerId,
                    currentCustomerId: this.field.customerId
                };
                Nova.request().post(
                    `/custom/supplier/disconnect`
                    , params);
                location.reload();
            },
            connectToCustomer(ib) {
                this.is_booking = ib
                if (!this.sending) {
                    this.showModal = true
                }
            },
            connectToBuyer: async function (data) {
                const params = {
                    selectedCustomerId: this.item.value,
                    customerId: parseInt(this.field.customerId),
                    supplierId: this.field.supplierId
                };
                await Nova.request().post(
                    `/custom/supplier/connect`
                    , params);

                location.reload();
            }
        },
        components: {
            ModelSelect
        }
    }
</script>
