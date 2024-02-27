@component('mail::message')

<div style="text-align: left;">
    {{ $content["CompanyName"] }}
</div>
<div style="text-align: right;">

    {{ $content["InvDate"] }}
    386 RT 59 Suite 300J Airmont, NY 10952
</div>

Congrats Your Shipment Left Today
<br />
##### Shipment Details
<hr>
@component('mail::table')
| | |
| :------------ |:-------------|
|<b> Customer Name </b>| {{ $content['customer'] }} |
|<b> Shifl Reference </b>| {{ $content['shifl_ref'] }} |
|<b> Estimated Time of Departure </b>|{{ \Carbon\Carbon::parse($content['schedule'][0]->etd)->format('D d M , Y') }}|
|<b> Location From </b>|{{ ($content['schedule'][0]->location_from) == 1 ? 'NY/NJ' :( ($content['schedule'][0]->location_from) == 2 ?  'Shanghai' : '') }}|
|<b> Estimated Time of Arrival </b>|{{ \Carbon\Carbon::parse($content['schedule'][0]->eta)->format('D d M , Y') }}|
|<b> Location To </b>|{{ ($content['schedule'][0]->location_to) == 1 ? 'NY/NJ' :( ($content['schedule'][0]->location_to) == 2 ?  'Shanghai' : '') }}|
|<b> Status </b>|{{ $content['status'] }}|

@endcomponent
<br />
Check out the Attachments for essential documents.
{{-- <hr> --}}


Thanks<br>
{{ config('app.name') }}
@endcomponent