<?php

namespace App\Observers;

use App\PurchaseOrder;
use App\PurchaseOrderItem;

class PurchaseOrderObserver
{

    public function created(PurchaseOrder $purchaseOrder)
    {
        if($purchaseOrder->po_num === '' || $purchaseOrder->po_num === null){
            $prefix = 'ITE';
            $num_code = 111000000 + $purchaseOrder->id;
            $purchaseOrder->po_num = $num_code;
        }
        if($purchaseOrder->po_items !== null){
            $items = json_decode($purchaseOrder->po_items);
            foreach($items as $item){
                PurchaseOrderItem::create([
                    'po_id'=>$purchaseOrder->id,
                    'item_id'=>$item->item->id,
                    'quantity'=>$item->quantity
                ]);
            }
        }
        
        $purchaseOrder->save();
    }

    public function updated(PurchaseOrder $purchaseOrder)
    {
        if($purchaseOrder->wasChanged('po_items')){
            $poItems = json_decode($purchaseOrder->po_items);
            $getPOItems = PurchaseOrderItem::select('item_id')->where('po_id','=',$purchaseOrder->id)->get();
            $itemIds = [];
            //Edit quantity
            foreach($poItems as $it){
                $itemIds[] = $it->item->id;
                PurchaseOrderItem::where('po_id','=',$purchaseOrder->id)
                    ->where('item_id','=',$it->item->id)
                    ->update(['quantity'=>$it->quantity]);
            }
            //Delete Item if removed from Edit
            foreach($getPOItems as $poi){
                if(!in_array($poi->item_id,$itemIds)){
                    PurchaseOrderItem::where('po_id','=',$purchaseOrder->id)
                    ->where('item_id','=',$poi->item_id)
                    ->delete();
                }
            }
        }
    }

    public function deleted(PurchaseOrder $purchaseOrder)
    {
        //
    }

    public function restored(PurchaseOrder $purchaseOrder)
    {
        //
    }

    public function forceDeleted(PurchaseOrder $purchaseOrder)
    {
        //
    }
}
