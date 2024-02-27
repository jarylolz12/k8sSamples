<?php

namespace App\Http\Controllers\Nova;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

use Laravel\Nova\Http\Requests\UpdateResourceRequest;
use Laravel\Nova\Http\Controllers\ResourceUpdateController as NovaResourceUpdateController;
use Carbon\Carbon;

class ResourceUpdateController extends NovaResourceUpdateController
{
    /**
     * Determine if the model has been updated since it was retrieved.
     *
     * @param  \Laravel\Nova\Http\Requests\UpdateResourceRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return bool
     */
    protected function modelHasBeenUpdatedSinceRetrieval(UpdateResourceRequest $request, $model)
    {
        $resource = $request->newResource();

        // Check to see whether Traffic Cop is enabled for this resource...
        if ($resource::trafficCop($request) === false) {
            return false;
        }

        $column = $model->getUpdatedAtColumn();

        if (! $model->{$column}) {
            return false;
        }

        return $request->input('_retrieved_at') && $model->usesTimestamps() && $model->{$column}->gt(
            Carbon::createFromTimestamp($request->input('_retrieved_at'))
        );
    }
}
