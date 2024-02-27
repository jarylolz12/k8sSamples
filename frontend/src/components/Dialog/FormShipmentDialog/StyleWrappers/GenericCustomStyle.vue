n<template>
    <div :class="customClass" :id="id">
    <slot name="content"></slot>
    </div>
</template>

<style lang="scss">
@import "./scss/GenericCustomStyle.scss";
</style>

<script>
export default {
	name: 'GenericCustomStyle',
    props: {
        id: {
            default: ''
        },
        customStyle: {
            default: ''
        },
        customClass: {
            default: ''
        }
    }
}
</script>
