<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('rating_scales')) {
            Schema::create('rating_scales', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('min_score')->default(1);
                $table->integer('max_score')->default(4);
                $table->json('options_json'); // [{"score": 1, "label": "Sangat Tidak Puas"}, ...]
                $table->text('description')->nullable();
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
        }

        Schema::table('surveys', function (Blueprint $table) {
            if (!Schema::hasColumn('surveys', 'rating_scale_id')) {
                $table->foreignId('rating_scale_id')->nullable()->after('survey_category_id')->constrained('rating_scales')->nullOnDelete();
            }
            if (!Schema::hasColumn('surveys', 'rating_scale')) {
                $table->integer('rating_scale')->default(4)->after('rating_scale_id');
            }
        });

        // Insert default seeds if empty
        if (DB::table('rating_scales')->count() === 0) {
            $defaultId = DB::table('rating_scales')->insertGetId([
                'name' => 'Skala Kepuasan 1-4 (Standar)',
                'min_score' => 1,
                'max_score' => 4,
                'options_json' => json_encode([
                    ['score' => 1, 'label' => 'Sangat Tidak Puas'],
                    ['score' => 2, 'label' => 'Tidak Puas'],
                    ['score' => 3, 'label' => 'Puas'],
                    ['score' => 4, 'label' => 'Sangat Puas'],
                ]),
                'description' => 'Standar skala kepuasan 4 tingkat untuk survei kepuasan internal.',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('rating_scales')->insert([
                [
                    'name' => 'Skala Likert Persetujuan 1-4',
                    'min_score' => 1,
                    'max_score' => 4,
                    'options_json' => json_encode([
                        ['score' => 1, 'label' => 'Sangat Tidak Setuju'],
                        ['score' => 2, 'label' => 'Tidak Setuju'],
                        ['score' => 3, 'label' => 'Setuju'],
                        ['score' => 4, 'label' => 'Sangat Setuju'],
                    ]),
                    'description' => 'Skala persetujuan 4 tingkat tanpa nilai tengah/netral.',
                    'is_default' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Skala Kepuasan 1-5 (Likert 5 Poin)',
                    'min_score' => 1,
                    'max_score' => 5,
                    'options_json' => json_encode([
                        ['score' => 1, 'label' => 'Sangat Tidak Puas'],
                        ['score' => 2, 'label' => 'Tidak Puas'],
                        ['score' => 3, 'label' => 'Cukup Puas / Netral'],
                        ['score' => 4, 'label' => 'Puas'],
                        ['score' => 5, 'label' => 'Sangat Puas'],
                    ]),
                    'description' => 'Skala kepuasan 5 poin dengan pilihan netral.',
                    'is_default' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Skala Penilaian 1-10 (NPS / Metrik Numerik)',
                    'min_score' => 1,
                    'max_score' => 10,
                    'options_json' => json_encode([
                        ['score' => 1, 'label' => '1 - Sangat Buruk'],
                        ['score' => 2, 'label' => '2 - Buruk'],
                        ['score' => 3, 'label' => '3 - Kurang'],
                        ['score' => 4, 'label' => '4 - Agak Kurang'],
                        ['score' => 5, 'label' => '5 - Cukup'],
                        ['score' => 6, 'label' => '6 - Lumayan'],
                        ['score' => 7, 'label' => '7 - Baik'],
                        ['score' => 8, 'label' => '8 - Sangat Baik'],
                        ['score' => 9, 'label' => '9 - Hebat'],
                        ['score' => 10, 'label' => '10 - Sempurna'],
                    ]),
                    'description' => 'Skala penilaian numerik 1-10 untuk penilaian komprehensif atau NPS.',
                    'is_default' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            // Assign default to existing surveys
            DB::table('surveys')->whereNull('rating_scale_id')->update([
                'rating_scale_id' => $defaultId,
                'rating_scale' => 4
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            if (Schema::hasColumn('surveys', 'rating_scale_id')) {
                $table->dropForeign(['rating_scale_id']);
                $table->dropColumn('rating_scale_id');
            }
            if (Schema::hasColumn('surveys', 'rating_scale')) {
                $table->dropColumn('rating_scale');
            }
        });

        Schema::dropIfExists('rating_scales');
    }
};
