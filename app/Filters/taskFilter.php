<?php
namespace App\Filters;

use Illuminate\Http\Request;

class TaskFilter extends ApiFilter {
    protected $safeParms = [
        'title' => ['eq', 'ne'],
    ];
    protected $opreatorMap = [
        'ne' => '!=',
        'eq' => '=',
    ];

}