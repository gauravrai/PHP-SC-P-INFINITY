<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use Helper;
use App\AcademicSession;
use App\Section;
use App\SchoolClass;

class AjaxController extends Controller
{
    public function count_chars(Request $request)
    {
    	return strlen($request->str);
    }
    public function sections(Request $request)
    {
    	$class=SchoolClass::where('id', $request->id)
                                    ->with('sections')
                                    ->orderBy('sort_order', 'asc')
                                    ->first();
        return $class->sections->pluck('name', 'id');
    }

}
