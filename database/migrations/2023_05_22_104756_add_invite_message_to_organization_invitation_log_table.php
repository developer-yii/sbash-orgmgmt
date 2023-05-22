<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInviteMessageToOrganizationInvitationLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_invitation_log', function (Blueprint $table) {
            $table->text('invited_message')->nullable()->after('to_email');
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
            $table->dropColumn('invited_message');
        });
    }
}
