<template>
    <div>
        <button @click="openModal">{{__('Open Modal')}}</button>

        <portal to="modals">
            <transition name="fade">
                <general-modal
                    v-if="modalOpen"
                    @confirm="confirmModal"
                    @close="closeModal"
                />
            </transition>
        </portal>
    </div>
</template>

<script>
    import GeneralModal from './parts/modals/GeneralModal.vue';

    export default {
        props: ["resourceName", "resourceId", "field"],
        data() {
            return {
                modalOpen: false
            }
        },
        components: {
            GeneralModal
        },

        mounted() {
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
    };
</script>
