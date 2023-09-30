<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Response;
use App\Traits\ResponseTrait;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    use ResponseTrait;

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $data = collect($request->validated())->except('image')->toArray();
    
        // Create the new user
        $user = User::create($data);
    
        if ($request['image']) {
            $user->image()->create(["url" => $request['image']]);
        }
    
        // Assign the role if it's specified in the request
        if ($request['role']) {
            $user->syncRoles($request['role']);
        }

        $user->load('image');
        $user->makeHidden(['image']); 
        $baseImagePath = "img/users/";
        $user->setAttribute('image_url', $user->image ? url($baseImagePath . $user->image->url) : null);
    
        return $this->successResponse($user, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('image');
    
        // Hide relationships for serialization 
        $user->makeHidden(['image']); 
    
        // Set the full image_url attribute on the user
        $baseImagePath = "img/users/";
        $user->setAttribute('image_url', $user->image ? url($baseImagePath . $user->image->url) : null);
    
        return $this->successResponse($user, Response::HTTP_OK);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->except('image');

        if ($request->has('image')) {
            // If user already has an image, we might want to delete it before updating.
            // Assuming there's an `image` relationship on your `User` model.
            if($user->image) {
                $user->image->delete(); // This would remove the old image record.
            }
    
            // Attach new image to the user
            $user->image()->create(["url" => $request['image']]);
        }

        $user->fill($data);
        $user->save();

        $user->load('image');
        $user->makeHidden(['image']); 
        $baseImagePath = "img/users/";
        $user->setAttribute('image_url', $user->image ? url($baseImagePath . $user->image->url) : null);

        return $this->successResponse($user, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->showMessage('ok', Response::HTTP_NO_CONTENT);
    }

    /**
     * Retrieve the full URL of the user's image.
     *
     * @param User $user The user whose image needs to be fetched.
     * 
     * @return \Illuminate\Http\JsonResponse The JSON response containing either the image URL or an error message.
     */
    public function getUserImage(User $user)
    {
        $imageUrl = $user->image->url ?? null;
    
        if (!$imageUrl) {
            return response()->json(['error' => 'Image not found'], 404);
        }
    
        // Adjust the path by prepending 'img/users/' to the image URL
        $adjustedUrl = "img/users/" . $imageUrl;
    
        // Return the image as binary content:
        $imagePath = public_path($adjustedUrl);
        if (file_exists($imagePath)) {
            // If you want to return the full URL of the image:
            $fullUrl = url($adjustedUrl);
            return response()->json(['image_url' => $fullUrl]);
        } else {
            return response()->json(['error' => 'Image file not found'], 404);
        }
    }
}
