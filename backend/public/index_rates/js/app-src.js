Vue.mixin({
	methods: {
		moneyFormat : function(m){
			return parseFloat(m).toLocaleString('en-US', {
			  style: 'currency',
			  currency: 'USD',
			});
		},
		isEmpty: function(v) {
            let type = typeof v;
            if (type === 'undefined') {
                return true;
            }
            if (type === 'boolean') {
                return !v;
            }
            if (v === null) {
                return true;
            }
            if (v === undefined) {
                return true;
            }
            if (v instanceof Array) {
                if (v.length < 1) {
                    return true;
                }
            } else if (type === 'string') {
                if (v.length < 1) {
                    return true;
                }
                if (v === '0') {
                    return true;
                }
            } else if (type === 'object') {
                if (Object.keys(v).length < 1) {
                    return true;
                }
            } else if (type === 'number') {
                if (v === 0) {
                    return true;
                }
            }
            return false;
        }
	}
});

function debounce(func, wait, immediate) {

	let timeout;

	return function() {
		let context = this, args = arguments;
		let later = function() {
			timeout = null;
			if (!immediate) func.apply(context, args);
		};

		let callNow = immediate && !timeout;

		clearTimeout(timeout);	

		timeout = setTimeout(later, wait);
		
		if (callNow) func.apply(context, args);
	}
}

Vue.component('search-select',{
	template: `<div :class="'search-select '+classes" v-click-outside="t"> <div class="search__select bg-white" @click="active=active?false:true" :style="getStyle"><i v-show="value!=''" @click="clear()" class="fas fa-close" style="z-index: 100;position: absolute;left: 10px;cursor:poiner"></i> {{ value != '' ? value : placeholder }} <i class="fas fa-chevron-down search__select__dp"></i> </div> <div class="search__results"> <div class="search__results__box" style="z-index:200" v-show="active"> <div class="search__results__input" style="position:relative;display:flex;align-items:center;" v-if="searchable"> <input type="text" placeholder="Search here..." v-model="keyword" @keydown="onKeyDown()" class="form-control"> <i class="ti-close" @click="keyword=''" v-show="keyword!=''" style="position: z-index: 100,position: absolute; right: 25px; font-size: 10px; cursor: pointer;"></i> </div> <div class="search__results__wrapper"> <ul> <li v-for="(item,index) in items2" :key="index"> <a href="#" @click.prevent="selectItem(item.text)">{{ item.text }}</a> </li> </ul> </div> </div> </div> </div>`,
	props : ['placeholder_value','default_value','classes_value', 'items_value', 'disabled_value','index','is_searchable'],
	data: function(){
		return {
		    value: '',
			keyword : '',
			classes: '',
			placeholder: '',
			items : [],
			items2: [],
			active : false,
			isdisabled: false,
			searchable: false
		}
	},
	watch: {
		keyword(v){
			if( v == '' ){
				this.items2 = this.items;
			}
		},
		items: function(v){
			this.items2 = v;
		},
		active: function(v){
		    if( !v ){
		        this.items2 = this.items;
		        this.keyword = '';
		    }
		    
		    this.$emit('search_select_open',v);
		}
	},
	computed: {
	   getDisabledCom: function(){
	       return this.isdisabled ? { 'opacity': '.8', 'pointer-events': 'none' } : {};
	   },
	   getStyle: function(){
	       return this.value == '' ? { 'height':'34px','border':'1px solid #ccc' } : { 'height':'34px','border':'1px solid #ccc','padding-left':'30px' };
	   }
	},
	methods : {
		t(){
			this.active = false;
		},
		clear: function(){
		  this.value = '';
		  
    		if( this.index || this.index == 0 ){
    		    this.$emit('search_select',{ text: '', index: this.index });
    		}else{
    		    this.$emit('search_select','');
    		}
		},
		onKeyDown : debounce(function(){
			if( this.keyword == '' ) return;

			const options = {
			  shouldSort: true,
			  includeMatches: true,
			  findAllMatches: true,
			  minMatchCharLength: 1,
			  location: 0,
			  threshold: 0.6,
			  distance: 100,
			  useExtendedSearch: true,
			  keys: [
			    "text"
			  ]
			};

			const fuse = new Fuse(this.items, options);

			this.items2 = fuse.search(this.keyword).map( i =>{
				return { text: i.item.text };
			});
		},800),
		selectItem(text){
			this.keyword = '';
			this.active = false;
			this.value = text;
			
			if( this.index || this.index == 0 ){
			    this.$emit('search_select',{ text: text, index: this.index });
			}else{
			    this.$emit('search_select',text);
			}
			
		}
	},
	mounted(){
		this.placeholder = this.placeholder_value ? this.placeholder_value : '';
		this.value = this.isEmpty(this.default_value) ? '' : this.default_value;
		this.classes = this.classes_value ? this.classes_value : '';
        this.items = this.items_value ? this.items_value : '';
        this.isdisabled = this.disabled_value ? true : false;
        this.searchable = this.is_searchable ? true : false;
	}
});

Vue.directive('click-outside', {
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
});


let Indexes = {
	template: `<div>
	<div class="with-line-grey mb16">
		<h3 class="font700 pt0 pb0 pl0 pr12">SHIFEX</h3>
	</div>
	<div :class="'box transation mb16 p20'+( item.active ? ' active' : '')" v-for="(item,index) in boxes" @click="activateIndex(index)" :key="index">
	<div style="top:0px;left:0px;border-radius:6px;position: absolute; z-index: 11; background: rgba(255,255,255,.7); padding: 5px 12px; color: #242424; height: 100%; width: 100%; display: flex; align-items: center; justify-content: center;" v-show="isloading">Loading data...</div>
		<h4 class="h4">{{ item.title1 }}</h4>
		<h3 class="h3"><b>{{ item.title2 }}</b></h3>
		<div class="d-none align-items-center">
			<h4 class="mb00 d-flex mr12 float-start align-items-center font700">Avg {{ moneyFormat(item.avg) }}</h4>
			<div v-if="item.rate > 0" :class="( item.rate_type == 1 ? 'box-success' : 'box-danger' ) + ' pt3 pb3 pl3 pr12 d-flex float-end align-items-center'">
				<i class="material-icons mr2">{{ item.rate_type == 1 ? 'arrow_drop_up' : 'arrow_drop_down' }}</i>{{ item.rate }}%
			</div>
		</div>
	</div>
	<p class="c-light font12 d-none">
		* Average figures are based on the data of last three months (Nov 2021 - Jan 2022)
	</p>
	</div>`,
	data : function(){
		return {
			boxes : [],
			isloading : true,
			filter: '',
			filters: []
		}
	},
	watch: {
		boxes: function(){
			this.$emit('activate_index',this.boxes.filter( i => i.active ).map( i => i.name ));
		},
		filters: function(v){
			
		}
	},
	computed: {},
	methods: {
		activateIndex: function(index){
			this.boxes[this.boxes.findIndex( i =>{
				return i.active;
			})].active = false;
			this.boxes[index].active = true;			

			this.$emit('activate_index',this.boxes.filter( i => i.active ).map( i => i.name ));
		}
	},
	mounted: function(){
	},
	created: function(){}
}


// <line-chart :chart-data="datacollection" :options="options"></line-chart>
new Vue({
	el : '#app',
	props: [],
	components: { Indexes : Indexes},
	data: {
		current_rate: '',
		all_time_avg: 0,
		data_filter: [
			{ active: true, text: 'All' },
			{ active: false, text: '18 Months' },
			{ active: false, text: '1 Year' },
			{ active: false, text: '6 Months' },
			{ active: false, text: '3 Months' }
		],
		pagination: {
			page: 1,
			total: 0
		},
		datacollection: { 
			labels: [],
			datasets: [] 
		},
		datacollection2: { 
			labels: [],
			datasets: [] 
		},
		chart: false,
		chart2: false,
		options: {
			responsive: true,
			maintainAspectRatio: false,
			interaction: {
		      intersect: false,
		    },
		    elements: {
                point:{
                    radius: 0
                }
            },
            tooltips: {enabled: false},
            hover: {mode: null},
			plugins: { 
				legend: {
			        display: false
			    },
			    tooltip: {
			    	enabled: false
			    }
			},
			scales: {
		        x: {
		            grid: {
		                display: false
		            },
		            ticks: {
		            	font: {
			        		size: 12
			        	},
	                    autoSkip: false,
	                    maxRotation: 90,
	                    minRotation: 90,
	        			callback: function(val, index) {
							// Hide every 2nd tick label
							if( (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i).test(navigator.userAgent) ) {

								// return index % 2 === 0 ? moment( this.getLabelForValue(val) ).format('MM-DD-YY') : '';
								return '';
							}else{
								// return moment( this.getLabelForValue(val) ).format('MM-DD-YYYY');
								return '';
							}
					    },
	                }
		        },
		        y: {
		        	gridLines: {
	                  display: false
					},
					grid: {
	                    drawBorder: false, // <-- this removes y-axis line
	                    lineWidth: 0.5,
	                },
					ticks: {
						// min: 0,
						// max: 10000,
					 	// suggestedMin: 0.5,
					    // suggestedMax: 5.5,
						callback: function(value, index, ticks) {
							return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' }).replace('.00','');
						}
					}
		        }
		    }
		},
		options2: {
			responsive: true,
			maintainAspectRatio: false,
			interaction: {
		      intersect: false,
		    },
		    elements: {
                point:{
                    radius: 0
                }
            },
            tooltips: {enabled: false},
            hover: {mode: null},
			plugins: { 
				legend: {
			        display: false
			    },
			    tooltip: {
			    	enabled: false
			    }
			},
			scales: {
		        x: {
		        	gridLines: {
	                 	display: false,
	                 	drawOnChartArea: false
					},
		            grid: {
		                display: false,
		                drawOnChartArea: false,
		                borderWidth: 0,
		                lineWidth: 0,
		                tickWidth: 0,
		                drawBorder: false
		            },
		            ticks: {
		            	font: {
			        		size: 12
			        	},
	                    autoSkip: false,
	                    maxRotation: 90,
	                    minRotation: 90,
	        			callback: function(val, index) {
	        				console.log(window.graph_labels);
							// Hide every 2nd tick label
							if( (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i).test(navigator.userAgent) ) {

								if( index % 2 === 0 && index == ( window.graph_labels.length - 2 ) ){
									return moment( window.graph_labels[window.graph_labels.length-1] ).format('MM-DD-YY');
								}else{
									return index % 2 === 0 ? moment( this.getLabelForValue(val) ).format('MM-DD-YY') : '';
								}
							}else{
								return moment( this.getLabelForValue(val) ).format('MM-DD-YYYY');
								
							}
					    },
	                }
		        },
		        y: {
		        	gridLines: {
	                 	display: false,
	                 	drawOnChartArea: false
					},
		            grid: {
		                display: false,
		                drawOnChartArea: false,
		                borderWidth: 0,
		                lineWidth: 0,
		                tickWidth: 0,
		                drawBorder: false,
		            },
					ticks: {
						color: 'transparent',
						// min: 0,
						// max: 10000,
					 	// suggestedMin: 0.5,
					    // suggestedMax: 5.5,
						callback: function(value, index, ticks) {
							return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' }).replace('.00','');
						}
					}
		        }
		    }
		},
		chartisloading: true,
		container_size_active: false,
		selected_container: "40'HQ",
		containers: [],
		location: 'Shifl China-NY/NJ|Shifl China-Los Angeles',
		active_indexes: false
	},
	watch: {
		all_time_avg: function(v){
			this.$refs.indexes.$data.all_time_avg = v;
		}
	},
	computed: {
		active_data_filter: function(){
			return this.data_filter.find( i => i.active );
		}
	},
	methods: {
		t: function(){
			this.container_size_active = false;
		},
		activateIndex: function(data){
			this.active_indexes = data;

			this.fetch();
		},
		dataFilter: function(i){

			let _this = this;

			this.data_filter[this.data_filter.findIndex( i => i.active )].active = false;
			this.data_filter[i].active = true;

			this.getIndexes(this.active_indexes[0]).then(()=>{
				_this.fetch();
			});
		},
		chartAddData: function(labels,data) {
		    this.chart.data.labels = labels;
		    this.chart.data.datasets = data;
		    this.chart.update();
		},
		chartAddData2: function(labels,data) {
		    this.chart2.data.labels = labels;
		    this.chart2.data.datasets = data;
		    this.chart2.update();
		},
		chartRemoveData: function(chart) {
		    this.chart.data.labels.pop();
		    this.chart.data.datasets.forEach((dataset) => {
		        dataset.data.pop();
		    });
		    this.chart.update();
		},
		fetch: function(){
			let _this = this;

			this.chartisloading = true;

			axios.get(window.api_url+'sell-buy-rates-index/data/get/graph/terminal?staging=1&terminals='+( this.active_indexes ? this.active_indexes.join('-') : 'all')+'&location='+this.location+'&container='+this.selected_container+'&filter='+( this.active_data_filter != 'undefined' ? this.active_data_filter.text : 'All' ))
			.then(res=>{

				if( res.data.success ){

					let labels = res.data.labels;

					let labels2 = res.data.labels.map( i =>{
						let q = i.split('-');
						q.pop();
						return q.join('-');
					});

					labels2 = labels2.filter( (i,e) => labels2.indexOf(i) == e ).map( i => i+'-01' );

					window.graph_labels = labels2;
					
					let gdata = [];
					let gdata2 = [];
					
					res.data.data.forEach(item=>{
						let ggdata = Object.values(item.data.data);

						gdata.push({ 
							label: item.name,
							data: ggdata,
							borderColor: item.color, 
							cubicInterpolationMode: 'monotone',
      						tension: 0.4
						});

						let dd = [];

						Object.keys(item.data.data).forEach( i =>{
							dd.push({ date: i, value: item.data.data[i] });
						});
						
						gdata2.push({ 
							label: item.name,
							data: dd.map( i =>{
								if( labels2.indexOf(i.date) != -1 ){
									return i.value;
								}
							}),
							borderColor: 'transparent', 
							cubicInterpolationMode: 'monotone',
      						tension: 0.4
						});
					});

					_this.current_rate = gdata[0].data[gdata[0].data.length - 1];
				
					_this.all_time_avg = this.$refs.indexes.$data.boxes.find( i => i.active ).avg;

					_this.chartAddData(res.data.labels,gdata);

					_this.chartAddData2(labels2,gdata2);
				}

				_this.chartisloading = false;
				
			}).catch(err=>{
				if(window.debug) console.log(err);

				_this.chartisloading = false;
			});
		},
		getContainers: function(){
			let _this = this;

			axios.get(window.api_url+'sell-buy-rates-index/data/get/container/all').then(res=>{
				_this.containers = res.data;
			}).catch(err=>{
				if(window.debug) console.log(err);
			});
		},
		containerFilter: function(item){
			let _this = this;

			this.selected_container = item.name;
			this.getIndexes(this.active_indexes[0]).then(()=>{
				_this.fetch();
			});
		},
		getIndexes : async function(t){
			let _this = this;

			this.$refs['indexes'].$data.isloading = true;

			this.$refs.indexes.$data.filter = this.data_filter.find( i => i.active ).text;

			return await axios.get(window.api_url+'sell-buy-rates-index/data/get/indexes/all?location='+this.location+'&container='+this.selected_container+'&filter='+( this.active_data_filter != 'undefined' ? this.active_data_filter.text : 'All' ))
			.then(res=>{

				if( res.data.success ){

					_this.$refs['indexes'].$data.boxes = res.data.data.map((item,index)=>{
						let active = false;

						if( ( !t && index == 0 ) || ( t && t == item.name ) ){
							active = true;
						}

						return {
							name: item.name,
							title1: 'Price/'+_this.selected_container,
							title2: 'China base port to '+( item.name != 'NY/NJ' ? item.name.split(' ').map( function(i){ return _.capitalize(i) }).join(' ') : item.name ),
							avg: item.data.average,
							rate: item.data.percentage,
							rate_type: parseFloat(item.data.percentage) < 0 ? 0 : 1,
							active: active
						}
					});
				}

				_this.$refs['indexes'].$data.isloading = false;

				return;
				
			}).catch(err=>{
				if(window.debug) console.log(err);

				_this.$refs['indexes'].$data.isloading = false;

				return;
			});
		}
	},
	mounted: function(){

		let _this = this;

		this.$refs.indexes.$data.filters = this.data_filter.map( i => i.text );

		if( (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i).test(navigator.userAgent) ) {
			document.querySelector('#chart-wrapper').style.height = '300px';
			this.options.scales.x.ticks.font.size = 7;
		}

		let ctx = document.getElementById('myChart').getContext('2d');
		let ctx2 = document.getElementById('myChart2').getContext('2d');

		this.chart = new Chart(ctx, {
			type: 'line',
			data: this.datacollection,
			options: this.options
		});

		this.chart2 = new Chart(ctx2, {
			type: 'line',
			data: this.datacollection2,
			options: this.options2
		});

		this.getContainers();
		this.getIndexes().then(()=>{
			_this.fetch();
		});

		document.querySelector('#loader').remove();
		document.querySelector('body').style.overflowY = 'auto';
	},
	created: function(){}
})