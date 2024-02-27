<template>
	<modal class="bg-none">
		<div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width: 550px;">
			<div class="p-8 text-left">
				<h2 class="mb-6 text-90 font-normal text-xl" v-html="subject"></h2>
				<p class="text-80 leading-normal" v-html="body"></p>
			</div>
      <div class="flex justify-between	border-t border-50 px-6 py-2 ml-auto">
        <div  >
          <div class="cursor-pointer btn dim flex items-center" style="min-height: 70px;" @click="showInfo" v-if="question_mark">
              <svg  fill="red" viewBox="0 0 24 24" width="24px" height="24px">    <path d="M 12 2 C 6.4889971 2 2 6.4889971 2 12 C 2 17.511003 6.4889971 22 12 22 C 17.511003 22 22 17.511003 22 12 C 22 6.4889971 17.511003 2 12 2 z M 12 4 C 16.430123 4 20 7.5698774 20 12 C 20 16.430123 16.430123 20 12 20 C 7.5698774 20 4 16.430123 4 12 C 4 7.5698774 7.5698774 4 12 4 z M 11 7 L 11 9 L 13 9 L 13 7 L 11 7 z M 11 11 L 11 17 L 13 17 L 13 11 L 11 11 z"/></svg>
          </div>
        </div>
  			<div class=" flex items-center" style="min-height: 70px; flex-direction: row-reverse;">
          <a v-if="cancel" @click="close" class="cursor-pointer btn text-80 font-normal px-3 mr-3 btn-link">
            Cancel
          </a>
  				<span>
  					<span v-if="override" @click="onOverride" class="nova-button nova-button-post cursor-pointer dim inline-block text-primary font-bold no-underline nova-button-post cursor-pointer dim inline-block text-primary font-bold no-underline"
  						style="min-width: 80px;">
  						      Override
  					</span>
  					<span v-if="overrideConfirm" @click="overrideConfirmation" class="nova-button nova-button-post cursor-pointer dim inline-block text-primary font-bold no-underline nova-button-post cursor-pointer dim inline-block text-primary font-bold no-underline"
  						style="min-width: 80px;">
                  Override Confirmed
  					</span>
  				</span>

  			</div>
      </div>

		</div>
	</modal>
</template>

<script>
import axios from "axios";
export default {
	props: ['subject','body','cancel','override', 'question_mark','overrideConfirm'],
  name: "Confirmation",

	mounted() {
		//
	},
	data() {
		return {
		}
	},
	methods: {
    showInfo(){
      this.$emit('onQuestionMark', true)
    },
    close(){
      this.$emit('onCancelled', false)
    },
    onOverride(){
      this.$emit('onOverride',{})
    },
    overrideConfirmation(){
      this.$emit('onOverrideConfirmation',{})
    }
	}
}
</script>
