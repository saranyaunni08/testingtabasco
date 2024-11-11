    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkingsTable extends Migration
{
    public function up()
    {
        Schema::create('parkings', function (Blueprint $table) {
            $table->id();
            $table->string('slot_number')->unique();
            $table->integer('floor_number');
            $table->enum('status', ['available', 'occupied'])->default('available');
            $table->string('purchaser_name')->nullable();
            $table->decimal('amount', 10, 2); // Adjust precision as needed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parkings');
    }
}
