<?php

namespace App\Models\Order;

use App\Models\Master\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'order';
    protected $guarded = [];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function orderItem()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id')->whereNull('deleted_at');
    }

    public function orderRulePoint()
    {
        return $this->hasMany(OrderRulePoint::class, 'order_id', 'id')->whereNull('deleted_at');
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedBy()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function deletedBy()
    {
        return $this->hasOne(User::class, 'id', 'deleted_by');
    }
}
