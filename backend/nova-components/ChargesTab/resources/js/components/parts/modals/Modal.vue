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
    props: ['services', 'customers', 'terms'],
    data() {
        return {
            isActive: false
        };
    },
    created() {
        //
    },
    beforeMount() {
        //console.log(this.customers);
    },
    methods: {
        open () {
            this.$emit('open');
            this.isActive = true;
        },
        
        close (e) {
            if (this.$refs.content.contains(e.target)) return;
            
            this.$emit('close');
            this.isActive = false;
        }
    },

    /**
     * Mount the component.
     */
    mounted() {
        //
    }
};

</script>

<style scoped>
    
</style>
