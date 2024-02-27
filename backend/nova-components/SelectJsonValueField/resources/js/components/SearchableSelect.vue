<template>
	<div class="searchable-select" v-click-outside="open=false">
		<a href="#" class="trigger" @click.prevent="open=true">{{ selected == '' ? 'Click to select' : selected }}</a>
		<input type="hidden" name="field.name.toLowerCase()" v-model="value">
		<div class="dropdown">
			<ul class="list" v-show="open">
				<li v-for="(item,index) in options" :key="index">
					<a href="#" @click.prevent="select(item.value)">{{ item.name }}</a>
				</li>
			</ul>
		</div>
	</div>
</template>

<style lang="scss" scoped>
	.searchable-select-wrapper{
		a{
			display:block;
			color: var(--80);
			text-decoration: none;
		}
		.trigger{
			background-color: var(--white);
		    border-width: 1px;
		    border-color: var(--60);
		    padding-left: 0.75rem;
		    padding-right: 0.75rem;
		    color: var(--80);
		    border-radius: 0.5rem;
		    width: 100%;
		    height: 2.25rem;
    		line-height: normal;
    		font-family: inherit;
			margin: 0;
		}
		.dropdown{
			position:relative;
			ul{
				position:absolute;
				top:0px;
				left:0px;
				box-shadow: 0 2px 4px 0 rgb(0 0 0 / 5%);
				overflow-x:hidden;
				overflow-y:auto;
				max-height: 500px;
				padding:0px;
				margin:0px;
				border-radius: 0.5rem;
				list-style:none;
				li{
					list-style:none;
					a{
						&:hover{
							background:#ededed;
						}
					}
				}
			}
		}
	}
</style>

<script>
	export default{
		props: ['options','selected'],
		data(){
			return {
				selected: '',
				open: false
			}
		},
		methods: {
			select(v){
				this.selected = v;
				this.open = false;
			}
		},
		directives: {
			bind: (el, binding, vnode)=>{
				el.clickOutsideEvent = event=>{
					// here I check that click was outside the el and his children
					if (!(el == event.target || el.contains(event.target))) {
					// and if it did, call method provided in attribute value
						vnode.context[binding.expression](event);
					}
				};

				document.body.addEventListener('click', el.clickOutsideEvent)
			},
			unbind: el=>{
				document.body.removeEventListener('click', el.clickOutsideEvent)
			}
		},
		mounted(){
			this.selected = !this.selected || typeof this.selected == 'undefined' ? '' : this.selected;
		}
	}
</script>
