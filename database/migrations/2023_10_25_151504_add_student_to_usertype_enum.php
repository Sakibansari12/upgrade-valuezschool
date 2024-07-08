<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddStudentToUsertypeEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_unique');

            // Change the email column to be nullable
            $table->string('email')->nullable()->change();

            // Add a new unique constraint with a different name
            $table->unique('email', 'unique_email');

            DB::statement("ALTER TABLE users MODIFY usertype ENUM('superadmin', 'admin', 'teacher', 'student')");
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('unique_email');
            $table->string('email')->nullable(false)->change();
            $table->unique('email');
            DB::statement("ALTER TABLE users MODIFY usertype ENUM('superadmin', 'admin', 'teacher')");
        });
    }
}
