<template>
    <div>
        <div>
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
            <v-expansion-panels :ripple="false" flat>
                <v-expansion-panel
                    class="expension-Panel-notes"
                    v-model="panel"
                    multiple
                >
                <v-expansion-panel-header>
                    <template v-slot:actions>
                        <v-btn style="width:30px !important;height:30px !important;min-width:auto" small class="btn-white icons">
                        <v-icon >mdi-chevron-down</v-icon>
                        </v-btn>
                    </template>

                    <div class="d-flex align-center justify-space-between">
                        <div class="">
                            <p class="body-2 font-weight-medium pb-0 mb-0" style="color:#4a4a4a;">Order on hold</p>
                        </div>
                        
                        <div class="d-flex justify-end mr-2">
                            <v-btn style="width:30px !important;height:30px !important;min-width:auto" @click.native.stop="openEditNotes" small class="btn-white icons mr-2">
					            <img src="../../../assets/icons/edit-blue.svg" alt="" />
					        </v-btn>
                            <v-btn style="width:30px !important;height:30px !important;min-width:auto" small @click.native.stop="openDeleteDialog" class="btn-white icons" >
					            <img src="../../../assets/icons/deleteRed.svg" alt="" />
					        </v-btn>
                        </div>
                    </div>
                    </v-expansion-panel-header>
                    <v-expansion-panel-content class="px-0 mx-0">
                       <p class="body-2 pb-0 mt-n1" style="color:#4a4a4a">I have told factories to hold on the products for two weeks. Wait on this booking.</p>
                        <div class="buttons d-flex align-center">
                            <p class="my-auto d-flex align-center mr-8">
                                <img src="@/assets/icons/document_black.png" width="15" height="18" class="mr-1" />
                                <span class="ml-2">Order on hold.pdf</span> 
                             </p>
                            <v-btn class="btn-white" style="border: none !important;height:28px !important;padding:0 6px !important">
                                <img src="@/assets/icons/visibility-black.svg" width="15" class="mr-1" /> 
                                Preview
                            </v-btn>
                            <v-btn class="btn-white font-weight-medium" style="border: none !important;height:28px !important;padding:0 6px !important">
                                <img class="mr-1" src="@/assets/icons/download.svg" alt="Download"> Download
                            </v-btn>
                        </div>
                        <div class="my-3" style="font-size:12px;color:#0171A1;font-weight:500">James Greece <div class="ellipse"></div>  12:32PM jul 18,2020</div>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </div>

        <AddNotesSalesOrder 
            :dialogNotes.sync="addNotesDialog"
            :editedIndex="editedIndex"
            @close="closeAddNoteDialog"
            :notesData="notesData"
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
				<v-btn class="btn-blue" @click="submitDeleteConfirm" text>
                    Delete
				</v-btn>

				<v-btn class="btn-white" text @click="closeDeleteDialog">
					Close
				</v-btn>
			</template>
		</ConfirmDialog>
        </div>
        <!-- <div>
            <v-card elevation="0" class="mt-15">
                <v-card-title class="justify-center pb-1" style="font-weight:600;color:#4A4A4A">No Note Added</v-card-title>
                <v-card-text class="text-center pb-1 body-2" style="color:#4a4a4a">You haven't added any note to this Purchase Order yet. <br>
                    Press the below button to add new note.</v-card-text>
                <v-card-actions class="d-flex justify-center">
                    <v-btn @click="AddNotesDialogOpen" class="btn-blue">Add Note</v-btn>
                </v-card-actions>
            </v-card>
        </div> -->
    </div>
</template>

<script>
import ConfirmDialog from '../../Dialog/GlobalDialog/ConfirmDialog.vue'
import { mapActions,mapGetters } from 'vuex'
import globalMethods from '../../../utils/globalMethods'
import AddNotesSalesOrder from '../../SalesOrdersComponenets/Dialog/AddNotesSalesOrder.vue'
    export default {
        components:{
            AddNotesSalesOrder,
            ConfirmDialog,
        },
        data(){
            return {
                panel:[],
                addNotesDialog:false,
                editedIndex:-1,
                // delete dialog
                confirmDeleteDialog:false,
                notesData:{
                    title:'',
                    notes:'',
                    url:[]
                }
            }
        },
        computed:{
            ...mapGetters({}),
        },
        methods:{
            ...mapActions({
                deleteNotesApi:'po/deleteNotesApi'
            }),
            ...globalMethods,
            AddNotesDialogOpen(){
                this.addNotesDialog = true
            },
            closeAddNoteDialog(){
                this.addNotesDialog = false   
                this.editedIndex = -1 
            },
            openEditNotes(){
                this.addNotesDialog = true
                this.editedIndex = 1
            },
            // Delete Function
            async submitDeleteConfirm(){
                try{
                    await this.deleteNotesApi()
                    this.notificationMessage("sdasdasd")
                }catch(e){
                    this.notificationError(e)
                }
            },
            openDeleteDialog(){
                // event.stopPropagation()
                this.confirmDeleteDialog =true
            },
            closeDeleteDialog(){
                this.confirmDeleteDialog =false
            }
        }
    }
</script>

<style lang="scss">
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
</style>