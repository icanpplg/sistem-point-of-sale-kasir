<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengaturansTable extends Migration
{
    public function up()
    {
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            $table->string('store_name');
            $table->text('store_address');
            $table->string('store_contact');
            $table->string('store_owner');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengaturans');
    }
}
