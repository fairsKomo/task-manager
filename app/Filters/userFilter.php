<?php
namespace App\Filters;

use Illuminate\Http\Request;

class UserFilter extends ApiFilter {
    protected $safeParms = [
        'id' => ['eq', 'ne'],
        'name' => ['eq', 'ne'],
        'email' => ['eq', 'ne'],
    ];
    protected $opreatorMap = [
        'ne' => '!=',
        'eq' => '=',
    ];

}