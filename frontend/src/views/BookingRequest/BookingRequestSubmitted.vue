<!-- @format -->

<template>
	<div class="booking-request-submitted-wrapper">
		<div class="request-submitted-card">
			<div class="shifl-logo">
				<img src="@/assets/images/logo.svg" alt="" class="logo" />
			</div>
			<div>
				<img
					src="@/assets/icons/CircleCheckOutline.svg"
					width="48px"
					height="48px"
					alt=""
				/>
				<div class="pa-6">
					<h2 class="mb-2">
						Booking Request Submitted
					</h2>
					<p class="mb-4">
						You have successfully submitted your booking request.
					</p>
					<p>
						Our team will review your request and get back with quotation via
						email.
					</p>
				</div>
				<p class="mb-0">
					Want to join <strong>Shifl’s</strong> supply chain platform?
					<span class="sign-up">
						<a class="text-decoration-none" href="https://shifl.com/register"
							><strong> Sign Up </strong></a
						></span
					>
				</p>
			</div>
		</div>
	</div>
</template>

<style lang="scss">
.booking-request-submitted-wrapper {
	background: #f5f9fc;
	text-align: center;
	width: 100%;
	height: 100%;
	display: flex;
	align-items: center;
	justify-content: center;
	.request-submitted-card {
		width: 380px;
		background: #ffffff;
		.shifl-logo {
			margin-bottom: 25px;
		}
	}
}
</style>
