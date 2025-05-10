<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFailureReasonToUploadsTable extends Migration
{
    public function up()
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->text('failure_reason')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->dropColumn('failure_reason');
        });
    }
}

