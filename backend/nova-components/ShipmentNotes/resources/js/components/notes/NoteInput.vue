<template>
  <div class="mb-4 flex">
      <div class="input-content-box">
          <div class="border-b border-40">
              <div class="py-3 px-8">
                  <input
                      v-model="subject"
                      @keyup="$emit('subject')"
                      placeholder="Title"
                      type="text"
                      class="w-full form-control form-input form-input-bordered"
                  />
              </div>
          </div>
          <div class="border-b border-40">
              <div class="py-3 px-8">
              <textarea
                  rows="3"
                  :placeholder="placeholder"
                  class="w-full form-control form-input form-input-bordered textarea-content-box"
                  v-model="description"
                  @keyup="$emit('description')"
              />
              </div>
          </div>
      </div>

    <div class="whitespace-no-wrap ml-2 my-4 flex button-box">
        <button
            class="btn btn-default btn-primary mr-2 inline-flex items-center relative ml-auto"
            @click="$emit('onCancelEdit')"
            type="button"
            :disabled="loading "
        >
            Cancel
        </button>
      <button
        class="btn btn-default btn-primary inline-flex items-center relative ml-auto"
        @click="isEditing ? $emit('onSubmitEdit', subject, description) : $emit('onSubmit', subject, description)"
        type="button"
        :disabled="loading || (!description || !subject)"
      >
        {{ isEditing ? 'Save' : 'Add' }} Note
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: ['placeholder', 'loading', 'description', 'subject', 'isEditing'],
};
</script>
<style lang="scss" scoped>
    .note-input-field {
        display: inline-block;
        margin-right: 10px;
        background: #eef1f4;
        width: 100%;
    }
    .textarea-content-box{
        height: 70px;
    }
    .input-content-box{
        width: 33%;
    }
    .input-content-box .form-input{
        width: 100%;
    }
    .button-box{
        justify-content: center;
        align-items: end;
    }
</style>

