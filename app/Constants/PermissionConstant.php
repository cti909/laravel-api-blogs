<?php

namespace App\Constants;

use Spatie\Permission\Models\Permission;

/**
 * Class quản lý các quyền hệ thống 
 */
class PermissionConstant
{
    /**
     * Quyền quản lý tài nguyên user
     */
    static string $CREATE_USER = 'create-user';
    static string $READ_ALL_USER = 'read-all-user';
    static string $READ_DETAIL_USER = 'read-detail-user';
    static string $UPDATE_USER = 'update-user';
    static string $DELETE_USER = 'delete-user';
    /**
     * Quyền quản lý tài nguyên note
     */
    static string $CREATE_NOTE = 'create-note';
    static string $READ_ALL_NOTE = 'read-all-note';
    static string $READ_DETAIL_NOTE = 'read-detail-note';
    static string $UPDATE_NOTE = 'update-note';
    static string $DELETE_NOTE = 'delete-note';
}
