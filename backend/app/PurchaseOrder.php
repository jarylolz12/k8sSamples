<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class PurchaseOrder extends Model
{
    protected $fillable = ['po_num', 'supplier_id', 'po_items'];

    public function items()
    {
        return $this->belongsToMany(Item::class, PurchaseOrderItem::class, 'po_id', 'item_id')->withPivot('quantity');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }
}
