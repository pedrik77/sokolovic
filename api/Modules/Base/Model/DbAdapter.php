<?php

namespace Tulic\aPiE\Base;

use ThinPdo\Db;

class DbAdapter extends Db
{
     public function __construct()
     {
          parent::__construct('sqlite:db.db');
     }
}
