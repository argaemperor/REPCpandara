<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterEventModel extends Model
{
    protected $table = 'masterEvent';
    protected $primaryKey = 'eventId';
    protected $allowedFields = ['eventId', 'eventName', 'eventYears', 'eventActive', 'created_at',];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $deletedField = 'delete_at';
}
