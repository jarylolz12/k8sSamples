<?php

namespace App\Nova\Flexible\Resolvers;

use Whitecube\NovaFlexibleContent\Value\ResolverInterface;

class CustomContentResolver implements ResolverInterface
{
    /**
     * get the field's value
     *
     * @param  mixed  $resource
     * @param  string $attribute
     * @param  Whitecube\NovaFlexibleContent\Layouts\Collection $layouts
     * @return Illuminate\Support\Collection
     */
    public function get($resource, $attribute, $layouts)
    {
        /*
        $blocks = $resource->blocks()->orderBy('order')->get();

        return $blocks->map(function($block) use ($layouts) {
            $layout = $layouts->find($block->name);

            if(!$layout) return;

            return $layout->duplicateAndHydrate($block->id, ['value' => $block->value]);
        })->filter();*/

    
    }

    /**
     * Set the field's value
     *
     * @param  mixed  $model
     * @param  string $attribute
     * @param  Illuminate\Support\Collection $groups
     * @return string
     */
    public function set($model, $attribute, $groups)
    {

    
        $class = get_class($model);

        $class::saved(function ($model) use ($groups) {
            $blocks = $groups->map(function($group, $index) {
                return [
                    'name' => $group->name(),
                    'value' => json_encode($group->getAttributes()),
                    'order' => $index
                ];
            });

            // This is a quick & dirty example, syncing the models is probably a better idea.
         //   $model->blocks()->delete();
            $model->suppliers()->createMany($blocks);
        });
    }
}
