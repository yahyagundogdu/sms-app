<?php

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

if (!function_exists('outputSuccess')) {

    /**
     * @param array|string|null $data
     * @param string|null $message
     * @return JsonResponse
     */
    function outputSuccess($data = [], $message = null, $status = true): JsonResponse
    {
        return response()->json(
            [
                'status' => $status,
                'message' => $message ?? __('İşlem başarıyla gerçekleşti.'),
                'data' => $data
            ],
            Response::HTTP_OK
        );
    }
}

if (!function_exists('outputError')) {

    /**
     * @param string|null $message
     * @param int $statusCode
     * @return JsonResponse
     */
    function outputError($data = [], string|null $message = null, int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json(
            [
                'status' => false,
                'message' => $message ?? __('Bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.'),
                'data' => $data
            ],
            $statusCode
        );
    }
}
