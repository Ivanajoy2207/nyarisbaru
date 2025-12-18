<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("
            ALTER TABLE transactions
            MODIFY status ENUM('pending', 'paid', 'shipped', 'completed')
            NOT NULL
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE transactions
            MODIFY status ENUM('paid', 'shipped', 'completed')
            NOT NULL
        ");
    }
};
