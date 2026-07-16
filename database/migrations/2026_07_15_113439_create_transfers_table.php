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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->date('transfer_date');
            $table->foreignId('from_warehouse_id')
            ->constrained('warehouses')
            ->cascadeOnDelete();

            $table->foreignId('to_warehouse_id')
            ->constrained('warehouses')
            ->onDelete('cascade');

            $table->decimal('discount',10,2)->default(0.00);
            $table->decimal('shipping',10,2)->default(0.00);
            $table->enum('status',['transfer','Pending','Received'])->default('Pending');
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
        Schema::dropIfExists('transfers');
    }
};
