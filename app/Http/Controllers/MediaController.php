<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MediaService;
use App\Http\Requests\Api\Media\CreateMediaRequest;

class MediaController extends Controller
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index()
    {
        return response()->json([
            'message' => 'Hello World!',
        ]);
    }

    public function createMedia(CreateMediaRequest $request)
    {
        $validated = $request->validated();
        $fileUrls = [];

        if ($request->hasFile('file_urls')) {
            foreach ($request->file('file_urls') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                $fileUrls[] = $filename;
            }

            // Lưu thông tin vào database
            $media = $this->mediaService->createMedia(
                $validated['post_id'],
                $validated['type'],
                $fileUrls
            );
        }

        if (isset($media)) {
            return response()->json([
                'status' => 201,
                'message' => 'Files uploaded and media created successfully',
                'data' => [
                    'media' => $media,
                ],
            ], 201, [], JSON_UNESCAPED_SLASHES);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create media',
                'data' => [],
            ], 500);
        }
    }

    public function deleteByPostId($post_id)
    {
        if (!is_numeric($post_id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid post_id',
                'data' => [],
            ], 400);
        }

        $deletedCount = $this->mediaService->deleteMediaByPostId((int)$post_id);

        if ($deletedCount > 0) {
            return response()->json([
                'status' => 204,
                'message' => 'Media deleted successfully',
                'data' => [
                    'deleted_count' => $deletedCount,
                ],
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'No media found to delete for the given post_id',
                'data' => [
                    'deleted_count' => $deletedCount,
                ],
            ], 404);
        }
    }

    public function getImagesByPostId($postId)
    {
        $images = $this->mediaService->findByPostId($postId);
        if ($images->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No images found for the given post_id',
                'data' => [],
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Images retrieved successfully',
            'data' => $images,
        ]);
    }
}
