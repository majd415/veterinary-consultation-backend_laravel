<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('key')) {
            $setting = Setting::where('key', $request->key)->first();
            return response()->json($setting);
        }
        return response()->json(Setting::all());
    }
}
