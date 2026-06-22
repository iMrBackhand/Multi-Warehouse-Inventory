<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    public function adminProfile()
    {
        $id = Auth::user()->id;
        $profileData=User::find($id);
        return view('admin.edit-profile',compact('profileData'));
    }


    private function deleteOldPhoto($path)
        {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

    public function updateProfile(Request $request, $id)
        {
            $users = User::findOrFail($id);

            $users->name = $request->name;
            $users->email = $request->email;
            $users->phone = $request->phone;
            $users->address = $request->address;

            if ($request->hasFile('photo')) {

                // delete old photo first
                $this->deleteOldPhoto($users->photo);

                // upload new photo
                $users->photo = $request->file('photo')->store('photo', 'public');
            }

            // binago ko to from $user->update
            $users->save();

            $notification = array(
                'message' => 'Profile Succesfully Updated',
                'alert-type' =>'success'
            );

            return redirect()->back()->with($notification);
        }
        // End Method

    // for update password button
        public function updatePassword(PasswordUpdateRequest $request)
        {
            $user = Auth::user();

            // check if password match to the database
            if(!Hash::check($request->old_password,$user->password))

                {
                $notification = array(
                    'message' => 'old password does not match',
                    'alert-type' => 'error'
                );
                return back()->with($notification);
                }

                // ito ang naghahanap ng data sa database na may same id
                User::whereId($user->id)->update([
                    'password' => Hash::make($request->new_password)
                ]);

                // default ni laravel to kung saan after mo makapagchange password ilogout
                Auth::logout();
                $notification = array(
                    'message' => 'Password Updated Succesfully',
                    'alert-type' => 'success'
                );
                return redirect()->route('login')->with($notification);
        }
    // End Method
}
