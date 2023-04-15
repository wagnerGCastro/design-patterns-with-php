<?php

use Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

final class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('first_name', 40)->nullable(false);
            $table->string('last_name', 150)->nullable(false);
            $table->string('login', 40)->unique()->nullable(false);
            $table->string('email', 150)->unique()->nullable(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->dropIfExists('users');
    }
};
