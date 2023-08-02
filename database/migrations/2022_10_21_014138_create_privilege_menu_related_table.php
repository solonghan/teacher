<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivilegeMenuRelatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privilege_menu_related', function (Blueprint $table) {
            $table->bigInteger('privilege_id')->comment("Privilege ID");
            $table->bigInteger('menu_id')->comment("Menu ID");
            $table->bigInteger('action_id')->comment("Action ID");
            // $table->enum('enabled', ['on','off'])->comment("Enabled")->default('on');
            $table->timestamps();

            $table->primary(['privilege_id','menu_id','action_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('privilege_menu_related');
    }
}
