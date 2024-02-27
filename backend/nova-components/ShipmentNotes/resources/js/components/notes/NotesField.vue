<template>
    <div :class="classes">
        <h3 class="text-90 mb-4">{{ field.name }}</h3>
        <div>
            <button 
                class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" 
                @click="enableAddNoteForm"
                v-if="!showAddNoteForm && isEditMode" >
                <button class="plus-button plus-button--small"></button>Add Note
            </button>
        </div>
        <note-input
            v-if="field.addingNotesEnabled && showAddNoteForm"
            :description="description"
            :subject="subject"
            @onSubmit="createNote"
            @onSubmitEdit="onNoteEdit"
            @onCancelEdit="onCancelEdit"
            :loading="loading"
            :placeholder="field.placeholder"
            :isEditing="isEditing"
        />

        <note
            v-for="note in notesToShow"
            :note="note"
            :key="note.id"
            :date-format="dateFormat"
            @onDeleteRequested="onNoteDeleteRequested"
            @editNote="editNote"
            :isEditMode="isEditMode"
        />

        <div class="flex justify-center mb-3 mt-3" v-if="hasMoreToShow">
      <span
          class="btn btn-default btn-primary leading-tight ml-2 px-3 text-sm text-center cursor-pointer"
          style="height: 24px; line-height: 24px"
          @click="maxToShow = void 0"
      >
        {{ __('Show all notes (:hiddenNoteCount more)', { hiddenNoteCount: String(notes.length - maxToShow) }) }}
      </span>
        </div>

        <delete-note-confirmation-modal
            v-if="showDeleteConfirmation"
            @close="showDeleteConfirmation = false"
            @confirm="deleteNote(noteToDelete)"
        />
    </div>
</template>

<script>
    import Note from './Note';
    import NoteInput from './NoteInput';
    import DeleteNoteConfirmationModal from './DeleteNoteConfirmationModal';

    export default {
        components: {Note, NoteInput, DeleteNoteConfirmationModal},
        props: ['resourceName', 'resourceId', 'field', 'extraClass', 'isEditMode'],
        data: () => ({
            currentNoteId: '',
            subject: '',
            description: '',
            note: '',
            loading: true,
            notes: [],
            showDeleteConfirmation: false,
            noteToDelete: void 0,
            maxToShow: 5,
            dateFormat: 'DD MMM YYYY HH:mm',
            isEditing: false,
            showAddNoteForm: false
        }),
        mounted() {
            this.fetchNotes();
        },
        computed: {
            params() {
                return {
                    resourceId: this.resourceId,
                    resourceName: this.resourceName,
                };
            },
            notesToShow() {
                if (this.maxToShow) return this.notes.slice(0, this.maxToShow);
                return this.notes;
            },
            hasMoreToShow() {
                return this.maxToShow && this.notes.length > this.maxToShow;
            },
            classes() {
                const defaultClasses = 'notes-field bg-20 px-4 pt-4 pb-2 rounded-b-lg overflow-hidden border-b border-40';
                return defaultClasses + (this.extraClass ? ` ${this.extraClass}` : '');
            },
        },
        methods: {
            enableAddNoteForm(){
                this.showAddNoteForm = true;
            },
            async fetchNotes() {
                this.loading = true;

                const {data} = await Nova.request().get(`/custom/shipment-notes`, {
                    params: this.params,
                });
                const {notes, date_format: dateFormat} = data;

                if (Array.isArray(notes)) this.notes = notes;
                this.dateFormat = dateFormat;

                this.loading = false;
            },
            async createNote(subject, description) {
                this.loading = true;

                try {
                    await Nova.request().post(`/custom/shipment-notes`, {
                        subject: subject,
                        note: description,
                        shipmentId: this.field.shipment_id,
                        uploaded_from: 0
                    }, {params: this.params});
                    await this.fetchNotes();
                } catch (e) {
                    Nova.error(this.__('There was a problem submitting the form.'));
                }

                this.subject = '';
                this.description = '';

                this.loading = false;
                this.showAddNoteForm = false;
                Nova.success('Note added successfully.');
            },
            async onNoteEdit(subject, description) {
                this.loading = true;

                try {
                    await Nova.request().put(`/custom/shipment-notes/${this.currentNoteId}`, {
                        subject: subject,
                        note: description
                    });
                    await this.fetchNotes();
                } catch (e) {
                    console.log(e);
                    Nova.error(this.__('There was a problem submitting the form.'));
                }

                this.subject = '';
                this.description = '';

                this.loading = false;
                this.isEditing = false;
                Nova.success('Note updated successfully.');
            },
            async deleteNote(note) {
                this.loading = true;

                try {
                    await Nova.request().delete(`/custom/shipment-notes`, {
                        params: this.params,
                        data: {noteId: note.id},
                    });
                    await this.fetchNotes();
                } catch (e) {
                    Nova.error('Unknown error when trying to delete the note.');
                }

                this.showDeleteConfirmation = false;
                this.loading = false;
                Nova.success('Note deleted successfully.');
            },
            onCancelEdit() {
                this.subject = '';
                this.description = '';
                this.isEditing = false;
                this.showAddNoteForm = false;
            },
            onNoteDeleteRequested(note) {
                this.showDeleteConfirmation = true;
                this.noteToDelete = note;
            },
            editNote(note) {
                this.showAddNoteForm = true;
                this.isEditing = true;
                this.currentNoteId = note.id;
                this.subject = note.subject;
                this.description = note.description;
            }
        },
    };
</script>

<style lang="scss" scoped>
    .notes-field:not(:last-child) {
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    .plus-button {
        border: 2px solid lightgrey;
        background-color: #fff;
        font-size: 16px;
        height: 2.5em;
        width: 2.5em;
        border-radius: 999px;
        position: relative;
        margin-right: 9px;

        &:after,
        &:before {
            content: "";
            display: block;
            background-color: grey;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        &:before {
            height: 1em;
            width: 0.2em;
        }

        &:after {
            height: 0.2em;
            width: 1em;
        }
    }

    .plus-button--small {
        font-size: 8px;
    }
</style>
