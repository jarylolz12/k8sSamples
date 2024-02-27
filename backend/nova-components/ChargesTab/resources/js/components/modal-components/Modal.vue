<template>
    <!-- <div class="modal" v-if="isActive" @click="close"> -->
    <div class="modal" v-if="isActive">
    	<div class="modal__body">
      	<div class="modal__content" ref="content">
          <slot />
        </div>
        <div class="modal__footer">
          <button @click.stop="close">Close</button>
        </div>
      </div>
    </div>
</template>

<script>
export default {
    name: "Modal",
    components: {
       //
    },
    props: [],
    data() {
        return {
            isActive: false
        };
    },
    created() {
        //
    },
    beforeMount() {
        //
    },
    methods: {
        open () {
            this.$emit('open');
            this.isActive = true;
        },
        
        close (e) {
            //if (this.$refs.content.contains(e.target)) return;
            this.$store.commit('updateSelectedInvoice_CT', null)
            this.$store.commit('showEditInvoiceModal_CT',false)
            this.$store.commit('showDeleteInvoiceModal_CT',false)

            this.$store.commit('updateSelectedBill_CT', null)
            this.$store.commit('showEditBillModal_CT',false)
            this.$store.commit('showDeleteBillModal_CT',false)
            //this.$emit('close');
            this.isActive = false;
            
        }
    },

    mounted() {
        //
    }
};

</script>

<style scoped>
    
</style>
