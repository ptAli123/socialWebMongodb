<?php

namespace App\Http\Controllers;

use MongoDB\Client as mongo;

use Illuminate\Http\Request;

class DataBaseController extends Controller
{
  public function getConnection($table)
  {
      $collection=(new mongo)->test->$table;
      return $collection;
  }
}
