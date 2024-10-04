<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Make collection response with paginate
     * @param $collections
     * @return array
     */
    public function collectionResponse($collections): array
    {
        return [
            'data'  => $collections,
            'links' => [
                'first' => $collections->url(1),
                'last'  => $collections->url($collections->lastPage()),
                'prev'  => $collections->previousPageUrl(),
                'next'  => $collections->nextPageUrl(),
            ],
            'meta'  => [
                'current_page' => $collections->currentPage(),
                'from'         => $collections->firstItem(),
                'last_page'    => $collections->lastPage(),
                'path'         => $collections->path(),
                'per_page'     => $collections->perPage(),
                'to'           => $collections->lastItem(),
                'total'        => $collections->total(),
            ]
        ];
    }
}
