<template>
    <div class="w-full">
        <div class="drag-drop-container">
            <input
                :id="'file-upload-' + supplierIndex"
                ref="files"
                class="file-input"
                type="file"
                name="files"
                @change="onChange"
                multiple
                :disabled="isLoading"
            />
            <label
                :for="'file-upload-' + supplierIndex"
                class="file-input-label"
                @drop="drop"
                @dragover="dragOver"
                @dragleave="dragLeave"
                v-show="!isLoading"
            >
                <div>
                    Click or Drop your files here.
                </div>
            </label>
        </div>
    </div>
</template>

<script>
export default {
    props: ["isLoading", "supplierIndex", "files"],
    methods: {
        onChange: function() {
            this.$emit("getFiles", {
                index: this.supplierIndex,
                files: [...this.$refs.files.files]
            });
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
        }
    }
};
</script>

<style scoped>
.drag-drop-container {
    display: flex;
    border: 2px solid #3c4b5f;
    border-radius: 10px;
    width: 50%;
    height: 100px;
    overflow: hidden;
}

.file-input {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
    overflow: hidden;
}

.file-input-label {
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    width: 100% !important;
    height: 100px;
}

.file-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 50% !important;
    padding: 10px 20px;
    margin-bottom: 5px;
    border: 1px solid #bacad6;
    border-radius: 10px;
}

.btn-delete {
    margin-left: 50px;
    color: #409ade;
    border: none;
}

.btn-delete:focus {
    outline: none !important;
}

.bg-blue {
    background-color: #409ade;
}
</style>
