<?php

namespace Database\Seeders;

use App\Models\Food;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $letters = range('a', 'z'); // جميع الحروف الأبجدية

        foreach ($letters as $letter) {
            $response = Http::get("https://www.themealdb.com/api/json/v1/1/search.php?f={$letter}");

            if ($response->successful()) {
                $meals = $response->json()['meals'] ?? null; // تجنب الخطأ عند عدم وجود بيانات

                if (!$meals) {
                    continue; // إذا لم تكن هناك وجبات، انتقل إلى الحرف التالي
                }

                foreach ($meals as $meal) {
                    try {
                        // تحميل الصورة من API
                        $imageUrl = $meal['strMealThumb'];

                        // التأكد من أن الرابط صالح
                        if (!$imageUrl) {
                            continue;
                        }

                        $imageName = 'uploads/images/foods/' . Str::random(10) . '.jpg'; // اسم عشوائي للصورة

                        // تنزيل الصورة وحفظها في storage/app/public/foods
                        $imageContents = @file_get_contents($imageUrl);
                        if ($imageContents === false) {
                            continue; // تخطي الوجبة إذا فشل تحميل الصورة
                        }

                        Storage::disk('public')->put($imageName, $imageContents);
                        // تخزين البيانات في قاعدة البيانات
                        Food::create([
                            'category_id' => rand(1, 4),
                            'chef_id' => rand(1, 3),
                            'name' => $meal['strMeal'],
                            'description' => 'وصفة مصرية لذيذة مصنوعة من مكونات طازجة.',
                            'price' => rand(50, 150),
                            'offer_price' => rand(30, 120),
                            'preparation_time' => rand(10, 60),
                            'rating' => rand(3, 5),
                            'food_type' => ['full', 'half'][rand(0, 1)],
                            'image' => "storage/" . $imageName, // حفظ مسار الصورة فقط
                            'status' => ['active', 'inactive'][rand(0, 1)],
                        ]);
                    } catch (\Exception $e) {
                        Log::error("خطأ أثناء حفظ الوجبة: " . $e->getMessage());
                        continue; // تخطي الوجبة في حالة وجود خطأ
                    }
                }
            }
        }
    }
}