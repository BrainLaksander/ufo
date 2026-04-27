<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('lost_found_items')) {
            return;
        }

        Schema::table('lost_found_items', function (Blueprint $table) {
            if (!Schema::hasColumn('lost_found_items', 'reporter_name')) {
                $table->string('reporter_name')->nullable()->after('reported_by');
            }

            if (!Schema::hasColumn('lost_found_items', 'reporter_contact')) {
                $table->string('reporter_contact')->nullable()->after('reporter_name');
            }

            if (!Schema::hasColumn('lost_found_items', 'linked_lost_item_id')) {
                $table->foreignId('linked_lost_item_id')
                    ->nullable()
                    ->after('claimed_by')
                    ->constrained('lost_found_items')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('lost_found_items')) {
            return;
        }

        Schema::table('lost_found_items', function (Blueprint $table) {
            if (Schema::hasColumn('lost_found_items', 'linked_lost_item_id')) {
                $table->dropConstrainedForeignId('linked_lost_item_id');
            }

            if (Schema::hasColumn('lost_found_items', 'reporter_contact')) {
                $table->dropColumn('reporter_contact');
            }

            if (Schema::hasColumn('lost_found_items', 'reporter_name')) {
                $table->dropColumn('reporter_name');
            }
        });
    }
};
