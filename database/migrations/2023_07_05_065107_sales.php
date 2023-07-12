 <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class Sales extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('sales', function (Blueprint $table) {
                $table->increments('id_sales');
                $table->integer('id_customers');
                $table->integer('total_item');
                $table->integer('total_harga');
                $table->tinyInteger('diskon')->default(0);
                $table->integer('bayar')->default(0);
                $table->integer('diterima')->default(0);
                $table->integer('id_user');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('sales');
        }
    }
