<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    |
    | Configure the resources (models) you'd like to be available in Runway.
    |
    */

    'resources' => [
        \App\Models\ContentReport::class => [
            'name' => 'Content Reports',
            'blueprint' => 'content_report',
            'title_field' => 'created_at',
            'order_by' => 'created_at',
            'order_by_direction' => 'DESC',
            'cp_icon' => 'flag'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Disable Migrations?
    |--------------------------------------------------------------------------
    |
    | Should Runway's migrations be disabled?
    | (eg. not automatically run when you next vendor:publish)
    |
    */

    'disable_migrations' => false,

];
