<template>
    <div class="flex border-b border-40">
        <div class="w-1/4 py-4">
            <h4 class="font-normal text-80">MBL Copy Surrendered/Released</h4>
        </div>
        <div class="w-3/4 pt-8 break-words">
            <div v-if="field.value=='' || field.value==null" class="dmcs mt-3 font-bold pb-4">
                No MBL Copy Surrendered Document yet.
            </div>
            <a v-if="field.value!=='' && field.value!==null" @click="download" class="cursor-pointer flex flex-row text-primary font-bold dmcs mt-3 pb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" aria-labelledby="download" role="presentation" class="fill-current mr-2">
                    <path d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"></path>
                </svg>
                <div>Download MBL Copy Surrendered/Released</div>
            </a>
        </div>
    </div>
</template>
<style type="text/css" scoped>
    .dmcs {
        margin-left: -29%;
    }

</style>
<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    methods: {
        download() {
           window.location.href = `${this.field.base_url}/custom/download-mbl-copy-surrendered/${this.resourceId}`
        }
    }
}
</script>


