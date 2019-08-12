<?php
namespace UrApi\Utils;

use Illuminate\Pagination\Paginator as BasePaginator;

class Paginator extends BasePaginator
{
    /**
     * Set the items for the paginator.
     *
     * @param  mixed  $items
     * @return void
     */
    protected function setItems($items)
    {
        $this->items = $items instanceof Collection ? $items : Collection::make($items);

        $this->hasMore = $this->items->count() > $this->perPage;

        $this->items = $this->items->slice((($this->currentPage - 1) * $this->perPage), $this->perPage);
    }
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
                'nextPageUrl' => $this->nextPageUrl(),
                'path' => $this->path,
                'perPage' => $this->perPage(),
                'prevPageUrl' => $this->previousPageUrl(),
                'to' => $this->lastItem(),
            ],
            'resultSet' => $this->items->toArray(),
        ];
    }

}
