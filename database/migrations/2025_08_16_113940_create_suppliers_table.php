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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
              // ربط المورد بالمستخدم
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); 

        // بيانات المورد الأساسية
        $table->string('company_name');       // اسم الشركة
        $table->string('business_type');      // نوع النشاط (صيدلية، معدات طبية.. الخ)
        $table->string('address')->nullable();
        $table->string('logo')->nullable();   // شعار الشركة

        // بيانات التحقق
        $table->string('commercial_registration_number'); // رقم السجل التجاري
        $table->string('identity_number');                // رقم الهوية
        $table->string('bank_account'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
