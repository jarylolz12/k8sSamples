<template>
    <div :style="customStyle" :id="id">
    <slot name="content"></slot>
    </div>
</template>

<style lang="scss">
@import "./scss/NotifyWrapperMobileStyle.scss";
</style>

<script>
export default {
	name: 'NotifyWrapperMobileStyle',
    props: {
        id: {
            default: ''
        },
        customStyle: {
            default: ''
        }
    }
}
</script>
