<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OfferSlider;
use App\Traits\HandlesImageUploads;

class AdminSliderController extends Controller
{
    use HandlesImageUploads;

    public function index()
    {
        $sliders = OfferSlider::latest()->paginate(10);
        return view('admin.sliders.index', compact('sliders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'nullable',
            'title_ar' => 'nullable',
        ]);

        $imagePath = $this->uploadImage($request->file('image'), 'sliders');

        OfferSlider::create([
            'title' => ['en' => $request->title, 'ar' => $request->title_ar ?? $request->title],
            'image_url' => $imagePath,
            'status' => true,
        ]);

        return redirect()->back()->with('success', 'Slider Offer added successfully');
    }

    public function update(Request $request, $id)
    {
        $slider = OfferSlider::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'nullable',
            'title_ar' => 'nullable',
        ]);

        $data = [
            'title' => ['en' => $request->title, 'ar' => $request->title_ar ?? $request->title],
        ];

        if ($request->hasFile('image')) {
            $this->deleteImage($slider->image_url);
            $data['image_url'] = $this->uploadImage($request->file('image'), 'sliders');
        }

        $slider->update($data);

        return redirect()->back()->with('success', 'Slider Offer updated successfully');
    }

    public function destroy($id)
    {
        $slider = OfferSlider::findOrFail($id);
        $this->deleteImage($slider->image_url);
        $slider->delete();

        return redirect()->back()->with('success', 'Slider Offer deleted successfully');
    }
}
