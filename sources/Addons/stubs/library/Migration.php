<?php

namespace {$namespace}\Database\Migrations;

use Jumilla\Versionia\Laravel\Support\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class {$class_name} extends Migration
{
    /**
     * Upgrade database.
     *
     * @return void
     */
    public function up()
    {
        // $this->createSamplesTable();
    }

    /**
     * Downgrade database.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('samples');
    }

    /**
     * Create 'samples' table.
     *
     * @return void
     */
    protected function createSamplesTable()
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }
}
