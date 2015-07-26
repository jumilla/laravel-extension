<?php

namespace {$namespace}\Database\Migrations;

use Jumilla\Versionia\Laravel\Support\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Auth_1_0 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createUsersTable();
        $this->createPasswordResetsTable();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
    }

    /**
     * Create 'users' table.
     *
     * @return void
     */
    protected function createUsersTable()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Create 'password_resets' table.
     *
     * @return void
     */
    protected function createPasswordResetsTable()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });
    }
}
