<template>
<div>
	<div
	  class="relative"
	  dusk="action-events-index-component"
	  initialperpage="5"
    v-if="totalTruckers"
	>
		<!---->
		<!---->
		<h1 class="mb-3 text-90 font-normal text-2xl">Connected Truckers</h1>
		<div
		  class="flex"
		  style=""
		>
			<!---->
			<div class="w-full flex items-center">
				<!---->
				<!---->
			</div>
		</div>
		<div class="card">
			<div class="flex items-center">
				<div class="flex items-center">
					<!---->
				</div>
				<div class="flex items-center ml-auto px-3">
					<!---->
					<!---->
					<!---->
					<!---->
				</div>
			</div>
			<div class="relative">
				<!---->
				<div
				  class="overflow-hidden overflow-x-auto relative"
				  style=""
				>
					<table
					  cellpadding="0"
					  cellspacing="0"
					  data-testid="resource-table"
					  class="table w-full"
					>
						<thead>
							<tr>
								<!---->
								<th class="text-left"><span></span></th>
								<th class="text-left"><span>Name</span></th>
								<th class="text-left"><span>Email</span></th>

								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<tr dusk="43448-row" v-for="trucker,index in truckers"  :key="trucker.id">
								<!---->
								<td>
									{{ index + 1}}
								</td>
								<td>
									<div
									  class="text-left text-left"
									><span class="whitespace-no-wrap">{{ trucker.name }}</span></div>
								</td>
								<td>
									<div
									  class="text-left text-left"
									><span class="whitespace-no-wrap"> {{ trucker.email }}</span></div>
								</td>
							</tr>
					</tbody>
					</table>
				</div>
				<div
				  class="bg-20 rounded-b"
				  per-page="5"
				  resource-count-label="1-3 of 3"
				  current-resource-count="3"
				  all-matching-resource-count="3"
				>
					<nav class="flex justify-between items-center"><button
						  disabled="disabled"
						  rel="prev"
						  dusk="previous"
						  class="btn btn-link py-3 px-4 text-80 opacity-50"
						> </button> <span class="text-sm text-80 px-4 py-2"> {{ totalTruckers > 0 ? 1 : 0 }} - {{ totalTruckers }} of {{ totalTruckers }} </span> <button
						  disabled="disabled"
						  rel="next"
						  dusk="next"
						  class="btn btn-link py-3 px-4 text-80 opacity-50"
						>  </button></nav>
				</div>
			</div>
		</div>
	</div>
</div>
</template>

<script>
import axios from 'axios'
export default {
	props: ['resourceName', 'resourceId', 'panel'],
	mounted() {
    axios.get('/nova-vendor/connected-truckers?customer_id=' + this.resourceId)
         .then(res => {
             if(res.status == 200){
                this.truckers = res.data
             }
         })
	},
  data(){
    return {
      truckers: []
    };
  },
  computed: {
    totalTruckers(){
      return this.truckers.length
    }
  },
}
</script>
