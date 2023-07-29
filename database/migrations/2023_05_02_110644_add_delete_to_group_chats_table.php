<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleteToGroupChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_chats', function (Blueprint $table) {
            $table->enum('deleted',[0,1,2])->default(0)->after('read');
            $table->timestamp('deleted_at')->nullable()->after('deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_chats', function (Blueprint $table) {
            //
        });
    }
}
