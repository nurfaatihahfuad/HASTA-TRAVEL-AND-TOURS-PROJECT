    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    //Auni tambah
    use Carbon\Carbon;

    return new class extends Migration
    {
        public function up()
        {
            Schema::table('booking', function (Blueprint $table) {
                // Add if they don't exist
                if (!Schema::hasColumn('booking', 'created_at')) {
                    $table->timestamp('created_at')->nullable()->useCurrent();
                }
                
                if (!Schema::hasColumn('booking', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
                }

            });
            
            // Update existing records
            /*
            DB::table('booking')
                ->whereNull('created_at')
                ->update([
                    'created_at' => DB::raw('NOW()'),
                    'updated_at' => DB::raw('NOW()')
                ]);
            */

            //Auni tambah
            DB::table('booking')
            ->whereTime('created_at', '01:11:03')
            ->update([
                'created_at' => Carbon::now('Asia/Kuala_Lumpur'),
                'updated_at' => Carbon::now('Asia/Kuala_Lumpur'),
            ]);

        }

        public function down()
        {
            Schema::table('booking', function (Blueprint $table) {
                $table->dropColumn(['created_at', 'updated_at']);
            });
        }
    };