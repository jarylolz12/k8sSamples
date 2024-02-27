<template>
  <div class="flex border-b border-40">
    <div class="w-1/4 py-4">
      <h4 class="font-normal text-80 flex">
        {{ field.name }}
      </h4>
    </div>
    <div class="w-3/4 py-4 break-words flex relative">
      <div class="flex justify-end item-center absolute top-0 left-auto right-0" style="left:auto;right:0px">
          <svg xmlns="http://www.w3.org/2000/svg" class="cursor-pointer w-4 mr-2" v-if="!field.defaultFields.disable_instructions" v-tooltip.top-end="'Instructions'" viewBox="0 0 448 512" @click="showContent('instructions')">
            <path :fill="showInstructions ? '#4099de' : '#b3b3b3'" d="M448 336v-288C448 21.49 426.5 0 400 0H96C42.98 0 0 42.98 0 96v320c0 53.02 42.98 96 96 96h320c17.67 0 32-14.33 32-31.1c0-11.72-6.607-21.52-16-27.1v-81.36C441.8 362.8 448 350.2 448 336zM143.1 128h192C344.8 128 352 135.2 352 144C352 152.8 344.8 160 336 160H143.1C135.2 160 128 152.8 128 144C128 135.2 135.2 128 143.1 128zM143.1 192h192C344.8 192 352 199.2 352 208C352 216.8 344.8 224 336 224H143.1C135.2 224 128 216.8 128 208C128 199.2 135.2 192 143.1 192zM384 448H96c-17.67 0-32-14.33-32-32c0-17.67 14.33-32 32-32h288V448z"/>
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" v-if="!field.defaultFields.disable_information" class="cursor-pointer w-4" v-tooltip.top-end="'Information'" @click="showContent('information')">
            <path :fill="showInformation ? '#4099de' : '#b3b3b3'" d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 128c17.67 0 32 14.33 32 32c0 17.67-14.33 32-32 32S224 177.7 224 160C224 142.3 238.3 128 256 128zM296 384h-80C202.8 384 192 373.3 192 360s10.75-24 24-24h16v-64H224c-13.25 0-24-10.75-24-24S210.8 224 224 224h32c13.25 0 24 10.75 24 24v88h16c13.25 0 24 10.75 24 24S309.3 384 296 384z"/>
          </svg>
        </div>
        <div class="field-container mr-3">
          <svg
            v-if="!field.defaultFields.disable_icon && field.value"
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            aria-labelledby="check-circle"
            role="presentation"
            class="fill-current text-success"
          >
            <path
              d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"
            ></path>
          </svg>
          <svg
            v-if="!field.defaultFields.disable_icon && !field.value"
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            aria-labelledby="x-circle"
            role="presentation"
            class="fill-current text-danger"
          >
            <path
              d="M4.93 19.07A10 10 0 1 1 19.07 4.93 10 10 0 0 1 4.93 19.07zm1.41-1.41A8 8 0 1 0 17.66 6.34 8 8 0 0 0 6.34 17.66zM13.41 12l1.42 1.41a1 1 0 1 1-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 1 1-1.42-1.42L10.6 12l-1.42-1.41a1 1 0 1 1 1.42-1.42L12 10.6l1.41-1.42a1 1 0 1 1 1.42 1.42L13.4 12z"
            ></path>
          </svg>
          <p v-if="field.defaultFields.disable_icon" style="width:160px">{{ field.value && field.value != '' ? field.value : '—' }}</p>
        </div>
        <div class="w-full overflow-auto">
          <div v-show="showInstructions">
            <div v-if="!field.defaultFields.disable_instructions && field.defaultFields.milestone && field.defaultFields.milestone.instructions" class="markdown leading-normal whitespace-pre-line" style="white-space:pre!important" v-html="field.defaultFields.milestone.instructions.trim()">
            </div>
          </div>
          <div v-show="showInformation">
            <div v-if="!field.defaultFields.disable_information && field.defaultFields.milestone && field.defaultFields.milestone.general_information" class="markdown leading-normal whitespace-pre-line" style="white-space:pre!important" v-html="field.defaultFields.milestone.general_information.trim()">
            </div>
          </div>
        </div>
    </div>
  </div>
</template>

<script>
import "vue-awesome/icons";
import Icon from "vue-awesome/components/Icon";
import { VTooltip } from 'v-tooltip'

export default {
  props: ["resource", "resourceName", "resourceId", "field"],
  components: {
    'v-icon': Icon
  },
  data() {
    return {
      showInstructions: false,
      showInformation: false
    };
  },
  directives: { 'v-tooltip': VTooltip },
  watch: {
    showInformation(v){
      this.showInformation = v;
    },
    showInformation(v){
      this.showInformation = v;
    }
  },
  methods: {
    showContent(t){
      if( t == 'information' ){
        this.showInstructions = false;
        this.showInformation = this.showInformation ? false : true;
      }else if( t == 'instructions' ){
        this.showInformation = false;
        this.showInstructions = this.showInstructions ? false : true;
      }
    }
  }
};
</script>

<style scoped>
.gray-color {
  fill: gray;
}
.tooltip[x-placement^="right"] {
  margin-right: 30px;
}
</style>
