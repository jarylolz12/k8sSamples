<template>
    <!-- <div class="modal" v-if="isActive" @click="close"> -->
    <div class="modal" v-if="showModal">
    	<div class="modal__body">
      	<div class="modal__content" ref="content">
          <slot/>
        </div>
        <!-- <div class="modal__footer">
          <button @click.stop="close">Close</button>
        </div> -->
      </div>
    </div>
</template>

<script>

export default {
    name: "Modal",
    components: {
       
    },
    props: [],
    data() {
        return {
            showModal: false,
        };
    },
    created() {
        //
    },
    beforeMount() {
        //
    },
    methods: {
        open() {
            this.$emit('open');
            this.showModal = true;
        },
        
        close (e) {
            if (this.$refs.content.contains(e.target)) return;
            
            this.$emit('close');
            this.showModal = false;
        }
    },

    mounted() {
        
    }
};

</script>

<style scoped>
    
</style>
