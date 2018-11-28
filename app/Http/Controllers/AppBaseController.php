<?php

namespace App\Http\Controllers;

use InfyOm\Generator\Utils\ResponseUtil;
use Response;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;
use Illuminate\Contracts\Pagination\Paginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Illuminate\Contracts\Routing\ResponseFactory;
/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    const CODE_VALIDATION = 'VALIDATION_ERROR';
    const CODE_RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND_ERROR';
    const CODE_SERVER_ERROR = 'SERVER_ERROR';
    const CODE_UNAUTHORIZED = 'UNAUTHORIZED_ERROR';
    const CODE_FORBIDDEN = 'FORBIDDEN_ERROR';
    const CODE_BAD_REQUEST = 'BAD_REQUEST';

    public static $ERROR_CODES_TO_STATUS_CODES = [
        self::CODE_VALIDATION => 422,
        self::CODE_RESOURCE_NOT_FOUND => 404,
        self::CODE_SERVER_ERROR => 500,
        self::CODE_UNAUTHORIZED => 401,
        self::CODE_FORBIDDEN => 403,
        self::CODE_BAD_REQUEST => 400,
    ];

    // public function __construct(Manager $fractal)
    // {
    //     $this->fractal = $fractal;
    // }

    /**
     * @param array | Illuminate\Database\Eloquent $item
     */
    protected function respondWithItem($item, $callback = null)
    {
        if ($callback) {
            $resource = new Item($item, $callback);
            $rootScope = (new Manager())->createData($resource);

            return $this->respondWithArray($rootScope->toArray());
        } elseif (is_array($item)) {
            return $this->respondWithArray(['data' => $item]);
        } else {
            return $this->respondWithArray(['data' => $item->toArray()]);
        }
    }

    /**
     * @param array | Illuminate\Database\Eloquent $item
     */
    protected function respondWithNewItem($item, $callback = null)
    {
        if ($callback) {
            $resource = new Item($item, $callback);
            $rootScope = (new Manager())->createData($resource);

            return $this->respondWithArray($rootScope->toArray(), [], 201);
        } elseif (is_array($item)) {
            return $this->respondWithArray(['data' => $item], [], 201);
        } else {
            return $this->respondWithArray(['data' => $item->toArray()], [], 201);
        }
    }

    /**
     * @param Illuminate\Database\Eloquent\Collection $collection
     */
    protected function respondWithCollection($collection, $callback = null)
    {
        if ($callback) {
            $resource = new Collection($collection, $callback);
            $rootScope = (new Manager())->createData($resource);

            return $this->respondWithArray($rootScope->toArray());
        } elseif (is_array($collection)) {
            return $this->respondWithArray(['data' => $collection]);
        } else {
            return $this->respondWithArray(['data' => $collection->toArray()]);
        }
    }

    /**
     * @param Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator
     */
    protected function respondWithPaginator(Paginator $paginator, $callback = null)
    {
        if ($callback) {
            $resource = new Collection($paginator->getCollection(), $callback);
            $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
            $rootScope = (new Manager())->createData($resource);

            return $this->respondWithArray($rootScope->toArray());
        } else {
            $result = $paginator->toArray();
            $keys = [
                'total',
                'per_page',
                'current_page',
                'from',
                'to',
            ];
            foreach ($keys as $key) {
                $result['meta']['pagination'][$key] = $result[$key];
                unset($result[$key]);
            }

            return $this->respondWithArray($result);
        }
    }

    protected function respondWithArray(array $data, array $headers = [], $statusCode = 200)
    {
        return response()->json($data, $statusCode, $headers);
    }

    /**
     * @params :
     * $callback : null || closure || transformer object
     */
    protected function respondWithCustomData($data, $callback = null)
    {
        if ($callback === null) {
            $callback = function ($data) {
                return $data;
            };
        }
        $resource = new Item($data, $callback);
        $rootScope = (new Manager())->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }
    /*
     * @params :
     * - $errors : Illuminate\Support\MessageBag or Array
     * - $message : string - global message
     */

    protected function respondWithValidationErrors($errors, $message = 'Could not process your request. Form validation error')
    {
        return $this->respondWithArray([
                    'error' => [
                        'code' => self::CODE_VALIDATION,
                        'http_code' => self::$ERROR_CODES_TO_STATUS_CODES[self::CODE_VALIDATION],
                        'message' => $message,
                        'errors' => $errors,
                    ],
        ]);
    }

    protected function respondWithError($message, $errorCode)
    {
        $httpStatusCode = self::$ERROR_CODES_TO_STATUS_CODES[$errorCode];

        return $this->respondWithArray(
                        [
                    'error' => [
                        'message' => $message,
                        'code' => $errorCode,
                        'http_code' => $httpStatusCode,
                    ],
                        ], [], $httpStatusCode
        );
    }

    /**
     * Generates a Response with a 403 HTTP header and a given message.
     *
     * @return Response
     */
    public function errorForbidden($message = 'Forbidden')
    {
        return $this->respondWithError($message, self::CODE_FORBIDDEN);
    }

    /**
     * Generates a Response with a 400 HTTP header and a given message.
     *
     * @return Response
     */
    public function errorBadRequest($message = 'Bab request')
    {
        return $this->respondWithError($message, self::CODE_BAD_REQUEST);
    }

    /**
     * Generates a Response with a 500 HTTP header and a given message.
     *
     * @return Response
     */
    public function errorInternalError($message = 'Server Error')
    {
        return $this->respondWithError($message, self::CODE_SERVER_ERROR);
    }

    /**
     * Generates a Response with a 404 HTTP header and a given message.
     *
     * @return Response
     */
    public function errorNotFound($message = 'Resource Not Found')
    {
        return $this->respondWithError($message, self::CODE_RESOURCE_NOT_FOUND);
    }
    public function errorNotPackage($message = 'Package Not Found')
    {
        return $this->respondWithError($message, self::CODE_RESOURCE_NOT_FOUND);
    }
    /**
     * Generates a Response with a 401 HTTP header and a given message.
     *
     * @return Response
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {
        return $this->respondWithError($message, self::CODE_UNAUTHORIZED);
    }

    public function errorInvalidData($errors)
    {
        return $this->respondWithValidationErrors($errors);
    }

    public function respondNoContent()
    {
        return response('', 204);
    }

    public function respondAccepted()
    {
        return response('', 202);
    }

    public function respondWithPageNumber($page)
    {
        return $this->respondWithArray([
                    'page' => $page,
        ]);
    }

    protected function checkIsJsonResponseObject($result)
    {
        return ($result instanceof ResponseFactory || $result instanceof Response);
    }

    protected function getCurrentUser()
    {
        return \Auth::user();
    }

    public function sendResponse($result, $message)
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendResponseWithCode($result, $code)
    {
        return Response::json([
            'code' => $code,
        ]);
    }

    public function sendError($error, $code = 404)
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }
    public function sendResponseFalse($result, $message)
    {
        return Response::json([
            'success' => false,
            'message' => $message,
        ]);
    }

}
