<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function editSlider()

    {   $slider = Slider::find(1);
        return view('admin.finances.edit-finances',compact('slider'));
    }

    private function deleteOldPhoto($path)
        {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }


    public function updateSlider(Request $request,$id)
    {
        $slider = Slider::find($id);

        $slider->title=$request->title;
        $slider->description=$request->description;
        $slider->link=$request->link;

        if($request->hasFile('image')) {

            $this->deleteOldPhoto($slider->image);
            $slider->image = $request->file('image')->store('photo','public');
        }

        $notification = array(
            'message' => 'Finances Succesfully Updated',
            'alert-type' =>'success'
        );

        $slider->save();
        return redirect()->route('finances')->with($notification);

    }
}
