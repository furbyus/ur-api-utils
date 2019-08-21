<?php
namespace UrApi\Utils;

use Illuminate\Pagination\LengthAwarePaginator as BasePaginator;

class LengthAwarePaginator extends BasePaginator
{
    public $slice;
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {

        $values = [];
        for ($iterator = $this->slice->getIterator(); $iterator->valid(); $iterator->next()) {
            $values[] = $iterator->current()->toArray();
        }
        return [
            'pagination' => [
                'currentPage' => $this->currentPage(),
                'firstPageUrl' => $this->url(1),
                'fromItem' => $this->firstPageItem(),
                'lastPage' => $this->lastPage(),
                'lastPageUrl' => $this->url($this->lastPage()),
                'nextPageUrl' => $this->nextPageUrl(),
                'path' => $this->path,
                'perPage' => $this->perPage(),
                'prevPageUrl' => $this->previousPageUrl(),
                'toItem' => $this->lastPageItem(),
                'totalItems' => $this->total(),
                'toalPages' => $this->lastPage(),
            ],
            'resultSet' => $values,
        ];
    }
    public function __construct($items, $total, $perPage, $curPage, $options)
    {

        $this->slice = $items->slice($curPage * $perPage - $perPage, $perPage);
        parent::__construct($items, $total, $perPage, $curPage, $options);
    }
    public function firstPageItem()
    {
        return $this->currentPage() * $this->perPage() - $this->perPage() + 1 ;
    }
    public function lastPageItem()
    {
        $lastItem = $this->currentPage() * $this->perPage();
        return ($lastItem > $this->total()) ? $this->total() : $lastItem;
    }
}
