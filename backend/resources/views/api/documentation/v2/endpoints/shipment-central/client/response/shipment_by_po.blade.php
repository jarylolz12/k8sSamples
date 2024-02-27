



<div style="margin-bottom: 10px">
    <div>
        <h4 style="display: inline-block">Responses</h4>
    </div>
    <div>
        {{--<div class="badge-success">--}}
        {{--<span class="">200</span>--}}
        {{--<span><p>Success</p></span>--}}
        {{--</div>--}}

        <table class="table-bordered">
            <thead>
            <tr>
                <th colspan="2"><span class="badge-success">200 collection of Shipments By PO</span></th>
            </tr>
            <tr>
                <th>RESPONSE SCHEMA: </th>
                <th>application/json</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td data-toggle="collapse"
                    data-target="#shipment_by_po_data"
                    aria-expanded="false"
                    aria-controls="shipment_by_po_data"
                    style="cursor: pointer;  "


                    class="glyphicon1 icn-shipment_by_po_data"
                    rel="icn-shipment_by_po_data"
                >
                    <span class="icn-shipment_by_po_data">
                       data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                    </span>
                </td>
                <td>object</td>
            </tr>

            <tr class="endpoint_ collapse" id="shipment_by_po_data">
                <td colspan="2">
                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                        <table class="table-bordered">
                            <thead></thead>
                            <tbody>
                            <tr>
                                <td>id</td>
                                <td><i>Integer</i> Unique identifier for the Shipments By PO, autoincrement</td>
                            </tr>
                            <tr>
                                <td>shifl_ref</td>
                                <td><i>String</i> Default: <code>NULL</code>, The shifl reference</td>
                            </tr>
                            <tr>
                                <td>customer</td>
                                <td><i>String</i> Default: <code>NULL</code>, Customer name </td>
                            </tr>
                            <tr>
                                <td>mbl_num</td>
                                <td><i>String</i> Default: <code>NULL</code>, Mobile number</td>
                            </tr>
                            <tr>
                                <td>is_tracking_shipment</td>
                                <td><i>Boolean</i> Default: <code>NULL</code>, Tracking shipment</td>
                            </tr>
                            <tr>
                                <td>vessel</td>
                                <td><i>Integer</i> Default: <code>NULL</code>, Vessel</td>
                            </tr>
                            <tr>
                                <td>booking_confirmed</td>
                                <td><i>boolean</i> Default: <code>NULL</code>, true or false</td>
                            </tr>
                            <tr>
                                <td>booking_confirmed_at</td>
                                <td><i>Date</i> Default: <code>NULL</code>, Booking confirmed date</td>
                            </tr>
                            <tr>

                                <td data-toggle="collapse"
                                    data-target="#shipment_by_po_schedules"
                                    aria-expanded="false"
                                    aria-controls="shipment_by_po_schedules"
                                    style="cursor: pointer;  "
                                    class="glyphicon1 icn-shipment_by_po_schedules"
                                    rel="icn-shipment_by_po_schedules"
                                >
                                    <span class="icn-shipment_by_po_schedules">
                                       schedules <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                    </span>


                                 </td>
                                <td><i>Object</i></td>
                            </tr>
                            <tr  class="collapse" id="shipment_by_po_schedules">
                                <td colspan="2">
                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                        <table class="table-bordered">
                                            <thead></thead>
                                            <tbody>
                                            <tr>
                                                <td>etd</td>
                                                <td><i>Date</i> Default: <code>NULL</code>, Estimated Time of Departure for Schedule</td>
                                            </tr>
                                            <tr>
                                                <td>eta</td>
                                                <td><i>Date</i> Default: <code>NULL</code>, Estimated Time of Arrival for Schedule</td>
                                            </tr>
                                            <tr>
                                                <td>location_from</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Location from for schedule</td>
                                            </tr>
                                            <tr>
                                                <td>location_to</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Location from for schedule</td>
                                            </tr>
                                            <tr>
                                                <td>mode</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Mode of shedule, Example: Ocean/Air/Rail/Truck</td>
                                            </tr>
                                            <tr>
                                                <td>carrier</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Carrier name of the shedule</td>
                                            </tr>
                                            <tr>
                                                <td>type</td>
                                                <td><i>Date</i> Default: <code>NULL</code>, Type of delivery, E.g: FCL/LCL/Air</td>
                                            </tr>
                                            <tr>
                                                <td data-toggle="collapse"
                                                    data-target="#shipment_by_po_legs"
                                                    aria-expanded="false"
                                                    aria-controls="shipment_by_po_legs"
                                                    style="cursor: pointer;  "
                                                    class="glyphicon1 icn-shipment_by_po_legs"
                                                    rel="icn-shipment_by_po_legs"
                                                >
                                                    <span class="icn-shipment_by_po_legs">
                                                       legs <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                    </span>
                                                </td>
                                                <td><i>Object</i></td>
                                            </tr>
                                            <tr  class="collapse" id="shipment_by_po_legs">
                                                <td colspan="2">
                                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                        <table class="table-bordered">
                                                            <thead></thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>mode</td>
                                                                <td><i>String</i> Default: <code>NULL</code>, Mode of shedule, Example: Ocean/Air/Rail/Truck</td>
                                                            </tr>
                                                            <tr>
                                                                <td>eta</td>
                                                                <td><i>Date</i> Default: <code>NULL</code>, Estimated Time of Departure for Schedule></td>
                                                            </tr>
                                                            <tr>
                                                                <td>location_to</td>
                                                                <td><i>String</i> Default: <code>NULL</code> Location to from legs</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </td>
                            </tr>
                            <!-- Supplier -->
                            <tr>
                                <td data-toggle="collapse"
                                    data-target="#shipment_by_po_suppliers"
                                    aria-expanded="false"
                                    aria-controls="shipment_by_po_suppliers"
                                    style="cursor: pointer;  "
                                    class="glyphicon1 icn-shipment_by_po_suppliers"
                                    rel="icn-shipment_by_po_suppliers"
                                >
                                                    <span class="icn-shipment_by_po_suppliers">
                                                       suppliers <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                    </span>


                                 </td>
                            </tr>

                            <tr  class="collapse" id="shipment_by_po_suppliers">
                                <td colspan="2">
                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                        <table class="table-bordered">
                                            <thead></thead>
                                            <tbody>
                                            <tr>
                                                <td>supplier_name</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Supplier name</td>
                                            </tr>
                                            <tr>
                                                <td>cargo_ready_date</td>
                                                <td><i>Date</i> Default: <code>NULL</code>, Cargo ready date for supplier</td>
                                            </tr>
                                            <tr>
                                                <td>po_num_old</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Old Purchase Order Number for supplier</td>
                                            </tr>
                                            <tr>
                                                <td data-toggle="collapse"
                                                  data-target="#shipment_by_po_selected_po"
                                                  aria-expanded="false"
                                                  aria-controls="shipment_by_po_selected_po"
                                                  style="cursor: pointer;  "
                                                  class="glyphicon1 icn-shipment_by_po_selected_po"
                                                  rel="icn-shipment_by_po_selected_po"
                                                >
                                                    <span class="icn-shipment_by_po_selected_po">
                                                       selected_po <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                    </span> </td>
                                                <td>
                                                    <i>Object</i>
                                                </td>
                                            </tr>
                                            <tr  class="collapse" id="shipment_by_po_selected_po">
                                                <td colspan="2">
                                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                        <table class="table-bordered">
                                                            <thead></thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>po_number</td>
                                                                <td><i>Integer</i></td>
                                                            </tr>
                                                            <tr>
                                                                <td data-toggle="collapse"
                                                                    data-target="#shipment_by_po_products"
                                                                    aria-expanded="false"
                                                                    aria-controls="shipment_by_po_products"
                                                                    style="cursor: pointer;  "
                                                                    class="glyphicon1 icn-shipment_by_po_products"
                                                                    rel="icn-shipment_by_po_products"
                                                                >
                                                                    <span class="icn-shipment_by_po_products">
                                                                       products <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                                    </span>
                                                                 </td>
                                                                <td><i>Object</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr  class="collapse" id="shipment_by_po_products">
                                                                <td colspan="2">
                                                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                                        <table class="table-bordered">
                                                                            <thead></thead>
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>sku</td>
                                                                                <td><i>Integer</i></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>name</td>
                                                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>total_cartons</td>
                                                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>to_ship_cartons</td>
                                                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>units_per_carton</td>
                                                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>weight</td>
                                                <td><i>Decimal</i> Default: <code>NULL</code>,Number of kg of selected po</td>
                                            </tr>
                                            <tr>
                                                <td>volume</td>
                                                <td><i>Decimal</i> Default: <code>NULL</code>, Number of CBM</td>
                                            </tr>
                                            <tr>
                                                <td>total_cartons</td>
                                                <td><i>Integer</i> Default: <code>NULL</code> Total cartons</td>
                                            </tr>
                                            <tr>
                                                <td>ams</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>hbl</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>marks</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>product_description</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Product description of selected po</td>
                                            </tr>
                                            <tr>
                                                <td>terms</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Terms of selected po</td>
                                            </tr>
                                            <tr>
                                                <td>bl_status</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Status of selected po. e.g: Pending/Telex Released/Original Received}</td>
                                            </tr>
                                            <tr>
                                                <td>hbl_copy</td>
                                                <td><i>String</i> Default: <code>NULL</code>, File path</td>
                                            </tr>
                                            <tr>
                                                <td>packing_list</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Packing list, file path</td>
                                            </tr>
                                            <tr>
                                                <td>commercial_documents</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Commercial documents, file path</td>
                                            </tr>
                                            <tr>
                                                <td>commercial_invoice</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Commercial invoic, file pathe</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>

                            <!-- Containers -->
                            <tr>
                                <td data-toggle="collapse"
                                    data-target="#shipment_by_po_containers"
                                    aria-expanded="false"
                                    aria-controls="shipment_by_po_containers"
                                    style="cursor: pointer;  "
                                    class="glyphicon1 icn-shipment_by_po_containers"
                                    rel="icn-shipment_by_po_containers"
                                >
                                    <span class="icn-shipment_by_po_containers">
                                       containers <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                    </span>
                                </td>
                                <td><i>Object</i></td>
                            </tr>

                            <tr  class="collapse" id="shipment_by_po_containers">
                                <td colspan="2">
                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                        <table class="table-bordered">
                                            <thead></thead>
                                            <tbody>
                                            <tr>
                                                <td>container_num</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Container number</td>
                                            </tr>
                                            <tr>
                                                <td>size</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>, Container size</td>
                                            </tr>
                                            <tr>
                                                <td>seal_num</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Seal number of container</td>
                                            </tr>
                                            <tr>
                                                <td>weight</td>
                                                <td><i>Decimal</i> Default: <code>NULL</code>, Number of kg for container</td>
                                            </tr>
                                            <tr>
                                                <td>volume</td>
                                                <td><i>Decimal</i> Default: <code>NULL</code>, Number of cbm</td>
                                            </tr>
                                            <tr>
                                                <td>carton_count</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>, Carton count for container</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>

                            <!-- Terminal -->

                            <tr>
                                <td data-toggle="collapse"
                                    data-target="#shipment_by_po_terminal"
                                    aria-expanded="false"
                                    aria-controls="shipment_by_po_terminal"
                                    style="cursor: pointer;  "
                                    class="glyphicon1 icn-shipment_by_po_terminal"
                                    rel="icn-shipment_by_po_terminal"
                                >
                                    <span class="icn-shipment_by_po_terminal">
                                       terminal <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                    </span>
                                </td>
                                <td><i>Object</i> </td>
                            </tr>

                            <tr  class="collapse" id="shipment_by_po_terminal">
                                <td colspan="2">
                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                        <table class="table-bordered">
                                            <thead></thead>
                                            <tbody>
                                            <tr>
                                                <td>name</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Name of Terminal</td>
                                            </tr>
                                            <tr>
                                                <td>firms_code</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Terminal firms code</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <!-- customs_documents -->
                            <tr>
                                <td data-toggle="collapse"
                                    data-target="#shipment_by_po_customs_documents"
                                    aria-expanded="false"
                                    aria-controls="shipment_by_po_customs_documents"
                                    style="cursor: pointer;  "
                                    class="glyphicon1 icn-shipment_by_po_customs_documents"
                                    rel="icn-shipment_by_po_customs_documents"
                                >
                                    <span class="icn-shipment_by_po_customs_documents">
                                       customs_documents <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                    </span>
                                </td>
                                <td><i>Object</i> </td>
                            </tr>
                            <tr  class="collapse" id="shipment_by_po_customs_documents">
                                <td colspan="2">
                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                        <table class="table-bordered">
                                            <thead></thead>
                                            <tbody>
                                            <tr>
                                                <td>supplier_name</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>, Supplier name</td>
                                            </tr>


                                            <tr data-toggle="collapse"
                                                data-target="#shipment_by_po_files"
                                                aria-expanded="false"
                                                aria-controls="shipment_by_po_files"
                                                style="cursor: pointer;"
                                            >
                                                <td>files > </td>
                                                <td><i>Object</i></td>
                                            </tr>


                                            <tr  class="collapse" id="shipment_by_po_files">
                                                <td colspan="2">
                                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                        <table class="table-bordered">
                                                            <thead></thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>name</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code>, File name </td>
                                                            </tr>
                                                            <tr>
                                                                <td>path</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code>, File path</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>


                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>







                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>

            </tbody>
        </table>
    </div>
</div>
