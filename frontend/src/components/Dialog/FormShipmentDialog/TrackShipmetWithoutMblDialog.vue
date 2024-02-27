<!-- @format -->

<template>
	<v-dialog
		v-model="dialog"
		max-width="772px"
		content-class="track-shipment-without-mbl-dialog"
	>
		<v-card class="track-shipment-without-mbl-card">
			<v-card-title class="headline">
				<span>Track Shipment without MBL</span>
				<button icon dark class="btn-close" @click.stop="close">
					<v-icon>mdi-close</v-icon>
				</button>
			</v-card-title>

			<v-card-text>
				<v-row>
					<v-col cols="12" sm="12" md="12">
						<div class="track-shipment-without-mbl-information">
							<p class="content-details">
								Please provide the following information to manually track the
								shipment
							</p>
						</div>
					</v-col>
				</v-row>

				<div class="track-shipment-without-mbl-content">
					<v-row>
						<v-col cols="12" sm="6">
							<div class="contents from">
								<p class="content-title mb-0">From</p>
								<v-select
									v-model="from"
									:items="filteredTerminalRegions"
									ref="location"
									placeholder="Enter location"
									outlined
									class="text-fields"
									hide-details="auto"
									item-text="name"
									item-value="id"
								>
								</v-select>
							</div>
						</v-col>

						<v-col cols="12" sm="6">
							<div class="contents po-number">
								<p class="content-title mb-0">To</p>
								<v-select
									v-model="to"
									ref="location"
									:items="filteredTerminalRegions"
									placeholder="Enter location"
									outlined
									class="text-fields"
									hide-details="auto"
									item-text="name"
									item-value="id"
								>
								</v-select>
							</div>
						</v-col>
					</v-row>

					<v-row>
						<v-col cols="12" sm="6">
							<div class="contents from">
								<p class="content-title mb-0">
									ETD <span class="content-sub-title">(Required)</span>
								</p>
								<v-text-field
									v-model="etd"
									type="date"
									placeholder="Select date"
									outlined
									class="text-fields"
									hide-details="auto"
									:min="new Date().toISOString().substr(0, 10)"
									@change="setETADate(etd)"
								>
								</v-text-field>
							</div>
						</v-col>

						<v-col cols="12" sm="6">
							<div class="contents po-number">
								<p class="content-title mb-0">
									ETA <span class="content-sub-title">(Required)</span>
								</p>
								<v-text-field
									v-model="eta"
									type="date"
									placeholder="Select date"
									outlined
									class="text-fields"
									hide-details="auto"
									:min="etaDate"
									:disabled="etaDateDisabled"
								>
								</v-text-field>
							</div>
						</v-col>
					</v-row>

					<v-row>
						<v-col cols="12" sm="6">
							<div class="contents from">
								<p class="content-title mb-0">MODE</p>

								<div class="d-flex radio-group-wrapper radio-group-wrapper-web">
									<div
										v-bind:key="`mode-${key}`"
										v-for="(m, key) in modes"
										:class="
											`d-flex radio-item align-center ${
												m === mode ? 'selected' : ''
											}`
										"
									>
										<label
											style="position: relative;"
											class="radio-label-wrapper"
										>
											{{ m }}
											<input
												name="mode"
												:value="m"
												class="custom-radio"
												style="position: absolute; opacity: 0;"
												type="radio"
												v-model="mode"
											/>
											<span></span>
										</label>
										<kenetic-icon :marginLeft="8" :iconName="m.toLowerCase()" />
									</div>
								</div>
							</div>
						</v-col>

						<v-col cols="12" sm="6">
							<div class="contents po-number">
								<p class="content-title mb-0">TYPE</p>
								<div class="d-flex radio-group-wrapper radio-group-wrapper-web">
									<div
										v-bind:key="`mode-${key}`"
										v-for="(m, key) in types"
										:class="
											`d-flex radio-item align-center ${
												m === type ? 'selected' : ''
											}`
										"
									>
										<label
											style="position: relative;"
											class="radio-label-wrapper"
										>
											{{ m }}
											<input
												name="type"
												:value="m"
												class="custom-radio"
												style="position: absolute; opacity: 0;"
												type="radio"
												v-model="type"
											/>
											<span></span>
										</label>
										<kenetic-icon :marginLeft="8" :iconName="m.toLowerCase()" />
									</div>
								</div>
							</div>
						</v-col>
					</v-row>

					<v-row>
						<v-col cols="12" sm="12">
							<div class="contents from">
								<p class="content-title mb-0">Incoterms</p>
								<v-radio-group
									class="mt-0"
									v-model="incoTerm"
									row
									hide-details="auto"
									outlined
								>
									<v-radio
										class="incoterm-radio"
										v-for="n in incoTerms"
										:key="n"
										:label="n"
										:value="n"
									>
									</v-radio>
								</v-radio-group>
							</div>
						</v-col>
					</v-row>

					<v-row>
						<v-col cols="12" sm="6">
							<div class="contents from">
								<p class="content-title mb-0">Terminal</p>
								<v-select
									v-model="terminal"
									:items="filteredTerminals"
									ref="terminal"
									placeholder="Select Terminal"
									outlined
									class="text-fields"
									hide-details="auto"
									item-text="name"
									item-value="id"
								>
								</v-select>
							</div>
						</v-col>
						
						<v-col cols="12" sm="6">
							<div class="contents from">
								<p class="content-title mb-0">CARRIER</p>
								<v-select
									v-model="carrier"
									:items="filteredCarriers"
									ref="carrier"
									placeholder="Select Carrier"
									outlined
									class="text-fields"
									hide-details="auto"
									item-text="name"
									item-value="id"
								>
								</v-select>
							</div>
						</v-col>
					</v-row>

					<v-row>
						<v-col cols="12" sm="6">
							<div class="contents from">
								<p class="content-title mb-0">Vessel</p>
								<v-text-field
									v-model="vessel"
									placeholder="Enter vessel"
									outlined
									class="text-fields"
									hide-details="auto"
								>
								</v-text-field>
							</div>
						</v-col>

						<v-col cols="12" sm="6">
							<div class="contents po-number">
								<p class="content-title mb-0">Voyage</p>
								<v-text-field
									v-model="voyage"
									placeholder="Enter Voyage"
									outlined
									class="text-fields"
									hide-details="auto"
								>
								</v-text-field>
							</div>
						</v-col>
					</v-row>
				</div>
			</v-card-text>

			<v-card-actions>
				<v-btn class="btn-blue" text @click="Save" :disabled="buttonDisabled">
					<span>Save</span>
				</v-btn>
				<v-btn class="btn-white" @click.stop="close">
					<span>Skip This Step</span>
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import { mapGetters } from "vuex";
import KeneticIcon from "../../Icons/KeneticIcon";
export default {
	name: "TrackShipmetWithoutMblDialog",
	props: ["dialogData"],
	components: {
		KeneticIcon,
	},
	data: () => ({
		modes: ["Ocean", "Air"],
		incoTerms: ["FOB", "EXW", "CIF", "FCA", "DAP", "CNF", "DAT"],
		types: ["FCL", "LCL"],
		mode: "",
		type: "",
		incoTerm: "",
		from: "",
		to: "",
		eta: "",
		etd: "",
		terminal: "",
		carrier: "",
		vessel: "",
		voyage: "",
		etaDate: "",
		etaDateDisabled: true,
	}),
	computed: {
		...mapGetters(["getTerminalRegions", "getTerminals", "getCarriers"]),
		dialog: {
			get() {
				return this.dialogData;
			},
			set(value) {
				this.$emit("update:dialogData", value);
			},
		},
		filteredTerminalRegions() {
			return this.getTerminalRegions.map((terminal) => ({
				name: terminal.name,
				id: terminal.id,
			}));
		},
		filteredTerminals() {
			return this.getTerminals.map((terminal) => ({
				name: terminal.name,
				id: terminal.id,
			}));
		},
		filteredCarriers() {
			return this.getCarriers.map((carrier) => ({
				name: carrier.name,
				id: carrier.id,
			}));
		},
		buttonDisabled() {
			return this.from != "" && this.to != "" ? false : true;
		},
	},
	methods: {
		close() {
			this.$emit("closeDialog");
		},
		async Save() {
			let parms = {
				from: this.from,
				to: this.to,
				eta: this.eta,
				etd: this.etd,
				mode: this.mode,
				type: this.type,
				incoTerm: this.incoTerm,
				terminal: this.terminal,
				carrier: this.carrier,
				vessel: this.vessel,
				voyage: this.voyage,
			};
			this.$emit("trackShipment", parms);
			this.close();
		},
		setETADate(etaDate) {
			var today = new Date(etaDate);
			var dd = String(today.getDate() + 1).padStart(2, '0');
			var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
			var yyyy = today.getFullYear();
			this.etaDate = yyyy + '-' + mm + '-' + dd;
			this.etaDateDisabled = false;
		}
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/shipment/trackShipmentWithoutMbl.scss";
</style>
