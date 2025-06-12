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
        $letters = range('a', 'z');

        Food::factory()->count(40)->create();

        foreach ($letters as $letter) {
            $response = Http::get("https://www.themealdb.com/api/json/v1/1/search.php?f={$letter}");

            if ($response->successful()) {
                $meals = $response->json()['meals'] ?? null;

                if (!$meals) {
                    continue;
                }

                foreach ($meals as $meal) {
                    try {
                        $imageUrl = $meal['strMealThumb'];
                        if (!$imageUrl) {
                            continue;
                        }

                        $imageName = 'uploads/images/foods/' . Str::random(10) . '.jpg';

                        $imageContents = Http::get($imageUrl)->body();

                        Storage::disk('public')->put($imageName, $imageContents);

                        $price = rand(50, 150);
                        $offer_price = rand(30, $price);

                        Food::create([
                            'category_id' => rand(1, 4),
                            'chef_id' => rand(1, 3),
                            'name' => $meal['strMeal'],
                            'description' => $meal['strInstructions'] ?? 'No description available.',
                            'price' => $price,
                            'offer_price' => $offer_price,
                            'preparation_time' => rand(10, 60),
                            'rating' => mt_rand(30, 50) / 10,
                            'food_type' => ['full', 'half'][rand(0, 1)],
                            'image' => $imageName,
                            'status' => ['active', 'inactive'][rand(0, 1)],
                        ]);
                    } catch (\Exception $e) {
                        Log::error("خطأ أثناء حفظ الوجبة: " . $e->getMessage());
                        continue;
                    }
                }
            }
        }
    }
}
