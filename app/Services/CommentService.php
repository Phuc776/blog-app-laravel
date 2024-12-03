<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Log;


class CommentService
{
    protected $model;

    public function __construct(Comment $comment)
    {
        $this->model = $comment;
    }

    public function getListByPostId($postId) {
        return $this->model
            ->where('post_id', $postId)
            ->orderBy('created_at', 'desc');
    }

    public function create($params, $postId)
    {
        try{
            $params['post_id'] = $postId->id;
            return $this->model->create($params);
        }catch(\Exception $exception){
            Log::error($exception);
            throw new \Exception($exception->getMessage());
            return false;
        }
        
    }

    public function update($comment, $param)
    {
        try{
            return $comment->update($param);
        }catch(\Exception $exception){
            Log::error($exception);
            throw new \Exception($exception->getMessage());
            return false;
        }  
    }

    public function destroy($comment)
    {
        try{
            return $comment->delete();
        }catch(\Exception $exception){
            Log::error($exception);
            throw new \Exception($exception->getMessage());
            return false;
        }  
    }
}
