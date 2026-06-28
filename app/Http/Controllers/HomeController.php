<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Review;
use App\Models\Slider;

class HomeController extends Controller
 {
    public function index()
    {
        return view('home.index', [
            'slider' => $this->getSlider(),
            'reviews' => $this->getReviews(),
            'features' => $this->getFeatures(),
        ]);
    }

       private function getSlider()
    {
        return Slider::find(1);
    }

       private function getReviews()
    {
        return Review::latest()->get();
    }

    private function getFeatures()
    {
        return Feature::all();
    }
}
