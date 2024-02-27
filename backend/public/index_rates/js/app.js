function debounce(t,e,i){let a;return function(){let n=this,s=arguments,l=i&&!a;clearTimeout(a),a=setTimeout(function(){a=null,i||t.apply(n,s)},e),l&&t.apply(n,s)}}Vue.mixin({methods:{moneyFormat:function(t){return parseFloat(t).toLocaleString("en-US",{style:"currency",currency:"USD"})},isEmpty:function(t){let e=typeof t;if("undefined"===e)return!0;if("boolean"===e)return!t;if(null===t)return!0;if(void 0===t)return!0;if(t instanceof Array){if(t.length<1)return!0}else if("string"===e){if(t.length<1)return!0;if("0"===t)return!0}else if("object"===e){if(Object.keys(t).length<1)return!0}else if("number"===e&&0===t)return!0;return!1}}}),Vue.component("search-select",{template:'<div :class="\'search-select \'+classes" v-click-outside="t"> <div class="search__select bg-white" @click="active=active?false:true" :style="getStyle"><i v-show="value!=\'\'" @click="clear()" class="fas fa-close" style="z-index: 100;position: absolute;left: 10px;cursor:poiner"></i> {{ value != \'\' ? value : placeholder }} <i class="fas fa-chevron-down search__select__dp"></i> </div> <div class="search__results"> <div class="search__results__box" style="z-index:200" v-show="active"> <div class="search__results__input" style="position:relative;display:flex;align-items:center;" v-if="searchable"> <input type="text" placeholder="Search here..." v-model="keyword" @keydown="onKeyDown()" class="form-control"> <i class="ti-close" @click="keyword=\'\'" v-show="keyword!=\'\'" style="position: z-index: 100,position: absolute; right: 25px; font-size: 10px; cursor: pointer;"></i> </div> <div class="search__results__wrapper"> <ul> <li v-for="(item,index) in items2" :key="index"> <a href="#" @click.prevent="selectItem(item.text)">{{ item.text }}</a> </li> </ul> </div> </div> </div> </div>',props:["placeholder_value","default_value","classes_value","items_value","disabled_value","index","is_searchable"],data:function(){return{value:"",keyword:"",classes:"",placeholder:"",items:[],items2:[],active:!1,isdisabled:!1,searchable:!1}},watch:{keyword(t){""==t&&(this.items2=this.items)},items:function(t){this.items2=t},active:function(t){t||(this.items2=this.items,this.keyword=""),this.$emit("search_select_open",t)}},computed:{getDisabledCom:function(){return this.isdisabled?{opacity:".8","pointer-events":"none"}:{}},getStyle:function(){return""==this.value?{height:"34px",border:"1px solid #ccc"}:{height:"34px",border:"1px solid #ccc","padding-left":"30px"}}},methods:{t(){this.active=!1},clear:function(){this.value="",this.index||0==this.index?this.$emit("search_select",{text:"",index:this.index}):this.$emit("search_select","")},onKeyDown:debounce(function(){if(""==this.keyword)return;const t=new Fuse(this.items,{shouldSort:!0,includeMatches:!0,findAllMatches:!0,minMatchCharLength:1,location:0,threshold:.6,distance:100,useExtendedSearch:!0,keys:["text"]});this.items2=t.search(this.keyword).map(t=>({text:t.item.text}))},800),selectItem(t){this.keyword="",this.active=!1,this.value=t,this.index||0==this.index?this.$emit("search_select",{text:t,index:this.index}):this.$emit("search_select",t)}},mounted(){this.placeholder=this.placeholder_value?this.placeholder_value:"",this.value=this.isEmpty(this.default_value)?"":this.default_value,this.classes=this.classes_value?this.classes_value:"",this.items=this.items_value?this.items_value:"",this.isdisabled=!!this.disabled_value,this.searchable=!!this.is_searchable}}),Vue.directive("click-outside",{bind:(t,e,i)=>{t.clickOutsideEvent=(a=>{t==a.target||t.contains(a.target)||i.context[e.expression](a)}),document.body.addEventListener("click",t.clickOutsideEvent)},unbind:t=>{document.body.removeEventListener("click",t.clickOutsideEvent)}});let Indexes={template:'<div>\n\t<div class="with-line-grey mb16">\n\t\t<h3 class="font700 pt0 pb0 pl0 pr12">SHIFEX</h3>\n\t</div>\n\t<div :class="\'box transation mb16 p20\'+( item.active ? \' active\' : \'\')" v-for="(item,index) in boxes" @click="activateIndex(index)" :key="index">\n\t<div style="top:0px;left:0px;border-radius:6px;position: absolute; z-index: 11; background: rgba(255,255,255,.7); padding: 5px 12px; color: #242424; height: 100%; width: 100%; display: flex; align-items: center; justify-content: center;" v-show="isloading">Loading data...</div>\n\t\t<h4 class="h4">{{ item.title1 }}</h4>\n\t\t<h3 class="h3"><b>{{ item.title2 }}</b></h3>\n\t\t<div class="d-none align-items-center">\n\t\t\t<h4 class="mb00 d-flex mr12 float-start align-items-center font700">Avg {{ moneyFormat(item.avg) }}</h4>\n\t\t\t<div v-if="item.rate > 0" :class="( item.rate_type == 1 ? \'box-success\' : \'box-danger\' ) + \' pt3 pb3 pl3 pr12 d-flex float-end align-items-center\'">\n\t\t\t\t<i class="material-icons mr2">{{ item.rate_type == 1 ? \'arrow_drop_up\' : \'arrow_drop_down\' }}</i>{{ item.rate }}%\n\t\t\t</div>\n\t\t</div>\n\t</div>\n\t<p class="c-light font12 d-none">\n\t\t* Average figures are based on the data of last three months (Nov 2021 - Jan 2022)\n\t</p>\n\t</div>',data:function(){return{boxes:[],isloading:!0,filter:"",filters:[]}},watch:{boxes:function(){this.$emit("activate_index",this.boxes.filter(t=>t.active).map(t=>t.name))},filters:function(t){}},computed:{},methods:{activateIndex:function(t){this.boxes[this.boxes.findIndex(t=>t.active)].active=!1,this.boxes[t].active=!0,this.$emit("activate_index",this.boxes.filter(t=>t.active).map(t=>t.name))}},mounted:function(){},created:function(){}};new Vue({el:"#app",props:[],components:{Indexes:Indexes},data:{current_rate:"",all_time_avg:0,data_filter:[{active:!0,text:"All"},{active:!1,text:"18 Months"},{active:!1,text:"1 Year"},{active:!1,text:"6 Months"},{active:!1,text:"3 Months"}],pagination:{page:1,total:0},datacollection:{labels:[],datasets:[]},datacollection2:{labels:[],datasets:[]},chart:!1,chart2:!1,options:{responsive:!0,maintainAspectRatio:!1,interaction:{intersect:!1},elements:{point:{radius:0}},tooltips:{enabled:!1},hover:{mode:null},plugins:{legend:{display:!1},tooltip:{enabled:!1}},scales:{x:{grid:{display:!1},ticks:{font:{size:12},autoSkip:!1,maxRotation:90,minRotation:90,callback:function(t,e){return/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),""}}},y:{gridLines:{display:!1},grid:{drawBorder:!1,lineWidth:.5},ticks:{callback:function(t,e,i){return t.toLocaleString("en-US",{style:"currency",currency:"USD"}).replace(".00","")}}}}},options2:{responsive:!0,maintainAspectRatio:!1,interaction:{intersect:!1},elements:{point:{radius:0}},tooltips:{enabled:!1},hover:{mode:null},plugins:{legend:{display:!1},tooltip:{enabled:!1}},scales:{x:{gridLines:{display:!1,drawOnChartArea:!1},grid:{display:!1,drawOnChartArea:!1,borderWidth:0,lineWidth:0,tickWidth:0,drawBorder:!1},ticks:{font:{size:12},autoSkip:!1,maxRotation:90,minRotation:90,callback:function(t,e){return console.log(window.graph_labels),/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)?e%2==0&&e==window.graph_labels.length-2?moment(window.graph_labels[window.graph_labels.length-1]).format("MM-DD-YY"):e%2==0?moment(this.getLabelForValue(t)).format("MM-DD-YY"):"":moment(this.getLabelForValue(t)).format("MM-DD-YYYY")}}},y:{gridLines:{display:!1,drawOnChartArea:!1},grid:{display:!1,drawOnChartArea:!1,borderWidth:0,lineWidth:0,tickWidth:0,drawBorder:!1},ticks:{color:"transparent",callback:function(t,e,i){return t.toLocaleString("en-US",{style:"currency",currency:"USD"}).replace(".00","")}}}}},chartisloading:!0,container_size_active:!1,selected_container:"40'HQ",containers:[],location:"Shifl China-NY/NJ|Shifl China-Los Angeles",active_indexes:!1},watch:{all_time_avg:function(t){this.$refs.indexes.$data.all_time_avg=t}},computed:{active_data_filter:function(){return this.data_filter.find(t=>t.active)}},methods:{t:function(){this.container_size_active=!1},activateIndex:function(t){this.active_indexes=t,this.fetch()},dataFilter:function(t){let e=this;this.data_filter[this.data_filter.findIndex(t=>t.active)].active=!1,this.data_filter[t].active=!0,this.getIndexes(this.active_indexes[0]).then(()=>{e.fetch()})},chartAddData:function(t,e){this.chart.data.labels=t,this.chart.data.datasets=e,this.chart.update()},chartAddData2:function(t,e){this.chart2.data.labels=t,this.chart2.data.datasets=e,this.chart2.update()},chartRemoveData:function(t){this.chart.data.labels.pop(),this.chart.data.datasets.forEach(t=>{t.data.pop()}),this.chart.update()},fetch:function(){let t=this;this.chartisloading=!0,axios.get(window.api_url+"sell-buy-rates-index/data/get/graph/terminal?staging=1&terminals="+(this.active_indexes?this.active_indexes.join("-"):"all")+"&location="+this.location+"&container="+this.selected_container+"&filter="+("undefined"!=this.active_data_filter?this.active_data_filter.text:"All")).then(e=>{if(e.data.success){e.data.labels;let i=e.data.labels.map(t=>{let e=t.split("-");return e.pop(),e.join("-")});i=i.filter((t,e)=>i.indexOf(t)==e).map(t=>t+"-01"),window.graph_labels=i;let a=[],n=[];e.data.data.forEach(t=>{let e=Object.values(t.data.data);a.push({label:t.name,data:e,borderColor:t.color,cubicInterpolationMode:"monotone",tension:.4});let s=[];Object.keys(t.data.data).forEach(e=>{s.push({date:e,value:t.data.data[e]})}),n.push({label:t.name,data:s.map(t=>{if(-1!=i.indexOf(t.date))return t.value}),borderColor:"transparent",cubicInterpolationMode:"monotone",tension:.4})}),t.current_rate=a[0].data[a[0].data.length-1],t.all_time_avg=this.$refs.indexes.$data.boxes.find(t=>t.active).avg,t.chartAddData(e.data.labels,a),t.chartAddData2(i,n)}t.chartisloading=!1}).catch(e=>{window.debug&&console.log(e),t.chartisloading=!1})},getContainers:function(){let t=this;axios.get(window.api_url+"sell-buy-rates-index/data/get/container/all").then(e=>{t.containers=e.data}).catch(t=>{window.debug&&console.log(t)})},containerFilter:function(t){let e=this;this.selected_container=t.name,this.getIndexes(this.active_indexes[0]).then(()=>{e.fetch()})},getIndexes:async function(t){let e=this;return this.$refs.indexes.$data.isloading=!0,this.$refs.indexes.$data.filter=this.data_filter.find(t=>t.active).text,await axios.get(window.api_url+"sell-buy-rates-index/data/get/indexes/all?location="+this.location+"&container="+this.selected_container+"&filter="+("undefined"!=this.active_data_filter?this.active_data_filter.text:"All")).then(i=>{i.data.success&&(e.$refs.indexes.$data.boxes=i.data.data.map((i,a)=>{let n=!1;return(!t&&0==a||t&&t==i.name)&&(n=!0),{name:i.name,title1:"Price/"+e.selected_container,title2:"China base port to "+("NY/NJ"!=i.name?i.name.split(" ").map(function(t){return _.capitalize(t)}).join(" "):i.name),avg:i.data.average,rate:i.data.percentage,rate_type:parseFloat(i.data.percentage)<0?0:1,active:n}})),e.$refs.indexes.$data.isloading=!1}).catch(t=>{window.debug&&console.log(t),e.$refs.indexes.$data.isloading=!1})}},mounted:function(){let t=this;this.$refs.indexes.$data.filters=this.data_filter.map(t=>t.text),/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)&&(document.querySelector("#chart-wrapper").style.height="300px",this.options.scales.x.ticks.font.size=7);let e=document.getElementById("myChart").getContext("2d"),i=document.getElementById("myChart2").getContext("2d");this.chart=new Chart(e,{type:"line",data:this.datacollection,options:this.options}),this.chart2=new Chart(i,{type:"line",data:this.datacollection2,options:this.options2}),this.getContainers(),this.getIndexes().then(()=>{t.fetch()}),document.querySelector("#loader").remove(),document.querySelector("body").style.overflowY="auto"},created:function(){}});