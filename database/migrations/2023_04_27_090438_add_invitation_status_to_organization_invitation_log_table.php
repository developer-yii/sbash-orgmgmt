<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvitationStatusToOrganizationInvitationLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_invitation_log', function (Blueprint $table) {
            $table->tinyInteger('invitation_status')->default('0')->after('invited_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organization_invitation_log', function (Blueprint $table) {
            $table->dropColumn('invitation_status');
        });
    }
}
