<!-- @format -->

<template>
	<v-row>
		<v-col>
			<!-- <v-menu
                ref="menu"
                v-model="menuData"
                :close-on-content-click="false"
                :return-value.sync="date"
                transition="scale-transition"
                offset-y
                min-width="auto">

                <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="dateData"
                        prepend-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        style="margin-top: 0;"
                        class="date-picker-wrapper text-fields">
                    </v-text-field>
                </template>

                <v-date-picker
                    v-model="dateData"
                    no-title
                    scrollable>

                    <v-spacer></v-spacer>

                    <v-btn text color="primary" @click="this.setMenu(false)">
                        Cancel
                    </v-btn>

                    <v-btn text color="primary" @click="$refs.menu.save(date)">
                        OK
                    </v-btn>
                </v-date-picker>
            </v-menu> -->

			<!-- <v-menu
                v-model="menuData"
                :close-on-content-click="false"
                :return-value.sync="dateData"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                min-width="auto">

                <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        height="40px"
                        class="text-fields"
                        v-model="dateData"
                        prepend-icon="mdi-calendar"
                        placeholder="2021-05-21"
                        readonly
                        v-bind="attrs"
                        outlined
                        v-on="on"></v-text-field>
                </template>

                <v-date-picker v-model="dateData" scrollable>
                    <v-spacer></v-spacer>

                    <v-btn text class="white-two-button" @click="menuData = false">
                        Cancel
                    </v-btn>

                    <v-btn text class="blue-button" @click="$refs.menu.save(date)">
                        OK
                    </v-btn>
                </v-date-picker>
            </v-menu> -->

			<v-menu
				ref="menuData"
				v-model="menuData"
				:close-on-content-click="false"
				:return-value.sync="dateData"
				transition="scale-transition"
				offset-y
				min-width="auto"
			>
				<template v-slot:activator="{ on, attrs }">
					<v-text-field
						height="40px"
						class="text-fields"
						:class="className"
						v-model="formattedDate"
						append-icon="mdi-calendar"
						:placeholder="placeholder"
						readonly
						v-bind="attrs"
						outlined
						v-on="on"
						:disabled="disabledDate"
					>
					</v-text-field>
				</template>

				<!-- <v-date-picker v-model="dateData" no-title scrollable>
                    <v-spacer></v-spacer>

                    <v-btn text class="white-two-button" @click="menuData = false">
                        Cancel
                    </v-btn>

                    <v-btn text class="blue-button" @click="$refs.menuData.save(dateData)">
                        OK
                    </v-btn>
                </v-date-picker> -->

				<v-date-picker
					v-model="dateData"
					no-title
					@input="$refs.menuData.save(dateData)"
				>
				</v-date-picker>
			</v-menu>
		</v-col>
	</v-row>
</template>

<script>
import moment from "moment";

export default {
	name: "DatePicker",
	props: [
		"menu",
		"date",
		"setMenu",
		"placeholder",
		"className",
		"dateFormat",
		"disabledDate",
	],
	data() {
		return {
			dateData: this.date,
			menuData: this.menu,
		};
	},
	// data: () => ({

	// }),
	computed: {
		formattedDate() {
			this.$emit("update:date", this.dateData);

			return this.dateFormat
				? this.dateData
					? moment(this.dateData).format("MMMM Do")
					: ""
				: this.formatDate(this.dateData);
		},
		// dateData: {
		//     get() {
		//         return this.date
		//     },
		//     set(value) {
		//         this.$emit('update:date', value)
		//     }
		// },
		// menuData: {
		//     get() {
		//         return this.menu
		//     },
		//     set(value) {
		//         this.$emit('update:menu', value)
		//     }
		// }
	},
	methods: {
		formatDate(date) {
			if (!date) return null;

			const [year, month, day] = date.split("-");
			return `${month}/${day}/${year}`;
		},
	},
};
</script>

<style>
.date-picker-wrapper {
	margin-top: 0px;
	border: 1px solid #b3cfe0 !important;
	border-radius: 4px;
	height: 48px;
	padding: 5px 10px 0 !important;
	margin-bottom: 10px;
}

.date-picker-wrapper i {
	color: #212121 !important;
	padding-top: 5px;
}
</style>
