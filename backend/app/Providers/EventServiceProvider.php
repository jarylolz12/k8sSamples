<?php

namespace App\Providers;

use App\Events\SendAddUserToCompanyInvitationEvent;
use App\Events\SendShipmentToNetCHBEvent;
use App\Listeners\SendAddUserToCompanyInvitationEventListener;
use App\Listeners\SendShipmentToNetCHBListener;
use App\Events\SendInvitationVendorEvent;
use App\Listeners\SendInvitationVendorEventListener;
use App\Events\SendInvitationBuyerEvent;
use App\Listeners\SendInvitationBuyerEventListener  ;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\SendLeaveShipmentNotificationMailEvent' => [
            'App\Listeners\SendLeaveShipmentNotificationMailEventListener',
        ],
        'App\Events\SendBookingEmailEvent' => [
            'App\Listeners\SendBookingEmailEventListener',
        ],
        'App\Events\SendNewBookingEmailEvent' => [
            'App\Listeners\SendNewBookingEmailEventListener',
        ],
        'App\Events\SendUpdatedBookingEmailEvent' => [
            'App\Listeners\SendUpdatedBookingEmailEventListener',
        ],
        'App\Events\SendBookingEmailCustomerEvent' => [
            'App\Listeners\SendBookingEmailCustomerEventListener',
        ],
        'App\Events\UpdateCustomerEvent' => [
            'App\Listeners\UpdateCustomerEventListener',
        ],
        'App\Events\SendArrivalNoticeEvent' => [
            'App\Listeners\SendArrivalNoticeEventListener',
        ],
        'App\Events\SendDeliveryOrderEvent' => [
            'App\Listeners\SendDeliveryOrderEventListener',
        ],
        'App\Events\SendNewDeliveryOrderEvent' => [
            'App\Listeners\SendNewDeliveryOrderEventListener',
        ],
        'App\Events\SendBookingEmailConfirmedByCustomerEvent' => [
            'App\Listeners\SendBookingEmailConfirmedByCustomerEventListener',
        ],
        'App\Events\InsertBrexEvent' => [
            'App\Listeners\InsertBrexEventListener',
        ],
        SendShipmentToNetCHBEvent::class => [
            SendShipmentToNetCHBListener::class
        ],
        'App\Events\InvoiceUpdateEvent' => [
            'App\Listeners\InvoiceUpdateEventListener'
        ],
        'App\Events\InvoiceEditEvent' => [
            'App\Listeners\InvoiceEditEventListener'
        ],
        'App\Events\InsertDocumentEvent' => [
            'App\Listeners\InsertDocumentEventListener',
        ],
        'App\Events\SendShipmentDocumentsEvent' => [
            'App\Listeners\SendShipmentDocumentsEventListener',
        ],
        'App\Events\InsertBillAttachmentEvent' => [
            'App\Listeners\InsertBillAttachmentEventListener'
        ],
        'App\Events\UpdateDocumentEvent' => [
            'App\Listeners\UpdateDocumentEventListener',
        ],
        'App\Events\SendForwarderEmailEvent' => [
            'App\Listeners\SendForwarderEmailEventListener',
        ],
        'App\Events\SendForwarderEmailAdminEvent' => [
            'App\Listeners\SendForwarderEmailAdminEventListener',
        ],
        SendInvitationVendorEvent::class => [
            SendInvitationVendorEventListener::class,
        ],
        SendInvitationBuyerEvent::class => [
            SendInvitationBuyerEventListener::class,
        ],
        SendAddUserToCompanyInvitationEvent::class => [
            SendAddUserToCompanyInvitationEventListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
