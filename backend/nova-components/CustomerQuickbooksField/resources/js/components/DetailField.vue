<template>
    <!-- <panel-item :field="field"/> -->
    <div>
        <div class="flex border-b border-40 remove-bottom-border">
            <div class="w-1/4 py-4">
                <h4 class="font-normal text-80">Linked to Quickbooks Customer</h4>
            </div>
            <div class="w-3/4 py-4 break-words">
                <span class="italic" style="color:var(--primary-dark)"> {{typeof fieldValue.company !=='undefined'  ? fieldValue.company + ' - ' : ''}}</span>
                <span>{{ typeof fieldValue.customer !== 'undefined' && typeof fieldValue.customer.DisplayName !== 'undefined'? fieldValue.customer.DisplayName : '' }}</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data(){
        return{
            fieldValue:{},
        }
    },
    
    mounted(){
        if(this.field.value){
            this.fieldValue = JSON.parse(this.field.value);
        }
    }
}
</script>
