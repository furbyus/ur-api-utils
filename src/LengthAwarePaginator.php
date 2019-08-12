<?php
namespace UrApi\Utils;


use Illuminate\Pagination\LengthAwarePaginator as BasePaginator;

class Paginator extends BasePaginator
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
                'from' => $this->firstItem(),
                'lastPage' => $this->lastPage(),
                'lastPageUrl' => $this->url($this->lastPage()),
                'nextPageUrl' => $this->nextPageUrl(),
                'path' => $this->path,
                'perPage' => $this->perPage(),
                'prevPageUrl' => $this->previousPageUrl(),
                'to' => $this->lastItem(),
                'total' => $this->total(),
            ],
            'resultSet' => $this->items->toArray(),
        ];
    }

}