<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
Schema::create('devis', function (Blueprint $table) {
    $table->id();
    $table->string('num_devis');
    $table->foreignId('client_id')->constrained();
    $table->decimal('montant', 10, 2);
    $table->enum('statut', ['EN_ATTENTE', 'ACCEPTE', 'REFUSE']);
    $table->date('date');
    $table->boolean('facture_existante')->default(false);
    $table->boolean('bl_existante')->default(false);
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
        Schema::dropIfExists('devis');
    }
}
