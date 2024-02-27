<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class Item extends Model
{
    protected $fillable = ['item_num', 'customer_id', 'description','classification_code','duty_rate'];

    public function purchase_order()
    {
        return $this->belongsToMany(PurchaseOrder::class, PurchaseOrderItem::class, 'item_id', 'po_id')->withPivot('quantity');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'item_suppliers', 'item_id', 'supplier_id');
    }
}
