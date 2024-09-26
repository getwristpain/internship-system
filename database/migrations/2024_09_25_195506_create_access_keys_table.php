    <?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('access_keys', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique(); // Stores the plain access key (optional)
                $table->string('hashed_key')->unique(); // Stores the hashed access key
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Optional: reference to a user
                $table->timestamp('expires_at')->nullable(); // Optional: key expiration time
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('access_keys');
        }
    };
