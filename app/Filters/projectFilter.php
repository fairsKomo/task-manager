<?php
namespace App\Filters;

use Illuminate\Http\Request;

class ProjectFilter extends ApiFilter {
    protected $safeParms = [
        'title' => ['eq', 'ne'],
    ];
    protected $opreatorMap = [
        'ne' => '!=',
        'eq' => '=',
    ];

}