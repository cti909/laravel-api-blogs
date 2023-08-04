<?php
namespace App\Repositories;

interface IBaseRepository {
    // write
    function create($data);
    function update($data, $id);
    function destroy($data);
    // read
    function findAll($options);
    function findOne($options);
    function findById($id);
}