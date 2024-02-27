<template>
    <div>
        <div v-if="fieldData.isLoading || suppliersAndUploadButtonLoading">
            <div class="w-1/4 py-4">
                <h4 class="font-normal text-80">Fetching Entry Data.</h4>
            </div>
        </div>
        <div v-if="fieldData.isShow">
            <div class="flex border-b border-40">
                <div class="w-1/4 py-4">
                    <h4 class="font-normal text-80">Entry No #</h4>
                </div>
                <div class="w-3/4 py-4 break-words">
                    {{ fieldData.entryNo }}
                </div>
            </div>
            <div class="flex border-b border-40">
                <div class="w-1/4 py-4">
                    <h4 class="font-normal text-80">Entry Created Date</h4>
                </div>
                <div class="w-3/4 py-4 break-words">
                    {{ fieldData.entrySubmittedDate }}
                </div>
            </div>
            <div v-show="fieldData.entryPdf" class="flex border-b border-40">
                <div class="w-1/4 py-4">
                    <h4 class="font-normal text-80">Form 7501 PDF</h4>
                </div>
                <div class="w-3/4 py-4 break-words">
                    <div class="file-name-container">
                        <p class="flex items-center text-sm mt-3 file-name">
                            <a
                                :href="
                                    `/custom/download?link=${encodeURIComponent(
                                        fieldData.entryPdf
                                    )}`
                                "
                                tabindex="0"
                                class="cursor-pointer dim btn btn-link text-primary inline-flex items-center"
                                ><svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    aria-labelledby="download"
                                    role="presentation"
                                    class="fill-current mr-2"
                                >
                                    <path
                                        d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"
                                    ></path>
                                </svg>
                                <span class="class mt-1">Download</span></a
                            >
                        </p>
                    </div>
                </div>
            </div>
            <div v-show="fieldData.entryXml" class="flex border-b border-40">
                <div class="w-1/4 py-4">
                    <h4 class="font-normal text-80">Form 7501 XML</h4>
                </div>
                <div class="w-3/4 py-4 break-words">
                    <div class="file-name-container">
                        <p class="flex items-center text-sm mt-3 file-name">
                            <a
                                :href="
                                    `/custom/download?link=${encodeURIComponent(
                                        fieldData.entryXml
                                    )}`
                                "
                                tabindex="0"
                                class="cursor-pointer dim btn btn-link text-primary inline-flex items-center"
                                ><svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    aria-labelledby="download"
                                    role="presentation"
                                    class="fill-current mr-2"
                                >
                                    <path
                                        d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"
                                    ></path>
                                </svg>
                                <span class="class mt-1">Download</span></a
                            >
                        </p>
                    </div>
                </div>
            </div>
            <template v-if="suppliers.length > 0">
                <div v-if="1==2" class="flex border-b border-40">
                    <div class="w-1/4 py-4">
                        <h4 class="font-normal text-80">Commercial Docs</h4>
                    </div>
                    <div class="w-3/4 py-4 break-words">
                        <div class="w-full">
                            <label style="font-weight: bold;"
                                >Supplier Item</label
                            >
                        </div>
                        <div
                            v-for="(supplier, index) in suppliers"
                            :key="index"
                            class="w-full py-4"
                        >
                            <div class="flex">
                                <div class="w-1/4 py-2">
                                    <label>Supplier Name</label>
                                </div>
                                <div class="w-3/4 py-2 break-words">
                                    <span>{{ supplier.companyName }}</span>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="w-1/4 py-2">
                                    <label>Commercial Documents</label>
                                </div>
                                <div class="w-3/4 py-2 break-words">
                                    <div
                                        v-for="(item, index2) in supplier.files"
                                        :key="
                                            'file-' +
                                                index.toString() +
                                                '-' +
                                                index2.toString()
                                        "
                                    >
                                        <div v-if="item.status === 'old'">
                                            <div class="file-item">
                                                <div
                                                    class="file-name-container"
                                                >
                                                    <p
                                                        class="flex items-center text-sm mt-3 file-name"
                                                    >
                                                        <a
                                                            :href="
                                                                `/custom/download?link=${encodeURIComponent(
                                                                    item.file
                                                                )}`
                                                            "
                                                            tabindex="0"
                                                            class="cursor-pointer dim btn btn-link text-primary inline-flex items-center"
                                                            ><svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                width="16"
                                                                height="16"
                                                                viewBox="0 0 24 24"
                                                                aria-labelledby="download"
                                                                role="presentation"
                                                                class="fill-current mr-2"
                                                            >
                                                                <path
                                                                    d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"
                                                                ></path>
                                                            </svg>
                                                            <span
                                                                class="class mt-1"
                                                                >{{
                                                                    item.name
                                                                }}</span
                                                            ></a
                                                        >
                                                    </p>
                                                </div>
                                                <button
                                                    v-if="!readOnly"
                                                    :disabled="
                                                        buttonState.isLoading ||
                                                            suppliersAndUploadButtonLoading
                                                    "
                                                    type="button"
                                                    class="btn-delete"
                                                    @click="
                                                        deleteFile(
                                                            index,
                                                            index2
                                                        )
                                                    "
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
                                                        <path
                                                            fill-rule="nonzero"
                                                            d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"
                                                        ></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div v-else-if="item.status === 'new'">
                                            <div class="file-item">
                                                <p>{{ item.file.name }}</p>
                                                <button
                                                    v-if="!readOnly"
                                                    :disabled="
                                                        buttonState.isLoading ||
                                                            suppliersAndUploadButtonLoading
                                                    "
                                                    type="button"
                                                    class="btn-delete"
                                                    @click="
                                                        deleteFile(
                                                            index,
                                                            index2
                                                        )
                                                    "
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
                                                        <path
                                                            fill-rule="nonzero"
                                                            d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"
                                                        ></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <template v-if="!readOnly">
                                        <drag_and_drop
                                            :isLoading="
                                                buttonState.isLoading ||
                                                    suppliersAndUploadButtonLoading
                                            "
                                            :supplierIndex="index"
                                            @getFiles="getFiles"
                                        />
                                    </template>
                                </div>
                                <!-- <div
                                    v-if="!readOnly"
                                    class="w-3/4 py-2 break-words"
                                >
                                    <drag_and_drop
                                        :buttonState="buttonState"
                                        :componentIndex="index"
                                        @getFiles="getFiles"
                                    />
                                </div> -->
                                <!-- <div v-else class="w-3/4 py-2 break-words">
                                    <div
                                        v-for="(item, index) in supplier.files"
                                        :key="'file-' + index.toString()"
                                    >
                                        <p
                                            class="flex items-center text-sm mt-3"
                                        >
                                            <a
                                                :href="
                                                    `/custom/download?link=${encodeURIComponent(
                                                        item.file
                                                    )}`
                                                "
                                                tabindex="0"
                                                class="cursor-pointer dim btn btn-link text-primary inline-flex items-center"
                                                ><svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="16"
                                                    height="16"
                                                    viewBox="0 0 24 24"
                                                    aria-labelledby="download"
                                                    role="presentation"
                                                    class="fill-current mr-2"
                                                >
                                                    <path
                                                        d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"
                                                    ></path>
                                                </svg>
                                                <span class="class mt-1">{{
                                                    item.name
                                                }}</span></a
                                            >
                                        </p>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <div :class="['flex', 'save-documents']">
                            <div class="w-1/4"></div>
                            <div class="w-3/4">
                                <button
                                    v-if="!readOnly"
                                    :disabled="
                                        suppliersAndUploadButtonLoading ||
                                            buttonState.isLoading
                                    "
                                    class="btn btn-primary p-3"
                                    @click.prevent="onSaveDocuments"
                                >
                                    {{
                                        suppliersAndUploadButtonLoading
                                            ? "Loading"
                                            : "Save Documents"
                                    }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-if="buttonState.isShow">
                <netchb_entry_button
                    :isLoading="buttonState.isLoading"
                    :isDisabled="
                        suppliersAndUploadButtonLoading || buttonState.isLoading
                    "
                    @onClick="handleSubmitToNetCHB"
                />
            </template>
        </div>
    </div>
</template>

<script>
export default {
    props: ["resource", "resourceName", "resourceId", "field"],
    data: function() {
        return {
            readOnly: false,
            suppliers: [],
            buttonState: {
                isLoading: false,
                isShow: false
            },
            fieldData: {
                isLoading: false,
                isShow: false,
                entryNo: "",
                entrySubmittedDate: "",
                entryPdf: "",
                entryXml: ""
            },
            suppliersAndUploadButtonLoading: false
        };
    },
    methods: {
        handleSubmitToNetCHB: async function() {
            try {
                this.buttonState = { ...this.buttonState, isLoading: true };
                const formData = this.getFormData();

                const { data } = await Nova.request().post(
                    `/custom/netchb/entry`,
                    formData,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data"
                        }
                    }
                );
                
                await this.handleGetEntry();
                
                this.$toasted.show("Success!", { type: "success" });
            } catch (err) {
                this.buttonState = { ...this.buttonState, isLoading: false };
                this.$toasted.show("Creating Entry Failed!", { type: "error" });
            }
        },
        handleGetEntry: async function() {
            try {
                this.fieldData = { ...this.fieldData, isLoading: true };
                const { data } = await Nova.request().get(
                    `/custom/netchb/entry/${this.resourceId}`
                );
                if (data) {
                    if (data.entry_netchb_no) {
                        this.readOnly = true;
                        this.fieldData = {
                            entryNo: data.entry_netchb_no,
                            entrySubmittedDate: data.entry_netchb_date,
                            entryPdf: data.entry_netchb_pdf,
                            entryXml: data.entry_netchb_xml,
                            isShow: true,
                            isLoading: false
                        };
                        this.buttonState = {
                            ...this.buttonState,
                            isShow: false
                        };
                    } else {
                        this.fieldData = {
                            ...this.fieldData,
                            isLoading: false,
                            isShow: true
                        };
                        this.buttonState = {
                            ...this.buttonState,
                            isShow: true
                        };
                    }
                }
            } catch (err) {
                this.$toasted.show("Fetching Entry Failed!", { type: "error" });
            }
        },
        getFiles: function(value) {
            let tempSupplier = [...this.suppliers];
            const tempFiles = value.files.map(file => {
                return { file: file, status: "new" };
            });
            tempSupplier[value.index] = {
                ...tempSupplier[value.index],
                files: [...tempSupplier[value.index]["files"], ...tempFiles]
            };
            this.suppliers = [...tempSupplier];
        },
        getSuppliers: async function(suppliers) {
            if (suppliers.length > 0) {
                let allSuppliers = [];
                this.suppliersAndUploadButtonLoading = true;
                for (const supplier of suppliers) {
                    try {
                        const { data } = await Nova.request().get(
                            `/custom/netchb/entry/shipments/${this.resourceId}/suppliers/${supplier.supplier_id}/${supplier.id}`
                        );

                        let tempFiles = [];
                        if (data.files.length > 0) {
                            tempFiles = data.files.map(file => {
                                return {
                                    ...file,
                                    status: "old"
                                };
                            });
                        }

                        allSuppliers.push({
                            id: supplier.id,
                            supplierId: data.id,
                            companyName: data.company_name,
                            files: tempFiles
                        });
                    } catch (err) {
                        this.$toasted.show(
                            `Can't fetch supplier with ID ${supplier.supplier_id}`,
                            { type: "error" }
                        );
                    }
                }
                this.suppliers = [...allSuppliers];
                this.suppliersAndUploadButtonLoading = false;
            }
        },
        onSaveDocuments: async function() {
            try {
                const formData = this.getFormData();
                this.suppliersAndUploadButtonLoading = true;
                const { data } = await Nova.request().post(
                    `/custom/netchb/entry/documents`,
                    formData,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data"
                        }
                    }
                );
                if (data.id) {
                    this.$toasted.show("Updated Succesfully!", {
                        type: "success"
                    });
                    await this.getSuppliers(this.field.suppliers);
                }
            } catch (err) {
                this.$toasted.show("Saving documents failed!", {
                    type: "error"
                });
            }
            this.suppliersAndUploadButtonLoading = false;
        },
        getFormData: function() {
            const formData = new FormData();
            let i = 0;
            formData.append("resourceId", this.resourceId);
            if (this.suppliers.length > 0) {
                for (const supplier of this.suppliers) {
                    formData.append("suppliers[" + i + "][id]", supplier.id);
                    formData.append(
                        "suppliers[" + i + "][supplier_id]",
                        supplier.supplierId
                    );
                    formData.append(
                        "suppliers[" + i + "][company_name]",
                        supplier.companyName
                    );

                    if (supplier.files.length > 0) {
                        let j = 0;
                        for (const file of supplier.files) {
                            formData.append(
                                "suppliers[" + i + "][files][" + j + "][file]",
                                file.file
                            );
                            formData.append(
                                "suppliers[" +
                                    i +
                                    "][files][" +
                                    j +
                                    "][status]",
                                file.status
                            );
                            formData.append(
                                "suppliers[" + i + "][files][" + j + "][name]",
                                file.name || ""
                            );
                            j++;
                        }
                    }
                    i++;
                }
            }
            return formData;
        },
        deleteFile: function(supplierIndex, fileIndex) {
            const tempSuppliers = [...this.suppliers];
            if (
                tempSuppliers[supplierIndex]["files"][fileIndex]["status"] !==
                "new"
            ) {
                tempSuppliers[supplierIndex]["files"][fileIndex] = {
                    ...tempSuppliers[supplierIndex]["files"][fileIndex],
                    status: "deleted"
                };
            } else {
                const tempFiles = this.suppliers[supplierIndex].files.filter(
                    (_, index) => index !== fileIndex
                );
                tempSuppliers[supplierIndex] = {
                    ...tempSuppliers[supplierIndex],
                    files: tempFiles
                };
            }
            this.suppliers = [...tempSuppliers];
        }
    },
    mounted() {
        this.handleGetEntry();
        this.getSuppliers(this.field.suppliers);
    }
};
</script>

<style scoped>
.save-documents {
    margin-top: 1.5rem;
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

.file-name-container {
    width: 80%;
    overflow: hidden;
}

.file-name {
    margin: 0;
}

.btn-delete {
    margin-left: 50px;
    color: #409ade;
    border: none;
}

.btn-delete:focus {
    outline: none !important;
}
</style>
