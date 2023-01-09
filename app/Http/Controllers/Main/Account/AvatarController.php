<?php

namespace App\Http\Controllers\Main\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;
use App\Helpers\Permission;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class AvatarController extends Controller
{
    public function edit(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'member');
        $this->authorize('auth-check', $userAuth->authorize('member-account-avatar'));
        
        return view('main.account.account-settings.avatar');
    }

    public function crop(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'member');
        $this->authorize('auth-check', $userAuth->authorize('member-account-avatar'));

        $request->validate([
            'avatar' => ['required', 'file', 'image', 'max:2048'],
        ]);

        if ($request->file('avatar')->isValid()) {
            $fileExtension = $request->file('avatar')->extension();
            $currentTimestamp = time();

            $rawFileName = $request->user()->username . '-' . $currentTimestamp . '.' . $fileExtension;
            $path = $request->file('avatar')->storePubliclyAs('avatars-raw', $rawFileName, 's3');
            $remoteURL = Storage::disk('s3')->url($path);
            $fullPath = 'avatars-raw/' . $rawFileName;

            $request->user()->update([
                'avatar_tem' => $remoteURL,
                'avatar_tem_path' => $fullPath,
                // 'avatar_tem_expired' => ($currentTimestamp + (60 * 60)), // 1 hour
                'avatar_tem_expired' => ($currentTimestamp + 60), // 1 hour
            ]);

            $image = base64_encode($request->file('avatar')->get());
            $image = 'data:' . $request->file('avatar')->getMimeType() . ';base64,' . $image;
            $imageKey = Crypt::encryptString($fullPath);

            return view('main.account.account-settings.avatar-crop', compact('image', 'imageKey'));
        }
        else {
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'member');
        $this->authorize('auth-check', $userAuth->authorize('member-account-avatar'));

        $user = $request->user();

        $request->validate([
            'avatar' => ['required', 'string'],
            'points' => ['required', 'string', 'max:19'],
        ]);

        $points = $request->points;
        $points = explode(',', $points);
        if (
            count($points) == 4 && 
            is_numeric($points[0]) && 
            is_numeric($points[1]) && 
            is_numeric($points[2]) && 
            is_numeric($points[3]) && 
            ($points[0] < $points[2]) && 
            ($points[1] < $points[3])
        ) {

        }
        else {
            return redirect()->route('account.account-settings.avatar.edit');
        }

        $imageKey = $request->avatar;
        try {
            $imageKey = Crypt::decryptString($imageKey);
        } catch (DecryptException $e) {
            abort(500);
        }

        if ($imageKey == $user->avatar_tem_path) {
            $remoteURL = $user->avatar_tem;

            $result = Image::make($remoteURL)->crop($points[2] - $points[0], $points[3] - $points[1], $points[0], $points[1])->resize(128, 128)->encode('jpg');

            $currentTimestamp = time();
            $filenameAndPath = 'avatars/' . $user->username . '-' . $currentTimestamp . '.jpg';
            Storage::disk('s3')->put($filenameAndPath, $result, 'public');
            $finalPath = Storage::disk('s3')->url($filenameAndPath);
            
            if ($user->avatar) {
                Storage::disk('s3')->delete($user->avatar_path);
            }

            Storage::disk('s3')->delete($user->avatar_tem_path);

            $request->user()->update([
                'avatar' => $finalPath,
                'avatar_path' => $filenameAndPath,
                'avatar_tem' => null,
                'avatar_tem_path' => null,
                'avatar_tem_expired' => 0,
            ]);
        }
        else {
            abort(500);
        }

        return redirect()->route('account.account-settings.avatar.edit');
    }
}
