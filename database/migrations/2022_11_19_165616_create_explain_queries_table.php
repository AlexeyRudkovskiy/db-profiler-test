<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('explain_queries', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->text('query');
            $table->json('bindings');
            $table->json('explain');

            $table->foreignIdFor(\App\Models\ExplainQuery::class, 'previous_query_id')
                ->nullable()
                ->constrained('explain_queries')
                ->onDelete('set null');

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
        Schema::dropIfExists('explain_queries');
    }
};
