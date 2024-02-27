<template>
<panel-item :field="field">
	<div slot="value">

		<div v-if="(containers.length==0 || containers=='')">
			<div>
				No Container data available yet to this Shipment.
			</div>
		</div>


		<div v-show="containers.length>0" v-for="(item, key) in containers" class="shipment-container-group">
			<div>
				<label style="font-weight: bold;">Container </label>
			</div>
			<div>
				<label>Container #</label>
				<span>{{ (item.container_num == null) ? "N/A" : item.container_num  }}</span>

			</div>

			<div>
				<label>Customs Hold</label>
				<span>{{ (item.customs_hold == null) ? "N/A" : item.customs_hold  }}</span>

			</div>

			<div>
				<label>Line Hold</label>
				<span>{{ (item.line_hold == null) ? "N/A" : item.line_hold  }}</span>

			</div>

			<div>
				<label>Firm's Code</label>
				<span>{{ (item.firms_code == null) ? "N/A" : item.firms_code  }}</span>

			</div>

			<div>
				<label>ETA</label>
				<span>{{ (item.eta == null) ? "N/A" : item.eta }}</span>
			</div>
			<div>
				<label>Return Text</label>
				<span>{{ (item.return_text == null) ? "N/A" : item.return_text  }}</span>

			</div>
			<div>
				<label>Discharged At</label>
				<span>{{ (item.discharged_at == null) ? "N/A" : item.discharged_at  }}</span>

			</div>
			<div>
				<label>Last Free Day</label>
				<span>{{ (item.last_free_day == null) ? "N/A" : item.last_free_day  }}</span>
			</div>

			<div>
				<label>Departed At</label>
				<span>{{ (item.departed_at == null) ? "N/A" : item.departed_at }}</span>
			</div>

			<div>
				<label>Returned At</label>
				<span>{{ (item.returned_at == null) ? "N/A" : item.returned_at }}</span>
			</div>

		</div>


	</div>
</panel-item>
</template>

<script>
import axios from 'axios'
export default {
	props: ['resource', 'resourceName', 'resourceId', 'field'],
	data() {
		return {
			containers: [],

		}
	},


	mounted() {
		var containers = JSON.parse(this.field.value)
		console.log(this.resourceId)
		containers.forEach(container => {
			axios
				.get("/custom-api/crux-container-detail/" + container.container_num)
				.then(response => {
					this.containers.push(response.data.crux_container)
				})
		});



	}
}
</script>
