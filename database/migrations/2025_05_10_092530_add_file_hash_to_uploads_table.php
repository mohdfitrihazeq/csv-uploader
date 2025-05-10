<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileHashToUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->string('file_hash')->nullable()->unique(); 
        });
    }
    
    public function down()
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->dropColumn('file_hash');
        });
    }
    
}
