<template>
    <div class="flex border-b border-40" resource-id="resourceId">
        <div class="w-1/4 py-4">
            <label   class="inline-block text-80 pt-2 leading-tight">
                Telex
            </label>
        </div>

        <div class="w-3/4 py-4 break-words">

            <div class="flex">
                <div class="w-3/6 flex flex-row">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Release Freight without Telex Released
                    </label>
                    <div style="margin-top: 5px;" class="mx-1">


                        <svg v-if="telex_release.release_freight_without_telex_release==1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="check-circle" role="presentation" class="fill-current text-success"><path d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"></path></svg>
                        <svg v-if="telex_release.release_freight_without_telex_release==0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="x-circle" role="presentation" class="fill-current text-danger mx-1"><path d="M4.93 19.07A10 10 0 1 1 19.07 4.93 10 10 0 0 1 4.93 19.07zm1.41-1.41A8 8 0 1 0 17.66 6.34 8 8 0 0 0 6.34 17.66zM13.41 12l1.42 1.41a1 1 0 1 1-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 1 1-1.42-1.42L10.6 12l-1.42-1.41a1 1 0 1 1 1.42-1.42L12 10.6l1.41-1.42a1 1 0 1 1 1.42 1.42L13.4 12z"></path></svg>

                    </div>
                </div>
            </div>
            <div class="flex">

                <div class="w-3/6 flex flex-row">
                    <label class="inline-block text-80 pt-2 leading-tight">
                        Release Customs without Telex Released
                    </label>
                    <div style="margin-top: 5px;" class="mx-1">

                        <svg v-if="telex_release.release_customs_without_telex_release==1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="check-circle" role="presentation" class="fill-current text-success"><path d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"></path></svg>
                        <svg v-if="telex_release.release_customs_without_telex_release==0"xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="x-circle" role="presentation" class="fill-current text-danger mx-1"><path d="M4.93 19.07A10 10 0 1 1 19.07 4.93 10 10 0 0 1 4.93 19.07zm1.41-1.41A8 8 0 1 0 17.66 6.34 8 8 0 0 0 6.34 17.66zM13.41 12l1.42 1.41a1 1 0 1 1-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 1 1-1.42-1.42L10.6 12l-1.42-1.41a1 1 0 1 1 1.42-1.42L12 10.6l1.41-1.42a1 1 0 1 1 1.42 1.42L13.4 12z"></path></svg>

                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data() {
        return {
            loading: true,
            telex_release: (this.field.value=='' || this.field.value==null) ?
                {
                    "release_freight_without_telex_release":0,
                    "release_customs_without_telex_release":0,
                } : JSON.parse(this.field.value)
        }
    },
}
</script>
