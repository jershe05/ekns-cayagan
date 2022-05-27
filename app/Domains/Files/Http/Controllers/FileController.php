<?php

namespace App\Domains\Files\Http\Controllers;

use App\Domains\Files\Models\File;
use App\Domains\Files\Http\Requests\StoreImageRequest;
use Illuminate\Support\Facades\Storage;
use F9Web\ApiResponseHelpers;
class FileController
{
    use ApiResponseHelpers;

    public function store(StoreImageRequest $request)
    {
        $imageName = time() . '_' . $request->user_id . '.' .$request->file->extension();

        $path = Storage::disk('s3')->put('ekns/upload', $request->file);
        $path = Storage::disk('s3')->url($path);

        $file = File::create([
            'user_id' => $request->user_id,
            'file' => $imageName,
            'directory_s3' => $path,
            'description' => $request->description
        ]);

        return  $this->respondCreated([
            'file' => $file,
        ]);
    }

    public function show(File $file)
    {
        return view('backend.file.show')
            ->with('image', $file);
    }
}
