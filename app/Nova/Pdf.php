<?php

namespace App\Nova;

use App\Nova\Actions\DownloadPdef;
use App\Nova\Actions\previewAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Whitecube\NovaFlexibleContent\Flexible;
// use NumaxLab\NovaCKEditor5Classic\CKEditor5Classic;
use Mostafaznv\NovaCkEditor\CkEditor;
use Yna\NovaSwatches\Swatches;



class Pdf extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Pdf::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','title'
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
            Text::make('Title','title')->required(),
            // Image::make('Developer logo','developer_logo'),
            Image::make('Cover','cover')->creationRules('required'),
            Image::make('Last page image','last'),
            // BelongsTo::make('Area','area')->searchable(),
            Select::make('Area', 'area_id') ->searchable() ->options(\App\Models\Area::all()->pluck('title', 'id')) ->displayUsingLabels()->required(),
            Select::make('Developer', 'developer_id') ->searchable() ->options(\App\Models\Developer::all()->pluck('title', 'id')) ->displayUsingLabels(),
            Flexible::make('pages')
            ->button('Add Page')
            ->addLayout('Map', 'map', [
                // Text::make('Title','title'),
                CkEditor::make(trans('Title'), 'title')->height('60')->stacked()->toolbar([
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    'strikethrough',
                    '|',
                    'undo',
                    'redo'
                ])->rules('required'),
                // Trix::make('Description','description'),
                CkEditor::make(trans('Description'), 'description')->height('400')->stacked()->toolbar([
                    'heading',
                    '|',
                    'link',
                    '|',
                    'bold',
                    'italic',
                    'alignment',
                    'subscript',
                    'superscript',
                    'underline',
                    'strikethrough',
                    '|',
                    'blockQuote',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'insertTable',
                    '|',
                    'undo',
                    'redo'
                ])->rules('required'),
                Image::make('map')->creationRules('required'),
                Swatches::make('Text Color', 'text_color')->withProps([
                    'colors' => ['#D5DCDD', '#002D31',"#fff","#000"],
                ])->default('#fff')->rules('required'),
                Swatches::make('Background Color', 'background_color')->withProps([
                    'colors' => ['#D5DCDD', '#002D31'],
                ])->default('#002D31')->rules('required'),
            ])
            ->addLayout('Description - 2 Images', 'description-2-images', [
                // Text::make('Title','title'),
                CkEditor::make(trans('Title'), 'title')->height('60')->stacked()->toolbar([
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    'strikethrough',
                    '|',
                    'undo',
                    'redo'
                ])->rules('required'),
                // Trix::make('Description','description'),
                CkEditor::make(trans('Description'), 'description')->height('400')->stacked()->toolbar([
                    'heading',
                    '|',
                    'link',
                    '|',
                    'bold',
                    'italic',
                    'alignment',
                    'subscript',
                    'superscript',
                    'underline',
                    'strikethrough',
                    '|',
                    'blockQuote',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'insertTable',
                    '|',
                    'undo',
                    'redo'
                ])->rules('required'),
                Image::make('img1')->creationRules('required'),
                Image::make('img2')->creationRules('required'),
                Swatches::make('Text Color', 'text_color')->withProps([
                    'colors' => ['#D5DCDD', '#002D31',"#fff","#000"],
                ])->default('#fff')->rules('required'),
                Swatches::make('Background Color', 'background_color')->withProps([
                    'colors' => ['#D5DCDD', '#002D31'],
                ])->default('#002D31')->rules('required'),
            ])
            ->addLayout('Single image with caption', 'single_pic_with_caption', [
                Image::make('Image','img')->creationRules('required'),
                Text::make('Caption','caption')->required(),
                Swatches::make('Text Color', 'text_color')->withProps([
                    'colors' => ['#D5DCDD', '#002D31',"#fff","#000"],
                ])->default('#fff')->rules('required'),
                Swatches::make('Background Color', 'background_color')->withProps([
                    'colors' => ['#D5DCDD', '#002D31'],
                ])->default('#002D31')->rules('required'),
            ])
            ->addLayout('Floorplan', 'floor_plans', [
                Image::make('Floorplan','floorplan')->creationRules('required'),
            ])
            ->addLayout('Text Image Image Text', 'text_img_img_text', [
                CkEditor::make(trans('Title1'), 'title1')->height('60')->stacked()->toolbar([
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    'strikethrough',
                    '|',
                    'undo',
                    'redo'
                ])->rules('required'),
                CkEditor::make(trans('Description1'), 'description1')->height('400')->stacked()->toolbar([
                    'heading',
                    '|',
                    'link',
                    '|',
                    'bold',
                    'italic',
                    'alignment',
                    'subscript',
                    'superscript',
                    'underline',
                    'strikethrough',
                    '|',
                    'blockQuote',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'insertTable',
                    '|',
                    'undo',
                    'redo'
                ])->rules('required'),
                Image::make('Image 1','img1')->creationRules('required'),
                CkEditor::make(trans('Title2'), 'title2')->height('60')->stacked()->toolbar([
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    'strikethrough',
                    '|',
                    'undo',
                    'redo'
                ])->rules('required'),
                CkEditor::make(trans('Description2'), 'description2')->height('400')->stacked()->toolbar([
                    'heading',
                    '|',
                    'link',
                    '|',
                    'bold',
                    'italic',
                    'alignment',
                    'subscript',
                    'superscript',
                    'underline',
                    'strikethrough',
                    '|',
                    'blockQuote',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'insertTable',
                    '|',
                    'undo',
                    'redo'
                ])->rules('required'),
                Image::make('Image 2','img2')->creationRules('required'),
                Swatches::make('Text Color', 'text_color')->withProps([
                    'colors' => ['#D5DCDD', '#002D31',"#fff","#000"],
                ])->default('#fff')->rules('required'),
                Swatches::make('Background Color', 'background_color')->withProps([
                    'colors' => ['#D5DCDD', '#002D31'],
                ])->default('#002D31')->rules('required'),
            ]),


            Flexible::make('Landmarks','landmarks')
            ->button('Add Landmark')
            ->addLayout('Landmark', 'landmarks', [
                Text::make('Landmark','landmark')->required(),
                Text::make('Distance','distance')->required(),
                Text::make('Drive','drive')->required(),
            ]),
            Flexible::make('Payment plans','payment_plan')
            ->button('Add Payment Plan')
            ->addLayout('Paymentplan', 'paymentplan', [
                Text::make('Installment','installment')->required(),
                Text::make('Milestone','milestone')->required(),
                Text::make('Payment','payment')->required(),

            ]),
            Flexible::make('Projection','projections')
            ->button('Add Projection')
            ->addLayout('Projection', 'projection', [
                Text::make('Property price','property_price')->required(),
                Text::make('Minimum rate per night','minimum_rate_per_night')->required(),
                Text::make('Yearly service charge','yearly_service_charge')->required(),
                Text::make('Rental rate per year','rental_rate_per_year')->required(),
            ])->limit(1),
            Hidden::make('user_id')->default(Auth::user()->id),
            BelongsTo::make('User','user',User::class)->default(Auth::user()->id)->hideWhenCreating()->hideWhenUpdating(),

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
        return [
            new DownloadPdef,
            new previewAction,
        ];
    }

    // public static function indexQuery(NovaRequest $request, $query)
    // {
    //     if(Auth::user()->is_admin){
    //         return $query;
    //     }
    //     else{

    //         return $query->where('user_id', Auth::user()->id) ;
    //     }
    // }
    public static function label() {
        return 'Brochures';
    }

}
