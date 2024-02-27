<template>
    <notes-field
    :field="field"
    :resourceName="resourceName"
    :resourceId="resourceId"
    :extraClass="'-mx-6 -my-px border-t'"
    :isEditMode="false"
  />
</template>

<script>
import NotesField from './notes/NotesField';

export default {
    components: { NotesField },
    props: ['resource', 'resourceName', 'resourceId', 'field'],
}
</script>
