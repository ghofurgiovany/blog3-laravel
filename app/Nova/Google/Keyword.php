<?php

namespace App\Nova\Google;

use App\Models\Google\Keyword as KeywordModel;
use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use NovaAttachMany\AttachMany;

class Keyword extends Resource
{
    public static $group = 'Google News';
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = KeywordModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'keyword';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'keyword',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            // ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make('Author'),
            Text::make('Keyword'),
            Text::make('Country')->displayUsing(fn ($country) => $country->name)->hideWhenCreating()->hideWhenUpdating(),
            Text::make('Language')->displayUsing(fn ($language) => \strtoupper($language)),
            MorphMany::make('Posts'),
            MorphMany::make('Categories'),
            MorphMany::make('Countries'),

            // AttachMany::make('Posts'),
            AttachMany::make('Categories'),
            AttachMany::make('Countries')
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
