/** @format */

// All
const ALL = null;
const ACTIVE = "active";
const ARCHIVED = "archived";
const REJECTED = "rejected";
const CANCELLED = "cancelled";

//Open
const DRAFT = "draft";
const RECEIVED = "received";
const PENDING_APPROVAL = "pending_approval";
const CHANGE_REQUEST = "change_request";
const PERFORMA_REQUEST = "performa_request";

// Booking
const PENDING_QUOTE = "pending_quote";
const REQUIRES_BOOKING = "requires_booking";
const BOOKING_PENDING_APPROVAL = "booking_pending_approval";
const AWAITING_SHIPMENT = "awaiting_shipment";

const IN_PROGRESS = "in_progress";
const IN_TRANSIT = "in_transit";
const DELIVERED = "delivered";

// In Progress
const APPROVED = "approved";
const IN_PRODUCTION = "in_production";
const CLOSE_PASSED_CRD = "close_passed_crd";
const WAITING_FOR_BOOKING = "waiting_for_booking";
const ALL_IN_PROGRESS = "all_in_progress";

const STATES = {
    [DRAFT]: "Draft",
    [PENDING_APPROVAL]: "Pending Approval",
    [APPROVED]: "Approved",
    [REJECTED]: "Rejected",
    [PENDING_QUOTE]: "Pending Quote",
    [RECEIVED]: "Received",
    [CHANGE_REQUEST]: "Change Request",
    [CANCELLED]: "Cancelled",
    [REQUIRES_BOOKING]: "Requires Booking",
    [PERFORMA_REQUEST]: "Proforma Received",
    [AWAITING_SHIPMENT]: "Awaiting Shipment",
    [IN_TRANSIT]: "In Transit",
    [DELIVERED]: "Delivered",
};

export {
    ALL,
    DRAFT,
    PENDING_APPROVAL,
    RECEIVED,
    APPROVED,
    REJECTED,
    REQUIRES_BOOKING,
    PENDING_QUOTE,
    ACTIVE,
    STATES,
    IN_PROGRESS,
    IN_TRANSIT,
    DELIVERED,
    ARCHIVED,
    BOOKING_PENDING_APPROVAL,
    AWAITING_SHIPMENT,
    PERFORMA_REQUEST,
    CHANGE_REQUEST,
    IN_PRODUCTION,
    CLOSE_PASSED_CRD,
    WAITING_FOR_BOOKING,
    ALL_IN_PROGRESS,
};