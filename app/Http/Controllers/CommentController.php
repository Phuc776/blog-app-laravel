<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\Comment\CreateRequest;
use App\Http\Requests\Api\Comment\UpdateRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request, Post $post)
    {
        $result = $this->commentService->getListByPostId($post->id);
        return response()->success(200, 'Get list comment successful.', CommentResource::apiPaginate($result, $request));
    }

    public function store(CreateRequest $createRequest,Post $post)
    {
        $requests = $createRequest->validated();

        $result = $this->commentService->create($requests, $post);

        return response()->success(201, 'Comment added successfully.', new CommentResource($result));
    }

    public function update(Comment $comment, UpdateRequest $updateRequest)
    {
        try{
            if (!$comment) {
                dd(123);
                return response()->error(404, 'Comment not found.', null);
            }

            $request = $updateRequest->validated();

            $result = $this->commentService->update($comment, $request);

            if ($result) {
                return response()->success(200 ,'Comment was successfully updated', new CommentResource($comment));
            }            
        }catch(\Exception $e){
            return response()->error(500, 'Error occurred', $e->getMessage());
        }   
    }

    public function destroy(Comment $comment)
    {
        try{
            if ($comment->exists) {
                $result = $this->commentService->destroy($comment);
            } else {
                throw new \Exception('Comment does not exist or has not been retrieved correctly.');
            }
            if ($result) {
                return response()->success(204, 'Comment was successfully deleted', null);
            }        
        }catch(\Exception $e){
            return response()->error(500, 'Error occurred', $e->getMessage());
        }  
    }
}
