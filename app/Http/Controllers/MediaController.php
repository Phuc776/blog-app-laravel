<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MediaService;

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

    public function createMedia(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|integer',
            'type' => 'required|int',
            'fileUrls' => 'required|array',
            'fileUrls.*' => 'required|file',
        ]);

        $fileUrls = [];

        if ($request->hasFile('fileUrls')) {
            foreach ($request->file('fileUrls') as $file) {
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

        if ($media) {
            return response()->json([
                'status' => 'success',
                'message' => 'Files uploaded and media created successfully',
                'data' => [
                    'media' => $media,
                ],
            ], 201, [], JSON_UNESCAPED_SLASHES);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload files and create media',
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
                'status' => 'success',
                'message' => 'Media deleted successfully',
                'data' => [
                    'deleted_count' => $deletedCount,
                ],
            ]);
        } else {
            return response()->json([
                'status' => 'error',
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
                'status' => 'error',
                'message' => 'No images found for the given post_id',
                'data' => [],
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Images retrieved successfully',
            'data' => $images,
        ]);
    }
}
