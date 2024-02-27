<template>
    <span class="title-value" style="display: none">{{ field.value }}</span>
</template>

<script>
export default {
    props: ['resourceName', 'field'],

    mounted() {
        $('.title-value').parent().addClass('hidden')
        $(`span:contains(${this.field.name})`).parent().addClass('hidden')

        let isExist = $('small[class="italic ml-1 text-sm"]')

        isExist.length < 1 ? $('h1[class="mb-3 text-90 font-normal text-2xl"]').removeClass('mb-3') : undefined
        isExist.length < 1 ? $('h1[class="text-90 font-normal text-2xl"]').after(() =>
        (
            `<small class="italic ml-1 text-sm">${this.field.text}</small>`
        )) : undefined
        isExist.length < 1 ? $('small[class="italic ml-1 text-sm"]').next().addClass('mt-3') : undefined
    },
}
</script>
