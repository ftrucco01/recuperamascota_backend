<?php

namespace App\Http\Controllers\Image;

use App\Enums\DiskTypeEnum;
use App\Http\Requests\ImageRequest;
use Illuminate\Http\JsonResponse;
use App\Traits\ResponseTrait;

class ImageController
{
    use ResponseTrait;

    private array $types = [];

    public function __construct()
    {
        foreach (DiskTypeEnum::cases() as $enum) {
            $this->types[] = $enum->value;
        }
    }

    /**
     * Handle the incoming request.
     *
     * @param $type
     * @param ImageRequest $request
     * @return JsonResponse
     */
    public function __invoke($type, ImageRequest $request):string
    {
        if (!in_array($type, $this->types)) {
            return $this->errorResponse('Type not found');
        }

        return $this->uploadImage($request, $type);
    }

    /**
     * Uploads the given image file.
     * 
     * @param  \Illuminate\Http\Request $request The incoming HTTP request containing the file to be uploaded.
     * @param  string $type The type of storage (disk) where the image will be saved.
     * @return \Illuminate\Http\Response Returns a JSON response with the image URL or an error message.
     * 
     * @throws \Exception Throws an exception if there's an error during file upload.
     */
    private function uploadImage($request, $type)
    {
        // Check if a file is uploaded
        if (!$request->hasFile('file')) {
            return $this->errorResponse('No file uploaded.', 400);
        }
    
        $file = $request->file('file');
        $allowedImageExtensions = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'webp'];
    
        // Check if the uploaded file is valid
        if (!$file->isValid()) { 
            return $this->errorResponse('Uploaded file is not valid.', 400);
        }
    
        // Check if the file is an image
        if (!in_array($file->getClientOriginalExtension(), $allowedImageExtensions)) {
            return $this->errorResponse('Uploaded file is not a valid image.', 400);
        }
    
        try {
            // Save the image and return the path
            $storedPath = $request->file->store('', $type);
            
            // Assuming you're storing the images in the 'public' disk and can be accessed via a public URL
            $absoluteURL = url('img/'.$type.'/' . $storedPath); 

            return response()->json([
                'image_url' => $absoluteURL
            ])->header('Content-Type', 'application/json');
            
        } catch (\Exception $e) {
            // Handle errors during upload
            return $this->errorResponse('Failed to upload the file.', 500);
        }
    }    

}