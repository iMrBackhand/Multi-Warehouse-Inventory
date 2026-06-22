<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function allReview(Request $request)
     {
            $reviews = Review::when($request->search,function($query)use($request){
                return $query->whereAny([
                    'name',
                    'position',
                    'image',
                    'message'
                ],'like','%'.$request->search.'%');
            })->orderBy('created_at','desc')->paginate(10);
            return view('admin.review.all-reviews',compact('reviews'));
        }
        // End Method


    // without image intervention
    public function addReview(Request $request)
    {
        $review = new Review();
        $review->name=$request->name;
        $review->position=$request->position;

        if($request->hasFile('image'))
            {
                $review->image = $request->file('image')->store('photo', 'public');
            }
        $review->message=$request->message;

        $review->save();

        $notification = array(
                'message' => 'Review Succesfully Added',
                'alert-type' =>'success'
            );

        return redirect()->route('all.review')->with($notification);
    }

    // add review with image intervention
//    public function storeReview(Request $request)
// {
//     $review = new Review();
//     $review->name = $request->name;
//     $review->position = $request->position;

//     $save_url = 'upload/review/no_image.png';

//     if ($request->hasFile('image')) {

//         $image = $request->file('image');
//         $manager = new ImageManager(new Driver());

//         $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

//         $img = $manager->read($image);
//         $img->resize(60, 60)
//             ->save(public_path('upload/review/' . $name_gen));

//         $save_url = 'upload/review/' . $name_gen;
//     }

//     $review->image = $save_url;
//     $review->save();

//     $notification = [
//         'message' => 'Review Successfully Added',
//         'alert-type' => 'success'
//     ];

//     return redirect()->route('all.review')->with($notification);
// }

    private function deleteOldPhoto($path)
        {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

    public function editReview($id)
    {
        $review = Review::findOrFail($id);
        return view('admin.review.edit-review', compact('review'));
    }

    public function updateReview(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $review->name=$request->name;
        $review->position=$request->position;

        if($request->hasFile('image'))
            {
                $this->deleteOldPhoto($review->image);
                $review->image = $request->file('image')
                ->store('photo','public');
            }
        $review->message=$request->message;
        $review->save();

        $notification = array(
                'message' => 'Review Succesfully Updated',
                'alert-type' =>'success'
            );
        return redirect()->route('all.review')->with($notification);
    }

    public function deleteReview($id)
    {
        Review::findOrFail($id)->delete();

        return redirect()->route('all.review');
    }

    public function archiveReview(Request $request)
    {
        $reviews = Review::onlyTrashed()->when($request->search,function($query)use($request){
                return $query->whereAny([
                    'name',
                    'position',
                    'message',
                    'image'
                ],'like','%'.$request->search.'%');
            })->orderBy('id','asc')->paginate(10);

            return view('admin.review.archived-review',compact('reviews'));
    }

    public function restoreReview($id)
    {
        Review::withTrashed()->findOrFail($id)->restore();

        $notification = array(
                'message' => 'Review Succesfully Restore',
                'alert-type' =>'success'
            );
        return redirect ()->route('all.review')->with($notification);
    }
}
