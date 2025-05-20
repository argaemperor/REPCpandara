<?php

namespace App\Models;

use CodeIgniter\Model;

class UserEventModel extends Model
{
    protected $table            = 'user_event';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'user_id',
        'event_id',
        'level',
        'identifier',
        'updated_by',
        'deleted_by',
        'updated_at',
        'deleted_at'
    ];

    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
}
