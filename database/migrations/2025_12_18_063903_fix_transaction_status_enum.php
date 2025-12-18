<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("
            ALTER TABLE transactions
            MODIFY status ENUM('pending','paid','shipped','completed','canceled')
            NOT NULL DEFAULT 'pending'
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE transactions
            MODIFY status ENUM('pending','paid','shipped','completed')
            NOT NULL DEFAULT 'pending'
        ");
    }
};
