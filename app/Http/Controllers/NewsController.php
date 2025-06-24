<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsRequest;
use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

    public function store(StoreNewsRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $img = Image::make($image->getRealPath());
            $img->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $filename = uniqid() . '.webp';
            $path = 'news_images/' . $filename;
            Storage::disk('public')->put($path, (string) $img->encode('webp', 90));
            $validated['image'] = $path;
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