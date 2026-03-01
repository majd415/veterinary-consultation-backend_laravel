<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Traits\HandlesImageUploads;

class AdminSettingController extends Controller
{
    use HandlesImageUploads;

    public function index()
    {
        $settings = Setting::all();
        $logo = Setting::where('key', 'logo')->value('value');
        return view('admin.settings.index', compact('settings', 'logo'));
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $currentLogo = Setting::where('key', 'logo')->value('value');
        if ($currentLogo) {
            $this->deleteImage($currentLogo);
        }

        $imagePath = $this->uploadImage($request->file('logo'), 'logo');

        Setting::updateOrCreate(['key' => 'logo'], ['value' => $imagePath]);

        return redirect()->back()->with('success', 'Logo updated successfully');
    }

    public function destroy($id)
    {
        // Simple removal from list if ever needed, but user wanted logo focus
        Setting::destroy($id);
        return redirect()->back()->with('success', 'Setting removed');
    }
}
