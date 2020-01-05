<?php
/**
 * Created by PhpStorm.
 * User: manhhd
 * Date: 3/11/19
 * Time: 3:37 PM
 */

namespace App\Repositories;


use App\Models\MasterSearchAttribute;
use App\Repositories\Interfaces\MasterSearchRepositoryInterface;

class MasterSearchRepository extends BaseRepository implements MasterSearchRepositoryInterface
{
    public function __construct()
    {
        $this->modelClass = MasterSearchAttribute::class;
    }
}