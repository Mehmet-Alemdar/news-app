<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsRequest;
use App\Models\News;
use Illuminate\Http\Request;
use App\Validators\NewsValidator;

class NewsController extends Controller
{
    public function index()
    {
        $query = News::query();

        if (request()->has('search')) {
            $search = request()->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        $news = $query->paginate(10);

        return response()->json([
            'data' => $news->items(),
            'current_page' => $news->currentPage(),
            'per_page' => $news->perPage(),
            'total' => $news->total(),
            'last_page' => $news->lastPage(),
            'from' => $news->firstItem(),
            'to' => $news->lastItem(),
        ]);
    }

    public function show($id)
    {
        $news = News::find($id);
        if (!$news) {
            return response()->json(['message' => 'Haber bulunamadı.'], 404);
        }
        return response()->json($news);
    }

    public function store(Request $request)
    {
        $validator = NewsValidator::validate($request->all());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Doğrulama hataları var.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $filename = uniqid('news_') . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('news_images', $filename, 'public');

            $validated['image'] = 'storage/' . $path;
        }

        $news = News::create($validated);
        return response()->json($news, 201);
    }

    public function destroy($id)
    {
        $news = News::find($id);
        if (!$news) {
            return response()->json(['message' => 'Haber bulunamadı.'], 404);
        }
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }
        $news->delete();
        return response()->json(['message' => 'Haber silindi.']);
    }
}