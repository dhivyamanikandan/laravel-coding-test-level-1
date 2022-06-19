<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->uuid('id', 36)->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamp('startAt')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('endAt')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('createdAt')->useCurrent()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updatedAt')->useCurrentOnUpdate()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('deletedAt')->softDeletes()->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event');
    }
}
