<template>
    <div class="w-full">
        <div>
            <div class="drag-drop-container flex-1">
                <input
                    :id="'file-upload-' + componentIndex"
                    ref="files"
                    class="file-input"
                    type="file"
                    name="files"
                    @change="onChange"
                    multiple
                    :disabled="buttonState.isLoading"
                />
                <label
                    :for="'file-upload-' + componentIndex"
                    class="file-input-label my-1"
                    @drop="drop"
                    @dragover="dragOver"
                    @dragleave="dragLeave"
                    v-show="!buttonState.isLoading"
                >
                    <div style="width:100%">
                        <span class="italic" style="font-size:1rem">Click or Drop your files here.</span>
                    </div>
                    <div style="width:100%" class="mt-1">
                        <span class="italic" style="font-size:.80rem">Allowable files: ai, csv, doc, docx, eps, gif, jpeg, jpg, ods, pdf, png, rtf, tif, xls, xlsx and xml</span>
                    </div>
                </label>
            </div>
            <div class="flex-1 ml-1">
                <div v-for="(file, index) in files" :key="index">
                    <div class="file-item">
                        <input type="checkbox" class="mr-1" checked/>
                        <span>{{ file.name }}</span>
                        <button
                        :disabled="buttonState.isLoading"
                        type="button"
                        class="btn-delete"
                        @click="deleteFile(index)"
                        >
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        viewBox="0 0 20 20"
                        aria-labelledby="delete"
                        role="presentation"
                        class="fill-current"
                        >
                            <path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                        </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name:'DragNDropInput',
    props: ["buttonState", "componentIndex"],
    data: function() {
        return {
            files: []
        };
    },
    methods: {
        onChange: function() {
            this.files = [...this.files, ...this.$refs.files.files];
            this.$refs.files.value = null;
        },
        drop: function(e) {
            e.preventDefault();
            this.$refs.files.files = e.dataTransfer.files;
            e.currentTarget.classList.remove("bg-blue");
            this.onChange();
        },
        dragOver: function(e) {
            e.preventDefault();
            e.currentTarget.classList.add("bg-blue");
        },
        dragLeave: function(e) {
            e.preventDefault();
            e.currentTarget.classList.remove("bg-blue");
        },
        deleteFile: function(fileIndex) {
            const tempFiles = this.files.filter(
                (_, index) => index !== fileIndex
            );
            this.files = [...tempFiles];
        }
    },
    watch: {
        files: function(value) {
            this.$emit("getAttachements", {
                files: value
            });
        }
    }
};
</script>

<style scoped>
.drag-drop-container {
    display: flex;
    border: 1px solid #bacad6;
    border-radius: 0px;
    width: 50%;
    height: auto;
    min-height: 50px;
    overflow: hidden;
    margin: 0 auto 20px;
}

.file-input {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
    overflow: hidden;
}

.file-input-label {
    display: block;
    position: relative;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    width: 100% !important;
    height: auto;
    text-align: center;
}

.file-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100% !important;
    padding: 3px 5px;
    margin-bottom: 2px;
    border: 1px solid #bacad6;
    border-radius: 0px;
}

.btn-delete {
    margin-left: 10px;
    color: #409ade;
    border: none;
    width: 20px;
}

.btn-delete:focus {
    outline: none !important;
}

.bg-blue {
    background-color: #409ade;
}

.paper-clip-icon{
    color: #7c858e;
}
</style>
