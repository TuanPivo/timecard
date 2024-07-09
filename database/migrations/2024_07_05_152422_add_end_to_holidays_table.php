<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->date('end')->nullable(); // Thêm trường end
        });
    }

    public function down()
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->dropColumn('end'); // Xóa trường end khi rollback
        });
    }
};
