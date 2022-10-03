<?php

namespace App\Models;

use App\Http\Traits\UniqueUndeletedTrait;
use App\Models\Asset;
use App\Models\SnipeModel;
use App\Models\Traits\Searchable;
use App\Models\User;
use App\Presenters\Presentable;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;
use Watson\Validating\ValidatingTrait;

class Location extends SnipeModel
{
    use HasFactory;

    protected $presenter = \App\Presenters\LocationPresenter::class;
    use Presentable;
    use SoftDeletes;

    protected $table = 'locations';
    protected $rules = [
        'name'          => 'required|min:2|max:255|unique_undeleted',
        'city'          => 'min:2|max:255|nullable',
        'country'       => 'min:2|max:255|nullable',
        'address'       => 'max:80|nullable',
        'address2'      => 'max:80|nullable',
        'zip'           => 'min:3|max:10|nullable',
        'manager_id'    => 'exists:users,id|nullable',
        'parent_id'     => 'non_circular:locations,id',
    ];

    protected $casts = [
        'parent_id'     => 'integer',
        'manager_id'    => 'integer',
    ];

    /**
     * Whether the model should inject it's identifier to the unique
     * validation rules before attempting validation. If this property
     * is not set in the model it will default to true.
     *
     * @var bool
     */
    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;
    use UniqueUndeletedTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'parent_id',
        'address',
        'address2',
        'city',
        'state',
        'country',
        'zip',
        'ldap_ou',
        'currency',
        'manager_id',
        'image',
    ];
    protected $hidden = ['user_id'];

    use Searchable;

    /**
     * The attributes that should be included when searching the model.
     *
     * @var array
     */
    protected $searchableAttributes = ['name', 'address', 'city', 'state', 'zip', 'created_at', 'ldap_ou'];

    /**
     * The relations and their attributes that should be included when searching the model.
     *
     * @var array
     */
    protected $searchableRelations = [
      'parent' => ['name'],
    ];

    public function isDeletable()
    {
        return Gate::allows('delete', $this)
                && ($this->assignedAssets()->count() === 0)
                && ($this->assets()->count() === 0)
                && ($this->users()->count() === 0);
    }

    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'location_id');
    }


    public function assets()
    {
        return $this->hasMany(\App\Models\Asset::class, 'location_id')
            ->whereHas('assetstatus', function ($query) {
                $query->where('status_labels.deployable', '=', 1)
                        ->orWhere('status_labels.pending', '=', 1)
                        ->orWhere('status_labels.archived', '=', 0);
            });
    }

    public function rtd_assets()
    {
        /* This used to have an ...->orHas() clause that referred to
           assignedAssets, and that was probably incorrect, as well as
           definitely was setting fire to the query-planner. So don't do that.

           It is arguable that we should have a '...->whereNull('assigned_to')
           bit in there, but that isn't always correct either (in the case
           where a user has no location, for example).

           In all likelyhood, we need to denorm an "effective_location" column
           into Assets to make this slightly less miserable.
        */
        return $this->hasMany(\App\Models\Asset::class, 'rtd_location_id');
    }

    public function consumables()
    {
        return $this->hasMany(\App\Models\Consumable::class, 'location_id');
    }

    public function components()
    {
        return $this->hasMany(\App\Models\Component::class, 'location_id');
    }

    public function accessories()
    {
        return $this->hasMany(\App\Models\Accessory::class, 'location_id');
    }



    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id')
            ->with('parent');
    }

    public function manager()
    {
        return $this->belongsTo(\App\Models\User::class, 'manager_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->with('children');
    }

    // I don't think we need this anymore since we de-normed location_id in assets?
    public function assignedAssets()
    {
        return $this->morphMany(\App\Models\Asset::class, 'assigned', 'assigned_type', 'assigned_to')->withTrashed();
    }

    public function setLdapOuAttribute($ldap_ou)
    {
        return $this->attributes['ldap_ou'] = empty($ldap_ou) ? null : $ldap_ou;
    }

    /**
     * Query builder scope to order on parent
     *
     * @param  Illuminate\Database\Query\Builder  $query  Query builder instance
     * @param  text                              $order       Order
     *
     * @return Illuminate\Database\Query\Builder          Modified query builder
     */
    public static function indenter($locations_with_children, $parent_id = null, $prefix = '')
    {
        $results = [];

        if (! array_key_exists($parent_id, $locations_with_children)) {
            return [];
        }

        foreach ($locations_with_children[$parent_id] as $location) {
            $location->use_text = $prefix.' '.$location->name;
            $location->use_image = ($location->image) ? url('/').'/uploads/locations/'.$location->image : null;
            $results[] = $location;
            //now append the children. (if we have any)
            if (array_key_exists($location->id, $locations_with_children)) {
                $results = array_merge($results, self::indenter($locations_with_children, $location->id, $prefix.'--'));
            }
        }

        return $results;
    }

    /**
     * Query builder scope to order on parent
     *
     * @param  Illuminate\Database\Query\Builder  $query  Query builder instance
     * @param  text                              $order       Order
     *
     * @return Illuminate\Database\Query\Builder          Modified query builder
     */
    public function scopeOrderParent($query, $order)
    {
        // Left join here, or it will only return results with parents
        return $query->leftJoin('locations as parent_loc', 'locations.parent_id', '=', 'parent_loc.id')->orderBy('parent_loc.name', $order);
    }

    /**
     * Query builder scope to order on manager name
     *
     * @param  \Illuminate\Database\Query\Builder  $query  Query builder instance
     * @param  text                              $order       Order
     *
     * @return \Illuminate\Database\Query\Builder          Modified query builder
     */
    public function scopeOrderManager($query, $order)
    {
        return $query->leftJoin('users as location_user', 'locations.manager_id', '=', 'location_user.id')->orderBy('location_user.first_name', $order)->orderBy('location_user.last_name', $order);
    }
}
