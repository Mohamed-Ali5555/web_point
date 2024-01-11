<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Facilite;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Translation;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;

class FacilitesController extends Controller
{
  

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                 => 'required',
        ]);

        $facilite = new Facilite;
        $facilite->name = $request->name;
        $facilite->save();
        Toastr::success('facilite added successfully!');
    
        return back();
    }

    /**
     * Brand list show, search
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */



     
    function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $facilites = Facilite::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else {
            $facilites = Facilite::orderBy('id', 'desc');
        }
        $facilites = $facilites->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.facilites.view', compact('facilites','search'));
    }

    /**
     * Export brand list by excel
     * @return string|\Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
 

    public function edit($id)
    {
        $facilite = Facilite::where(['id' => $id])->first();
        return view('admin-views.Facilites.edit', compact('facilite'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'url is required!',
        ]);

        $facilite = Facilite::find($id);
        $facilite->name = $request->name;
    
        $facilite->save();

        Toastr::success('Facilite updated successfully!');
        return back();
    }

    public function status_update(Request $request)
    {
        $facilite = Facilite::find($request['id']);
        $facilite->status = $request['status'];

        if($facilite->save()){
            $success = 1;
        }else{
            $success = 0;
        }
        return response()->json([
            'success' => $success,
        ], 200);
    }

    public function delete(Request $request)
    {
        $translation = Translation::where('translationable_type','App\Model\Facilite')
                                    ->where('translationable_id',$request->id);
        $translation->delete();
        $facilite = Facilite::find($request->id);
        
        $facilite->delete();
        return response()->json();
    }
}
