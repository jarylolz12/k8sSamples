<template>
    <!-- <span>{{ field.value }}</span> -->
    <span>{{ typeof fieldValue.customer !=='undefined' && typeof fieldValue.customer.DisplayName !=='undefined' ? fieldValue.customer.DisplayName : '' }}</span>
</template>

<script>
export default {
    props: ['resourceName', 'field'],

    data(){
        return{
            fieldValue: {},
        }
    },
    
    mounted(){
        if(this.field.value){
            this.fieldValue = JSON.parse(this.field.value);
        }
    }
}
</script>
