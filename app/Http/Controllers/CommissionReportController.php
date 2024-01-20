<?php

namespace App\Http\Controllers;

use App\Models\MonthlyCommission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommissionReportController extends Controller
{
    public function index(){
        return view('commission_report.list');
    }

    public function allCommissionReportlist(Request $request){
        if ($request->ajax()) {
            $columns = array(
                0 =>'id',
                1 =>'user_info',
                2=> 'total_amount',
                3=> 'commission_status',
                4=> 'payment_date',
                5=> 'month',
            );

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if($order == "id"){
                $order = "created_at";
                $dir = 'desc';
            }

            $totalData = MonthlyCommission::where('user_id',Auth::user()->id)->count();
            $totalFiltered = $totalData;

            $start_date = date("Y-m-d",strtotime($request->start_date));
            $end_date = date("Y-m-d",strtotime($request->end_date));

            if(empty($request->input('search.value')))
            {
                $MonthlyCommissions = MonthlyCommission::with('commission','user')->where('user_id',Auth::user()->id);
                if (isset($request->start_date) && $request->start_date!="" && isset($request->end_date) && $request->end_date!=""){
                    $MonthlyCommissions = $MonthlyCommissions->whereRaw("DATE(created_at) between '".$start_date."' and '".$end_date."'");
                }
                $MonthlyCommissions = $MonthlyCommissions->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

                $totalFiltered = count($MonthlyCommissions->toArray());
            }
            else {
                $search = $request->input('search.value');
                $MonthlyCommissions = MonthlyCommission::with('commission','user')->where('user_id',Auth::user()->id);
                if (isset($request->start_date) && $request->start_date!="" && isset($request->end_date) && $request->end_date!=""){
                    $MonthlyCommissions = $MonthlyCommissions->whereRaw("DATE(created_at) between '".$start_date."' and '".$end_date."'");
                }
                $MonthlyCommissions = $MonthlyCommissions->where(function($mainQuery) use($search){
                    $mainQuery->where('total_amount','LIKE',"%{$search}%")
                        ->orWhere('current_month', 'LIKE',"%{$search}%")
                        ->orWhere('current_year', 'LIKE',"%{$search}%")
                        ->orWhere('payment_date', 'LIKE',"%{$search}%")
                        ->orWhereHas('user',function ($Query) use($search) {
                            $Query->where('first_name', 'Like', '%' . $search . '%')->orWhere('last_name', 'Like', '%' . $search . '%');
                        });
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

                $totalFiltered = count($MonthlyCommissions->toArray());
            }

            $data = array();

            if(!empty($MonthlyCommissions))
            {
                foreach ($MonthlyCommissions as $MonthlyCommission)
                {
                    if(isset($MonthlyCommission->user->profile_pic) && $MonthlyCommission->user->profile_pic!=null){
                        $profile_pic = env('DISPLAY_IMAGE_URL').'profile_pic/'.$MonthlyCommission->user->profile_pic;
                    }
                    else{
                        $profile_pic = url('public/images/default_avatar.jpg');
                    }

                    $user_info = '<img src="'.$profile_pic.'" width="50px" height="50px">';
                    if (isset($MonthlyCommission->user->first_name) && isset($MonthlyCommission->user->last_name)){
                        $user_info .= '<span>'.$MonthlyCommission->user->first_name.' '.$MonthlyCommission->user->last_name.'</span>';
                    }

                    if(isset($MonthlyCommission->commission_status)) {
                        $commission_status = getCommissionStatus($MonthlyCommission->commission_status);
                        $commission_status = '<span class="'.$commission_status['class'].'">'.$commission_status['commission_status'].'</span>';
                    }

                    $payment_date = "";
                    if (isset($MonthlyCommission->payment_date)){
                        $payment_date = date('d-m-Y H:i:s', strtotime($MonthlyCommission->payment_date));
                    }

                    $nestedData['user_info'] = $user_info;
                    $nestedData['total_amount'] = '<i class="fa fa-inr" aria-hidden="true"></i> '.$MonthlyCommission->total_amount;
                    $nestedData['commission_status'] = $commission_status;
                    $nestedData['payment_date'] = $payment_date;
                    $nestedData['month'] = date("F", mktime(0, 0, 0, $MonthlyCommission->current_month, 1)) .", ".$MonthlyCommission->current_year;
                    $data[] = $nestedData;
                }
            }

            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data,
            );

            echo json_encode($json_data);
        }
    }
}
