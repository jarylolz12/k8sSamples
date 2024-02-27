<template>
	<v-card width="400" height="300" class="mx-auto mt-16 py-10">
		<v-card-title class="justify-center">Logging in...</v-card-title>
		<v-card-text class="text-center" v-if="creatingToken">
			<v-progress-circular :size="70" :width="7" color="green" indeterminate></v-progress-circular>
		</v-card-text>
	</v-card>
</template>

<script>
	import { mapActions, mapState } from 'vuex';

	export default {
		computed: {
			...mapState('user', ['authToken']),
			...mapState('accounting', ['accountingLastPath'])
		},
		created() {
			const { code, realmId } = this.$route.query;
			const accessToken = this.authToken;
			this.setQuickbooksAccessToken({ code, realmId, accessToken })
				.then(() => {
					this.$router.push(this.accountingLastPath + '?from=redirect');
				})
				.catch(() => {
					// Redirect even if error to replay the process.
					this.$router.push(this.accountingLastPath + '?from=redirect');
				});
		},
		data() {
			return {
				response: {},
				creatingToken: true
			};
		},
		methods: {
			...mapActions('accounting', ['setQuickbooksAccessToken'])
		}
	};
</script>

<style lang="scss"></style>
