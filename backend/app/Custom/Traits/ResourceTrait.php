<?php

namespace App\Custom\Traits;

trait ResourceTrait {
    public function isIndex($request)
    {
        return $request instanceof \Laravel\Nova\Http\Requests\ResourceIndexRequest;
    }
    public function isDetail($request)
    {
        return $request instanceof \Laravel\Nova\Http\Requests\ResourceDetailRequest;
    }
    public function isCreate($request)
    {
        return $request instanceof \Laravel\Nova\Http\Requests\NovaRequest &&
            $request->editMode === 'create';
    }
    public function isUpdate($request)
    {
        return $request instanceof \Laravel\Nova\Http\Requests\NovaRequest &&
            $request->editMode === 'update';
    }
}