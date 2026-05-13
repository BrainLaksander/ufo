<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->unsignedBigInteger('account_user_id')->nullable()->after('status');
            $table->string('account_email')->nullable()->after('account_user_id');
            $table->foreign('account_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign(['account_user_id']);
            $table->dropColumn(['account_user_id','account_email']);
        });
    }
};
