<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_rule_point', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('id')->autoIncrement();
            $table->integer('order_id');
            $table->integer('rule_calculation_point_id');
            $table->double('point');
            $table->integer('created_by');
            $table->timestamp('created_at');
            $table->integer('updated_by');
            $table->timestamp('updated_at');
            $table->integer('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();

            // Foreign Key
            $table->foreign('order_id')->references('id')->on('order');
            $table->foreign('rule_calculation_point_id')->references('id')->on('rule_calculation_point');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_rule_pointt_');
    }
};
