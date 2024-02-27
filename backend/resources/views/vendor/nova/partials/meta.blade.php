{{-- <link rel="icon" type="image/png" href="{{ asset('/img/favicon.png') }} "> --}}
<?php
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$urlParts = explode('/', str_ireplace(array('http://', 'https://'), '', $url));

if (isset($urlParts[5])) {
    $urlEdit = explode('?', $urlParts[5]);
} else {
    $urlEdit = '';
}

$is_shipments = isset($urlParts[3]) ? $urlParts[3] : "";
$is_edit = isset($urlEdit[0]) ? $urlEdit[0] : "";
?>
<style media="screen">
    /* div[dusk="shipments-index-component"] .table td:first-child,
    div[dusk="shipments-index-component"] .table th:first-child,
    div[dusk="shipments-index-component"] .v-popover.-mx-2, */
    div[dusk="admins-index-component"] .table td:first-child,
    div[dusk="admins-index-component"] .table th:first-child,
    div[dusk="admins-index-component"] .v-popover.-mx-2,
    div[dusk="account-managers-index-component"] .table td:first-child,
    div[dusk="account-managers-index-component"] .table th:first-child,
    div[dusk="account-managers-index-component"] .v-popover.-mx-2,
    div[dusk="customers-index-component"] .table td:first-child,
    div[dusk="customers-index-component"] .table th:first-child,
    div[dusk="customers-index-component"] .v-popover.-mx-2,
    div[dusk="customer-admins-index-component"] .table td:first-child,
    div[dusk="customer-admins-index-component"] .table th:first-child,
    div[dusk="customer-admins-index-component"] .v-popover.-mx-2,
    div[dusk="suppliers-index-component"] .table td:first-child,
    div[dusk="suppliers-index-component"] .table th:first-child,
    div[dusk="suppliers-index-component"] .v-popover.-mx-2,
    div[dusk="sale-agents-index-component"] .table td:first-child,
    div[dusk="sale-agents-index-component"] .table th:first-child,
    div[dusk="sale-agents-index-component"] .v-popover.-mx-2 {
        display: none;
    }
</style>
<?php
//if ($is_shipments == 'shipments' && $is_edit == 'edit') {
?>
    <!-- <style>
        button[dusk="update-and-continue-editing-button"] {
            display: none;
        }
        button[dusk="update-button"] {
            display: none;
        }
    </style> -->
<?php
//}
?>
{{-- <script type="text/javascript">
    var hello = "{{ \App\Nova\User::label() }}"
alert(helo)
</script> --}}
