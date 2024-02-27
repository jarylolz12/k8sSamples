<template>
  <div
    class="w-3/5 bg-white rounded border border-40 px-2 py-2 flex mb-2 mt-2"
  >
    <div class="rounded-full w-12 h-12 mr-3 overflow-hidden text-center" style="flex-shrink: 0">
      <div class="w-12 h-12 text-sm font-bold bg-60 text-40" style="line-height: 3rem">
        {{ !!note.createdBy ? (note.createdBy || '').substr(0, 3).toUpperCase() : '??' }}
      </div>
    </div>

    <div>
      <!-- Title area -->
      <div class="mb-2">
        <span class="font-bold text-lg text-90">{{
          note.createdBy
        }}</span>
        <span class="text-xs text-80">
          {{ formattedCreatedAtDate }}
        </span>
        <span
          class="text-xs text-error hover:underline cursor-pointer"
          style="color: #4099de"
          @click="$emit('editNote', note)"
          v-if="isEditMode"
          >[Edit]
        </span>
        <span
          class="text-xs text-error hover:underline cursor-pointer"
          style="color: #e74c3c"
          @click="$emit('onDeleteRequested', note)"
          v-if="isEditMode && note.uploaded_from == 0"
          >[Delete]
        </span>
      </div>
            <!-- Title -->
            <p v-html="note.subject" class="whitespace-pre-wrap font-semibold"></p>
            <!-- Content -->
            <p v-html="note.description" class="whitespace-pre-wrap"></p>
    </div>
  </div>
</template>

<script>
export default {
  props: ['note', 'dateFormat', 'isEditMode'],
  computed: {
    formattedCreatedAtDate() {
      return moment(this.note.created_at).format(this.dateFormat || 'DD MMM YYYY HH:mm');
    },
  },
};
</script>
