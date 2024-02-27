<div class="col-sm-12" id="po-location">
    <h3 class="page-header">Storage Location</h3>

    <!-- Get Storage Location -->
    <div class="col-sm-12" id="po-get-storage-location" >

        <h3>
            Get Storage Location
        </h3>
        <p>
            Get Storage Location for Location endpoint.
            To access their Storage Location details, they have to provide warehouse_id.
            And the endpoint will return all their storage location regarding the given warehouse_id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/storage-loaction</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Storage Location</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
    <pre>

{
    "results": {
        "all": {
            "current_page": 1,
            "data": [
                {
                    "id": 27,
                    "type": "storage",
                    "row": "Test12",
                    "rack": "Test12",
                    "shelf": "Test12",
                    "bin": null,
                    "gate_name": null,
                    "position": null,
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-03-09T20:53:21.000000Z",
                    "updated_at": "2022-03-09T20:53:21.000000Z",
                    "ref_no": [
                        {
                            "ref_no": "123456",
                            "inbound_id": 155
                        }
                    ],
                    "storable_units": [
                        {
                            "id": 16,
                            "warehouse_id": 241,
                            "customer_id": 447,
                            "action": "recieve",
                            "label": "803419",
                            "type": "drum",
                            "dimensions": "{\"l\":\"10\",\"w\":\"5\",\"h\":\"25\",\"uom\":\"cm\"}",
                            "weight": "{\"value\":\"50\",\"unit\":\"LB\"}",
                            "products": null,
                            "deleted_at": null,
                            "created_at": "2022-03-25T22:11:30.000000Z",
                            "updated_at": "2022-03-25T22:11:30.000000Z",
                            "location_id": 27,
                            "is_active": 1
                        }
                    ]
                }, {...}, ...,
            ],
            "first_page_url": "api/warehouse/241/storage-loaction?page=1",
            "from": 1,
            "last_page": 2,
            "last_page_url": "api/warehouse/241/storage-loaction?page=2",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouse/241/storage-loaction?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": "api/warehouse/241/storage-loaction?page=2",
                    "label": "2",
                    "active": false
                },
                {
                    "url": "api/warehouse/241/storage-loaction?page=2",
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "next_page_url": "api/warehouse/241/storage-loaction?page=2",
            "path": "api/warehouse/241/storage-loaction",
            "per_page": 35,
            "prev_page_url": null,
            "to": 35,
            "total": 42
        },
        "empty": {
            "current_page": 1,
            "data": [
                {
                    "id": 24,
                    "type": "storage",
                    "row": "Test9",
                    "rack": "Test9",
                    "shelf": "Test9",
                    "bin": null,
                    "gate_name": null,
                    "position": null,
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-03-09T20:52:53.000000Z",
                    "updated_at": "2022-03-09T20:52:53.000000Z",
                    "ref_no": [],
                    "storable_units": []
                }, {...}, ...,
            ],
            "first_page_url": "api/warehouse/241/storage-loaction?page=1",
            "from": 1,
            "last_page": 1,
            "last_page_url": "api/warehouse/241/storage-loaction?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouse/241/storage-loaction?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": null,
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "next_page_url": null,
            "path": "api/warehouse/241/storage-loaction",
            "per_page": 35,
            "prev_page_url": null,
            "to": 25,
            "total": 25
        },
        "filled": {
            "current_page": 1,
            "data": [
                {
                    "id": 1,
                    "type": "storage",
                    "row": "AISLE01",
                    "rack": "COL01",
                    "shelf": "F01",
                    "bin": null,
                    "gate_name": null,
                    "position": null,
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-01-05T23:17:10.000000Z",
                    "updated_at": "2022-01-28T22:13:52.000000Z",
                    "ref_no": [],
                    "storable_units": [
                        {
                            "id": 1,
                            "warehouse_id": 241,
                            "customer_id": 447,
                            "action": "recieve",
                            "label": null,
                            "type": "pallet",
                            "dimensions": "{\"l\":\"15\",\"w\":\"15\",\"h\":\"15\",\"uom\":\"cm\"}",
                            "weight": "{\"value\":\"15\",\"unit\":\"KG\"}",
                            "products": null,
                            "deleted_at": null,
                            "created_at": "2022-03-01T01:13:55.000000Z",
                            "updated_at": "2022-03-30T18:38:21.000000Z",
                            "location_id": 1,
                            "is_active": 0
                        }
                    ]
                },{...}, ...,
            ],
            "first_page_url": "api/warehouse/241/storage-loaction?page=1",
            "from": 1,
            "last_page": 1,
            "last_page_url": "api/warehouse/241/storage-loaction?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouse/241/storage-loaction?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": null,
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "next_page_url": null,
            "path": "api/warehouse/241/storage-loaction",
            "per_page": 35,
            "prev_page_url": null,
            "to": 17,
            "total": 17
        }
    }
}

    </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get Storage Location -->










    <!-- Gate Location -->
    <div class="col-sm-12" id="po-get-gate-location" >

        <h3>
            Get Gate Location
        </h3>
        <p>
            Get Gate Location for Location endpoint.
            To access their Gate Location details, they have to provide warehouse_id.
            And the endpoint will return all their gate location regarding the given warehouse_id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/gate-location</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Gate Location</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "results": {
        "all": {
            "current_page": 1,
            "data": [
                {
                    "id": 11,
                    "type": "gate",
                    "row": null,
                    "rack": null,
                    "shelf": null,
                    "bin": null,
                    "gate_name": "TEST IN-A 3",
                    "position": "1003",
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-01-07T18:32:18.000000Z",
                    "updated_at": "2022-03-04T01:01:02.000000Z",
                    "ref_no": [
                        {
                            "ref_no": "123445",
                            "inbound_id": 72
                        }
                    ],
                    "storable_units": [
                        {
                            "id": 145,
                            "warehouse_id": 241,
                            "customer_id": 447,
                            "action": "build",
                            "label": "562022",
                            "type": "pallet",
                            "dimensions": "{\"l\":0,\"w\":0,\"h\":0,\"uom\":\"cm\"}",
                            "weight": "{\"value\":\"\",\"unit\":\"cm\"}",
                            "products": null,
                            "deleted_at": null,
                            "created_at": "2022-04-06T22:24:26.000000Z",
                            "updated_at": "2022-04-06T22:24:26.000000Z",
                            "location_id": 11,
                            "is_active": 1
                        }
                    ]
                }, {...}, ...,
            ],
            "first_page_url": "api/warehouse/241/gate-location?page=1",
            "from": 1,
            "last_page": 1,
            "last_page_url": "api/warehouse/241/gate-location?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouse/241/gate-location?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": null,
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "next_page_url": null,
            "path": "api/warehouse/241/gate-location",
            "per_page": 35,
            "prev_page_url": null,
            "to": 3,
            "total": 3
        },
        "empty": {
            "current_page": 1,
            "data": [],
            "first_page_url": "api/warehouse/241/gate-location?page=1",
            "from": null,
            "last_page": 1,
            "last_page_url": "api/warehouse/241/gate-location?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouse/241/gate-location?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": null,
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "next_page_url": null,
            "path": "api/warehouse/241/gate-location",
            "per_page": 35,
            "prev_page_url": null,
            "to": null,
            "total": 0
        },
        "filled": {
            "current_page": 1,
            "data": [
                {
                    "id": 11,
                    "type": "gate",
                    "row": null,
                    "rack": null,
                    "shelf": null,
                    "bin": null,
                    "gate_name": "TEST IN-A 3",
                    "position": "1003",
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-01-07T18:32:18.000000Z",
                    "updated_at": "2022-03-04T01:01:02.000000Z",
                    "ref_no": [
                        {
                            "ref_no": "123445",
                            "inbound_id": 72
                        }
                    ],
                    "storable_units": [
                        {
                            "id": 145,
                            "warehouse_id": 241,
                            "customer_id": 447,
                            "action": "build",
                            "label": "562022",
                            "type": "pallet",
                            "dimensions": "{\"l\":0,\"w\":0,\"h\":0,\"uom\":\"cm\"}",
                            "weight": "{\"value\":\"\",\"unit\":\"cm\"}",
                            "products": null,
                            "deleted_at": null,
                            "created_at": "2022-04-06T22:24:26.000000Z",
                            "updated_at": "2022-04-06T22:24:26.000000Z",
                            "location_id": 11,
                            "is_active": 1
                        }
                    ]
                }
            ],
            "first_page_url": "api/warehouse/241/gate-location?page=1",
            "from": 1,
            "last_page": 1,
            "last_page_url": "api/warehouse/241/gate-location?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouse/241/gate-location?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": null,
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "next_page_url": null,
            "path": "api/warehouse/241/gate-location",
            "per_page": 35,
            "prev_page_url": null,
            "to": 3,
            "total": 3
        }
    }
}
    </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get Gate Location -->












    <!-- Get All Location -->
    <div class="col-sm-12" id="po-get-all-location" >

        <h3>
            Get All Location
        </h3>
        <p>
            Get All Location for Location endpoint.
            To access their location details, they have to provide warehouse_id.
            And the endpoint will return all their location regarding the given warehouse_id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/locations</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get All Location</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "results": [
        {
            "id": 1,
            "type": "storage",
            "row": "AISLE01",
            "rack": "COL01",
            "shelf": "F01",
            "bin": null,
            "gate_name": null,
            "position": null,
            "warehouse_id": 241,
            "customer_id": 447,
            "deleted_at": null,
            "created_at": "2022-01-05T23:17:10.000000Z",
            "updated_at": "2022-01-28T22:13:52.000000Z",
            "ref_no": [],
            "storable_units": [
                {
                    "id": 1,
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "action": "recieve",
                    "label": null,
                    "type": "pallet",
                    "dimensions": "{\"l\":\"15\",\"w\":\"15\",\"h\":\"15\",\"uom\":\"cm\"}",
                    "weight": "{\"value\":\"15\",\"unit\":\"KG\"}",
                    "products": null,
                    "deleted_at": null,
                    "created_at": "2022-03-01T01:13:55.000000Z",
                    "updated_at": "2022-03-30T18:38:21.000000Z",
                    "location_id": 1,
                    "is_active": 0
                }
            ]
        }, {...}, ...,
    ]
}


    </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get All Location  -->










    <!-- Get empy Location -->
    <div class="col-sm-12" id="po-get-empy-location" >

        <h3>
            Get Empy Location
        </h3>
        <p>
            Get empy Location for Location endpoint.
            To access their empy location details, they have to provide warehouse_id.
            And the endpoint will return all their empy location regarding the given warehouse_id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/empty-locations</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Empty Location</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "results": [
        {
            "id": 24,
            "type": "storage",
            "row": "Test9",
            "rack": "Test9",
            "shelf": "Test9",
            "bin": null,
            "gate_name": null,
            "position": null,
            "warehouse_id": 241,
            "customer_id": 447,
            "deleted_at": null,
            "created_at": "2022-03-09T20:52:53.000000Z",
            "updated_at": "2022-03-09T20:52:53.000000Z",
            "ref_no": [],
            "storable_units": []
        }, {...}, ...,
    ]
}

    </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get Empty Location  -->










    <!-- Get Place Inbound Location -->
    <div class="col-sm-12" id="po-get-place-inbound-location" >

        <h3>
            Get Place Inbound
        </h3>
        <p>
            Get Place Inbound for Location endpoint.
            To access their Place location details, they have to provide warehouse_id.
            And the endpoint will return all their place location regarding the given warehouse_id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/inbound-place-locations</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Place Inbound</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "results": [
        {
            "id": 24,
            "type": "storage",
            "row": "Test9",
            "rack": "Test9",
            "shelf": "Test9",
            "bin": null,
            "gate_name": null,
            "position": null,
            "warehouse_id": 241,
            "customer_id": 447,
            "deleted_at": null,
            "created_at": "2022-03-09T20:52:53.000000Z",
            "updated_at": "2022-03-09T20:52:53.000000Z",
            "storable_unit_count": 0,
            "ref_no": [],
            "storable_units": []
        }, {...}, ...,
        {
            "id": 27,
            "type": "storage",
            "row": "Test12",
            "rack": "Test12",
            "shelf": "Test12",
            "bin": null,
            "gate_name": null,
            "position": null,
            "warehouse_id": 241,
            "customer_id": 447,
            "deleted_at": null,
            "created_at": "2022-03-09T20:53:21.000000Z",
            "updated_at": "2022-03-09T20:53:21.000000Z",
            "storable_unit_count": 1,
            "ref_no": [
                {
                    "ref_no": "123456",
                    "inbound_id": 155
                }
            ],
            "storable_units": [
                {
                    "id": 16,
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "action": "recieve",
                    "label": "803419",
                    "type": "drum",
                    "dimensions": "{\"l\":\"10\",\"w\":\"5\",\"h\":\"25\",\"uom\":\"cm\"}",
                    "weight": "{\"value\":\"50\",\"unit\":\"LB\"}",
                    "products": null,
                    "deleted_at": null,
                    "created_at": "2022-03-25T22:11:30.000000Z",
                    "updated_at": "2022-03-25T22:11:30.000000Z",
                    "location_id": 27,
                    "is_active": 1
                }
            ]
        },{...},...,
    ]
}

    </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get Place Inbound Location  -->













    <!-- Get All Storage Location -->
    <div class="col-sm-12" id="po-get-all-location" >

        <h3>
            Get All Storage
        </h3>
        <p>
            Get All Storage for Location endpoint.
            To access their all storage location details, they have to provide warehouse_id.
            And the endpoint will return all their storage location regarding the given warehouse_id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/storage-location/all</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get All Storage</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "type": "storage",
                "row": "AISLE01",
                "rack": "COL01",
                "shelf": "F01",
                "bin": null,
                "gate_name": null,
                "position": null,
                "warehouse_id": 241,
                "customer_id": 447,
                "deleted_at": null,
                "created_at": "2022-01-05T23:17:10.000000Z",
                "updated_at": "2022-01-28T22:13:52.000000Z",
                "ref_no": [],
                "storable_units": [
                    {
                        "id": 1,
                        "warehouse_id": 241,
                        "customer_id": 447,
                        "action": "recieve",
                        "label": null,
                        "type": "pallet",
                        "dimensions": "{\"l\":\"15\",\"w\":\"15\",\"h\":\"15\",\"uom\":\"cm\"}",
                        "weight": "{\"value\":\"15\",\"unit\":\"KG\"}",
                        "products": null,
                        "deleted_at": null,
                        "created_at": "2022-03-01T01:13:55.000000Z",
                        "updated_at": "2022-03-30T18:38:21.000000Z",
                        "location_id": 1,
                        "is_active": 0
                    }
                ]
            },{...},...,
        ],
        "first_page_url": "api/warehouse/241/storage-location/all?page=1",
        "from": 1,
        "last_page": 2,
        "last_page_url": "api/warehouse/241/storage-location/all?page=2",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/241/storage-location/all?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": "api/warehouse/241/storage-location/all?page=2",
                "label": "2",
                "active": false
            },
            {
                "url": "api/warehouse/241/storage-location/all?page=2",
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": "api/warehouse/241/storage-location/all?page=2",
        "path": "api/warehouse/241/storage-location/all",
        "per_page": 35,
        "prev_page_url": null,
        "to": 35,
        "total": 42
    }
}

    </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get All Storage Location  -->






    <!-- Get Empty Storage Location -->
    <div class="col-sm-12" id="po-get-empty-storage-location" >

        <h3>
            Get Empty Storage
        </h3>
        <p>
            Get Empty Storage Location for Location endpoint.
            To access their get empty storage location details, they have to provide warehouse_id.
            And the endpoint will return all their empty storage location regarding the given warehouse_id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/storage-location/empty</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Empty Storage</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
<pre>


{
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 24,
                "type": "storage",
                "row": "Test9",
                "rack": "Test9",
                "shelf": "Test9",
                "bin": null,
                "gate_name": null,
                "position": null,
                "warehouse_id": 241,
                "customer_id": 447,
                "deleted_at": null,
                "created_at": "2022-03-09T20:52:53.000000Z",
                "updated_at": "2022-03-09T20:52:53.000000Z",
                "ref_no": [],
                "storable_units": []
            },{...},...
        ],
        "first_page_url": "api/warehouse/241/storage-location/empty?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/241/storage-location/empty?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/241/storage-location/empty?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "api/warehouse/241/storage-location/empty",
        "per_page": 35,
        "prev_page_url": null,
        "to": 25,
        "total": 25
    }
}

</pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get Empty Storage Location  -->

























    <!-- Get Filled Storage Location -->
    <div class="col-sm-12" id="po-get-filled-storage-location" >

        <h3>
            Get Filled Storage
        </h3>
        <p>
            Get Filled Storage Location for Location endpoint.
            To access their get filled storage location details, they have to provide warehouse_id.
            And the endpoint will return all their filled storage location regarding the given warehouse_id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/storage-location/filled</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Filled Storage</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
<pre>
{
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "type": "storage",
                "row": "AISLE01",
                "rack": "COL01",
                "shelf": "F01",
                "bin": null,
                "gate_name": null,
                "position": null,
                "warehouse_id": 241,
                "customer_id": 447,
                "deleted_at": null,
                "created_at": "2022-01-05T23:17:10.000000Z",
                "updated_at": "2022-01-28T22:13:52.000000Z",
                "ref_no": [],
                "storable_units": [
                    {
                        "id": 1,
                        "warehouse_id": 241,
                        "customer_id": 447,
                        "action": "recieve",
                        "label": null,
                        "type": "pallet",
                        "dimensions": "{\"l\":\"15\",\"w\":\"15\",\"h\":\"15\",\"uom\":\"cm\"}",
                        "weight": "{\"value\":\"15\",\"unit\":\"KG\"}",
                        "products": null,
                        "deleted_at": null,
                        "created_at": "2022-03-01T01:13:55.000000Z",
                        "updated_at": "2022-03-30T18:38:21.000000Z",
                        "location_id": 1,
                        "is_active": 0
                    }
                ]
            },{...},...,
        ],
        "first_page_url": "api/warehouse/241/storage-location/filled?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/241/storage-location/filled?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/241/storage-location/filled?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "api/warehouse/241/storage-location/filled",
        "per_page": 35,
        "prev_page_url": null,
        "to": 17,
        "total": 17
    }
}
</pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get Filled Storage Location  -->











    <!-- Get All Gates Location Location -->
    <div class="col-sm-12" id="po-get-all-gates-location" >

        <h3>
            Get All Gates Location
        </h3>
        <p>
            Get All Gates Location for Location endpoint.
            To access their get all gates location details, they have to provide warehouse_id.
            And the endpoint will return all their all gates location regarding the given warehouse_id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/gate-location/all</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get All Gates Location</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>

{
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 2,
                "type": "gate",
                "row": null,
                "rack": null,
                "shelf": null,
                "bin": null,
                "gate_name": "TEST IN-A 2",
                "position": "1002",
                "warehouse_id": 241,
                "customer_id": 447,
                "deleted_at": null,
                "created_at": "2022-01-05T23:27:46.000000Z",
                "updated_at": "2022-03-04T01:00:49.000000Z",
                "ref_no": [
                    {
                        "ref_no": "JGHJQKX8",
                        "inbound_id": 71
                    }
                ],
                "storable_units": [
                    {
                        "id": 6,
                        "warehouse_id": 241,
                        "customer_id": 447,
                        "action": "recieve",
                        "label": "778293",
                        "type": "pallet",
                        "dimensions": "{\"l\":\"10\",\"w\":\"12\",\"h\":\"12\",\"uom\":\"cm\"}",
                        "weight": "{\"value\":\"12.5\",\"unit\":\"KG\"}",
                        "products": null,
                        "deleted_at": null,
                        "created_at": "2022-03-01T01:48:57.000000Z",
                        "updated_at": "2022-03-01T01:48:57.000000Z",
                        "location_id": 2,
                        "is_active": 1
                    }
                ]
            },{...},...,
        ],
        "first_page_url": "api/warehouse/241/gate-location/all?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/241/gate-location/all?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/241/gate-location/all?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "api/warehouse/241/gate-location/all",
        "per_page": 35,
        "prev_page_url": null,
        "to": 3,
        "total": 3
    }
}
</pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get All Gates Location  -->








    <!-- Get Empty Gates Location Location -->
    <div class="col-sm-12" id="po-get-empty-gates-location" >

        <h3>
            Get Empty Gates Location
        </h3>
        <p>
            Get Empty Gates Location for Location endpoint.
            To access their get empty gates location details, they have to provide warehouse_id.
            And the endpoint will return all their empty gates location regarding the given warehouse_id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/gate-location/empty</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Empty Gates Location</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>

            {
    "results": {
        "current_page": 1,
        "data": [],
        "first_page_url": "api/warehouse/265/gate-location/empty?page=1",
        "from": null,
        "last_page": 1,
        "last_page_url": "api/warehouse/265/gate-location/empty?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/265/gate-location/empty?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "api/warehouse/265/gate-location/empty",
        "per_page": 35,
        "prev_page_url": null,
        "to": null,
        "total": 0
    }
}
</pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get Empty Gates Location  -->










    <!-- Get Filled Gates Location -->
    <div class="col-sm-12" id="po-get-filled-gates-location" >

        <h3>
            Get Filled Gates Location
        </h3>
        <p>
            Get Filled Gates Location for Location endpoint.
            To access their get filled gates location details, they have to provide warehouse_id.
            And the endpoint will return all their filled gates location regarding the given warehouse_id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/gate-location/filled</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Filled Gates Location</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 11,
                "type": "gate",
                "row": null,
                "rack": null,
                "shelf": null,
                "bin": null,
                "gate_name": "TEST IN-A 3",
                "position": "1003",
                "warehouse_id": 241,
                "customer_id": 447,
                "deleted_at": null,
                "created_at": "2022-01-07T18:32:18.000000Z",
                "updated_at": "2022-03-04T01:01:02.000000Z",
                "ref_no": [
                    {
                        "ref_no": "123445",
                        "inbound_id": 72
                    }
                ],
                "storable_units": [
                    {
                        "id": 145,
                        "warehouse_id": 241,
                        "customer_id": 447,
                        "action": "build",
                        "label": "562022",
                        "type": "pallet",
                        "dimensions": "{\"l\":0,\"w\":0,\"h\":0,\"uom\":\"cm\"}",
                        "weight": "{\"value\":\"\",\"unit\":\"cm\"}",
                        "products": null,
                        "deleted_at": null,
                        "created_at": "2022-04-06T22:24:26.000000Z",
                        "updated_at": "2022-04-06T22:24:26.000000Z",
                        "location_id": 11,
                        "is_active": 1
                    }
                ]
            }
        ],
        "first_page_url": "api/warehouse/241/gate-location/filled?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/241/gate-location/filled?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/241/gate-location/filled?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "api/warehouse/241/gate-location/filled",
        "per_page": 35,
        "prev_page_url": null,
        "to": 3,
        "total": 3
    }
}
</pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get Filled Gates Location  -->









    <!-- Get List Location -->
    <div class="col-sm-12" id="po-get-list-location" >

        <h3>
            Get List Location
        </h3>
        <p>
            Get List for Location endpoint.
            To access their get list location details, they have to provide warehouse_id.
            And the endpoint will return all their list location regarding the given warehouse_id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/locations</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get List Location</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
            {
    "storage_locations": {
        "all": {
            "current_page": 1,
            "data": [
                {
                    "id": 27,
                    "type": "storage",
                    "row": "Test12",
                    "rack": "Test12",
                    "shelf": "Test12",
                    "bin": null,
                    "gate_name": null,
                    "position": null,
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-03-09T20:53:21.000000Z",
                    "updated_at": "2022-03-09T20:53:21.000000Z",
                    "ref_no": [
                        {
                            "ref_no": "123456",
                            "inbound_id": 155
                        }
                    ],
                    "storable_units": [
                        {
                            "id": 16,
                            "warehouse_id": 241,
                            "customer_id": 447,
                            "action": "recieve",
                            "label": "803419",
                            "type": "drum",
                            "dimensions": "{\"l\":\"10\",\"w\":\"5\",\"h\":\"25\",\"uom\":\"cm\"}",
                            "weight": "{\"value\":\"50\",\"unit\":\"LB\"}",
                            "products": null,
                            "deleted_at": null,
                            "created_at": "2022-03-25T22:11:30.000000Z",
                            "updated_at": "2022-03-25T22:11:30.000000Z",
                            "location_id": 27,
                            "is_active": 1
                        }
                    ]
                }, {...},...
            ],
            "first_page_url": "api/warehouses/241/locations?page=1",
            "from": 1,
            "last_page": 2,
            "last_page_url": "api/warehouses/241/locations?page=2",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouses/241/locations?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": "api/warehouses/241/locations?page=2",
                    "label": "2",
                    "active": false
                },
                {
                    "url": "api/warehouses/241/locations?page=2",
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "next_page_url": "api/warehouses/241/locations?page=2",
            "path": "api/warehouses/241/locations",
            "per_page": 35,
            "prev_page_url": null,
            "to": 35,
            "total": 42
        },
        "empty": {
            "current_page": 1,
            "data": [
                {
                    "id": 24,
                    "type": "storage",
                    "row": "Test9",
                    "rack": "Test9",
                    "shelf": "Test9",
                    "bin": null,
                    "gate_name": null,
                    "position": null,
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-03-09T20:52:53.000000Z",
                    "updated_at": "2022-03-09T20:52:53.000000Z",
                    "ref_no": [],
                    "storable_units": []
                },{...},...,
            ],
            "first_page_url": "api/warehouses/241/locations?page=1",
            "from": 1,
            "last_page": 1,
            "last_page_url": "api/warehouses/241/locations?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouses/241/locations?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": null,
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "next_page_url": null,
            "path": "api/warehouses/241/locations",
            "per_page": 35,
            "prev_page_url": null,
            "to": 25,
            "total": 25
        },
        "filled": {
            "current_page": 1,
            "data": [
                {
                    "id": 27,
                    "type": "storage",
                    "row": "Test12",
                    "rack": "Test12",
                    "shelf": "Test12",
                    "bin": null,
                    "gate_name": null,
                    "position": null,
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-03-09T20:53:21.000000Z",
                    "updated_at": "2022-03-09T20:53:21.000000Z",
                    "ref_no": [
                        {
                            "ref_no": "123456",
                            "inbound_id": 155
                        }
                    ],
                    "storable_units": [
                        {
                            "id": 16,
                            "warehouse_id": 241,
                            "customer_id": 447,
                            "action": "recieve",
                            "label": "803419",
                            "type": "drum",
                            "dimensions": "{\"l\":\"10\",\"w\":\"5\",\"h\":\"25\",\"uom\":\"cm\"}",
                            "weight": "{\"value\":\"50\",\"unit\":\"LB\"}",
                            "products": null,
                            "deleted_at": null,
                            "created_at": "2022-03-25T22:11:30.000000Z",
                            "updated_at": "2022-03-25T22:11:30.000000Z",
                            "location_id": 27,
                            "is_active": 1
                        }
                    ]
                },{...},...,
            ],
            "first_page_url": "api/warehouses/241/locations?page=1",
            "from": 1,
            "last_page": 1,
            "last_page_url": "api/warehouses/241/locations?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouses/241/locations?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": null,
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "next_page_url": null,
            "path": "api/warehouses/241/locations",
            "per_page": 35,
            "prev_page_url": null,
            "to": 17,
            "total": 17
        }
    },
    "gate_locations": {
        "all": {
            "current_page": 1,
            "data": [
                {
                    "id": 11,
                    "type": "gate",
                    "row": null,
                    "rack": null,
                    "shelf": null,
                    "bin": null,
                    "gate_name": "TEST IN-A 3",
                    "position": "1003",
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-01-07T18:32:18.000000Z",
                    "updated_at": "2022-03-04T01:01:02.000000Z",
                    "ref_no": [
                        {
                            "ref_no": "123445",
                            "inbound_id": 72
                        }
                    ],
                    "storable_units": [
                        {
                            "id": 145,
                            "warehouse_id": 241,
                            "customer_id": 447,
                            "action": "build",
                            "label": "562022",
                            "type": "pallet",
                            "dimensions": "{\"l\":0,\"w\":0,\"h\":0,\"uom\":\"cm\"}",
                            "weight": "{\"value\":\"\",\"unit\":\"cm\"}",
                            "products": null,
                            "deleted_at": null,
                            "created_at": "2022-04-06T22:24:26.000000Z",
                            "updated_at": "2022-04-06T22:24:26.000000Z",
                            "location_id": 11,
                            "is_active": 1
                        }
                    ]
                }, {...},...,
            ],
            "first_page_url": "api/warehouses/241/locations?page=1",
            "from": 1,
            "last_page": 1,
            "last_page_url": "api/warehouses/241/locations?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouses/241/locations?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": null,
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "next_page_url": null,
            "path": "api/warehouses/241/locations",
            "per_page": 35,
            "prev_page_url": null,
            "to": 3,
            "total": 3
        },
        "empty": {
            "current_page": 1,
            "data": [],
            "first_page_url": "api/warehouses/241/locations?page=1",
            "from": null,
            "last_page": 1,
            "last_page_url": "api/warehouses/241/locations?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouses/241/locations?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": null,
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "next_page_url": null,
            "path": "api/warehouses/241/locations",
            "per_page": 35,
            "prev_page_url": null,
            "to": null,
            "total": 0
        },
        "filled": {
            "current_page": 1,
            "data": [
                {
                    "id": 2,
                    "type": "gate",
                    "row": null,
                    "rack": null,
                    "shelf": null,
                    "bin": null,
                    "gate_name": "TEST IN-A 2",
                    "position": "1002",
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-01-05T23:27:46.000000Z",
                    "updated_at": "2022-03-04T01:00:49.000000Z",
                    "ref_no": [
                        {
                            "ref_no": "JGHJQKX8",
                            "inbound_id": 71
                        }
                    ],
                    "storable_units": [
                        {
                            "id": 6,
                            "warehouse_id": 241,
                            "customer_id": 447,
                            "action": "recieve",
                            "label": "778293",
                            "type": "pallet",
                            "dimensions": "{\"l\":\"10\",\"w\":\"12\",\"h\":\"12\",\"uom\":\"cm\"}",
                            "weight": "{\"value\":\"12.5\",\"unit\":\"KG\"}",
                            "products": null,
                            "deleted_at": null,
                            "created_at": "2022-03-01T01:48:57.000000Z",
                            "updated_at": "2022-03-01T01:48:57.000000Z",
                            "location_id": 2,
                            "is_active": 1
                        }
                    ]
                }, {...},...,
            ],
            "first_page_url": "api/warehouses/241/locations?page=1",
            "from": 1,
            "last_page": 1,
            "last_page_url": "hapi/warehouses/241/locations?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouses/241/locations?page=1",
                    "label": "1",
                    "active": true
                },
                {
                    "url": null,
                    "label": "Next &raquo;",
                    "active": false
                }
            ],
            "next_page_url": null,
            "path": "api/warehouses/241/locations",
            "per_page": 35,
            "prev_page_url": null,
            "to": 3,
            "total": 3
        }
    }
}
</pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get List Location  -->




















    <!-- Get Specific Location -->
    <div class="col-sm-12" id="po-get-specific-location" >

        <h3>
            Get Specific Location
        </h3>
        <p>
            Get Specific Location for Location endpoint.
            To access their get specific location details, they have to provide warehouse_id and id.
            And the endpoint will return all their specific location regarding the given warehouse_id and id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/locations/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code>, <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Specific Location</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "data": {
        "id": 1,
        "type": "storage",
        "row": "AISLE01",
        "rack": "COL01",
        "shelf": "F01",
        "bin": null,
        "gate_name": null,
        "position": null,
        "warehouse_id": 241,
        "customer_id": 447,
        "deleted_at": null,
        "created_at": "2022-01-05T23:17:10.000000Z",
        "updated_at": "2022-01-28T22:13:52.000000Z",
        "ref_no": [],
        "storable_units": [
            {
                "id": 1,
                "warehouse_id": 241,
                "customer_id": 447,
                "action": "recieve",
                "label": null,
                "type": "pallet",
                "dimensions": "{\"l\":\"15\",\"w\":\"15\",\"h\":\"15\",\"uom\":\"cm\"}",
                "weight": "{\"value\":\"15\",\"unit\":\"KG\"}",
                "products": null,
                "deleted_at": null,
                "created_at": "2022-03-01T01:13:55.000000Z",
                "updated_at": "2022-03-30T18:38:21.000000Z",
                "location_id": 1,
                "is_active": 0
            }
        ]
    }
}
</pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get Specific Location  -->













    <!-- Create Location -->
    <div class="col-sm-12" id="po-create-location" >

        <h3>
            Create Location
        </h3>
        <p>
            Create location endpoint.
            To create the location we have to input the ff. data: warehouse_id,
            customer_id, type, row, rack, shelf, bin, gate_name and position.
            And the endpoint will return json value once created.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/locations/create</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b> <code>warehouse_id</code>, <code>customer_id</code>,
            <code>type</code>,
            <code>row</code>,
            <code>rack</code>,
            <code>shelf</code>,
            <code>bin</code>,
            <code>gate_name</code>,
            <code>position</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Specific Location</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "warehouse_id" : "{warehouse_id}",
    "customer_id" : "{customer_id}",
    "type" : "{type}",
    "row" : "{row}",
    "rack" : "{rack}",
    "shelf" : "{shelf}",
    "bin" : "{bin}",
    "gate_name" : "{gate_name}",
    "position" : "{position}"
}
       </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Successfully created new location.",
    "data": {
        "warehouse_id": "241",
        "customer_id": "447",
        "type": "soluta",
        "row": "voluptas",
        "rack": "quaerat",
        "shelf": "consequatur",
        "bin": "est",
        "gate_name": "recusandae",
        "position": "ut",
        "updated_at": "2022-08-09T16:28:04.000000Z",
        "created_at": "2022-08-09T16:28:04.000000Z",
        "id": 248,
        "ref_no": []
    }
}
</pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Create Location -->





    <!-- Update Location -->
    <div class="col-sm-12" id="po-update-location" >

        <h3>
            Update Location
        </h3>
        <p>
            Update location endpoint.
            To update the location we have to input the ff. data: id, warehouse_id,
            customer_id, type, row, rack, shelf, bin, gate_name and position.
            And the endpoint will return json value once updated.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/locations/create</code> <br>
            <b>Request Type: &nbsp;</b> <code>PUT</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b> <code>id</code>,
            <code>warehouse_id</code>,
            <code>customer_id</code>,
            <code>type</code>,
            <code>row</code>,
            <code>rack</code>,
            <code>shelf</code>,
            <code>bin</code>,
            <code>gate_name</code>,
            <code>position</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Specific Location</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "id" : "{id}",
    "warehouse_id" : "{warehouse_id}",
    "customer_id" : "{customer_id}",
    "type" : "{type}",
    "row" : "{row}",
    "rack" : "{rack}",
    "shelf" : "{shelf}",
    "bin" : "{bin}",
    "gate_name" : "{gate_name}",
    "position" : "{position}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Successfully updated the location.",
    "data": {
        "warehouse_id": "241",
        "customer_id": "447",
        "type": "nostrum",
        "row": "qui",
        "rack": "nobis",
        "shelf": "ex",
        "bin": "temporibus",
        "gate_name": "asperiores",
        "position": "voluptatem"
    }
}

</pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Update Location -->




    <!-- Delete Location -->
    <div class="col-sm-12" id="po-delete-location" >

        <h3>
            Delete Location
        </h3>
        <p>
            Destroy or delete for location endpoint.
            To delete the location we should have valid warehouse_id and id.
            And the endpoint will destroy or delete the data.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/locations/delete/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>DELETE</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b> <code>id</code>,
            <code>warehouse_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Specific Location</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "id" : "{id}",
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Location Deleted."
}
</pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Delete Location -->


</div> <!-- end Location -->
