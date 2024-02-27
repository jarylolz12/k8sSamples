<table>
    <thead>
        <tr>
            <th style="font-weight: bold;">Shifl Ref#</th>
            <th style="font-weight: bold;">Eta</th>
            @if($tab == 'All')
            <th style="font-weight: bold;">Int/Ext </th>
            @endif
            <th style="font-weight: bold;">Expected to be Addressed</th>
            <th style="font-weight: bold;">Last Addressed/Tracked</th>
            <th style="font-weight: bold;">Last Addressed/Tracked By</th>
            <th style="font-weight: bold;">Next Expected to be Addressed</th>
            <th style="font-weight: bold;">Last Updated At</th>
            <th style="font-weight: bold;">Last Updated By</th>
            <th style="font-weight: bold;">Status </th>
            <th style="font-weight: bold;">Comments </th>
        </tr>
    </thead>
    <tbody>

        @foreach ($shipments as $shipment)

        <tr>

            <!-- Shifl Ref# -->
            <td> {{ $shipment->shifl_ref ?? '' }}</td>

            <td> {{ rtrim($shipment->eta ?? '', "00:00:00")}}</td>

            @if($tab == 'All')
            <!-- Status -->
            <td>
                {{ $shipment->is_tracking_shipment ? 'External' : 'Internal' }}
            </td>
            @endif
            <!-- Expected to be Addressed -->
            <td>
                {{ !empty($shipment->mt_expected_to_be_addressed ?? '')? \Carbon\Carbon::parse($shipment->mt_expected_to_be_addressed)->addMinute()->setTimezone('America/New_York') : '' }}
            </td>

            <!-- Last Tracked At -->
            <td>
                {{ $shipment->untracked_tool_last_tracked_at ?? '' }}
            </td>

            <!-- Last Tracked By -->
            <td>
                {{ $shipment->mt_last_addressed_by ? (\App\User::find($shipment->mt_last_addressed_by)->email ?? '') : '' }}
            </td>

            <!-- Next Expected to be Addressed -->
            <td>
                {{ !empty($shipment->tracked_up_to ?? '')? \Carbon\Carbon::parse($shipment->tracked_up_to)->addMinute()->setTimezone('America/New_York') : '' }}
            </td>





            <!-- Last Updated At# -->
            <td>
                {!! $shipment->untracked_tool_last_updated_at ?? $shipment->created_at !!}
            </td>

            <!-- Last updated by -->
            <td>
                {{ $shipment->untracked_tool_last_updated_by ? (\App\User::find($shipment->untracked_tool_last_updated_by)->email ?? '') : ''  }}
            </td>

            <!-- Status -->
            <td>
                {{ $shipment->getManualTrackingReportStatus() ?? '' }}
            </td>

            <!-- Status -->
            <td>
                {{ $shipment->getManualTrackingComments() ?? '' }}
            </td>


        </tr>
        @endforeach

    </tbody>
</table>
