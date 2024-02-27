<template>
    <div @click="handleGlobalClick" class="containerMain" v-resize="onResize">
        <no-connected-supplier-modal
            :show="closeNoConnectedSupplierModalView"
            className="add-shipment-dialog-modal"
            :isMobile="isMobile"
            :closeNoConnectedSupplierModalRole="closeNoConnectedSupplierModalRole"
            :bookingShipmentDialogView.sync="bookingShipmentDialogView"
            @close="closeNoSupplierConnectedModal"
        >
            <template v-slot:title>
                <div>
                    <generic-icon iconName="warning"></generic-icon>
                </div>
            </template>
            <template v-slot:actions="{ footer }">
                <div class="d-flex footer">
                    <v-btn @click.stop="footer.close" class="save-btn btn-blue" text >
                        <span >{{ "I understood" }}</span>
                    </v-btn>
                </div>
            </template>
        </no-connected-supplier-modal>
        <add-shipment-dialog-modal
            :show="addShipmentDialogModalView"
            :refNumber.sync="refNumber"
            className="add-shipment-dialog-modal"
            :isMobile="isMobile"
            @close="closeAddShipmentModal"
            @addAnotherShipment="addAnotherShipment"
        >
            <template v-slot:title>
                <div>
                    <generic-icon iconName="check-success"></generic-icon>
                </div>
            </template>
            <template v-slot:actions="{ footer }">
                <div class="d-flex footer">
                    <v-btn @click.stop="footer.closeRefresh" class="save-btn btn-blue" text >
                        <span >{{ "I understood" }}</span>
                    </v-btn>
                    <v-btn class="delete-cancel btn-white edit-shipment-cancel-btn btn-blue" text @click="footer.addAnotherShipment">
                        <span>{{ "Add Another" }}</span>
                    </v-btn>
                </div>
            </template>
        </add-shipment-dialog-modal>
        <booking-shipment-dialog
            :reference="`${getEditingDraftShipment ? 'formEditShipment' : 'formBookingShipment'}`"
            v-if="bookingShipmentDialogView"
            className="edit-shipment-dialog-wrapper"
            :show.sync="bookingShipmentDialogView"
            :bookingShipmentDialogView.sync="bookingShipmentDialogView"
            :isMobile="isMobile"
            :submitRequestModalView.sync="submitRequestModalView"
            :bookingRequestSubmittedModalView.sync="bookingRequestSubmittedModalView"
            :item="editedItem"
            :rules="createShipmentRules"
            :windowWidth="windowWidth"
            :isEdit.sync="isEdit"
            :closeNoConnectedSupplierModalView.sync="closeNoConnectedSupplierModalView"
            :closeNoConnectedSupplierModalRole.sync="closeNoConnectedSupplierModalRole"
            :addShipmentDialogModalView.sync="addShipmentDialogModalView"
            @close="handleCloseBookingShipmentDialog"
            @reloadShipments="callShipmentsAPI"
        >
            <template v-slot:title>
                <div id="headline-custom-wrapper">
                    <span v-if="1==2" class="headline-custom">{{ "Add Shipment" }}</span>
                    <span class="headline-custom-track booking">{{ isEdit ? "Edit Shipment" : "Create Booking Request" }}</span>
                </div>
            </template>
            <template v-slot:sidebar="{ mainContent }">
                <div style="padding-top: 16px !important;" class="d-flex flex-column first-column sidebar-item-main-wrapper">
                    <div :class="`d-flex align-center sidebar-items-wrapper ${sidebarItem.selected ? 'selected' : ''}`" v-bind:key="`si-${key}`" v-for="(sidebarItem, key) in mainContent.sidebarItems">
                        <a @click.stop="mainContent.selectPage(sidebarItem)" :class="`d-flex sidebar-item align-center ${sidebarItem.selected ? 'selected' : ''}`">
                            <kenetic-icon :paddingTop="`${sidebarItem.icon==='general' ? 6: 0}`" :color="`${(sidebarItem.selected) ? '#0171A1' : '#4A4A4A'}`" v-if="sidebarItem.icon!==''" :iconName="sidebarItem.icon" :width="sidebarItem.width" :height="sidebarItem.height" />
                            <div style="margin-left: 13px;" class="sidebar-label">
                                {{
                                    sidebarItem.label
                                }}
                            </div>
                        </a>
                    </div>
                </div>
            </template>
            <template v-slot:actions="{ footer }">
                <div class="d-flex footer">
                    <v-btn v-if="!getEditingShipment" style="margin-right: 8px;" :disabled="footer.createLoading" @click.stop="footer.createShipment" class="save-btn btn-blue" text >
                        <span >{{ "Submit Request" }}</span>
                    </v-btn>
                    <v-btn v-if="getEditingShipment" style="margin-right: 8px;" :disabled="footer.createLoading" @click.stop="footer.updateShipment" class="save-btn btn-blue" text >
                        <span >{{ "Save" }}</span>
                    </v-btn>
                    <v-btn v-if="!getEditingShipment" :disabled="footer.submitLoading" style="margin-right: 8px;" class="delete-cancel btn-white edit-shipment-cancel-btn btn-blue" text @click="footer.saveAsDraft">
                        <span style="color: #0171A1;">{{ "Save as Draft" }}</span>
                    </v-btn>
                    <v-btn class="delete-cancel btn-white edit-shipment-cancel-btn btn-blue" text @click="footer.close">
                        <span>{{ "Cancel" }}</span>
                    </v-btn>
                </div>
            </template>
        </booking-shipment-dialog>
        <create-shipment-dialog
            reference="formCreateShipment"
            v-if="createShipmentDialogView"
            className="edit-shipment-dialog-wrapper"
            :show="createShipmentDialogView"
            :isMobile="isMobile"
            :rules="createShipmentRules"
            :item.sync="editedItem"
            :isEdit.sync="isEdit"
            :windowWidth="windowWidth"
            :addShipmentDialogModalView.sync="addShipmentDialogModalView"
            :refNumber.sync="refNumber"
            @close="handleCloseCreateShipmentDialog"
            @reloadShipments="callShipmentsAPI"
            :addBulkShipmentDialogModalView.sync="addBulkShipmentDialogModalView"
            :shipmentCounter.sync="shipmentCounter"
        >
            <template v-slot:title>
                <div id="headline-custom-wrapper">
                    <span class="headline-custom">{{ (getEditingShipment) ? "Edit Shipment" : (getMarkingBookedExternal) ? "Mark Booked External" : "Add Shipment for Tracking" }}</span>
                    <span v-if="!getMarkingBookedExternal && 1==2" class="headline-custom-track">Track Shipment</span>
                </div>
            </template>
            <template v-slot:sidebar="{ mainContent }">
                <div v-if="!getMarkingBookedExternal" class="d-flex flex-column first-column sidebar-item-main-wrapper">
                    <div :class="`d-flex align-center sidebar-items-wrapper ${sidebarItem.selected ? 'selected' : ''}`" v-bind:key="`si-${key}`" v-for="(sidebarItem, key) in mainContent.sidebarItems">
                        <a @click.stop="mainContent.selectPage(sidebarItem)" :class="`d-flex sidebar-item align-center ${sidebarItem.selected ? 'selected' : ''}`">
                            <kenetic-icon :paddingTop="`${sidebarItem.icon==='general' ? 6: 0}`" :color="`${(sidebarItem.selected) ? '#0171A1' : '#4A4A4A'}`" v-if="sidebarItem.icon!==''" :iconName="sidebarItem.icon" :width="sidebarItem.width" :height="sidebarItem.height" />
                            <div style="margin-left: 13px;" class="sidebar-label">
                                {{
                                    (getAddingShipmentTracking && sidebarItem.icon === 'po-icon') ? 'Orders' : sidebarItem.label
                                }}
                            </div>
                        </a>
                    </div>
                </div>
            </template>
            <template v-slot:actions="{ footer }">
                <div class="d-flex footer">
                    <v-btn v-if="!getMarkingBookedExternal" :disabled="footer.createLoading" @click.stop="() => { isEdit ? footer.updateShipment() : footer.createShipment()}"  class="save-btn btn-blue" text >
                        <span >{{ isEdit ? "Edit Shipment" : "Add Shipment" }}</span>
                    </v-btn>
                    <v-btn :disabled="!footer.checkMarkExternalValidation() || footer.updateLoading" @click.stop="footer.updateShipment" class="save-btn btn-blue" v-if="getMarkingBookedExternal">
                        <span>Mark Booked External</span>
                    </v-btn>
                    <v-btn class="delete-cancel btn-white edit-shipment-cancel-btn btn-blue" text @click="footer.close">
                        <span>{{ "Cancel" }}</span>
                    </v-btn>
                </div>
            </template>
        </create-shipment-dialog>
        <div v-if="!ArchivedShipmentFlag" id="shipments_header">
            <div :class="`${(windowWidth<=768) ? 'k-flex-col-reverse' : ''}`" id="shipments_root">
                <v-tabs 
                    v-if="!isMobile" 
                    show-arrows 
                    class="customTab" 
                    height="56px" 
                    @change="onTabChange" 
                    v-model="activeTab">

                    <v-tab v-for="(n, i) in tabs" :key="i" @click="getCurrentTab(i)">
                        <span>
                            <span :class="n == 'Completed' ? 'tab-name-completed' : 'tab-name'">
                                <span>                                   
                                    <span v-if="n == 'Pending Approval'"> Booking </span>
                                    <span v-else> {{ n }} </span>
                                </span>
                            </span>
                            <v-badge
                                v-if="n !== 'Completed'"
                                color="#819FB2"
                                class="customBadge"
                                bordered
                                :content="getShipmentCount(i)">
                            </v-badge>
                        </span>
                    </v-tab>
                </v-tabs>

                <v-tabs 
                    v-if="isMobile && selectedTab === 0" 
                    show-arrows 
                    :class="`customTab custom-tab-mobile custom-tab-mobile-special ${(tabsComputed.length>3) ? 'custom-tab-mobile-special-wrapper' : ''}`" 
                    height="45px" 
                    @change="onTabMobileChange" 
                    v-model="activeTabComputed">

                    <v-tab 
                        :style="`${(i == 0 && selectedTab > 0) ? 'display: none;' : ''}`" 
                        v-for="(n, i) in tabsComputed" :key="i"
                        :class="`${(tabsComputed.length == 3) ? `v-tab-special ${n}`: `${n}`}`">

                        <span class="tab-name"> {{ n }} </span>
                    </v-tab>
                </v-tabs>

                <!-- <CreateShipment 
                    :editedIndexData.sync="editedIndex"
                    :dialogData.sync="dialog"
                    :editedItemData.sync="editedItem"
                    @close="close"
                    @save="save" /> -->

                <div class="shipment-tabs-mobile" v-if="isMobile">
                    <div>
                        <v-select
                            @change="selectTab"
                            append-icon="mdi-chevron-down"
                            class="tab-selections"
                            :items="tabsMobile" 
                            v-model="selectedTab"
                            item-text="label"
                            item-value="value"
                            hide-details="auto"
                            :menu-props="{ bottom: true, offsetY: true }"
                            height="45px">
                        </v-select>
                    </div>
                    <div>
                        <!-- @showAddShipmentDialog="showAddShipmentDialog" -->
                        <shipment-dropdown
                        @showBookingShipmentDialog="showBookingShipmentDialog"
                        @showSingleAddShipmentDialog="showSingleAddShipmentDialog"
                        :shipmentDropDownClicked.sync="shipmentDropDownClicked"
                        :isMobile="isMobile"
                        :isEdit.sync="isEdit"

                        >
                        </shipment-dropdown>

                        <v-btn v-if="1==2" class="track-shipment-mobile track-shipment btn-white mr-4 shipment-header-button-btn" @click="showAddShipmentDialog">
                        {{ "Add Shipment" }}
                        </v-btn>
                        <v-btn v-if="1==2" class="track-shipment-mobile track-shipment btn-white mr-4 shipment-header-button-btn" @click="openTrackShipment">
                            Track
                        </v-btn>
                    </div>
                </div>

                <div v-if="!isMobile" class="shipment-header-buttons">
                    <!-- @showAddShipmentDialog="showAddShipmentDialog" -->
                    <shipment-dropdown
                        @showBookingShipmentDialog="showBookingShipmentDialog"
                        @showSingleAddShipmentDialog="showSingleAddShipmentDialog"
                        :isMobile="isMobile"
                        :isEdit.sync="isEdit"
                    >
                    </shipment-dropdown>
                    <v-btn v-if="1==2" :class="`${(isMobile) ? 'track-shipment-mobile' : ''} track-shipment btn-white mr-4 shipment-header-button-btn`" @click="showAddShipmentDialog">
                        Add Shipment
                    </v-btn>
                    <v-btn v-if="1==2" :class="`${(isMobile) ? 'track-shipment-mobile' : ''} track-shipment btn-white mr-4 shipment-header-button-btn`" @click="openTrackShipment">
                        Track Shipment
                    </v-btn>

                    <div v-if="isMobile" style="width:2%;"></div>
                    <v-btn v-if="1==2" @click.stop="showAddShipmentDialog" dark color="primary" :class="`${(isMobile) ? 'create-shipment-mobile' : ''} btn-blue shipment-header-button-btn`">
                        Add Shipment
                    </v-btn>
                </div>
                <div>
                    <v-menu
                        offset-y
                        bottom
                        left
                        content-class="pending-dropdown-container"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <button v-bind="attrs" v-on="on" class="three-dots-container">
                                <custom-icon
                                    iconName="three-dots"
                                    color="#0171A1"
                                    width="11"
                                    height="3"
                                />
                            </button>
                        </template>
                        <v-list>
                            <v-list-item>
                                <v-list-item-title class="k-fw-sm k-font-inter-regular d-flex" @click="showArchivedShipment()">
                                    <img src="@/assets/icons/archive.svg" style="margin-right: 10px;"/> Archived
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </div>

            <div class="filters-wrapper d-flex align-center" 
                :class="shipments.length > 0 || completed.length > 0 ? 'justify-space-between' : 'justify-end'">
                <div class="pending-approval-tab" v-if="activeTab === 0">
                    <v-tabs v-if="!isMobile" 
                        :class="`pending-inner-tab ${(isMobile) ? 'pending-inner-tab-mobile' : 'pending-inner-tab-desktop'} ${(windowWidth<= 912 && windowWidth >=769) ? 'k-w-100' : ''}`" 
                        @change="onPendingTabChange" 
                        v-model="pendingCurrentTab">
                        <v-tab v-for="(tab, index) in pendingSubTabs" 
                            :key="index" 
                            class="pending-tabs"
                            :class="pendingSubTabsClasses[index]">
                            {{ tab }}
                        </v-tab>
                    </v-tabs>

                    <v-spacer></v-spacer>

                    <div :class="`pending-tabs-search-wrapper ${(isMobile) ? 'pending-tabs-search-wrapper-mobile' : 'pending-tabs-search-wrapper-desktop'}`" 
                        v-if="isShowSearchShipment()">
                        <!-- v-if="activeTab === 0 && (pending.length > 0 || expired.length > 0 || pendingQuote.length > 0 || snooze.length)"> -->
                        <div class="search-wrapper search-wrapper-pending">
                            <div class="search-wrapper-pending-group">
                                <img src="@/assets/images/search-icon.svg" alt="">
                                <input
                                    v-if="!isMobile"
                                    class="search" 
                                    type="text"
                                    id="search-input"
                                    v-model.trim="search"
                                    @input="handleSearch((pendingCurrentTab == 1) ? 'pendingQuote' : (pendingCurrentTab==3) ? 'snooze' : (pendingCurrentTab == 2) ? 'pending': (pendingCurrentTab==0) ? 'draft' : (pendingCurrentTab==4) ? 'expired' : 'pendingQuote')"
                                    placeholder="Search Shipment"                                
                                    autocomplete="off" />
                                    <!-- 
                                    v-model.trim="searchPendingData"
                                    @keyup.enter="handleSearchPending((pendingCurrentTab == 1) ? 'pending' : (pendingCurrentTab==3) ? 'expired' : (pendingCurrentTab == 2) ? 'snooze': 'pendingQuote')" -->

                                <input
                                    v-if="isMobile"
                                    class="search" 
                                    type="text"
                                    id="search-input"
                                    v-model.trim="search"
                                    placeholder="Search Shipment"
                                    @input="handleSearch((pendingCurrentTab == 1) ? 'pendingQuote' : (pendingCurrentTab==3) ? 'snooze' : (pendingCurrentTab == 2) ? 'pending': (pendingCurrentTab==0) ? 'draft' : (pendingCurrentTab==4) ? 'expired' : 'pendingQuote')"
                                    autocomplete="off" />
                            </div>

                            <!-- <v-btn v-if="!isMobile" 
                                @click="handleSearchPending((pendingCurrentTab == 1) ? 'pending' : (pendingCurrentTab==3) ? 'expired' : (pendingCurrentTab == 2) ? 'snooze': 'pendingQuote')" class="pending-sub-search-btn">
                                <icon 
                                    color="#002F44"
                                    iconName="filter"
                                    :height="14"
                                    :width="16" /> 
                                <div class="pending-sub-search-filter">Filters</div>
                                <div class="pending-sub-search-filter">Search</div>
                            </v-btn>-->
                        </div>
                    </div>
                </div>

                <div class="shipments-tabs-search-wrapper">
                    <div class="search-wrapper" v-if="activeTab === 1 && shipments.length > 0">
                        <img src="@/assets/images/search-icon.svg" alt="">

                        <input
                            class="search" 
                            type="text"
                            id="search-input"
                            v-model.trim="search"
                            placeholder="Search Shipment"
                            @input="handleSearch('shipments')"
                            autocomplete="off"
                            ref="shipmentSearchInput" />
                            <!-- @input="handleSearch('shipments')" -->

                        <!-- <button @click="clearSearched" v-if="search !== ''">
                            <img src="@/assets/images/close.svg" alt="clear" class="img-clear-searched">
                        </button> -->
                    </div>

                    <div class="search-wrapper" v-if="activeTab === 2 && completed.length > 0">
                        <img src="@/assets/images/search-icon.svg" alt="">

                        <input
                            class="search" 
                            type="text"
                            id="search-input"
                            v-model.trim="search"
                            placeholder="Search Shipment"
                            @input="handleSearch('completed')"
                            autocomplete="off" />
                    </div>
                </div>

                <div class="shipments-view-lists">
                    <div class="select-view-tabs-wrapper">
                        <v-tabs class="select-view-tabs" v-model="currentViewTab" @change="setSelectedView">
                            <v-tab v-for="(n, i) in viewTabsName" :key="i">
                                <v-tooltip bottom content-class="tooltip-wrapper tooltip-bottom">
                                    <template v-slot:activator="{ on }">
                                        <span v-on="on" class="d-flex align-center">
                                            <img :src="getImgUrl(n.image, i)" alt="" width="18px" height="18px">
                                        </span>
                                    </template>
                                    <span style="font-size:13px;">{{ n.name }}</span>
                                </v-tooltip>
                            </v-tab>
                        </v-tabs>
                    </div>
                </div>
            </div>
        </div>

        <ShipmentsDesktopTable 
            :shipments="shipments"
            :pending="pending"
            :expired="expired"
            :snooze="snooze"
            :draft="draft"
            :pendingQuote="pendingQuote"
            :completed="completed"
            :editedIndex.sync="editedIndex"
            :editedItem.sync="editedItem"
            :isMobile="isMobile"
            :activeTab.sync="activeTab"
            :pendingCurrentTab.sync="pendingCurrentTab"
            :tablePage.sync="page"
            :search.sync="search"
            v-if="!isMobile && !ArchivedShipmentFlag"
            @showEditShipment="showEditShipment"
            :paginationData.sync="$store.state.shipment.shipmentsPagination"
            :currentViewTab="currentViewTab" />

        <ShipmentsMobileTable 
            :shipments="shipments"
            :pending="pending"
            :expired="expired"
            :snooze="snooze"
            :draft="draft"
            :pendingQuote="pendingQuote"
            :completed="completed"
            :editedIndex.sync="editedIndex"
            :editedItem.sync="editedItem"
            :isMobile="isMobile"
            :activeTab.sync="activeTab"
            :pendingCurrentTab.sync="pendingCurrentTab"
            :tablePage.sync="page"
            :search.sync="search"
            v-if="isMobile && !createShipmentDialogView"
            :paginationData.sync="$store.state.shipment.shipmentsPagination" />

        <TrackShipment 
            :dialogData.sync="dialogTrackShipment"
            :editedItemData.sync="editedTrackShipment"
            @close="closeTrackShipment"
            :isMobile="isMobile"/>

        <ArchivedShipment
            v-if="ArchivedShipmentFlag"
			:dialog.sync="dialogView"
            :selectedTab="activeTab"
            @hideArchivedShipment="hideArchivedShipment"
		/>

        <BulkShipmentAddedModal
            :show="addBulkShipmentDialogModalView"
            :shipmentCounter="shipmentCounter"
            className="add-shipment-dialog-modal"
            :isMobile="isMobile"
            @close="closeAddShipmentModal"
            @addAnotherShipment="addAnotherShipment"
        >
            <template v-slot:title>
                <div>
                    <generic-icon iconName="check-success"></generic-icon>
                </div>
            </template>
            <template v-slot:actions="{ footer }">
                <div class="d-flex footer">
                    <v-btn @click.stop="footer.closeRefresh" class="save-btn btn-blue" text >
                        <span>Done Adding</span>
                    </v-btn>
                    <v-btn class="delete-cancel btn-white edit-shipment-cancel-btn btn-blue" text @click="footer.addAnotherShipment">
                        <span>Add More</span>
                    </v-btn>
                </div>
            </template>
        </BulkShipmentAddedModal>

        <CreateSingleShipmentDialog
            reference="formCreateShipment"
            v-if="createSingleShipmentDialogView"
            className="create-shipment-dialog-wrapper"
            :show.sync="createSingleShipmentDialogView"
            :isMobile="isMobile"
            :rules="createShipmentRules"
            :item.sync="editedItem"
            :isEdit.sync="isEdit"
            :windowWidth="windowWidth"
            :addShipmentDialogModalView.sync="addShipmentDialogModalView"
            :refNumber.sync="refNumber"
            @close="handleCloseCreateSingleShipmentDialog"
            @reloadShipments="callShipmentsAPI"
            :addBulkShipmentDialogModalView.sync="addBulkShipmentDialogModalView"
            @showSingleShipmentSuccessDialog="showSingleShipmentSuccessDialog"
            :createSingleShipmentId.sync="createSingleShipmentId"
            @createSingleShipmentPayload="createSingleShipmentPayload"
            :createSingleShipmentPayloadData="createSingleShipmentPayloadData"
            :createdShipmentType.sync="createdShipmentType"
            :shipmentCounter.sync="shipmentCounter"
        >
            <template v-slot:title>
                <div id="headline-custom-wrapper">
                    <span class="headline-custom">{{ (getEditingShipment) ? "Edit Shipment" : (getMarkingBookedExternal) ? "Mark Booked External" : "Add Shipment for Tracking" }}</span>
                    <span v-if="!getMarkingBookedExternal && 1==2" class="headline-custom-track">Track Shipment</span>
                </div>
            </template>
        </CreateSingleShipmentDialog>

        <CreateSingleShipmentSuccessDialog
            :show="createSingleShipmentSuccessDialogView"
            :createSingleShipmentId.sync="createSingleShipmentId"
            :createdShipmentDetails.sync="getShipmentDetails"
            :getShipmentDetailsLoading.sync="getShipmentDetailsLoading"
            @showAddMoreShipmentInfo="showAddMoreShipmentInfo"
            @close="handleCloseSingleShipmentSuccessDialog"
            :createdShipmentType.sync="createdShipmentType"
            @shipmentDetails="passShipmentDetailsToEditComp"
        />

        <AddMoreShipmentInfoDialog
            :show="showAddMoreShipmentInfoDialog"
            :createSingleShipmentId.sync="createSingleShipmentId"
            @close="handleCloseAddMoreShipmentInfoDialog"
            :createdShipmentType.sync="createdShipmentType"
            :getShipmentDetailsToEditComp="getShipmentDetailsToEditComp"
            @shipmentInfoUpdatedSuccess="shipmentInfoUpdated = true"
            :editItem="shipmentEditItem"
            :isShipmentDetailExist="isShipmentDetailExist"
        />

        <ConfirmDialog :dialogData.sync="createSingleShipmentPayloadData.invalid_mbl_number">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Invalid MBL</h2>
			</template>

			<template v-slot:dialog_content>
				<p>The MBL number you have provided can’t be tracked, Do you want to update the MBL number or Do you want to continue.</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" text @click="editMblNumber()" :disabled="getCreateShipmentLoading">
					<span>Edit MBL</span>
				</v-btn>
				<v-btn class="btn-white" text @click="addShipmentAnyway()" :disabled="getCreateShipmentLoading">
					<span v-if="getCreateShipmentLoading">Shipment Adding...</span>
					<span v-else>Add Shipment Anyway</span>
				</v-btn>
			</template>
		</ConfirmDialog>

        <ConfirmDialog :dialogData.sync="createSingleShipmentPayloadData.existing_lcl_shipment">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Is this a LCL shipment?</h2>
			</template>

			<template v-slot:dialog_content>
				<p>This MBL is already being tracked by our system. Do you want to track this as an LCL Shipment?</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" text @click="addShipmentAnyway()" :disabled="getCreateShipmentLoading">
                    <span v-if="getCreateShipmentLoading">Shipment Adding...</span>
					<span v-else>Yes, Continue</span>
				</v-btn>
				<v-btn class="btn-white" text @click="editMblNumber()" :disabled="getCreateShipmentLoading">
					<span>No, Change MBL</span>
				</v-btn>
			</template>
		</ConfirmDialog>

        <ConfirmDialog :dialogData.sync="createSingleShipmentPayloadData.existing_mbl_number">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Shipment Already Exist!</h2>    
			</template>

			<template v-slot:dialog_content>
				<p>There is an existing shipment with the same MBL number. Do you want to view the shipment?</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" text>
					<span><a :href="'/shipment/'+createSingleShipmentPayloadData.existing_shipment_id" style="color: #FFFFFF; text-decoration: none;">View Shipment</a></span>
				</v-btn>
				<v-btn class="btn-white" text @click="editMblNumber()">
					<span>Edit MBL</span>
				</v-btn>
			</template>
		</ConfirmDialog>

        <ConfirmDialog :dialogData.sync="createSingleShipmentPayloadData.existing_fcl_shipment">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/icon-delete.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Shipment Already Exist!</h2>
			</template>

			<template v-slot:dialog_content>
				<p>There is an existing shipment with the same MBL number. Do you want to view the shipment?</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" text>
					<span><a :href="'/shipment/'+createSingleShipmentPayloadData.existing_shipment_id" style="color: #FFFFFF; text-decoration: none;">View Shipment</a></span>
				</v-btn>
				<v-btn class="btn-white" text @click="editMblNumber()">
					<span>Edit MBL</span>
				</v-btn>
			</template>
		</ConfirmDialog>

        <ConfirmDialog :dialogData.sync="shipmentInfoUpdated">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/CircleCheckOutline.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Shipment Updated Successfully</h2>
			</template>

			<template v-slot:dialog_content>
				<p>Your shipment has been successfully updated! you can view it.</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" text>
					<span><a :href="'/shipment/'+createSingleShipmentId" style="color: #FFFFFF; text-decoration: none;">View Shipment</a></span>
				</v-btn>
				<v-btn class="btn-white" text @click="shipmentInfoUpdated = false">
					<span>Close</span>
				</v-btn>
			</template>
		</ConfirmDialog>

    </div>
</template>

<script>
// import Filters from "@/components/Filters.vue"
// import CreateShipment from '../components/ShipmentComponents/CreateShipment'
import { mapActions, mapGetters } from "vuex"
// import Search from '../components/Search.vue'
import ShipmentsDesktopTable from '../components/Tables/Shipments/ShipmentsDesktopTable.vue'
import ShipmentsMobileTable from '../components/Tables/Shipments/ShipmentsMobileTable.vue'
import TrackShipment from '../components/ShipmentComponents/TrackShipment/TrackShipmentDialog.vue'
import _ from 'lodash'
import axios from 'axios'
import KeneticIcon from '../components/Icons/KeneticIcon'
import GenericIcon from '../components/Icons/GenericIcon'
import CreateShipmentDialog from '../components/Dialog/FormShipmentDialog/CreateShipmentDialog'
import BookingShipmentDialog from '../components/Dialog/FormShipmentDialog/BookingShipmentDialog'
import AddShipmentDialogModal from '../components/Dialog/FormShipmentDialog/Modals/AddShipmentModal'
import NoConnectedSupplierModal from '../components/Dialog/FormShipmentDialog/Modals/NoConnectedSupplierModal'
import ShipmentDropdown from '../components/Dropdown/ShipmentDropdown';
import CustomIcon from "@/components/Icons/CustomIcon";
import ArchivedShipment from "../components/Tables/Shipments/ArchivedShipment.vue";
import BulkShipmentAddedModal from '@/components/Dialog/FormShipmentDialog/Modals/BulkShipmentAddedModal'
import CreateSingleShipmentDialog from '../components/Dialog/FormShipmentDialog/CreateSingleShipmentDialog'
import CreateSingleShipmentSuccessDialog from '../components/Dialog/FormShipmentDialog/CreateSingleShipmentSuccessDialog'
import AddMoreShipmentInfoDialog from '../components/Dialog/FormShipmentDialog/AddMoreShipmentInfoDialog'
import ConfirmDialog from "@/components/Dialog/GlobalDialog/ConfirmDialog.vue";
import BookingItem from "../components/Dialog/FormShipmentDialog/Structures/BookingItem";
import moment from "moment";

let cancel
let CancelToken = axios.CancelToken

export default {
    components: {
        // Filters: Filters,
        // CreateShipment
        // Search,
        // Icon,
        KeneticIcon,
        AddShipmentDialogModal,
        ShipmentsDesktopTable,
        ShipmentsMobileTable,
        TrackShipment,
        CreateShipmentDialog,
        BookingShipmentDialog,
        NoConnectedSupplierModal,
        ShipmentDropdown,
        GenericIcon,
        CustomIcon,
        ArchivedShipment,
        BulkShipmentAddedModal,
        CreateSingleShipmentDialog,
        CreateSingleShipmentSuccessDialog,
        AddMoreShipmentInfoDialog,
        ConfirmDialog,
    },
    data: () => ({
        windowWidth: 0,
        menu: false,
        isEdit: false,
        addShipmentDialogModalView: false,
        refNumber: '',
        shipmentDropDownClicked: false,
        submitRequestModalView: false,
        closeNoConnectedSupplierModalView: false,
        closeNoConnectedSupplierModalRole: '',
        bookingRequestSubmittedModalView: false,
        page: 1,
        searchPendingData: '',
        createShipmentRules: {
            mbl_num: [
                (v) => !!v || "MBL number is required."
            ],
            etd: [
                (v) => !!v || "ETD is required."
            ],
            eta: [
                (v) => !!v || "ETA is required."
            ],
            contact_number: [
                (v) => !!v || "Contact Number is required."
            ]
        },
        createShipmentDialogView: false,
        bookingShipmentDialogView: false,
        dialog: false,
        isMobile: false,
        itemsPerPage: 35,
        typingTimeout: 0,
        tabs: ["Pending Approval", "Shipments", "Completed"],
        // tabs: ["Pending Approval", "Shipments"],
        tabsMobile: [
            {
                value: 0,
                label: "Booking"
            },
            {
                value: 1,
                label: "Shipments"
            },
            {
                value: 2,
                label: "Completed"
            }
        ],
        pendingSubTabs: [ "Draft","Pending Quote", "Pending Approval", "Snoozed", "Expired"],
        pendingSubTabsClasses: ['pending-quote', 'pending', 'snooze', 'expired', 'draft'],
        activeTab: 1,
        selectedTab: 1,
        pendingCurrentTab: 0,
        search: "",
        dialogDelete: false,
        editedIndex: -1,
        editedItem: {
            Departure: "",
            Arrival: "",
            Suppliers: "",
            PO: "",
            Status: "",
            id: "",
            mode: "",
            container_num_list: ""
        },
        defaultItem: {
            Departure: "",
            Arrival: "",
            Suppliers: "",
            PO: "",
            Status: "",
            id: "",
            mode: "",
            container_num_list: ""
        },
        dialogTrackShipment: false,
        editedTrackShipment: {
            mbl_num: '',
            po_num: null
        },
        defaultTrackShipment: {
            mbl_num: '',
            po_num: null
        },
        ArchivedShipmentFlag: false,
        dialogView: false,
        addBulkShipmentDialogModalView: false,
        shipmentCounter: '',
        createSingleShipmentDialogView: false,
        createSingleShipmentSuccessDialogView: false,
        createSingleShipmentId: '',
        showAddMoreShipmentInfoDialog: false,
        createSingleShipmentPayloadData: {},
        createdShipmentType: '',
        getShipmentDetailsToEditComp: {},
        shipmentInfoUpdated: false,
        shipmentEditItem: BookingItem,
        isShipmentDetailExist: {},
        currentViewTab: 0,
        viewTabsName: [
            {
                name: 'Default View',
                image: 'default-view'
            },
            {
                name: 'Calendar View',
                image: 'calendar-view'
            },
            {
                name: 'Card View',
                image: 'list-view'
            },
            {
                name: 'Compact View',
                image: 'compact-view'
            },
        ]
    }),
    computed: {
        ...mapGetters([
            "getAllShipments", 
            "getAllPendingShipments",
            "getAllDraftShipments",
            "getAllDraftShipmentsLoading",
            "getAllPendingQuoteShipments",
            "getAllSnoozeShipments",
            "getAllExpiredShipments",
            "getAllCompletedShipments", 
            "getShipmentLoadingStatus", 
            "getAllPendingShipmentsLoading",
            "getAllExpiredShipmentsLoading",
            "getAllCompletedShipmentsLoading",
            "getSearchedShipments",
            "getSearchedShipmentLoading",
            "getCreateShipmentLoading",
            "getTerminalRegions"
        ]),
        getEditingShipment() {
            return this.$store.getters['page/getEditingShipment']
        },
        getEditingDraftShipment(){
          return this.$store.getters['page/getEditingDraftShipment']
        },
        getMarkingBookedExternal() {
            return this.$store.getters['page/getMarkingBookedExternal']
        },
        getAddingShipmentTracking() {
            return this.$store.getters['page/getAddingShipmentTracking']
        },
        getCreatingBookingRequest() {
            return this.$store.getters['page/getCreatingBookingRequest']
        },
        activeTabComputed: {
            get() {
                if (this.selectedTab>0) {
                    return this.activeTab
                } else {
                    return this.pendingCurrentTab
                }
            },
            set(value) {
                if (this.selectedTab > 0) {
                    this.activeTab = value
                } else {
                    this.pendingCurrentTab = value
                }
            }
        },
        tabsComputed() {
            if ( this.selectedTab > 0) {
                let newTabs = []
                newTabs = ['Pending Approval','Shipments', 'Completed']
                // newTabs = ['Pending Approval', 'Shipments']
                return newTabs
            } else {
                return this.pendingSubTabs
            }
        },
        formTitle() {
            return this.editedIndex === -1 ? "New Item" : "Edit Item"
        },
        shipments() {
            let data = []
            if (typeof this.getAllShipments.shipments !== 'undefined') {
                data = this.getAllShipments.shipments
            }
            return data
        },
        snooze() {
            let data = []
            if (typeof this.getAllSnoozeShipments.shipments !== 'undefined') {
                data = this.getAllSnoozeShipments.shipments
            }
            return data
        },
        pendingQuote() {
            let data = []
            
            if (typeof this.getAllPendingQuoteShipments.shipments !== 'undefined') {
                data = this.getAllPendingQuoteShipments.shipments
            }
            return data
        },
        pending() {
            let data = []
            
            if (typeof this.getAllPendingShipments.shipments !== 'undefined') {
                data = this.getAllPendingShipments.shipments
            }

            return data
        },
        expired() {
            let data = []
            
            if (typeof this.getAllExpiredShipments.shipments !== 'undefined') {
                data = this.getAllExpiredShipments.shipments
            }

            return data
        },
        draft() {
            let data = []
            
            if (typeof this.getAllDraftShipments.shipments !== 'undefined') {
                data = this.getAllDraftShipments.shipments
            }

            return data
        },
        completed() {
            let data = []
            
            if (typeof this.getAllCompletedShipments.shipments !== 'undefined') {
                data = this.getAllCompletedShipments.shipments
            }

            return data
        }
    },
    watch: {
        dialog(val) {
            val || this.close();
        },
        dialogDelete(val) {
            val || this.closeDelete();
        },        
    },
    async created() {
    },
    async mounted() {

        //clear shipment documents
        this.clearShipmentDocuments()

        //set current page
        this.$store.dispatch("page/setPage", "shipments")
        this.windowWidth = window.innerWidth

        //set tab
        if (this.$store.state.page.currentTab !== 'undefined') {
            if (this.activeTab !== this.$store.state.page.currentTab) {
                this.activeTab = this.$store.state.page.currentTab
            }
        }

        // set current shipment page
        if (this.$store.state.page.currentShipmentPage !== 'undefined') {
            if (this.page !== this.$store.state.page.currentShipmentPage) {
                this.page = this.$store.state.page.currentShipmentPage
            }
        }

        // set current pending tab
        if (this.$store.state.page.currentPendingShipmentTab !== 'undefined') {
            if (this.pendingCurrentTab !== this.$store.state.page.currentPendingShipmentTab) {
                this.pendingCurrentTab = this.$store.state.page.currentPendingShipmentTab
            }
        }

        this.selectedTab = this.$store.state.page.currentTab

        //clear tooltips
        this.clear()

        //call all shipments api
        this.callShipmentsAPI()
    },
    methods: {
        ...mapActions([
            "fetchShipments", 
            "fetchCompletedShipments", 
            "fetchPendingShipments",
            "fetchPendingQuoteShipments",
            "fetchSnoozeShipments",
            "fetchDraftShipments",
            "fetchExpiredShipments",
            "setShipmentPagination",
            "setPendingPagination",
            "setPendingQuotePagination",
            "setDraftPagination",
            "setSnoozePagination",
            "setExpiredPagination",
            "setCompletedPagination",
            "fetchShipmentsSearched",
            "setSearchedPagination",
            "setShipmentSearchedVal",
            "setSearchedShipmentsLoading",
            "clearTooltips",
            "clearShipmentDocuments",
            "fetchShipmentDetails",
            "createShipment"
        ]),
        showEditShipment(item) {
            this.editedIndex = this.shipments.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.isEdit = true
            if ( this.getMarkingBookedExternal ) {
                this.createShipmentDialogView = true
            } else {
                this.bookingShipmentDialogView = true
            }
            
            
        },
        markBookedExternal() {
            console.log('edited item', this.editedItem)
        },
        closeNoSupplierConnectedModal() {
            this.closeNoConnectedSupplierModalView = false
        },
        closeAddShipmentModal() {
            this.addShipmentDialogModalView = false
        },
        addAnotherShipment() {
            this.addBulkShipmentDialogModalView = false
            this.createSingleShipmentDialogView = true
        },
        handleCloseCreateShipmentDialog() {
            this.createShipmentDialogView = false
        },
        handleCloseBookingShipmentDialog() {
            this.bookingShipmentDialogView = false
            this.$store.dispatch('page/setEditingDraftShipment', false)
        },
        showBookingShipmentDialog() {
            this.bookingShipmentDialogView = true
        },
        showAddShipmentDialog() {
            this.createShipmentDialogView = true
        },
        showSingleAddShipmentDialog() {
            this.createSingleShipmentDialogView = true
        },
        showSingleShipmentSuccessDialog() {
            this.createSingleShipmentDialogView = false;
            this.createSingleShipmentSuccessDialogView = true;
            this.createSingleShipmentPayloadData.invalid_mbl_number = false;
            this.createSingleShipmentPayloadData.existing_mbl_number = false;
            this.createSingleShipmentPayloadData.existing_fcl_shipment = false;
            this.createSingleShipmentPayloadData.existing_lcl_shipment = false;
        },
        showAddMoreShipmentInfo() {
            this.createSingleShipmentDialogView = false
            this.createSingleShipmentSuccessDialogView = false
            this.showAddMoreShipmentInfoDialog = true
        },
        getCurrentTabMobile(id) {
            if ( this.activeTab==0 ) {
                this.pendingCurrentTab = id
                //this.$store.dispatch("page/setTab", id)
            } else {
                /*
                if ( this.selectedTab== 0 ) {
                    this.pendingCurrentTab = id
                } else {
                    this.selectedTab = id
                }*/
                this.activeTab = id
                this.selectedTab = id
            }
        },
        getCurrentTab(id) {
            this.selectedTab = id
            this.$store.dispatch("page/setTab", id)
        },
        selectTab(value) {
            this.$store.dispatch("page/setCurrentShipmentPage", 1)
            this.selectedTab = value
            this.$store.dispatch("page/setTab", value)
            this.activeTab = value
            this.pendingCurrentTab = 0
            this.$store.dispatch("page/setCurrentPendingShipmentTab", 0)
            this.onPendingTabChange()
        },
        clear(e) {
            if (typeof e !=='undefined') {
                let classList = e.target.classList
                if (!classList.contains('custom-tooltip')) {

                    if ( typeof this.getAllPendingQuoteShipments!=='undefined' && this.getAllPendingQuoteShipments.shipments!=='undefined' ) {
                        this.clearTooltips(this.getAllPendingQuoteShipments) 
                    }

                    if ( typeof this.getAllSnoozeShipments!=='undefined' && this.getAllSnoozeShipments.shipments!=='undefined' ) {
                        let snoozeShipments = this.getAllSnoozeShipments
                        snoozeShipments.shipment_type = 'snooze'
                        this.clearTooltips(snoozeShipments) 
                    }

                    if ( typeof this.getAllPendingShipments!=='undefined' && this.getAllPendingShipments.shipments!=='undefined' ) {
                        let pendingShipments = this.getAllPendingShipments
                        pendingShipments.shipment_type = 'pending'
                        this.clearTooltips(pendingShipments) 
                    }

                    if ( typeof this.getAllExpiredShipments!=='undefined' && this.getAllExpiredShipments.shipments!=='undefined' ) {
                        let expiredShipments = this.getAllExpiredShipments
                        expiredShipments.shipment_type = 'expired'
                        this.clearTooltips(expiredShipments) 
                    }
                    
                }    
            } else {
                let limit = 10000
                let start = 0
                let t = setInterval(() => {

                    if ( start === limit) {
                        clearInterval(t)
                    } else {
                        if (typeof this.getAllPendingQuoteShipments!=='undefined' && this.getAllPendingQuoteShipments.shipments!=='undefined') {
                            let pendingQuoteShipments = this.getAllPendingQuoteShipments
                            let expiredShipments = this.getAllExpiredShipments
                            let snoozeShipments = this.getAllSnoozeShipments
                            let pendingShipments = this.getAllPendingShipments
                            expiredShipments.shipment_type = 'expired'
                            snoozeShipments.shipment_type = 'snooze'
                            pendingShipments.shipment_type = 'pending'

                            this.clearTooltips(pendingQuoteShipments)
                            this.clearTooltips(expiredShipments)
                            this.clearTooltips(snoozeShipments)
                            this.clearTooltips(pendingShipments)
                            clearInterval(t)
                        } else {
                            start+=200
                        }    
                    }
                },200)
                
            }
            
        },
        clearSearched() {
            if (this.search !== '') {
                document.getElementById("search-input").focus();
            }
            this.search = ''
            this.searchPendingData = ''
            setTimeout(() => {
                this.setShipmentSearchedVal([])
            }, 300);
        },
        isShowSearchShipment() {
            if (this.activeTab === 0) {
                if (this.pendingCurrentTab === 0 && this.draft.length > 0) {
                    return true
                } else if (this.pendingCurrentTab === 1 && this.pendingQuote.length > 0) {
                    return true
                } else if (this.pendingCurrentTab === 2 && this.pending.length > 0 ) {
                    return true
                } else if (this.pendingCurrentTab === 3 && this.snooze.length > 0 ) {
                    return true
                } else if (this.pendingCurrentTab === 4 && this.expired.length > 0 ) {
                    return true
                } else {
                    return false
                }
                /*
                if (this.pendingCurrentTab === 0 && this.pendingQuote.length > 0) {
                    return true
                } else if (this.pendingCurrentTab === 1 && this.pending.length > 0) {
                    return true
                } else if (this.pendingCurrentTab === 2 && this.snooze.length > 0 ) {
                    return true
                } else if (this.pendingCurrentTab === 3 && this.expired.length > 0 ) {
                    return true
                } else if (this.pendingCurrentTab === 4 && this.draft.length > 0 ) {
                    return true
                } else {
                    return false
                }*/
            }
        },
        handleGlobalClick(e) {
            this.clear(e)
        },
        getShipmentCount(tab) {
            let data = '0'

            if (tab == 0) {
                let pendingLength = typeof this.getAllPendingShipments.total !== 'undefined' ? this.getAllPendingShipments.total : 0             
                let snoozeLength = typeof this.getAllSnoozeShipments.total !== 'undefined' ? this.getAllSnoozeShipments.total : 0
                let pendingQuoteLength = typeof this.getAllPendingQuoteShipments.total !== 'undefined' ? this.getAllPendingQuoteShipments.total : 0

                let draftLength = typeof this.getAllDraftShipments.total !== 'undefined' ? this.getAllDraftShipments.total : 0

                // let expiredLength = typeof this.getAllExpiredShipments.total !== 'undefined' ? this.getAllExpiredShipments.total : 0
                // data = parseInt(pendingLength + expiredLength + snoozeLength + pendingQuoteLength)

                data = parseInt(pendingLength + snoozeLength + pendingQuoteLength + draftLength)
            } else {
                data = typeof this.getAllShipments.total !== 'undefined' ? this.getAllShipments.total : 0
            }

            let finalData = data !== 0 ? data : '0'

            return finalData
        },
        async onTabMobileChange(id) {
            
            this.page = 1;
            // set current shipment page back to 1 if user changes tab
            this.$store.dispatch("page/setCurrentShipmentPage", 1)
            this.search = ''
            this.searchPendingData = ''
            this.setShipmentSearchedVal([]) 
            
            if ( this.selectedTab === 0 ) {
                this.activeTab = 0
                this.pendingCurrentTab = id
                this.onPendingTabChange()
            } else {
                this.page = 1;
                // set current shipment page back to 1 if user changes tab
                this.$store.dispatch("page/setCurrentShipmentPage", 1)
                this.$store.dispatch("page/setCurrentPendingShipmentTab", 0)
                this.$store.dispatch("page/setTab", id)
                this.search = ''
                this.setShipmentSearchedVal([])
                this.activeTab = id
                this.selectedTab = id
            }
        },
        async onTabChange() {
            this.page = 1;
            // set current shipment page back to 1 if user changes tab
            this.$store.dispatch("page/setCurrentShipmentPage", 1)
            this.$store.dispatch("page/setCurrentPendingShipmentTab", 0)
            this.search = ''
            this.searchPendingData = ''
            this.setShipmentSearchedVal([])    
        },
        onPendingTabChange() {
            this.$store.dispatch("page/setCurrentPendingShipmentTab", this.pendingCurrentTab)
            this.search = ''
            this.searchPendingData = ''
            let expiredShipments = this.getAllExpiredShipments
            let pendingShipments = this.getAllPendingShipments
            let pendingQuoteShipments = this.getAllPendingQuoteShipments
            let snoozeShipments = this.getAllSnoozeShipments

            expiredShipments.shipment_type = 'expired'
            pendingShipments.shipment_type = 'pending'
            pendingQuoteShipments.shipment_type = 'pendingQuote'
            snoozeShipments.shipment_type = 'snooze'

            this.clearTooltips(pendingQuoteShipments)
            this.clearTooltips(pendingShipments)
            this.clearTooltips(snoozeShipments)
            this.clearTooltips(expiredShipments)
            this.setShipmentSearchedVal([])
        },
        setMenu(value) {
            this.menu = value;
        },
        onResize() {
            this.windowWidth = window.innerWidth
            if (window.innerWidth < 769) {
                this.isMobile = true
            } else {
                this.isMobile = false
            }
        },
        openTrackShipment() {
            this.dialogTrackShipment = true
            this.$nextTick(() => {
                this.editedTrackShipment = Object.assign({}, this.defaultTrackShipment);
            });
        },
        closeTrackShipment() {
            this.dialogTrackShipment = false
            this.$nextTick(() => {
                this.editedTrackShipment = Object.assign({}, this.defaultTrackShipment);
            });
        },
        editItem(item) {
            this.editedIndex = this.shipments.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },
        deleteItem(item) {
            this.editedIndex = this.shipments.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialogDelete = true;
        },
        deleteItemConfirm() {
            this.shipments.splice(this.editedIndex, 1);
            this.closeDelete();
        },
        close() {
            this.dialog = false;
            this.$nextTick(() => {
            this.editedItem = Object.assign({}, this.defaultItem);
            this.editedIndex = -1;
            });
        },
        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
            this.editedItem = Object.assign({}, this.defaultItem);
            this.editedIndex = -1;
            });
        },
        save() {
            if (this.editedIndex > -1) {
                Object.assign(this.shipments[this.editedIndex], this.editedItem);
            } else {
                this.shipments.push(this.editedItem);
            }

            this.close();
        },
        currentTabPendingQuote() {
            if (typeof this.getAllPendingQuoteShipments !== 'undefined') {
                let pending = this.getAllPendingQuoteShipments
                this.getTotalPendingQuoteShipments(pending)
            } else {
                this.currentTabPendingQuote()
            }
        },
        currentTabPending() {
            if (typeof this.getAllPendingShipments !== 'undefined') {
                let pending = this.getAllPendingShipments
                this.getTotalPendingShipments(pending)
            } else {
                this.currentTabPending()
            }
        },
        currentTabDraft() {
            if (typeof this.getAllDraftShipments !== 'undefined') {
                let pending = this.getAllDraftShipments
                this.getTotalDraftShipments(pending)
            } else {
                this.currentTabDraft()
            }
        },
        currentTabSnooze() {
            if (typeof this.getAllSnoozeShipments !== 'undefined') {
                let expired = this.getAllSnoozeShipments
                this.getTotalSnoozeShipments(expired)
            } else {
                this.currentTabSnooze()
            }
        },
        currentTabExpired() {
            if (typeof this.getAllExpiredShipments !== 'undefined') {
                let expired = this.getAllExpiredShipments
                this.getTotalExpiredShipments(expired)
            } else {
                this.currentTabExpired()
            }
        },
        currentTabShipment() {
            if (typeof this.getAllShipments !== 'undefined') {
                let shipments = this.getAllShipments
                this.getTotalShipments(shipments)
            } else {
                this.currentTabShipment()
            }
        },
        currentTabCompleted() {
            if (typeof this.getAllCompletedShipments !== 'undefined') {
                let completed = this.getAllCompletedShipments
                this.getTotalCompletedShipments(completed)
            } else {
                this.currentTabCompleted()
            }
        },
        getTotalSnoozeShipments(shipments_data) {
            let pagination = {
                currentTab: 1,
                currentSubTab: 0,
                current_page: 1,
                old_page: 1,
                total: 0,
                per_page: 0
            }

            if (shipments_data !== null) {
                if (typeof shipments_data.last_page !== 'undefined') {
                    pagination.total = shipments_data.last_page
                }

                if (typeof shipments_data.current_page !== 'undefined') {
                    pagination.current_page = shipments_data.current_page
                    pagination.old_page = shipments_data.current_page
                }

                if (typeof shipments_data.per_page !== 'undefined') {
                    pagination.per_page = shipments_data.per_page
                }
            }

            this.setSnoozePagination(pagination)
        },
        getTotalPendingQuoteShipments(shipments_data) {
            let pagination = {
                currentTab: 1,
                currentSubTab: 0,
                current_page: 1,
                old_page: 1,
                total: 0,
                per_page: 0
            }
            
            if (shipments_data !== null) {
                if (typeof shipments_data.last_page !== 'undefined') {
                    pagination.total = shipments_data.last_page
                }

                if (typeof shipments_data.current_page !== 'undefined') {
                    pagination.current_page = shipments_data.current_page
                    pagination.old_page = shipments_data.current_page
                }

                if (typeof shipments_data.per_page !== 'undefined') {
                    pagination.per_page = shipments_data.per_page
                }
            }

            this.setPendingQuotePagination(pagination)
        },
        getTotalDraftShipments(shipments_data) {
            let pagination = {
                currentTab: 1,
                currentSubTab: 0,
                current_page: 1,
                old_page: 1,
                total: 0,
                per_page: 0
            }
            
            if (shipments_data !== null) {
                if (typeof shipments_data.last_page !== 'undefined') {
                    pagination.total = shipments_data.last_page
                }

                if (typeof shipments_data.current_page !== 'undefined') {
                    pagination.current_page = shipments_data.current_page
                    pagination.old_page = shipments_data.current_page
                }

                if (typeof shipments_data.per_page !== 'undefined') {
                    pagination.per_page = shipments_data.per_page
                }
            }
            this.setDraftPagination(pagination)
        },
        getTotalPendingShipments(shipments_data) {
            let pagination = {
                currentTab: 1,
                currentSubTab: 0,
                current_page: 1,
                old_page: 1,
                total: 0,
                per_page: 0
            }
            
            if (shipments_data !== null) {
                if (typeof shipments_data.last_page !== 'undefined') {
                    pagination.total = shipments_data.last_page
                }

                if (typeof shipments_data.current_page !== 'undefined') {
                    pagination.current_page = shipments_data.current_page
                    pagination.old_page = shipments_data.current_page
                }

                if (typeof shipments_data.per_page !== 'undefined') {
                    pagination.per_page = shipments_data.per_page
                }
            }

            this.setPendingPagination(pagination)
        },
        getTotalExpiredShipments(shipments_data) {
            let pagination = {
                currentTab: 1,
                currentSubTab: 0,
                current_page: 1,
                old_page: 1,
                total: 0,
                per_page: 0
            }
            
            if (shipments_data !== null) {

                if (typeof shipments_data.last_page !== 'undefined') {
                    pagination.total = shipments_data.last_page
                }

                if (typeof shipments_data.current_page !== 'undefined') {
                    pagination.current_page = shipments_data.current_page
                    pagination.old_page = shipments_data.current_page
                }

                if (typeof shipments_data.per_page !== 'undefined') {
                    pagination.per_page = shipments_data.per_page
                }
            }

            this.setExpiredPagination(pagination)
        },
        getTotalShipments(shipments_data) {
            let pagination = {
                currentTab: 1,
                currentSubTab: 0,
                current_page: 1,
                old_page: 1,
                total: 0,
                per_page: 0
            }

            if (shipments_data !== null) {
                if (typeof shipments_data.last_page !== 'undefined') {
                    pagination.total = shipments_data.last_page
                }

                if (typeof shipments_data.current_page !== 'undefined') {
                    pagination.current_page = shipments_data.current_page
                    pagination.old_page = shipments_data.current_page
                }

                if (typeof shipments_data.per_page !== 'undefined') {
                    pagination.per_page = shipments_data.per_page
                }
            }

            this.setShipmentPagination(pagination)
        },
        getTotalCompletedShipments(shipments_data) {
            let pagination = {
                currentTab: 1,
                currentSubTab: 0,
                current_page: 1,
                old_page: 1,
                total: 0,
                per_page: 0
            }
            
            if (shipments_data !== null) {
                if (typeof shipments_data.last_page !== 'undefined') {
                    pagination.total = shipments_data.last_page
                }

                if (typeof shipments_data.current_page !== 'undefined') {
                    pagination.current_page = shipments_data.current_page
                    pagination.old_page = shipments_data.current_page
                }

                if (typeof shipments_data.per_page !== 'undefined') {
                    pagination.per_page = shipments_data.per_page
                }
            }

            this.setCompletedPagination(pagination)
        },  
        getTotalSearchedShipments(shipments_data) {
            let pagination = {
                currentTab: 1,
                currentSubTab: 0,
                current_page: 1,
                old_page: 1,
                total: 0,
                per_page: 0
            }

            if (shipments_data !== null) {
                if (typeof shipments_data.last_page !== 'undefined') {
                    pagination.total = shipments_data.last_page
                }

                if (typeof shipments_data.current_page !== 'undefined') {
                    pagination.current_page = shipments_data.current_page
                    pagination.old_page = shipments_data.current_page
                }

                if (typeof shipments_data.per_page !== 'undefined') {
                    pagination.per_page = shipments_data.per_page
                }
            }

            this.setSearchedPagination(pagination)
        },
        async handleSearchPending(tab) {
            if (cancel !== undefined) {
                cancel()
            }
            this.setSearchedShipmentsLoading(false)
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                this.search = this.searchPendingData
                let data = { 
                    search: this.search,
                    tab
                }   
                this.setSearchedShipmentsLoading(true)
                this.apiCall(data)
            },800)
        },
        async handleSearch(tab) {
            if (cancel !== undefined) {
                cancel()
            }
            this.setSearchedShipmentsLoading(false)
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                let data = { 
                    search: this.search,
                    tab
                }
                this.searchPendingData = this.search
                this.setSearchedShipmentsLoading(true)
                this.apiCall(data)
            },800)
        },
        handleSearchBak: _.debounce(function(tab) {
            this.preApiCall(tab)
        }, 300),
        preApiCall(tab) {
            if (cancel !== undefined) {
                cancel()
                this.setSearchedShipmentsLoading(false)
                console.log("cancelled");
            } 

            let data = { 
                search: this.search,
                tab
            }

            this.apiCall(data)
        },
        apiCall(data) {
            if (data !== null && this.search !== '') {
                let passedData = {
                    method: "get",
                    url: "/v2/shipments/search",
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: {
                        q: data.search,
                        tab: data.tab,
                        page: 1
                    }
                }

                // if (data.tab === 'shipments' || data.tab === 'shipments-completed-merge') {
                if (data.tab === 'shipments') {
                    // passedData.params.tab = 'shipments-completed-merge'
                    passedData.params.sort = 'eta'
                    passedData.params.order = this.$store.state.shipment.shipmentOrder.order
                } else if (data.tab === 'completed') {
                    passedData.params.sort = 'eta'
                    passedData.params.order = this.$store.state.shipment.completedOrder.order
                }

                passedData.params.for_testing = 1

                this.fetchShipmentsSearched(passedData).then(() => {
                    if (typeof this.getSearchedShipments !== 'undefined' && this.getSearchedShipments !== null) {
                        this.getTotalSearchedShipments(this.getSearchedShipments)
                    }
                }).catch(e => {
                    this.setSearchedShipmentsLoading(false)
                    console.log(e, 'Search error')
                })
                /*
                try {

                    await this.fetchShipmentsSearched(passedData)

                    if (typeof this.getSearchedShipments !== 'undefined' && this.getSearchedShipments !== null) {
                        await this.getTotalSearchedShipments(this.getSearchedShipments)
                    }
                } catch (e) {
                    console.log(e, 'error search');
                }*/
            } else {
                this.setShipmentSearchedVal([])
            }
        },
        async callShipmentsAPI() {
            let storePagination = this.$store.state.shipment.shipmentsPagination
            let storeShipmentTabData = this.$store.state.shipment

            let payloadShipments = {
                page: (storePagination.shipmentTab.currentTab === 1) ? storePagination.shipmentTab.current_page : 1,
                order: storeShipmentTabData.shipmentOrder.order
            }

            let payloadCompleted = {
                page: (storePagination.completedTab.currentTab === 2) ? storePagination.completedTab.current_page : 1,
                order: storeShipmentTabData.completedOrder.order
            }

            if ( this.shipments.length === 0 && 
                this.snooze.length === 0 && 
                this.pendingQuote.length === 0 && 
                this.pending.length === 0 && 
                this.expired.length === 0 && 
                this.completed.length === 0 &&
                this.draft.length === 0) {
                
                //synchronous all shipment api calls
                //so that all other data will not wait for transit
                this.fetchShipments(payloadShipments).then(() => {
                    this.currentTabShipment()
                })
                
                this.fetchPendingQuoteShipments((storePagination.pendingQuoteTab.currentTab === 0 && storePagination.pendingTab.currentSubTab === 0) ? storePagination.pendingQuoteTab.current_page : 1).then(() => {
                    this.currentTabPendingQuote()
                })
                
                this.fetchPendingShipments((storePagination.pendingTab.currentTab === 0 && storePagination.pendingTab.currentSubTab === 1) ? storePagination.pendingTab.current_page : 1).then(() => {
                    this.currentTabPending()
                })
                
                this.fetchSnoozeShipments((storePagination.snoozeTab.currentTab === 0 && storePagination.snoozeTab.currentSubTab === 2) ? storePagination.snoozeTab.current_page : 1).then(() => {
                    this.currentTabSnooze()
                })
                

                this.fetchDraftShipments((storePagination.draftTab.currentTab === 0 && storePagination.draftTab.currentSubTab === 2) ? storePagination.draftTab.current_page : 1).then(() => {
                    this.currentTabDraft()
                })

                this.fetchExpiredShipments((storePagination.expiredTab.currentTab === 0 && storePagination.expiredTab.currentSubTab === 3) ? storePagination.expiredTab.current_page : 1).then(() => {
                    this.currentTabExpired()
                })

                this.fetchCompletedShipments(payloadCompleted).then(() => {
                    this.currentTabCompleted()
                })
            }
        },
        showArchivedShipment() {
            this.ArchivedShipmentFlag = true;
        },
        hideArchivedShipment() {
            this.ArchivedShipmentFlag = false;
        },
        handleCloseCreateSingleShipmentDialog() {
            this.createSingleShipmentDialogView = false
        },
        handleCloseSingleShipmentSuccessDialog() {
            this.createSingleShipmentSuccessDialogView = false
        },
        handleCloseAddMoreShipmentInfoDialog() {
            this.showAddMoreShipmentInfoDialog = false
        },
        async fetchCreatedShipmentInfo() {
            await this.$store.dispatch("booking/fetchShipmentDetails", this.createSingleShipmentId);
        },
        getShipmentDetails() {
            return this.$store.getters["booking/getShipmentDetails"];
        },
        getShipmentDetailsLoading() {
            return this.$store.getters["booking/getShipmentDetailsLoading"];
        },
        createSingleShipmentPayload(payload) {
            payload['is_edit_dialog'] = true;
            this.createSingleShipmentPayloadData = payload;
        },
        editMblNumber() {
            this.createSingleShipmentPayloadData.existing_mbl_number = false;
            this.createSingleShipmentPayloadData.existing_lcl_shipment = false;
            this.createSingleShipmentPayloadData.existing_fcl_shipment = false;
            this.createSingleShipmentPayloadData.invalid_mbl_number = false;
            this.createSingleShipmentDialogView = true;
        },
        addShipmentAnyway() {
            if(this.createSingleShipmentPayloadData.mbl_num !== '') {
                this.createShipment(this.createSingleShipmentPayloadData)
                    .then((response) => {
                        if(response.data) {
                            if(this.createSingleShipmentPayloadData.invalid_mbl_number === true) {
                                this.createdShipmentType = 'withInValidMBL';
                                this.createSingleShipmentDialogView = false;
                                this.createSingleShipmentSuccessDialogView = true;
                            } else {
                                this.createdShipmentType = 'withValidMBL';
                                this.createSingleShipmentDialogView = false;
                                this.createSingleShipmentSuccessDialogView = true;
                            }
                            this.createSingleShipmentPayloadData.invalid_mbl_number = false;
                            this.createSingleShipmentPayloadData.existing_mbl_number = false;
                            this.createSingleShipmentPayloadData.existing_fcl_shipment = false;
                            this.createSingleShipmentPayloadData.existing_lcl_shipment = false;
                            this.createSingleShipmentId = response.data.shipment_id;
                            this.createSingleShipmentPayload.mbl_number = '';
                            this.createSingleShipmentPayload.booking_number = '';
                            this.createSingleShipmentPayload.customer_reference_number = '';
                        } else {
                            this.notificationError("SOMETHING WENT WRONG!");
                        }
                    })
                    .catch((e) => {
                        console.log(e);
                        this.notificationError("SOMETHING WENT WRONG!");
                    });
            }
        },
        passShipmentDetailsToEditComp(payload) {
            this.getShipmentDetailsToEditComp = payload;
            
            if(this.getShipmentDetailsToEditComp.mbl_num) {
                let getSelectedLocationFrom = this.getTerminalRegions.find((getTerminalRegions) => {
                    return getTerminalRegions.name == this.getShipmentDetailsToEditComp.location_from_name;
                });
                let getSelectedLocationTo = this.getTerminalRegions.find((getTerminalRegions) => {
                    return getTerminalRegions.name == this.getShipmentDetailsToEditComp.location_to_name;
                });
                this.shipmentEditItem.location_from = getSelectedLocationFrom?.id ? getSelectedLocationFrom.id : '';
                this.shipmentEditItem.location_to = getSelectedLocationTo?.id ? getSelectedLocationTo.id : '';
                this.shipmentEditItem.eta = moment(this.getShipmentDetailsToEditComp.eta).format("YYYY-MM-DD");
                this.shipmentEditItem.etd = moment(this.getShipmentDetailsToEditComp.etd).format("YYYY-MM-DD");
                this.shipmentEditItem.vessel = this.getShipmentDetailsToEditComp.vessel;
                this.shipmentEditItem.voyage_number = this.getShipmentDetailsToEditComp.voyage_number;
                this.shipmentEditItem.carrier_id = this.getShipmentDetailsToEditComp.carrier;

                let isLocationFromExist = this.shipmentEditItem.location_from ? true : false;
                let isLocationToExist = this.shipmentEditItem.location_to ? true : false;
                let isEtaExist = this.shipmentEditItem.eta ? true : false;
                let isEtdExist = this.shipmentEditItem.etd ? true : false;
                let isVesselExist = this.shipmentEditItem.vessel ? true : false;
                let isVoyageNumberExist = this.shipmentEditItem.voyage_number ? true : false;
                let isCarrierExist = this.shipmentEditItem.carrier_id ? true : false;

                this.isShipmentDetailExist = {
                    'isLocationFromExist': isLocationFromExist,
                    'isLocationToExist': isLocationToExist,
                    'isEtaExist': isEtaExist,
                    'isEtdExist': isEtdExist,
                    'isVesselExist': isVesselExist,
                    'isVoyageNumberExist': isVoyageNumberExist,
                    'isCarrierExist': isCarrierExist,
                }
            }
        },
        setSelectedView(i) {
            this.currentViewTab = i
        },
        getImgUrl(image_name, index) {
            if (image_name !== 'undefined' && image_name !== null) {
                let name = index === this.currentViewTab ? image_name + '-blue' : image_name;
				return require(`../assets/icons/shipment-view-icons/${name}.svg`)
			}
        }
    },
    updated() {}
}
</script>
<style lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');
/* @import '../assets/css/shipments_styles/shipment.css'; */
@import '../assets/scss/buttons.scss';
@import '../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../assets/scss/pages_scss/shipment/shipment.scss';
@import '../assets/scss/buttons.scss';
@import '../assets/scss/pages_scss/shipment/shipmentSearch.scss';
@import '../assets/scss/pages_scss/shipment/shipmentHeaderButtons.scss';
@import '../assets/scss/utilities.scss';
</style>
