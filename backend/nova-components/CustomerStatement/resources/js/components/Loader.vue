<template>
	<div class="loader-wrapper">
		<div v-if="type == 1" class="lds-ripple" :style="cssVars"><div></div><div></div></div>
		<div v-if="type == 2" class="lds-roller" :style="cssVars"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
		<div v-if="type == 3" class="lds-ellipsis" :style="cssVars"><div></div><div></div><div></div><div></div></div>
		<div v-if="type == 4" class="lds-facebook" :style="cssVars"><div></div><div></div><div></div></div>
		<div v-if="type == 5" class="lds-ring" :style="cssVars"><div></div><div></div><div></div><div></div></div>
		<div v-if="type == 6" class="lds-default" :style="cssVars"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
		<div v-if="type == 7" class="lds-spinner" :style="cssVars"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
	</div>
</template>

<style lang="scss" scoped>
	.loader-wrapper{
		display:flex;
		align-items:center;
		justify-content:center;
	}
	// loader 1
	.lds-ripple {
		display: inline-block;
		position: relative;
		width: 80px;
		height: 80px;
	}
	.lds-ripple div {
		position: absolute;
		border: 4px solid var(--color);
		opacity: 1;
		border-radius: 50%;
		animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
	}
	.lds-ripple div:nth-child(2) {
		animation-delay: -0.5s;
	}
	@keyframes lds-ripple {
		0% {
			top: 36px;
			left: 36px;
			width: 0;
			height: 0;
			opacity: 1;
		}
		100% {
			top: 0px;
			left: 0px;
			width: 72px;
			height: 72px;
			opacity: 0;
		}
	}
	// loader 2
	.lds-roller {
		display: inline-block;
		position: relative;
		width: 80px;
		height: 80px;
	}
	.lds-roller div {
		animation: lds-roller 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
		transform-origin: 40px 40px;
	}
	.lds-roller div:after {
		content: " ";
		display: block;
		position: absolute;
		width: 7px;
		height: 7px;
		border-radius: 50%;
		background: var(--color);
		margin: -4px 0 0 -4px;
	}
	.lds-roller div:nth-child(1) {
		animation-delay: -0.036s;
	}
	.lds-roller div:nth-child(1):after {
		top: 63px;
		left: 63px;
	}
	.lds-roller div:nth-child(2) {
		animation-delay: -0.072s;
	}
	.lds-roller div:nth-child(2):after {
		top: 68px;
		left: 56px;
	}
	.lds-roller div:nth-child(3) {
		animation-delay: -0.108s;
	}
	.lds-roller div:nth-child(3):after {
		top: 71px;
		left: 48px;
	}
	.lds-roller div:nth-child(4) {
		animation-delay: -0.144s;
	}
	.lds-roller div:nth-child(4):after {
		top: 72px;
		left: 40px;
	}
	.lds-roller div:nth-child(5) {
		animation-delay: -0.18s;
	}
	.lds-roller div:nth-child(5):after {
		top: 71px;
		left: 32px;
	}
	.lds-roller div:nth-child(6) {
		animation-delay: -0.216s;
	}
	.lds-roller div:nth-child(6):after {
		top: 68px;
		left: 24px;
	}
	.lds-roller div:nth-child(7) {
		animation-delay: -0.252s;
	}
	.lds-roller div:nth-child(7):after {
		top: 63px;
		left: 17px;
	}
	.lds-roller div:nth-child(8) {
		animation-delay: -0.288s;
	}
	.lds-roller div:nth-child(8):after {
		top: 56px;
		left: 12px;
	}
	@keyframes lds-roller {
		0% {
			ransform: rotate(0deg);
		}
			100% {
		transform: rotate(360deg);
		}
	}
	// loader 3
	.lds-ellipsis {
		display: inline-block;
		position: relative;
		width: 80px;
		height: 80px;
	}
	.lds-ellipsis div {
		position: absolute;
		top: 33px;
		width: 13px;
		height: 13px;
		border-radius: 50%;
		background: var(--color);
		animation-timing-function: cubic-bezier(0, 1, 1, 0);
	}
	.lds-ellipsis div:nth-child(1) {
		left: 8px;
		animation: lds-ellipsis1 0.6s infinite;
	}
	.lds-ellipsis div:nth-child(2) {
		left: 8px;
		animation: lds-ellipsis2 0.6s infinite;
	}
	.lds-ellipsis div:nth-child(3) {
		left: 32px;
		animation: lds-ellipsis2 0.6s infinite;
	}
	.lds-ellipsis div:nth-child(4) {
		left: 56px;
		animation: lds-ellipsis3 0.6s infinite;
	}
	@keyframes lds-ellipsis1 {
		0% {
			transform: scale(0);
		}
		100% {
			transform: scale(1);
		}
	}
	@keyframes lds-ellipsis3 {
		0% {
			transform: scale(1);
		}
		100% {
			transform: scale(0);
		}
	}
	@keyframes lds-ellipsis2 {
		0% {
			transform: translate(0, 0);
		}
		100% {
			transform: translate(24px, 0);
		}
	}
	// loader 4
	.lds-facebook {
		display: inline-block;
		position: relative;
		width: 80px;
		height: 80px;
	}
	.lds-facebook div {
		display: inline-block;
		position: absolute;
		left: 8px;
		width: 16px;
		background: var(--color);
		animation: lds-facebook 1.2s cubic-bezier(0, 0.5, 0.5, 1) infinite;
	}
	.lds-facebook div:nth-child(1) {
		left: 8px;
		animation-delay: -0.24s;
	}
	.lds-facebook div:nth-child(2) {
		left: 32px;
		animation-delay: -0.12s;
	}
	.lds-facebook div:nth-child(3) {
		left: 56px;
		animation-delay: 0;
	}
	@keyframes lds-facebook {
		0% {
			top: 8px;
			height: 64px;
		}
		50%, 100% {
			top: 24px;
			height: 32px;
		}
	}
	// loader 5
	.lds-ring {
		display: inline-block;
		position: relative;
		width: 80px;
		height: 80px;
	}
	.lds-ring div {
		box-sizing: border-box;
		display: block;
		position: absolute;
		width: 64px;
		height: 64px;
		margin: 8px;
		border: 8px solid var(--color);
		border-radius: 50%;
		animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
		border-color: var(--color) transparent transparent transparent;
	}
	.lds-ring div:nth-child(1) {
		animation-delay: -0.45s;
	}
	.lds-ring div:nth-child(2) {
		animation-delay: -0.3s;
	}
	.lds-ring div:nth-child(3) {
		animation-delay: -0.15s;
	}
	@keyframes lds-ring {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}
	// loader 6
	.lds-default {
		display: inline-block;
		position: relative;
		width: 80px;
		height: 80px;
	}
	.lds-default div {
		position: absolute;
		width: 6px;
		height: 6px;
		background: var(--color);
		border-radius: 50%;
		animation: lds-default 1.2s linear infinite;
	}
	.lds-default div:nth-child(1) {
		animation-delay: 0s;
		top: 37px;
		left: 66px;
	}
	.lds-default div:nth-child(2) {
		animation-delay: -0.1s;
		top: 22px;
		left: 62px;
	}
	.lds-default div:nth-child(3) {
		animation-delay: -0.2s;
		top: 11px;
		left: 52px;
	}
	.lds-default div:nth-child(4) {
		animation-delay: -0.3s;
		top: 7px;
		left: 37px;
	}
	.lds-default div:nth-child(5) {
		animation-delay: -0.4s;
		top: 11px;
		left: 22px;
	}
	.lds-default div:nth-child(6) {
		animation-delay: -0.5s;
		top: 22px;
		left: 11px;
	}
	.lds-default div:nth-child(7) {
		animation-delay: -0.6s;
		top: 37px;
		left: 7px;
	}
	.lds-default div:nth-child(8) {
		animation-delay: -0.7s;
		top: 52px;
		left: 11px;
	}
	.lds-default div:nth-child(9) {
		animation-delay: -0.8s;
		top: 62px;
		left: 22px;
	}
	.lds-default div:nth-child(10) {
		animation-delay: -0.9s;
		top: 66px;
		left: 37px;
	}
	.lds-default div:nth-child(11) {
		animation-delay: -1s;
		top: 62px;
		left: 52px;
	}
	.lds-default div:nth-child(12) {
		animation-delay: -1.1s;
		top: 52px;
		left: 62px;
	}
	@keyframes lds-default {
		0%, 20%, 80%, 100% {
			transform: scale(1);
		}
		50% {
			transform: scale(1.5);
		}
	}
	// loader 7
	.lds-spinner {
		color: official;
		display: inline-block;
		position: relative;
		width: 80px;
		height: 80px;
	}
	.lds-spinner div {
		transform-origin: 40px 40px;
		animation: lds-spinner 1.2s linear infinite;
	}
	.lds-spinner div:after {
		content: " ";
		display: block;
		position: absolute;
		top: 3px;
		left: 37px;
		width: 6px;
		height: 18px;
		border-radius: 20%;
		background: var(--color);
	}
	.lds-spinner div:nth-child(1) {
		transform: rotate(0deg);
		animation-delay: -1.1s;
	}
	.lds-spinner div:nth-child(2) {
		transform: rotate(30deg);
		animation-delay: -1s;
	}
	.lds-spinner div:nth-child(3) {
		transform: rotate(60deg);
		animation-delay: -0.9s;
	}
	.lds-spinner div:nth-child(4) {
		transform: rotate(90deg);
		animation-delay: -0.8s;
	}
	.lds-spinner div:nth-child(5) {
		transform: rotate(120deg);
		animation-delay: -0.7s;
	}
	.lds-spinner div:nth-child(6) {
		transform: rotate(150deg);
		animation-delay: -0.6s;
	}
	.lds-spinner div:nth-child(7) {
		transform: rotate(180deg);
		animation-delay: -0.5s;
	}
	.lds-spinner div:nth-child(8) {
		transform: rotate(210deg);
		animation-delay: -0.4s;
	}
	.lds-spinner div:nth-child(9) {
		transform: rotate(240deg);
		animation-delay: -0.3s;
	}
	.lds-spinner div:nth-child(10) {
		transform: rotate(270deg);
		animation-delay: -0.2s;
	}
	.lds-spinner div:nth-child(11) {
		transform: rotate(300deg);
		animation-delay: -0.1s;
	}
	.lds-spinner div:nth-child(12) {
		transform: rotate(330deg);
		animation-delay: 0s;
	}
	@keyframes lds-spinner {
		0% {
			opacity: 1;
		}
		100% {
			opacity: 0;
		}
	}
</style>

<script>
	export default{
		props : ['type', 'color'],
		watch : {}, 
		computed : {
			cssVars(){
				return { '--color' : this.color ? this.color : 'initial' };
			}
		}
	}
</script>