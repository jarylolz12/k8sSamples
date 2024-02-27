<template>
    <a :href="'/administrator/resources/shipments/'+field.id" class="no-underline dim text-primary font-bold">{{ field.shifl_ref }}</a>
</template>

<script>
export default {
    props: ['resourceName', 'field'],
}
</script>
