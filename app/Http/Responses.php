<?php

namespace App\Http;

// ToDo: Refactor to a more suitable Dessign Pattern
class Responses {

  static function format($data = null, $statusCode = 200, $errors = null)
  {
      return response()->json([
          "data" => $data,
          "meta" => [
              "status" => [ "code" => $statusCode ],
          ],
          "errors"  => $errors
      ], $statusCode);
  }
}