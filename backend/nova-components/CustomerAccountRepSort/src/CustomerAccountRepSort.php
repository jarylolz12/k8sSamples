<?php

namespace Kenetashi\CustomerAccountRepSort;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Illuminate\Support\Facades\DB;

class CustomerAccountRepSort extends Filter
{

    public $name = 'Filter by Account Representative';

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'customer-account-rep-sort';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->whereJsonContains('offices_managers', [['manager_id' => intval($value)]]);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {

        $checkRoleId= \Spatie\Permission\Models\Role::where('name', 'Account Manager')->first()->id;

        $managers = DB::table('model_has_roles')
                                ->select(['users.name AS text','users.id AS id'])
                                ->join('users', 'users.id', '=', 'model_has_roles.model_id')
                                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                                ->where('roles.id', $checkRoleId)
                                ->where('model_has_roles.model_type', 'App\AccountManager')
                                ->distinct('model_has_roles.id')
                                ->get();

        $finalManagers = [];

        if (count($managers) > 0) {
            foreach ($managers as $key => $manager) {
              array_push($finalManagers,
                [
                    'name' => $manager->text,
                    'value' => $manager->id
                ]);
            }
        }

        return $finalManagers;
        //return [];
    }
}
