<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OfferSlider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function getOffers()
    {
        $offers = OfferSlider::where('status', true)->get();
        return response()->json($offers);
    }
}
