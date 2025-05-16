<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'Username',
        'Password',
        'name',
        'email',
        'phone',
        'address',
        'id_number',
        'usia',
        'Level',
        'EventId',
        'updated_by',
        'deleted_by'
    ];

    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
}
