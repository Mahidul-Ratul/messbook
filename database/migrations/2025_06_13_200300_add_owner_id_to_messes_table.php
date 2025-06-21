<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOwnerIdToMessesTable extends Migration
{
    public function up()
    {
        Schema::table('messes', function (Blueprint $table) {
            $table->foreignId('owner_id')->constrained('users')->after('id');
        });
    }

    public function down()
    {
        Schema::table('messes', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropColumn('owner_id');
        });
    }
}
?>