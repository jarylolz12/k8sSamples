<template>
	<modal class="bg-none">
		<div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width: 600px;">
      <span style="color: red; float: right; padding-top: 10px; padding-right: 15px; cursor: pointer" @click="close">
        <b>X</b>
      </span>
			<div class="p-8 text-left">
				<h2 class="mb-6 text-90 font-normal text-xl">{{ subject }}</h2>
				<p class="text-80 leading-normal" v-html="body"></p>
			</div>
		</div>
	</modal>
</template>

<script>
import axios from "axios";
export default {
	props: ['subject','body'],
  name: "InfoModal",

	mounted() {
		//
	},
	data() {
		return {
		}
	},
	methods: {
    close(){
      this.$emit('imOnCancelled', false)
    }
	}
}
</script>
