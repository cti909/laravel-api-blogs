<?php

namespace App\Repositories\Comment;

use App\Repositories\IBaseRepository;

interface ICommentRepository extends IBaseRepository
{
    function findAllCommentsByPost(mixed $options);
    function findIdCommentByPath($post_id, $path = "%");
    function inforCommentByPost($post_id, $path = "%", $path_length = 1);
}
