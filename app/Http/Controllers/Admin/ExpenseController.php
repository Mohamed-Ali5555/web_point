<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Expense;
use App\Model\Facilite;
use App\Model\OrderDetail;
use App\Model\Order;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Model\Translation;
use Rap2hpoutre\FastExcel\FastExcel;

class ExpenseController extends Controller
{
    
    public function store(Request $request)
    {
       

        $request->validate([
            'value'                 => 'required|numeric',
            'facilite_id'          => 'required',
            'date'                 => 'required|date',

   

        ]);
        $expense = new Expense;
        $expense->value = $request->value;
        $expense->date = $request->date;

        $expense->facilite_id = $request->facilite_id;
        $expense->save();

        
        Toastr::success('Expense added successfully!');
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
            $expenses = Expense::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhereHas('facilite', function ($query) use ($value) { //facilite refer to relationship in model expense
                        $query->where('name', 'like', "%{$value}%");
                    });
                   // $q->Where('name', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else {
            $expenses = Expense::orderBy('id', 'desc');
        }
        $expenses = $expenses->paginate(Helpers::pagination_limit())->appends($query_param);

        $facilites = Facilite::where('status',1)->get();
        return view('admin-views.expenses.view', compact('expenses','search','facilites'));

    }



    public function edit($id)
    {
        $expense = Expense::where(['id' => $id])->first();
        $facilites = Facilite::where('status',1)->get();

        return view('admin-views.expenses.edit', compact('expense','facilites'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'value'                 => 'required|numeric',
            'date'                 => 'required|date',

            'facilite_id'          => 'required',

        ]);
        $expense = Expense::find($id);
        $expense->value = $request->value;
        $expense->date = $request->date;

        $expense->facilite_id = $request->facilite_id;
        
        $expense->save();
        

        Toastr::success('expense updated successfully!');
        return back();
    }

    // public function status_update(Request $request)
    // {
    //     $brand = Brand::find($request['id']);
    //     $brand->status = $request['status'];

    //     if($brand->save()){
    //         $success = 1;
    //     }else{
    //         $success = 0;
    //     }
    //     return response()->json([
    //         'success' => $success,
    //     ], 200);
    // }

    public function delete(Request $request)
    {
        $translation = Translation::where('translationable_type','App\Model\Expense')
                                    ->where('translationable_id',$request->id);
        $translation->delete();
        $expense = Expense::find($request->id);
     
        $expense->delete();
        return response()->json();
    }

    public function report(Request $request){
        // $from = $request->input('from');
        // $to = $request->input('to');
        // $facilites = Facilite::where('status',1)->get();

        // $expenses = Expense::whereBetween('date',[$from,$to])->get();
        return view('admin-views.expenses.report_expense');
    }

    public function report_search(Request $request){
        // $from = $request->input('from');
        // $to = $request->input('to');
        // $facilites = Facilite::where('status',1)->get();
        // // if ($request->start_at =='' && $request->end_at =='') {
        // //     $expenses  = Expense::select('*')->get();
        // //           return view('admin-views.expenses.report_expense',compact('expenses'));
        // // }else{

        // $expenses = Expense::whereBetween('date',[$from,$to])->get();
        // $totalPrice = $expenses->sum('value');

        // return view('admin-views.expenses.report_expense', compact('expenses','facilites','totalPrice'));
        // // }
        
        // $request->validate([
        //     'start_at' => 'required|date',
        //     'end_at' => 'required|date|after_or_equal:start_at',
        // ]);
        
        $rdio = $request->rdio;
        $start_at = date($request->start_at);
        $end_at = date($request->end_at);

        if ($rdio == 1) {
            if ($request->start_at == '' && $request->end_at == '') {
                
                $expenses = Expense::select('*')->get();
                $totalPrice = $expenses->sum('value');
                return view('admin-views.expenses.report_expense', compact('expenses', 'totalPrice','start_at','end_at'));
            } else {
                // $start_at = date($request->start_at);
                // $end_at = date($request->end_at);
        
                $expenses = Expense::whereBetween('date', [$start_at, $end_at])->get();
                $totalPrice = $expenses->sum('value');
                return view('admin-views.expenses.report_expense', compact('start_at', 'end_at', 'expenses', 'totalPrice'));
            }
        } else {
            if ($request->start_at == '' && $request->end_at == '') {
                $expenses = Expense::select('*', DB::raw('SUM(value) as total_value'))
                    ->groupBy('facilite_id')
                    ->get();
        
                $totalPrice = $expenses->sum('total_value');
                return view('admin-views.expenses.report_expense', compact('expenses', 'totalPrice','start_at','end_at'));
            } else {
                // $start_at = date($request->start_at);
                // $end_at = date($request->end_at);
        
                $expenses = Expense::select('*', DB::raw('SUM(value) as total_value'))
                    ->whereBetween('date', [$start_at, $end_at])
                    ->groupBy('facilite_id')
                    ->get();
                $totalPrice = $expenses->sum('total_value');
                return view('admin-views.expenses.report_expense', compact('start_at', 'end_at', 'expenses', 'totalPrice'));
            }
        }
    }  
    
    



///////////////////////////
public function profit_report(){
    return view('admin-views.expenses.profit_report');
}


public function profit_report_search(Request $request){
    $start_at = date($request->start_at);
    $end_at = date($request->end_at);
    // $request->validate([
    //     'start_at' => 'required|date',
    //     'end_at' => 'required|date|after_or_equal:start_at',
    // ]);
    
    $start_at = date($request->start_at);
    $end_at = date($request->end_at);
    $order_Details = OrderDetail::whereBetween('created_at', [$start_at, $end_at])->get();
    return view('admin-views.expenses.profit_report',compact('order_Details','start_at','end_at'));
}

    ////////////////////
/////////////////////
/////////////////////
// public function report_details(Request $request,$facilite_id){
//     $start_at = date($request->start_at);
//     $end_at = date($request->end_at);

//     $expenses = Expense::select('*', DB::raw('SUM(value) as total_value'))
//         ->whereBetween('date', [$start_at, $end_at])
//         ->groupBy('facilite_id')
//         ->get();
//     $totalPrice = $expenses->sum('total_value');
//     return $expenses;

// }
///////////////////
////////////////////
////////////////////
}
