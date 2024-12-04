<?php

namespace App\Services;

use App\Models\Media;
use Exception;

class MediaService
{
    public function index()
    {
        return Media::all();
    }

    public function store($request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            return response()->json([
                'message' => 'File uploaded successfully',
                'url' => url('uploads/' . $filename),
            ], 200, [], JSON_UNESCAPED_SLASHES);
        }
    }

    public function createMedia(int $postId, int $type, array $fileUrls): array
    {
        $bitType = ($type === '1' || $type === 1) ? 1 : 0;
        $mediaRecords = [];
        foreach ($fileUrls as $fileUrl) {
            $mediaRecords[] = Media::create([
                'post_id' => $postId,
                'type' => $bitType,
                'file_url' => $fileUrl,
            ]);
        }
        return $mediaRecords;
    }
    public function deleteMediaByPostId(int $postId): int
    {
        $mediaItems = Media::where('post_id', $postId)->get();
        foreach ($mediaItems as $media) {
            $filePath = public_path('uploads/' . basename($media->file_url));
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        return Media::where('post_id', $postId)->delete();
    }
    public function findByPostId($postId)
    {
        return Media::where('post_id', $postId)->get();
    }
}
