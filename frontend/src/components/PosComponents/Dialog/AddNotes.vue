<template>
    <div>
        <v-dialog
		v-model="dialog"
		max-width="824px"
		content-class="add-notes-dialog"
		:retain-focus="false"
		persistent
		scrollable
	>
		<v-card class="notes-dialog-card">
			<v-card-title>
				<span class="headline">{{ formTitle }}</span>
				<button icon dark class="btn-close" @click="close">
					<v-icon>mdi-close</v-icon>
				</button>
			</v-card-title>

			<v-card-text>
				<v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                    <v-row no-gutters>
                        <v-col cols="12">
                            <v-card-text class="my-0 pb-0 px-0">
                                <p class="notes-title">Title</p>
									<v-text-field
                                        v-model="notesAddEditData.title"
										class="text-fields"
										placeholder="Enter note title"
										outlined
										hide-details="auto"
									>
									</v-text-field>
                            </v-card-text>
                        </v-col>
                        <v-col cols="12">
                            <v-card-text class="px-0">
                                <div class="notes-textArea mb-4">
								<p class="notes-title"> Note</p>
								<v-textarea
                                    v-model="notesAddEditData.body"
									class="deliver-address text-fields"
									outlined
									name="input-7-4"
									placeholder="Write your note here"
									hide-details="auto">
								</v-textarea>
							</div>
                            </v-card-text>
                        </v-col>
                        <v-col cols="12">
                            <div class="documents-section">
								<p class="notes-title">ATTACHMENTS</p>
                                <p>Upload any of the supported file here (pdf, jpg, docs)</p>
                                <div class="d-flex"> 
								    <div
									    id="documents-files-select-box-id"
									    class="documents-files-select-box"
									    @click.stop="addDocuments()">
									    Browse or <br> Drop file

									    <button
									        class="btn-white btn-upload"
									        @click.stop="addDocuments()">

									        <img
										        src="@/assets/icons/upload.svg"
										        width="12px"
										        height="12px"/>
									        Upload
									    </button>
								    </div>

								    <input
								    	ref="add_notes_documents_reference"
								    	type="file"
								    	id="documents_files"
								    	@change="(e) => inputChanged(e)"
								    	name="documents[]"
								    	accept=".pdf,.docx,.jpg"
								    	multiple 
                                    />
                                    <div class="document_preview"  v-if="files !== null && files.length !== 'undefined' && files.length > 0">
                                        <div class="documents_list_view_All" v-for="(file, index) in preview"
								    	    :key="index">
                                            <div class="documents_list_view_single">
                                                <img width="100%" height="100%" :src="previewImg(file,index)" alt="">
                                                <v-btn @click="remove(index)" class="remove-document-btn">
                                                    Remove
                                                </v-btn>
                                            </div>
                                        </div>
                                    </div>
                                </div>

							</div>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-btn @click="addAndEditNotes" :disabled="loading" class="btn-blue">
                   {{fromButton}}
                </v-btn>
                <v-btn @click="close" class="btn-white" :disabled="loading" style="color: #4a4a4a !important;">
                    Cancel
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>    
    </div>
</template>

<script>
import { mapActions,mapGetters } from 'vuex';
import globalMethods from '../../../utils/globalMethods';
    export default {
        props:['dialogNotes','editedIndex','notesData',"orderId"],
        data(){
            return{
                valid:true,
                files:[],
                notesAddEditData:{
                    title:'',
                    body:'',
                    id:null,
                    url:[],
                },
                preview:[]
            }
        },
        watch:{
            dialog:function(){
                if(this.editedIndex === 1){
                    if (this.notesAddEditData !== this.editedItemnotesData) {
                    this.notesAddEditData = this.editedItemnotesData
                }
                if (this.files !== this.editedItemnotesData.url) {
                    this.files = this.editedItemnotesData.url
                    }
                    if (this.preview !== this.editedItemnotesData.preview) {
                        this.preview = this.editedItemnotesData.preview
                    }
                }
            }
        },
        computed:{
            ...mapGetters({
                getAddNotesLoading:'po/getAddNotesLoading',
                getEditNotesLoading:'po/getEditNotesLoading'
            }),
            loading(){
                let loading = false
                if(this.editedIndex === -1){
                    loading = this.getAddNotesLoading
                }else{
                    loading = this.getEditNotesLoading
                }
                return loading
            },
            formTitle() {
			    return this.editedIndex === -1 ? "Add Note" : "Edit Note";
		    },
            fromButton(){
                return this.editedIndex === -1 ? "Add Note" : "Save Changes";
            },
            dialog: {
	  		    get() {
				    return this.dialogNotes;
	  		    },
	  		    set(value) {
				    this.$emit("update:dialogNotes", value);
	  		    },
		    },
            editedItemnotesData: {
                get() {                            
                    let values = {...this.notesData }
                    return values
                },
                set(value) {
                    this.$emit('update:notesData', value)
                }
            },
        },
        methods:{
            ...mapActions({
                addNotesApi:'po/addNotesApi',
                editNotesApi:'po/editNotesApi',
                fetchAllNotesApi:'po/fetchAllNotesApi'
            }),
            ...globalMethods,
            previewImg(file,index){
                if(this.preview.length > 0 ) {

                if(typeof file == 'string'){
                    return file
                }else{
                    let img = ''
                    let preview
                    if(this.preview.length){
                        img = this.preview[index]
                        preview = URL.createObjectURL(img);
                    }else{
                        preview = ''
                    }
                    return preview
                }
                }

            },
            close() {
                this.notesAddEditData={
                    title:'',
                    body:'',
                    url:[],
                }
                this.preview=[]
                this.files = []
                this.$refs.form.resetValidation()
	  		    this.$emit("close");
            },
            inputChanged() {
	  		    let files = this.$refs.add_notes_documents_reference.files;

	  		    if (!files.length) {
			    	return false;
	  		    }

	  		    for (let i = 0; i < files.length; i++) {
			    	this.files.push(files[i]);
                    this.preview.push(files[i])
	  		    }
                // const preview = e.target.files[0];
                // this.importNameData.url = URL.createObjectURL(preview);
		    },
            remove(index) {
			    this.$refs.add_notes_documents_reference.value = "";
	  		    this.files.splice(index, 1);
                this.preview.splice(index,1)
		    },
            addDocuments() {
	  		    this.$refs.add_notes_documents_reference.click();
		    },
            async addAndEditNotes(){
                try{
                    if(this.editedIndex == -1){
                        let payload = {
                            id:this.orderId,
                            title:this.notesAddEditData.title,
                            body:this.notesAddEditData.body,
                            files:this.files
                        }
                        await this.addNotesApi(payload)
                        await this.fetchAllNotesApi(this.orderId)
                        this.close()
                        this.notificationMessage("Added note to order");
                    }else{
                        let temp = [...this.files]
                        if(this.files.length < this.notesAddEditData.order_notes_documents.length ){
                            let total = this.notesAddEditData.order_notes_documents.length - this.files.length
                            console.log("total",total)
                            
                            for( var i =0 ;i < total ;i++ ){
                               temp = [...temp ,'']
                            }
                        }
                        let payload = {
                            id:this.notesAddEditData.id,
                            data:this.notesAddEditData,
                            files:temp
                        }
                        await this.editNotesApi(payload)
                        await this.fetchAllNotesApi(this.orderId)
                        this.close()
                        this.notificationMessage("Note Edited");
                    }
                }catch(e){
                    this.notificationError(e)
                }
            }
        },
        mounted(){

        },
        updated() {
            
        },

    }
</script>

<style lang="scss" scoped>
@import "../../../assets/scss/pages_scss/po/addNotes.scss";

</style>