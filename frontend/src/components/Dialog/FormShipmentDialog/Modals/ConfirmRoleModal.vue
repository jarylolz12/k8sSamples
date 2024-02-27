<template>
	<div>
		<v-dialog v-if="!isMobile" v-model="show" max-width="480px" :content-class="className">
			<v-card class="confirm-role-dialog-success">
				<v-card-title id="confirm-role-dialog-title" class="headline">
					<slot name="title"></slot>
				</v-card-title>
				<v-card-text class="pb-0">
					<h2>
						{{ "Confirm your role" }}
					</h2>
					<p>
						{{
							"You have already selected " + selectedEntity + " in the order section. Selecting your role to ‘" + selectedRole + "’ will reset that order section. If you proceed, You will have to select one or more " + orders+ "."
						}}
					</p>
				</v-card-text>
				<v-card-actions>
					<slot name="actions" v-bind:footer="{close: close, submit: submit, cancelRole: cancelRole }"></slot>
                </v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>
<style lang="scss">
@import "./scss/confirmRoleModal.scss";
</style>
<script>
export default {
	name: 'ConfirmRoleModal',
	props: ['isMobile', 'show', 'className', 'currentRole'],
	data: () => ({
	}),
    computed: {
        selectedEntity() {
            return this.currentRole === 'shipper' ? 'PO' : 'SO'
        },
        selectedRole() {
            return this.currentRole === 'shipper' ? 'Shipper' : 'Consignee'
        },
        orders() {
            return this.currentRole === 'shipper' ? 'sales orders' : 'purchase orders'
        }

    },
	methods:{
        submit() {
            this.$emit('submit')
        },
		close() {
			this.$emit('close')
		},
        cancelRole() {
            this.$emit('cancelRole')
        }
	}
}
</script>