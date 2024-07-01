<?php

namespace App\Models\Order;

use App\Models\Master\RuleCalculationPoint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRulePoint extends Model
{
    use HasFactory;
    protected $table = 'order_rule_point';
    protected $guarded = [];

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function ruleCalculationPoint()
    {
        return $this->hasOne(RuleCalculationPoint::class, 'id', 'rule_calculation_point_id');
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
