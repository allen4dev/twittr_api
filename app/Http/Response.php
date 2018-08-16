<?php

namespace App\Http;

use Exception;

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

  static function unauthenticated() {
    $statusCode = 401;
    $title      = 'Unauthenticated';
    $detail     = 'This action is only allowed to authenticated members';

    return (new self)->createErrorResponse($statusCode, $title, $detail);
  }

  static function modelNotFound($exception) {
    $model = explode('\\', $exception->getModel());
    $modelName = end($model);

    $statusCode = 404;
    $title      = 'Model not found';
    $detail     = "{$modelName} with that id does not exist";

    return (new self)->createErrorResponse($statusCode, $title, $detail);
  }

  private function createErrorResponse($statusCode, $title, $detail)
  {
      return response()->json([
        'errors' => [
            'status' => (string) $statusCode,
            'title'  => $title,
            'detail' => $detail
        ]
    ], $statusCode);
  }
}