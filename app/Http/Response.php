<?php

namespace App\Http;

// ToDo: Refactor to a more suitable Dessign Pattern
class Response {

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

  static function formatError($statusCode, $title, $detail) {
    return response()->json([
        'errors' => [
            'status' => (string) $statusCode,
            'title'  => $title,
            'detail' => $detail
        ]
    ], $statusCode);
  }
}