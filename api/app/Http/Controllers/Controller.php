<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function paginate(Model $model)
    {
        $request = request();
        $this->validate($request, [
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1',
            'sortby' => 'alpha_dash',
            'order' => 'in:asc,desc',
        ]);

        $query = [];

        if ($request->order) {
            $sortBy = $request->sortby ?: $model->getKeyName();
            $model = $model->orderBy($sortBy, $request->order);
            $query['sortby'] = $sortBy;
            $query['order'] = $request->order;
        }

        return $model->paginate($request->per_page)->appends($query);
    }
}
