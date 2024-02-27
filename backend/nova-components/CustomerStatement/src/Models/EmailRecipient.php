<?php

namespace Juliverdevshifl\CustomerStatement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailRecipient extends Model
{
    use SoftDeletes;
    
    public $table = 'customer_statement_email_recipients';
	protected $guarded = ['id'];
    protected $fillable = ['statement_id','table_id','name','email','is_sent','table_name','json'];

    public function statement()
    {
        return $this->belongsTo('Juliverdevshifl\CustomerStatement\Models\CustomerStatement');
    }

    public function getJsonAttribute($value){
        return json_decode($value);
    }

    public function setJsonAttribute($value){
        $this->attributes['json'] = json_encode($value);
    }
}