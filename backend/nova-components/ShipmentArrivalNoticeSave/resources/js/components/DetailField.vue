<template>
    <div>
        <div class="flex border-b border-40">
            <div class="w-1/4 py-4">
                <h4 class="font-normal text-80">Carrier Arrival Notice Firms</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                {{field.canf_terminal == null ? '—' : field.canf_terminal}}
            </div>
        </div>
        <div class="flex border-b border-40">
            <div class="w-1/4 py-4">
                <h4 class="font-normal text-80">Customs Broker</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                {{field.custom_broker_name == null ? '—' : field.custom_broker_name}}
            </div>
        </div>

        <div v-show="field.customs_broker_id == null" class="flex border-b border-40">
            <div class="w-1/4 py-4"></div>
            <div class="w-3/4 py-4 break-words">
                <button class="nova-button-shipment cursor-pointer dim inline-block text-primary font-bold" @click="openModal">{{__('Send Arrival Notice')}}</button>
            </div>

            <portal to="modals">
                <transition name="fade">
                    <no-customs-broker-modal
                        v-if="modalOpen"
                        @confirm="confirmModal"
                        @close="closeModal"
                    />
                </transition>
            </portal>
        </div>
    </div>
</template>

<script>
import NoCustomsBrokerModal from './modals/NoCustomsBrokerModal'
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data() {
        return {
            modalOpen: false
        }
    },
    components: {
        NoCustomsBrokerModal
    },
    methods: {
        openModal() {
            this.modalOpen = true;
        },
        confirmModal() {
            this.modalOpen = false;
        },
        closeModal() {
            this.modalOpen = false;
        }
    }
}
</script>
<style scoped>
    .nova-button-shipment {
        border-block: aqua;
        border: var(--primary);
        border-style: solid;
        border-bottom-style: inherit;
        padding: 5px;
    }
</style>
