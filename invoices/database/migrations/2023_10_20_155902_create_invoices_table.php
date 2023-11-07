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

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->date('invoice_data')->nullable();
            $table->date('due_data')->nullable();
            $table->string('product',50);
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->string('rate_vat');
            $table->decimal('discount',8,2);
            $table->decimal('value_vat' , 8 , 2);
            $table->decimal('Amount_collection',8,2)->nullable();
            $table->decimal('Amount_Commission',8,2);
            $table->decimal('total' , 8 , 2);
            $table->string('status' , 50);
            $table->integer('value_status');
            $table->text('note')->nullable();
            $table->string('user');
            $table->date('Payment_Date')->nullable();
            $table->softDeletes();
            $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
