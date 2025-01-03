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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Bank Name
            $table->string('account_number'); // Account Number
            $table->string('account_holder_name'); //Account Holder Name
            $table->string('ifsc_code'); // IFSC Code
            $table->string('branch'); // Branch Name
            $table->string('address')->nullable(); // Bank Address
            $table->string('city')->nullable(); // City
            $table->string('country')->nullable(); // Country
            $table->string('contact_number')->nullable(); // Contact Number
            $table->string('email_address')->nullable(); // Email Address
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
