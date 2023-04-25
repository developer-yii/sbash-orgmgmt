<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationJoinRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_join_requests', function (Blueprint $table) {
            $table->id();
            $table->biginteger('organization_id');
            $table->string('user_id',36);
            $table->text('user_note')->nullable();
            $table->text('owner_note')->nullable();
            $table->tinyInteger('is_approved')->default(0);
            $table->string('action_by',36)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_join_requests');
    }
}
