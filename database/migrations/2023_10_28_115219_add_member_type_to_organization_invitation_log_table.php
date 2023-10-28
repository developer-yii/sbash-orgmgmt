<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMemberTypeToOrganizationInvitationLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_invitation_log', function (Blueprint $table) {
            $table->tinyInteger('member_type')->nullable()->after('invite_message');
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
            $table->dropColumn('member_type');
        });
    }
}
