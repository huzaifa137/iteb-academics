cat > /home/claude/iteb-academics/database/migrations/2026_06_13_000001_widen_class_ar_in_students_basic.php << 'EOF'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WidenClassArInStudentsBasic extends Migration
{
    public function up()
    {
        Schema::table('students_basic', function (Blueprint $table) {
            $table->string('Class_AR', 100)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('students_basic', function (Blueprint $table) {
            $table->string('Class_AR', 45)->nullable()->change();
        });
    }
}
