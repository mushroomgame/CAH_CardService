<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlackcardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blackcards', function (Blueprint $table) {
            //General infomation
            $table->bigIncrements('_id');
            $table->text('text')
                ->comment('Text on this card. Varibles enabled.');
            $table->json('tags')
                ->comment('Tags on this card.');
            
            //Vote on cards. Auto-cleanup.
            $table->unsignedInteger('plays')->default(0)
            ->comment('Number of plays to this card.');
            $table->unsignedInteger('votes')->default(0)
                ->comment('Number of supports to this card');
            $table->boolean('status')->default(1)
                ->comment('Is this card enabled. Not-enabled cards wont show up.');
            
            //Timestamps
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
        Schema::dropIfExists('blackcards');
    }
}
