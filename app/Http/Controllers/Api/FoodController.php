<?php

namespace App\Http\Controllers\Api;

use App\Models\Food;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Chef\FoodRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Chef\UpdateFoodRequest;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chef = Auth::user();

        $foods = Food::with(['category', 'chef'])
            ->where('chef_id', $chef->id)
            ->withAvg('reviews', 'star') 
            ->paginate(10);

        return ApiResponse::sendResponse(true, 'Data Retrieved Successfully', FoodResource::collection($foods));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(FoodRequest $request)
    // {
    //     $chef = Auth::user();
    //     $data = $request->validated();

    //     if ($request->hasFile('image') && $request->file('image')->isValid()) {
    //         $data['image'] = $request->file('image')->store('uploads/images/foods', 'public');
    //     }
    //     $data['chef_id'] = $chef->id;
    //     $food = Food::create($data);

    //     return ApiResponse::sendResponse(true, 'Data Retrieve Successful', new FoodResource($food));

    public function store(FoodRequest $request)
    {
        $data = $request->validated();
        $aiResult = null;
        $ingredients = [];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('uploads/images/foods', 'public');
            $data['image'] = $imagePath;

            $fullPath = Storage::disk('public')->path($imagePath);
            $imageBase64 = base64_encode(file_get_contents($fullPath));

            // برومبت التحليل - عربي
            $prompt = [
                [
                    'role' => 'system',
                    'content' => ' أنت طباخ مصري محترف متخصص في الأكلات الشعبية المصرية التي تُعد في البيوت. مهمتك هي التعرف على الأكلات المصرية الشعبية مثل الكشري، الملوخية، الحواوشي، الشكشوكة، الفول، المحشي، الطعمية، وغيرها، عند إرسال صور لها. يجب عليك أن تُحدد اسم الأكلة وتذكر المكونات الأساسية بشكل دقيق. أجب باللهجة المصرية أو باللغة العربية الفصحى ورجعلى المكونات عل شكل ارارى'
                ],
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'هذه صورة لطبق مصري شعبي، ما اسم الأكلة وما هي مكوناتها الأساسية؟'
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => 'data:image/jpeg;base64,' . $imageBase64,
                            ]
                        ]
                    ]
                ]
            ];

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                    'Content-Type' => 'application/json',
                ])->post('https://openrouter.ai/api/v1/chat/completions', [
                            'model' => 'openai/gpt-4o',
                            'messages' => $prompt,
                            'max_tokens' => 1000,
                        ]);

                if ($response->successful()) {
                    $aiResponse = $response->json();
                    $aiResult = $aiResponse['choices'][0]['message']['content'] ?? 'لا يوجد رد من الذكاء الاصطناعي.';

                    $notFoodPatterns = [
                        'هذا ليس',
                        'ليست أكلة',
                        'لا أستطيع تحديد',
                        'ليست طبق',
                        'لا تبدو كـ',
                        'ليست وجبة',
                        'ليست طعام',
                        'الصورة لا تحتوي على طعام',
                        'ليست أكلة مصرية شعبية تقليدية معروفة'
                    ];

                    foreach ($notFoodPatterns as $pattern) {
                        if (stripos($aiResult, $pattern) !== false) {
                            return ApiResponse::sendResponse(false, 'الصورة لا تمثل أكلة مصرية شعبية. من فضلك ارفع صورة واضحة لأكلة معروفة.');
                        }
                    }

                    // استخراج المكونات من الرد
                    $ingredientsText = $aiResult;

                    if (preg_match('/(?:المكونات|مكوناتها)\s+الأساسية\s+(?:هي|تشمل|تتكون من)?[:：]?\s*(.+?)(?:\.|\n|$)/u', $ingredientsText, $matches)) {
                        $ingredientsString = $matches[1];
                        $ingredients = explode('،', $ingredientsString);
                        $ingredients = array_map('trim', $ingredients);
                    }

                    // fallback: لو مفيش فاصلات، نجرب نجيب الشرطة
                    if (empty($ingredients)) {
                        preg_match_all('/-\s*(.+)/', $aiResult, $matches);
                        $ingredients = $matches[1] ?? [];
                        $ingredients = array_map('trim', $ingredients);
                    }

                } else {
                    $aiResult = 'حدث خطأ أثناء الاتصال بـ OpenRouter: ' . ($response->json()['error']['message'] ?? $response->body());
                }
            } catch (\Exception $e) {
                $aiResult = 'استثناء أثناء الاتصال بـ OpenRouter: ' . $e->getMessage();
            }

            $data['ai_result'] = $aiResult;
        }

        $data['ingredients'] = json_encode($ingredients);

        // تأكيد الشيف
        $data['chef_id'] = 1;

        dd($data, $ingredients);

        $food = Food::create($data);

        return ApiResponse::sendResponse(true, 'تم حفظ الأكلة وتحليلها بواسطة OpenRouter', [
            'food' => new FoodResource($food),
            'ai_result' => $aiResult,
        ]);
    }




    /**
     * Display the specified resource.
     */
    public function show(Food $food)
    {
        $food->load(['category', 'chef']);

        $reviews = $food->reviews()->with('user:id,name,image')->latest()->get();

        $formattedReviews = $reviews->map(function ($review) {
            return [
                'user_name' => $review->user->name,
                'user_image' => $review->user->image,
                'star' => $review->star,
                'comment' => $review->comment,
                'created_at' => $review->created_at->toDateTimeString(),
            ];
        });

        return ApiResponse::sendResponse(true, 'Food retrieved successfully', [
            'food' => new FoodResource($food),
            'reviews' => $formattedReviews
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFoodRequest $request, Food $food)
    {

        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($food->image) {
                Storage::disk('public')->delete($food->image);
            }
            $data['image'] = $request->file('image')->store('uploads/images/foods', 'public');
        }

        $food->update($data);

        return ApiResponse::sendResponse(true, 'Food updated successfully', new FoodResource($food));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $food)
    {
        if ($food->image) {
            Storage::disk('public')->delete($food->image);
        }

        $food->delete();

        return ApiResponse::sendResponse(true, 'Food deleted successfully');
    }
}