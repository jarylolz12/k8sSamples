<template>
    <div>
        <toggle-btn
          @setIsActiveState="isActivehandler"
          :defaultValue="defaultValue"
        ></toggle-btn>
    </div>
</template>

<script>
import ToggleBtn from './ToggleBtn.vue';
export default {
    name: "AllowBankToggle",
    components:{ToggleBtn,},
    
    props: ['resourceId','defaultValue'],

    data() {
        return {
        };
    },

    created() {
     
    },

    methods: {
       isActivehandler(e){
        this.$emit('setIsActiveState',e)
       }
    },
};

</script>

<style scoped>

</style>
