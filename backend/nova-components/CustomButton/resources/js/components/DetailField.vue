<template>
    <div>
        <div class="flex flex-row justify-center mt-1">
            <button @click="sendEmail(true)" class="btn btn-default btn-icon bg-primary text-white mr-3">
                <span>Send Booking Email to Customer</span> <!---->
            </button>
            <button @click="sendEmail(false)" class="btn btn-default btn-icon bg-primary text-white">
                <span>Send Updated Booking Email to Customer</span><!---->
            </button>
        </div>

        <div v-show="showModal" @click="closeModal" class="modal-overlay">
            <div class="modal">
                <h6 class="text-white py-16">{{ title }}</h6>
                <div class="flex flex-col button-modal-container">
                    <div>
                        {{
                            message
                        }}
                    </div>
                    <div class="button-main-container">
                        <button @click="confirm" class="btn btn-default bg-success text-white py-0 leading-3 btn-yes">
                            <span class="btn-yes" v-if="!sending">Yes</span>
                            <span v-if="sending">Sending...</span>
                        </button>
                        <button @click="closeModal" class="btn btn-default bg-danger text-white py-0 leading-3 btn-no">
                            <span>
                                No
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
  background-color: #000000da;
  z-index: 100;
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

.button-main-container {
    margin-top: 60px;
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
  background-color: rgb(37, 45, 55);
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
import iziToast from 'izitoast'
import 'izitoast/dist/css/iziToast.css'

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data() {
        return {
            sending: false,
            showModal: false,
            title: '',
            message: 'Are you sure you want to send this?',
            is_booking: true
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
        async confirm() {

            let ib = this.is_booking
            this.sending = true
            try {
                let fd = new FormData()
                fd.append('_token', this.token)
                fd.append('shipment_id', this.field.shipment_id)
                fd.append('is_booking', (ib) ? 1 : 0)

                const requestOptions = {
                    method: "POST",
                    body: fd
                }
                const response = await fetch(`${this.field.base_url}/custom/send-booking-email`, requestOptions)
                let message = 'Booking Email was successfully sent.'

                iziToast.success({
                    theme: 'dark',
                    message,
                    backgroundColor: '#16B442',
                    messageColor: '#ffffff',
                    iconColor: '#ffffff',
                    progressBarColor: '#ADEEBD',
                    displayMode: 1,
                    position: 'topRight',
                    timeout: 3000,
                })

                this.sending = false
                this.showModal = false

            } catch(e) {
                this.sending = false
                console.log(e)
            }
        },
        closeModal(e) {

            let currentSelector = e.target
            if(!currentSelector.classList.contains('btn-yes')) {
               this.showModal = false
            }

            if (currentSelector.classList.contains('btn-no')) {
                this.showModal = false
            }


        },
        sendEmail(ib) {

            this.is_booking = ib
            this.title = (ib) ? 'Send Booking Email' : 'Send Updated Booking Email'
            if ( !this.sending ) {
                this.showModal = true
            }
            
        }
    }
}
</script>
