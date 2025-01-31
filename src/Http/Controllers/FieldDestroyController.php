<?php

namespace Kongulov\NovaTabTranslatable\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class FieldDestroyController extends Controller
{
    /**
     * Delete the file at the given field.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function handle(NovaRequest $request): \Illuminate\Http\Response
    {
        $resource = $request->findResourceOrFail();

        $explode = explode('_', $request->field);
        $locale = last($explode);
        $fieldNameArray = array_slice($explode, 1, -1);
        $fieldName = implode('_', $fieldNameArray);

        if (!in_array($fieldName, $resource->translatable)) abort(404);

        $resource->authorizeToUpdate($request);
        $model = $resource->model();

        $model->forgetTranslation($fieldName, $locale);
        $model->timestamps = false;
        $model->save();

        return response(['success' => true]);
    }
}
