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
        Schema::create('return_purchases', function (Blueprint $table) {
            $table->id();
            $table->date('purchase_date');
            $table->foreignId('warehouse_id')
            ->nullable()
            ->constrained('warehouses')
            ->nullOnDelete();

            $table->foreignId('supplier_id')
            ->nullable()
            ->constrained('suppliers')
            ->nullOnDelete();

            $table->decimal('discount',10,2)->default(0.00);
            $table->decimal('shipping',10,2)->default(0.00);
            $table->enum('status', [
                    'Pending',
                    'Approved',
                    'Returned',
                    'Cancelled'
                ])->default('Pending');
            $table->text('note')->nullable();
            $table->decimal('grand_total',15,2)->default(0.00);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_purchases');
    }
};
