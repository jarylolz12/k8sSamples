import axios from 'axios'
class ShipmentDocumentModel {
    
    base_url = ''
    constructor(url) {
        this.base_url = url
    }

    deleteDocuments(payload) {

        let fd = new FormData()
        payload.map((p, key) => {
            fd.append(`ids[${key}]`, p)
        })

        let passedData = {
            url: `${this.base_url}/v2/shipment/delete-multiple-documents`,
            method: 'POST',
            data: fd
        }
        //s
        return new Promise((resolve, reject) => {
            function proceed() {
                axios(passedData).then(response => {
                    resolve(response)
                }).catch(err => {
                    reject(err)
                })
            }
            proceed()
        })
        //e

    }
    ucFirst(str) {
        let pieces = str.split(" ")
        for ( let i = 0; i < pieces.length; i++ ) {
            let j = pieces[i].charAt(0).toUpperCase()
            pieces[i] = j + pieces[i].substr(1)
        }
        return pieces.join(" ")

    }
    updateNameType(payload) {
        
        let {
            document_id,
            supplier_ids,
            type,
            name
        } = payload

        let fd = new FormData()
        fd.append('document_id', document_id)
        fd.append('type', type.toLowerCase())
        fd.append('name', name)
        
        if (supplier_ids.length !== 0) {
            fd.append(`supplier_ids[0]`, supplier_ids.id)
        }
        // supplier_ids.map((supplier, key) => {
        //     fd.append(`supplier_ids[${key}]`, supplier.id)    
        // })
        
        let passedData = {
            url: `${this.base_url}/v2/shipment/update-name-type`,
            method: 'POST',
            data: fd
        }

        return new Promise((resolve, reject) => {
            function proceed() {
                axios(passedData).then(response => {
                    resolve(response)
                }).catch(err => {
                    reject(err)
                })
            }
            proceed()
        })
    }
    submitDocuments(params) {
        let {
            shipment_id,
            document_ids,
            cancelToken
        } = params

        let fd = new FormData()

        fd.append('shipment_id', shipment_id)

        document_ids.map((d, key) => {
            fd.append(`document_ids[${key}]`, d)
        })

        let passedData = {
            url: `${this.base_url}/v2/shipment/submit-multiple-documents`,
            method: 'POST',
            data: fd,
            cancelToken
        }
        return new Promise((resolve, reject) => {
            function proceed() {
                axios(passedData).then(response => {
                    resolve(response)
                }).catch(err => {
                    reject(err)
                })
            }
            proceed()
        })

    }
    uploadDocuments(params) {

        let {
            documents,
            shipment_id
        } = params

        let fd = new FormData()
        fd.append('shipment_id', shipment_id)
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
                        // for single supplier id
                        fd.append(`document_infos[${key}][supplier_id][0]`, finalDocument.supplier_id.id)
                    }

                    // for multiple supplier id
                    // finalDocument.supplier_id.map((s, keySecond) => {
                    //     fd.append(`document_infos[${key}][supplier_id][${keySecond}]`,s.id)
                    // })
                }
                //fd.append(`document_infos[${key}][supplier_id]`, finalDocument.supplier_id.id)
            })
            
        })
        let passedData = {
            url: `${this.base_url}/v2/shipment/upload-multiple-documents`,
            method: 'POST',
            data: fd
        }
        return new Promise((resolve, reject) => {
            function proceed() {
                axios(passedData).then(response => {
                    resolve(response)
                }).catch(err => {
                    if ( typeof err.errors !=='undefined')
                        reject(err.errors)
                    else
                        reject(err)
                })
            }
            proceed()
        })  
    }
    getShipmentDocuments(params) {
        let {
            shipment_id,
            page
        } = params

        let passedData = {
            url: `${this.base_url}/v2/shipment/documents?shipment_id=${shipment_id}&page=${page}`,
            method: 'GET',
        }

        let context = this
        return new Promise((resolve, reject) => {
            function proceed() {
                axios(passedData).then(response => {
                    if (typeof response.data!=='undefined' && typeof response.data.results!=='undefined') {
                        
                        let final_data = response.data.results.data
                        
                        final_data.map((val, key) => {
                            //final_data[key].supplier = val.supplier.company_name
                            final_data[key].url = val.path
                            final_data[key].type = context.ucFirst(val.type)
                        })
                        
                        resolve({
                            items: final_data,
                            pageOptions: response.data.results
                        })
                    } else {
                        reject(response)
                    }
                }).catch(err => {
                    reject(err)
                })
            }
            proceed()
        })
    }
    findById(id) {
        let passedData = {
            url: `${this.base_url}/shipment/document/${id}`,
            method: 'GET',
        }

        return new Promise((resolve, reject) => {
            function proceed() {
                axios(passedData).then(response => {
                    
                    if (typeof response.data!=='undefined') {
                        resolve(response)
                    } else {
                        reject(response)
                    }
                })
                .catch( err => {
                    reject(err)
                })  
            }
            proceed()
            
        })
        
    }
}
export default ShipmentDocumentModel