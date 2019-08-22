<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            //General infomation
            $table->bigIncrements('id');
            $table->enum('type', ['whitecards', 'blackcards'])
                ->comment('Type of this card. White for answers and black for questions.');
            $table->text('text')
                ->comment('Text on this card. Variables enabled.');
            $table->json('tags')
                ->comment('Tags on this card.');
            
            //Vote on cards. Auto-cleanup.
            $table->unsignedInteger('votes')->default(0)
                ->comment('Number of votes to this card.');
            $table->unsignedInteger('vote_up')->default(0)
                ->comment('Number of voting-ups to this card');
            $table->boolean('enabled')->default(true)
                ->comment('Has this been cleaned up. Not-enabled cards wont show up.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
