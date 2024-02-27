import axios from "axios"
import jQuery from 'jquery'

axios.interceptors.response.use(
    response => {
        return response;
    },
    async (err) => {
        const {
            response: { status, data }
        } = err

        if ( status == 500 || status == 400) {
            return Promise.reject(data)
        }
    }
)
class DocumentModel {

	constructor(url) {
		this.base_url = url
	}
	csrfToken() {
		return jQuery('meta[name="csrf-token"]').attr("content")
	}

	async deleteDocument({
		item
	}) {
		return new Promise((resolve, reject) => {
			let fd = new FormData()
		    fd.append('ids[0]', item.id)
		    fd.append('_token', this.csrfToken())
		    let passedData = {
		        url: `${this.base_url}/custom/delete-multiple-documents-admin`,
		        method: 'POST',
		        data: fd
		    }
	      	axios(passedData).then(response => {
	        	resolve()
	      	}).catch(err => {
	      		reject(err)
	      	})	
		})
		
	}
	async uploadDocuments({
		resourceId,
		documents,
	}) {
		return new Promise((resolve, reject) => {
			let fd = new FormData()
	      	fd.append('shipment_id', resourceId)
	      	fd.append('_token', this.csrfToken())
	      	if ( documents.length > 0 ) {
		      	documents.map((finalDocument, key) => {
			        let checkInputs = ['supplier_id', 'type', 'file', 'name']
			        checkInputs.map(ci => {
			            if ( (ci === 'name' || ci === 'file' || ci === 'type') && typeof finalDocument[ci]!=='undefined') {
			              if ( ci === 'type') {
			                fd.append(`document_infos[${key}][${ci}]`, finalDocument[ci].toLowerCase())
			              } else
			                fd.append(`document_infos[${key}][${ci}]`, finalDocument[ci])
			            }
			            if ( ci === 'supplier_id' && typeof finalDocument[ci]!=='undefined') {
			              if (finalDocument.supplier_id.length !== 0) {
			                fd.append(`document_infos[${key}][supplier_id][0]`, finalDocument.supplier_id)
			              }
			            }
			        })
		      	})

		      	let passedData = {
		        	url: `${this.base_url}/custom/upload-multiple-documents-admin`,
		        	method: 'POST',
		        	data: fd
		      	}
		      	axios(passedData).then(response => {
		      		resolve()
		          /*
		          iziToast.success({
		            message: 'Document(s) successfully uploaded. Refreshing the page in 3 seconds',
		            displayMode: 1,
		            position: "bottomRight",
		            timeout: 3000,
		       	 	}) */
		        	//this.$emit('update:show', false)
		      	}).catch(err => {
		      		reject(err)
		         // item.is_loading = false
		      	})
	      	} else {
	      		reject()
	      	}
		})
		
	}
}
export default DocumentModel