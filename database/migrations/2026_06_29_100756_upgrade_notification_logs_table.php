<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_logs', function (Blueprint $table) {

            $table->string('title')->nullable()->after('user_id');

            $table->text('message')->nullable()->after('title');

            $table->string('type')->default('Reminder')->after('message');

            $table->string('icon')->default('bell')->after('type');

            $table->boolean('is_read')->default(false)->after('status');

        });
    }

    public function down(): void
    {
        Schema::table('notification_logs', function (Blueprint $table) {

            $table->dropColumn([
                'title',
                'message',
                'type',
                'icon',
                'is_read'
            ]);

        });
    }
};