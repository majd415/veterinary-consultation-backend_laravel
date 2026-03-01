<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TopRatedItem;
use App\Traits\HandlesImageUploads;

class AdminTopRatedController extends Controller
{
    use HandlesImageUploads;

    public function index()
    {
        $items = TopRatedItem::latest()->paginate(10);
        return view('admin.top_rated.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'name_ar' => 'required',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        $imagePath = $this->uploadImage($request->file('image'), 'top_rated');

        TopRatedItem::create([
            'name' => ['en' => $request->name, 'ar' => $request->name_ar],
            'image' => $imagePath,
            'rating' => $request->rating ?? 0,
        ]);

        return redirect()->back()->with('success', 'Top Rated Item added successfully');
    }

    public function update(Request $request, $id)
    {
        $item = TopRatedItem::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'name_ar' => 'required',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        $data = [
            'name' => ['en' => $request->name, 'ar' => $request->name_ar],
            'rating' => $request->rating ?? $item->rating,
        ];

        if ($request->hasFile('image')) {
            $this->deleteImage($item->image);
            $data['image'] = $this->uploadImage($request->file('image'), 'top_rated');
        }

        $item->update($data);

        return redirect()->back()->with('success', 'Top Rated Item updated successfully');
    }

    public function destroy($id)
    {
        $item = TopRatedItem::findOrFail($id);
        $this->deleteImage($item->image);
        $item->delete();

        return redirect()->back()->with('success', 'Top Rated Item deleted successfully');
    }
}
