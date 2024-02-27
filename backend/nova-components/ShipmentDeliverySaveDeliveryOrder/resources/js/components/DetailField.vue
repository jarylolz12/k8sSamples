<template>
    <div>
    <div class="flex border-b border-40">
        <div class="w-1/5 px-8 py-6">
            <label for="trucker" class="inline-block text-80 pt-2 leading-tight">
                Trucker
            </label>
        </div>
        <div class="py-6 px-8 w-1/2">
            {{field.trucker_name}}
        </div>
    </div>
    <div class="flex border-b border-40">
        <div class="w-1/5 px-8 py-6">
            <label for="trucker" class="inline-block text-80 pt-2 leading-tight">
                Delivery Notes
            </label>
        </div>
        <div class="py-6 px-8 w-1/2">
            {{field.trucker_custom_note}}
        </div>
    </div>
    </div>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
}
</script>
