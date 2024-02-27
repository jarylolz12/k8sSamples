<template>
    <div>
        <div v-if="typeof getOrderNotesData !== 'undefined' && typeof getOrderNotesData.data !== 'undefined' &&
			typeof getOrderNotesData.data !== null && getOrderNotesData.data !== 'null' && !getOrderNotesLoading && getOrderNotesData.data.length > 0">
            <v-card class="elevation-0">
                <v-card-title>
                    <h5>
                        Notes
                    </h5>
                    <v-spacer></v-spacer>
                    <v-btn style="height:30px !important" @click="AddNotesDialogOpen" class="btn-blue">
                       <v-icon small class="mt-1 px-0 mr-1">mdi-plus</v-icon> Add Note
                    </v-btn>
                </v-card-title>
            </v-card>
            <div class="mx-4 notes-expension">
                <v-expansion-panels
                    :ripple="false" 
                    multiple
                    flat>
                    <v-expansion-panel
                        class="expension-Panel-notes"
                        v-model="panel"
                        
                        v-for="notes in getOrderNotesData.data"
                        :key="notes.id"
                    >
                    <v-expansion-panel-header>
                        <template v-slot:actions>
                            <v-btn style="width:30px !important;height:30px !important;min-width:auto" small class="btn-white icons">
                            <v-icon >mdi-chevron-down</v-icon>
                            </v-btn>
                        </template>

                        <div class="d-flex align-center justify-space-between">
                            <div class="">
                                <p class="body-2 font-weight-medium pb-0 mb-0" style="color:#4a4a4a;">{{notes.title}}</p>
                            </div>

                            <div class="d-flex justify-end mr-2">
                                <v-btn style="width:30px !important;height:30px !important;min-width:auto" @click.native.stop="openEditNotes(notes)" small class="btn-white icons mr-2">
		    			            <img src="../../../assets/icons/edit-blue.svg" alt="" />
		    			        </v-btn>
                                <v-btn style="width:30px !important;height:30px !important;min-width:auto" small @click.native.stop="openDeleteDialog(notes.id)" class="btn-white icons" >
		    			            <img src="../../../assets/icons/deleteRed.svg" alt="" />
		    			        </v-btn>
                            </div>
                        </div>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content class="px-0 mx-0">
                            <p class="body-2 pb-0 mt-n1" style="color:#4a4a4a">{{notes.body}}</p>
                            <div
                                class="pdf_width" 
                                >
                                <div
                                    v-for="(orerspdf,index) in notes.order_notes_documents"
                                    :key="orerspdf.id + index"
                                    class="buttons-notes  mr-3">
                                    <p class="my-auto d-flex align-center mr-6">
                                        <img src="@/assets/icons/document_black.png" width="15" height="18" class="mr-1" />
                                        <span class="ml-2">{{orerspdf.name}}</span> 
                                     </p>
                                    <v-btn @click="downloadDoc(orerspdf,'preview')" :disabled="getDownloadNotesLoading" class="btn-white" style="border: none !important;height:28px !important;padding:0 6px !important;background-color:transparent !important;">
                                        <img src="@/assets/icons/visibility-black.svg" width="15" class="mr-1" /> 
                                        Preview
                                    </v-btn>
                                    <v-btn @click="downloadDoc(orerspdf,'download')" :disabled="getDownloadNotesLoading" class="btn-white font-weight-medium" style="border: none !important;height:28px !important;padding:0 6px !important;background-color:transparent !important;">
                                        <img class="mr-1" src="@/assets/icons/download.svg" alt="Download"> Download
                                    </v-btn>
                                </div>
                           </div>
                            <div class="my-3" style="font-size:12px;color:#0171A1;font-weight:500">{{notes.customer_admin_name}}<div class="ellipse">
                                </div>{{formatDate(notes)}}</div>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
            </div>

        </div>
        <div v-if="typeof getOrderNotesData !== 'undefined' && typeof getOrderNotesData.data !== 'undefined' &&
			typeof getOrderNotesData.data !== null && getOrderNotesData.data !== 'null'">
            <div v-if="getOrderNotesData.data.length == 0 && !getOrderNotesLoading">
                <v-card elevation="0" class="mt-15">
                    <v-card-title class="justify-center pb-1" style="font-weight:600;color:#4A4A4A">No Note Added</v-card-title>
                    <v-card-text class="text-center pb-1 body-2" style="color:#4a4a4a">You haven't added any note to this Purchase Order yet. <br>
                        Press the below button to add new note.</v-card-text>
                    <v-card-actions class="d-flex justify-center">
                        <v-btn @click="AddNotesDialogOpen" class="btn-blue">Add Note</v-btn>
                    </v-card-actions>
                </v-card>
            </div>
        </div>
        <div class="loading-wrapper-notes" v-if="getOrderNotesLoading">
            <v-progress-circular
                :size="40"
                color="#0171a1"
                indeterminate>
            </v-progress-circular>
        </div>
        <AddNotes 
            :dialogNotes.sync="addNotesDialog"
            :editedIndex="editedIndex"
            @close="closeAddNoteDialog"
            :notesData="notesData"
            :orderId="orderId"
        />
        <ConfirmDialog :dialogData.sync="confirmDeleteDialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert">
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Delete Note</h2>
			</template>
			<template v-slot:dialog_content>
				<p> 
					Do you want to delete the selected note? Once deleted, a note cannot be retrieved.
				</p>
			</template>
			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="submitDeleteConfirm" :disabled="getDeleteNotesLoading" text>
                    Delete
				</v-btn>
				<v-btn class="btn-white" text @click="closeDeleteDialog" :disabled="getDeleteNotesLoading">
					Close
				</v-btn>
			</template>
		</ConfirmDialog>
    </div>
</template>

<script>
import AddNotes from '../../PosComponents/Dialog/AddNotes.vue'
import ConfirmDialog from '../../Dialog/GlobalDialog/ConfirmDialog.vue'
import { mapActions,mapGetters } from 'vuex'
import globalMethods from '../../../utils/globalMethods'
import moment from "moment";
    export default {
        props:["orderId"],
        components:{
            AddNotes,
            ConfirmDialog
        },
        data(){
            return {
                panel:[],
                addNotesDialog:false,
                editedIndex:-1,
                // delete dialog
                confirmDeleteDialog:false,
                deleteNotesData:null,
                notesData:{
                    title:'',
                    body:'',
                    id:null,
                    url:[],
                    preview:[]
                },
            }
        },
        computed:{
            ...mapGetters({
                getOrderNotesLoading:'po/getOrderNotesLoading',
                getOrderNotes:'po/getOrderNotes',
                getDeleteNotesLoading:'po/getDeleteNotesLoading',
                getDownloadNotesLoading:'po/getDownloadNotesLoading',
                getDownloadNotes:'po/getDownloadNotes'
            }),
            getOrderNotesData(){
                let data = []
                if(typeof this.getOrderNotes !== 'undefined' &&  this.getOrderNotes !== null){
                    data = this.getOrderNotes 
                }else{
                    data = []
                }
                return data
            }
        },
        methods:{
            ...mapActions({
                deleteNotesApi:'po/deleteNotesApi',
                fetchAllNotesApi:'po/fetchAllNotesApi',
                downloadNotesDoc:'po/downloadNotesDoc'
            }),
            ...globalMethods,
            formatDate(notes) {
	  		if (typeof notes !== 'undefined' && notes !== null) {
				if (notes.created_at !== 'undefined') {
					let date = notes.created_at
					return moment(date).format('h:mm A, MMM:DD, YYYY')
				} else {
					return 'N/A'
				}
	  		}
		},
            AddNotesDialogOpen(){
                this.addNotesDialog = true
            },
            closeAddNoteDialog(){
                this.addNotesDialog = false   
                this.editedIndex = -1 
                this.notesData = {
                    title:'',
                    body:'',
                    id:null,
                    url:[],
                    preview:[]
                }
            },
            openEditNotes(item){
                this.addNotesDialog = true
                this.editedIndex = 1
                let itemCopy = {...item}
				itemCopy.title = itemCopy.title !== null ? itemCopy.title : ''
                itemCopy.body = itemCopy.body !== null ? itemCopy.body : ''
                itemCopy.id = itemCopy.id !== null ? itemCopy.id : null
                
                if(!itemCopy.order_notes_documents.length || itemCopy.order_notes_documents.length == null || itemCopy.order_notes_documents.length == 0){
                   itemCopy.url = [] 
                   itemCopy.preview = []
                }else{
                    let convertDocuments = []
                    // itemCopy.order_notes_documents.map((v) => {
                    //       if (v.name !== "undefined") {
                    //         return new File([v.name], `${v.name}`, {
                    //               type: v.type,
                    //         });
                    //      }
                    // });
                    let preview = []
                    itemCopy.order_notes_documents.map((v) => {
                        let url = v.path
                        const toDataURL = url => fetch(url)
                        .then(response => response.blob())
                        .then(blob => new Promise((resolve, reject) => {
                            const reader = new FileReader()
                            reader.onloadend = () => resolve(reader.result)
                            reader.onerror = reject
                            reader.readAsDataURL(blob)
                        }))
                        toDataURL(url)
                        .then(dataUrl => {
                            preview.push(dataUrl)
                            // console.log('Here is Base64 Url', dataUrl)
                            var fileData = this.dataURLtoFile(dataUrl,v.name)
                            // console.log("Here is JavaScript File Object",fileData)
                            
                            convertDocuments.push(fileData)
                            return dataUrl
                    })
                    });
                     itemCopy.preview = preview
                    itemCopy.url =  convertDocuments
                }
				this.notesData = itemCopy
            },
            dataURLtoFile(dataurl, filename) {
                var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
                while(n--){
                u8arr[n] = bstr.charCodeAt(n);
                }
                return new File([u8arr], filename, {type:mime});
            },
            // Delete Function
            async submitDeleteConfirm(){
                try{
                    if(this.deleteNotesData == null) return
                    await this.deleteNotesApi(this.deleteNotesData)
                    await this.fetchAllNotesApi(this.orderId)
                    this.closeDeleteDialog()
                    this.notificationMessage("The note has been deleted")
                }catch(e){
                    this.notificationError(e)
                }
            },
            openDeleteDialog(item){
                // event.stopPropagation()
                this.deleteNotesData = item
                this.confirmDeleteDialog =true
            },
            closeDeleteDialog(){
                this.deleteNotesData = null
                this.confirmDeleteDialog =false
            },
            async getAllNotes(){
                try{
                    await this.fetchAllNotesApi(this.orderId)
                }catch(e){
                    this.notificationError(e)
                }
            },
            // download notes
            async downloadDoc(item,from) {
                if (item !== null) {
                    try {
                        let payload = {
                            item: item,
                            from:from
                        }
                        await this.downloadNotesDoc(payload)
                            if (typeof this.getDownloadNotes !=='undefined' && this.getDownloadNotes !== undefined && 
                                this.getDownloadNotes !== null) {
                                if(from == 'download'){
                                    var url = window.URL.createObjectURL(new Blob([this.getDownloadNotes]));
                                    var a = document.createElement("a");
                                    a.href = url;
                                    a.download = `${item.original_name}.pdf`;
                                    document.body.appendChild(a);
                                    a.click();
                                    a.remove();
                                    this.notificationMessage('Document has been downloaded.')
                                }else{
                                    var fileURL = window.URL.createObjectURL(this.getDownloadNotes);
                                    window.open(fileURL);
                                }
                            
                            }
                    } catch(e) {
                        this.notificationError(e)
                    }
                }
            },
            previewPdfReportFile(){
                var fileURL = window.URL.createObjectURL(this.downloadNotesDoc);
                window.open(fileURL);
            }
        },
        mounted(){
            // if(this.orderId !== ''){
            //     this.getAllNotes()
            // }
        }
    }
</script>

<style lang="scss">
.loading-wrapper-notes{
    width: 100%;
    min-height: 150px;
    display: flex;
    justify-content: center;
    align-items: center;
        
}
.expension-Panel-notes{
    border: 1px solid #D8E7F0;
    border-radius: 4px;
    margin: 0 0 8px 0;
}
.notes-expension .v-expansion-panel-header {
    padding: 0 10px 0 10px;
    min-height: 54px !important;
}
.notes-expension .v-expansion-panel--active > .v-expansion-panel-header {
    min-height: 54px !important;
    // margin-top: 10px;
    // padding: 10px;
}
.notes-expension .v-expansion-panel-content__wrap {
    padding: 0 12px !important;
    max-width: 100%;
}
.notes-expension .ellipse {
    display: inline-block;
    width: 4px; 
    height: 4px;
    background-color:  #B4CFE0;
    border-radius: 50px;
    margin: 0 5px;
    padding-bottom: 4px;
    vertical-align:middle;
}
.pdf_width{
    display: flex;
    flex-wrap: wrap;
}
.buttons-notes{
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    padding: 4px 8px;
    margin: 8px 1px;
    background: #F5F9FC;
    border-radius: 4px;
    width: max-content;
    height: 32px;

}
</style>