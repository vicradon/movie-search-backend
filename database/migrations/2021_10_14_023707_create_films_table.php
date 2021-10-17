<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("description")->nullable();
            $table->year("release_year")->nullable();
            $table->unsignedTinyInteger("language_id")->nullable();
            $table->unsignedTinyInteger("original_language_id")->nullable();
            $table->unsignedTinyInteger("rental_duration")->default(3);
            $table->unsignedFloat("rental_rate")->default(4.99);
            $table->unsignedInteger("length")->default(19.99);
            $table->unsignedFloat("replacement_cost")->nullable();
            $table->enum("rating", ['G', 'PG', 'PG-13', 'R', 'NC-17'])->default("G");
            $table->set("special_features", ['Trailers', 'Commentaries', 'Deleted Scenes', 'Behind the Scenes'])->nullable();
            $table->timestamp("last_update")->default(now());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('films');
    }
}
