<?php

namespace App\Repositories\Like;

use App\Repositories\IBaseRepository;

interface ILikeRepository extends IBaseRepository
{
    function findLikeIdByObject($object_id, $type_id);
    function likeObjectAdd($user_id, $object_id, $type_id);
    function likeObjectDel($user_id, $object_id, $type_id);
}
