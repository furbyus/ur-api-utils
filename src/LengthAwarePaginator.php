<?php
namespace UrApi\Utils;

use Illuminate\Pagination\LengthAwarePaginator as BasePaginator;

class LengthAwarePaginator extends BasePaginator
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'pagination' => [
                'currentPage' => $this->currentPage(),
                'firstPageUrl' => $this->url(1),
                'fromItem' => $this->firstItem(),
                'lastPage' => $this->lastPage(),
                'lastPageUrl' => $this->url($this->lastPage()),
                'nextPageUrl' => $this->nextPageUrl(),
                'path' => $this->path,
                'perPage' => $this->perPage(),
                'prevPageUrl' => $this->previousPageUrl(),
                'toItem' => $this->lastItem(),
                'totalItems' => $this->total(),
                'toalPages' => $this->lastPage(),
            ],
            'resultSet' => $this->items->toArray(),
        ];
    }

}
