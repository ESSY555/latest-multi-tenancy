<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Remove gallery rows created by the removed GallerySeeder (sample image paths).
     */
    public function up(): void
    {
        $seedPaths = [
            'gallery/school-building.jpg',
            'gallery/science-lab.jpg',
            'gallery/sports-day.jpg',
            'gallery/art-exhibition.jpg',
            'gallery/library.jpg',
            'gallery/computer-lab.jpg',
            'gallery/graduation.jpg',
            'gallery/student-council.jpg',
            'gallery/teacher-workshop.jpg',
            'gallery/academic-award.jpg',
            'gallery/school-garden.jpg',
            'gallery/cultural-festival.jpg',
        ];

        DB::table('galleries')->whereIn('image_path', $seedPaths)->delete();
    }

    public function down(): void
    {
        // Data migration; no rollback of deleted rows.
    }
};
