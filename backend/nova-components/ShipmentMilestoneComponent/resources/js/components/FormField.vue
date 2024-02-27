<template>
  <div class="flex border-b border-40">
    <div class="w-1/5 px-8 py-6">
      <label for="custom_notes" class="inline-block text-80 pt-2 leading-tight">{{field.name}}</label>
    </div>
    <div class="py-6 px-8 w-4/5">
      <div class="flex w-full">
        <div class="mr-3">
          <input
            type="checkbox"
            class="checkbox mt-2"
            :id="field.attribute"
            :name="field.name"
            @click="onClickCheckbox"
            :checked="trueValue"
          />
        </div>
        <div class="note-container w-full">
          <div class="flex justify-end w-full">
            <a
              v-if="field.defaultFields.milestone && !showInstructions"
              aria-role="button"
              class="cursor-pointer dim inline-block font-bold"
              @click="onClickShow"
              ><v-icon class="gray-color" name="chevron-down"
            /></a>
            <a
              v-if="showInstructions && field.defaultFields.milestone"
              aria-role="button"
              class="
                cursor-pointer
                dim
                inline-block
                font-bold
                text-red
              "
              @click="onClickShow"
            >
              <v-icon class="gray-color" name="chevron-up" />
            </a>
          </div>

          <div v-if="showInstructions">
            <div class="markdown leading-normal whitespace-pre-line" style="white-space:pre" v-html="field.defaultFields.milestone.instructions">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import "vue-awesome/icons";
import Icon from "vue-awesome/components/Icon";

export default {
  mixins: [FormField, HandlesValidationErrors],
  components: {
    "v-icon": Icon,
  },
  mounted(){

    console.log("What is field", this.field)
  },
  props: ["resourceName", "resourceId", "field"],
  data() {
    return {
      trueValue: this.field.value || 0,
      showInstructions: false,
    };
  },
  methods: {
    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, this.trueValue);
    },
    onClickShow: function (e) {
      e.preventDefault();
      this.showInstructions = !this.showInstructions;
    },
    onClickCheckbox: function (e) {
      if(!this.field.readonly){
        this.trueValue = this.trueValue ? 0 : 1;
      } else {
        e.preventDefault()
        return false
      }
    },
  },
};
</script>

<style scoped>
.gray-color {
  fill: gray;
}
</style>