<template>
    <div>
        <div class="flex border-b border-40 -mx-6 px-6">
            <div class="w-1/4 py-4">
                <h4 class="font-normal text-80">Booking Sent to Customer</h4>
            </div>
            <div class="w-3/4 py-4 break-words" v-if="field.shipment.is_booking_email_sent == 1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="check-circle" role="presentation" class="fill-current text-success"><path d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"></path></svg>
            </div>
            <div class="w-3/4 py-4 break-words" v-else>
                <svg data-v-47b1ae0b="" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="x-circle" role="presentation" class="fill-current text-danger"><path data-v-47b1ae0b="" d="M4.93 19.07A10 10 0 1 1 19.07 4.93 10 10 0 0 1 4.93 19.07zm1.41-1.41A8 8 0 1 0 17.66 6.34 8 8 0 0 0 6.34 17.66zM13.41 12l1.42 1.41a1 1 0 1 1-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 1 1-1.42-1.42L10.6 12l-1.42-1.41a1 1 0 1 1 1.42-1.42L12 10.6l1.41-1.42a1 1 0 1 1 1.42 1.42L13.4 12z"></path></svg>
            </div>
        </div>
        <div class="flex border-b border-40 -mx-6 px-6">
            <div class="w-1/4 py-4">
                <h4 class="font-normal text-80">Booking Sent to Customer At</h4>
            </div>
            <div class="w-3/4 py-4 break-words" v-if="field.shipment.is_booking_email_sent_at != null && field.shipment.is_booking_email_sent_at != '' && field.shipment.is_booking_email_sent_at != 'undefined'">
                {{this.bookingSentToCustomer}}
            </div>
            <div class="w-3/4 py-4 break-words" v-else>
                -
            </div>
        </div>

        <div class="flex border-b border-40 -mx-6 px-6">
            <div class="w-1/4 py-4">
                <h4 class="font-normal text-80">Booking Sent to Agent</h4>
            </div>
            <div class="w-3/4 py-4 break-words" v-if="isAgentBookingSent == true">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="check-circle" role="presentation" class="fill-current text-success"><path d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"></path></svg>
            </div>
            <div class="w-3/4 py-4 break-words" v-else>
                <svg data-v-47b1ae0b="" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="x-circle" role="presentation" class="fill-current text-danger"><path data-v-47b1ae0b="" d="M4.93 19.07A10 10 0 1 1 19.07 4.93 10 10 0 0 1 4.93 19.07zm1.41-1.41A8 8 0 1 0 17.66 6.34 8 8 0 0 0 6.34 17.66zM13.41 12l1.42 1.41a1 1 0 1 1-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 1 1-1.42-1.42L10.6 12l-1.42-1.41a1 1 0 1 1 1.42-1.42L12 10.6l1.41-1.42a1 1 0 1 1 1.42 1.42L13.4 12z"></path></svg>
            </div>
        </div>
        <div class="flex border-b border-40 -mx-6 px-6">
            <div class="w-1/4 py-4">
                <h4 class="font-normal text-80">Booking Sent to Agent At</h4>
            </div>
            <div class="w-3/4 py-4 break-words" v-if="agentBookingSentAt != null && agentBookingSentAt != '' && agentBookingSentAt != 'undefined'">
                {{agentBookingSentAt}}
            </div>
            <div class="w-3/4 py-4 break-words" v-else>
                -
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data() {
        return {
            isAgentBookingSent: false,
            agentBookingSentAt: '',
            bookingSentToCustomer: this.field.shipment.is_booking_email_sent_at

        };
    },
    created(){
        console.log(this.field.shipment);
        let filteredSchedule =  this.field.shipment.schedules_group.filter(schedule => schedule.agent_booking_sent == true)

        this.isAgentBookingSent = filteredSchedule.length;
        this.agentBookingSentAt = filteredSchedule[0].hasOwnProperty('agent_booking_sent_at') ? filteredSchedule[0].agent_booking_sent_at : ''
        if(this.agentBookingSentAt != '' && this.agentBookingSentAt != '' && this.agentBookingSentAt != 'undefined'){
            const date = new Date();

            let day = date.getDate();
            let month = date.getMonth() + 1;
            let year = date.getFullYear();
            this.agentBookingSentAt = `${month}-${day}-${year}`;
        }
        if(this.bookingSentToCustomer != '' && this.bookingSentToCustomer != '' && this.bookingSentToCustomer != 'undefined'){
            const date = new Date(this.bookingSentToCustomer);

            let day = date.getDate();
            let month = date.getMonth() + 1;
            let year = date.getFullYear();
            this.bookingSentToCustomer = `${month}-${day}-${year}`;
        }
    }
}
</script>
