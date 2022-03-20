<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CustomPaginator
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Creates a new array paginator instance.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * Paginates an array of items.
     *
     * @param mixed   $items
     * @param integer $length
     * @param string  $path (optional)
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($items, $length = 10, $path = null)
    {
        if ($items instanceof Collection) {
            $items = $items->all();
        }

        $page = $this->request->get('page') ?: 1;

        $offset = ($page - 1) * $length;

        $paginator = new LengthAwarePaginator(array_slice($items, $offset, $length), count($items), $length);

        if ($path) {
            $paginator->setPath($path);
        } else {
            $paginator->setPath($this->request->path());
        }

        return $paginator;
    }
}