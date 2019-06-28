<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser {

    private function successResponse($data, $code) {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code) {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200) {
        if ($collection->isEmpty()) {
            return $this->successResponse(['data' => $collection], $code);
        }
        //dd($collection);
       // $trasnformer = $collection->first()->transformer;

        //$collection = $this->filterData($collection, $trasnformer);
        //$collection = $this->sortData($collection, $trasnformer);
        $collection = $this->paginate($collection);
        //$collection = $this->transformData($collection, $trasnformer);
        $collection = $this->cacheResponse($collection);
        return $this->successResponse($collection, $code);
    }
    protected function showAllSimple($collection, $code=200)
    {
        return $this->successResponse($collection, $code);
    }
    // IDEA: funcion para retornar codigo 201
    // La solicitud ha tenido éxito y se ha creado un nuevo recurso como resultado de ello
    public function showSave($collection, $code = 201){
      return $this->successResponse($collection, $code);
    }
    // IDEA: funcion para regresar error 404
    public function showError($message, $code = 404){
      return $this->successResponse($message, $code );
    }
    // IDEA:  Errores de ususario final
    public function showCapa8($message, $code = 401){
      return $this->successResponse($message, $code);
    }

    protected function showOne(Model $instance, $code = 200) {
        $trasnformer = $instance->transformer;
        $instance = $this->transformData($instance, $trasnformer);
        return $this->successResponse($instance, $code);
    }

    protected function showMessage($message, $code = 200) {
        return $this->successResponse(['data' => $message], $code);
    }

    protected function cacheResponse($data) {
        $url = request()->url();
        $queryParams = request()->query();
        krsort($queryParams);
        $queryString = http_build_query($queryParams);
        $fullUrl = "{$url}?{$queryString}";
        return \Illuminate\Support\Facades\Cache::remember($fullUrl, 30 / 60, function() use($data) {
                    return $data;
                });
    }

    protected function paginate(Collection $collection) {
        $rules = [
            'per_page' => 'integer|min:2|max:50'
        ];
        \Illuminate\Support\Facades\Validator::validate(request()->all(), $rules);
        $page = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        if (request()->has('per_page')) {
            $perPage = (int) request()->per_page;
        }
        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
        ]);
        $paginated->appends(request()->all());
        return $paginated;
    }

    function filterData(Collection $collection, $transformer) {
        foreach (request()->query() as $query => $value) {
            // $attribute = $transformer::originalAttribute($query);
            if (isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }
        return $collection;
    }

    function sortData(Collection $collection, $trasformer) {
        if (request()->has('sort_by')) {
            $attribute = $trasformer::originalAttribute(request()->sort_by);
            $collection = $collection->sortBy->{$attribute};
        }
        return $collection;
    }

    protected function transformData($data, $trasnformer) {

        //dd($trasnformer);
        $trasnformation = fractal($data, new $trasnformer);

        return $trasnformation->toArray();
    }

}
