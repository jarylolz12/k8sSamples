<template>
    <div>
        <div class="flex items-center mb-3">
            <div class="flex-auto">
                <heading>Invoices</heading>
            </div>
            <div class="flex-auto"> 
            </div>
        </div>
        <Container />
    </div>
</template>

<script>
import Container from './Container.vue';

export default {
    metaInfo() {
        return {
          title: 'Invoices',
        }
    },
    components : { Container }
}
</script>