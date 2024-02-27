   <template>
        <div v-if="defaultmodal" class="modaloverlay">
            <div style="width:560px" class="modale opened" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-header" style="">
                        <div style="display:flex; justify-content: space-between; align-items: center; margin-top:13px;" class="mb-2">
                            <div style="display:flex; justify-content: flex-start; align-items: center;">
                                <h2 class="modalheader_text" v-html="getreference()"></h2>
                                <span class="detailsstatus common font-medium text-capitalize">
                                    {{ touppercase(items.statusrow)}}
                                </span>
                            </div>
                            <div style="display:flex; align-items: center;">
                                <span  @click="closemodal" class="btn-close closemodale" aria-hidden="true">
                                    <img src="@/assets/icons/Close.svg">
                                </span>
                            </div>
                        </div>
                        <div class="DateDetails">
                            <span>
                                <span class="updatedat font-medium">Updated At:</span>                                    
                                <span class="time">{{ datenow(items.updated_at)}}</span>
                            </span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="topdetails" style="background:#F5F9FC; height: 170px">
                            <ul class="detailsfreight">
                                <li><span class="detailsicon">
                                    <img src="@/assets/icons/Circle Check Filled.svg" width="19px" height="19px"></span> 
                                    <span>Discharge</span>
                                </li>
                                <li>
                                    <span class="detailsicon">
                                        <img src="@/assets/icons/Alert (1).svg" width="19px" height="19px"></span> 
                                        <span>Discharge</span>
                                    <div class="holderwrapdetails">
                                        <span>Freight Hold</span>
                                        <span>TMF Hold</span>
                                        <span>Line Hold</span>
                                    </div>
                                </li>
                                <li>
                                    <span class="detailsicon">
                                    <img src="@/assets/icons/Circle Check Outline.svg" width="19px" height="19px"></span> 
                                    <span>Port Fees</span>
                                </li>
                           </ul>                            
                        </div>
                        <div class="DETAILS_WRAPPER">
                            <table class="DETAILS_TABLE">
                                <tbody>
                                    <tr 
                                        class="rowclass"	                              		
                                        v-for="item in detailsInformation"
                                        :key="item.id"
                                    >                                   
                                       <td>{{item.name}}</td><td>{{item.TEXT}}</td>                                                                          
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>  
    import moment from "moment";
    export default {
        name: "Modaldetails",
        props: ['items','defaultmodal'],
        components: {			
        },
        async mounted() {       
        },
        watch:{   
            defaultmodal(c){
                if(c){
                    let per_diem_date=(this.items.per_diem_date !=null)?moment(this.items.per_diem_date).format('MMM DD, Y'):'--';
                    let last_free_date=(this.items.per_diem_date !=null)?moment(this.items.last_free_date).format('MMM DD, Y'):'--';
                    let elem = [{ id:1,name:'ETA',TEXT:moment(this.items.eta).format('MMM DD, Y')},
                                { id:2,name:'PDD',TEXT:per_diem_date},
                                { id:3,name:'LFD',TEXT:last_free_date},
                                { id:4,name:'MBL#',TEXT:this.items.mbl_num},
                                { id:5,name:'SIZE',TEXT:this.items.container_size.name},
                                { id:6,name:'CONTAINER ID',TEXT:this.items.trucker_container},
                                { id:7,name:'LOCATION AT',TEXT:this.items.terminal.name},
                                { id:8,name:'CURRENT POSITION',TEXT:this.items.mbl_num}]
                    this.detailsInformation = (elem) 
                    //console.log(this.detailsInformation)
                }
            }
        },
        data(){
            return {	
            reference: '704475',
            status:'Available',
            UPDATE_AT_TIME: '09:34 PM, Jun 10, 2020',
            detailsInformation:[]
        }},
        methods:{
            datenow(c){
            return moment(c).format('h:mm a, MMM DD, Y');
            },
            touppercase(item){
                // const toCustomerCase = (ct) => {
                //     let lowercase = ct.toLowerCase().split(" ").map((word) => word.charAt(0).toUpperCase() + word.slice(1)).join(" ");
                //     return lowercase.replace("_", " ");
                // };
                // return toCustomerCase(item)

                if (item !== null) {
                    return item.replace(/_+/g, ' ');
                }
            },
            getreference(){
                return 'Drayage Ref#'+ this.items.shifl_ref ;
            },
            closemodal(){
                this.$emit("update:defaultmodal",false)
                this.$emit("datastatus",false);        
            },
            check(){
                console.log(this.items)
            }
        }
    }
</script>
<!--
    export default {
    name: "Modaldetails",
	props: ['items','defaultmodal'],
	components: {			
	},
    async mounted() {         
	},
    watch:{      
    },
	data: () => ({	
        reference: '704475',
        status:'Available',
        UPDATE_AT_TIME: '09:34 PM, Jun 10, 2020',
        detailsInformation: [{ id:1, name: 'ETA', TEXT:'Jul 11, 2023'},
                             { id:2,name: 'PDD', TEXT:'Jul 11, 2023'},
                             { id:3,name: 'LFD', TEXT:'Jul 11, 2020'},
                             { id:4,name: 'MBL#', TEXT:'Jul 11, 2020'},
                             { id:5,name: 'SIZE', TEXT:'4HC'},
                             { id:6,name: 'CONTAINER ID', TEXT:'4567QWAWE'},
                             { id:7,name: 'LOCATION AT', TEXT:'79453 Herzog Throughway Jalan example'},
                             { id:8,name: 'CURRENT POSITION', TEXT:'156 Davon Fields'}],
    }),
    methods:{
        getreference(){
            return 'Drayage Ref#'+ this.reference ;
        },
        closemodal(){
           this.$emit("update:defaultmodal",false)
           this.$emit("datastatus",false);        
        }
    }
    }-->

