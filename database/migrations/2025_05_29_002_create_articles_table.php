<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
Schema::create('articles', function (Blueprint $table) {
    $table->id();
    $table->string('unite');
    $table->foreignId('category_article_id')->constrained();
    $table->string('name_article');
    $table->text('description_article');
    $table->decimal('price_article', 10, 2);
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
        Schema::dropIfExists('articles');
    }
}
