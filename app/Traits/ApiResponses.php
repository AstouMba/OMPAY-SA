<?php
namespace App\Traits;

trait ApiResponses
{


    protected function successResponse($data, $message = null, $code = 200,$links = [])
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'links' => $links
        ], $code);
    }

    protected function errorResponseWithData($data, $message, $code)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], $code);
    }

    protected function paginatedResponse($data, $links = [], $message = null, $code = 200)
    {
        $paginationLinks = [];

        // Générer les liens HATEOAS (niveau 3 Richardson)
        if ($data->hasPages()) {
            $paginationLinks = [
                'first' => $data->url(1),
                'last' => $data->url($data->lastPage()),
            ];

            if ($data->currentPage() > 1) {
                $paginationLinks['prev'] = $data->url($data->currentPage() - 1);
            }

            if ($data->currentPage() < $data->lastPage()) {
                $paginationLinks['next'] = $data->url($data->currentPage() + 1);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data->items(),
            'pagination' => [
                'total' => $data->total(),
                'count' => $data->count(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
                'has_more_pages' => $data->hasMorePages(),
                'links' => array_merge($paginationLinks, $links)
            ],
            '_links' => $paginationLinks // HATEOAS links
        ], $code);
    }


    }