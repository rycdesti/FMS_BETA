<?php

namespace App\Http\Controllers\Requisition;

use App\Models\Requisition\PaymentTerm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PaymentTermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index()
    {
        if ($this->isRequestTypeDatatable(request())) {
            $status_filter = request()->status_filter;
            $paymentTerms = PaymentTerm::get();

            if ($status_filter) {
                $paymentTerms = $paymentTerms->where('disabled', $status_filter);
            }

            return DataTables::of($paymentTerms)
                ->editColumn('percentage', function (PaymentTerm $paymentTerm) {
                    $percentage = '';
                    $percentage_array = explode(';', rtrim($paymentTerm->percentage, ';'));
                    foreach ($percentage_array as $key => $percentage_breakdown) {
                        $breakdown = explode('^', $percentage_breakdown);
                        $percentage .= '<div>' . $breakdown[0] . '% ' . str_replace('_', ' ', $breakdown[1]) . '</div>';

                        end($percentage_array);
                        if ($key !== key($percentage_array)) {
                            $percentage .= '<hr>';
                        }
                    }
                    return $percentage;
                })
                ->editColumn('status', function (PaymentTerm $paymentTerm) {
                    if ($paymentTerm->disabled == 'N') {
                        return '<div><span class="badge-primary p-1">Enabled</span></div>';
                    } else {
                        return '<div><span class="badge-danger p-1">Disabled</span></div><br>
                                <div>' . $paymentTerm->disabled_by . '</div>
                                <div><i class="fa fa-clock-o pr-1"></i>' . $paymentTerm->date_disabled->diffForHumans() . '</div>';
                    }
                })
                ->editColumn('logs', function (PaymentTerm $paymentTerm) {
                    return '<div>' . $paymentTerm->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $paymentTerm->created_at->diffForHumans() . '</div><br>' .
                        ($paymentTerm->last_modified ? '<div>' . $paymentTerm->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $paymentTerm->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (PaymentTerm $paymentTerm) {
                    return '<button id="btn-edit" data-id="' . $paymentTerm->id . '" title="Edit Record" type="button" class="btn btn-outline-secondary"><i class="fa fa-edit"></i></button>' .
                        '<button id="btn-delete" data-id="' . $paymentTerm->id . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button><hr>' .
                        '<button id="btn-update-status" data-id="' . $paymentTerm->id . '" type="button" class="btn btn-link">' . ($paymentTerm->disabled == 'N' ? 'Disable' : 'Enable') . '</button>';
                })
                ->rawColumns(['percentage', 'status', 'logs', 'actions'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = [
            'payment_term_name' => 'required',
            'percentage_breakdown.*.percent_code' => 'required_with:percentage_breakdown.*.percent_value',
            'percentage_breakdown.*.percent_value' => 'required_with:percentage_breakdown.*.percent_code',
            'percentage_total' => 'in:100'
        ];

        $custom = [
            'percentage_breakdown.*.percent_code.required_with' => 'Percent code is required.',
            'percentage_breakdown.*.percent_value.required_with' => 'Percent value is required.',
            'percentage_total.in' => 'Payment terms breakdown should total to 100 percent.'
        ];

        $request->validate($validate, $custom);

        $payment_term_name = $request['payment_term_name'] . '@';
        $percentage = '';
        foreach ($request['percentage_breakdown'] as $breakdown) {
            $payment_term_name .= $breakdown['percent_value'] . '%|';
            $percentage .= $breakdown['percent_value'] . '^' . str_replace(' ', '_', trim($breakdown['percent_code'])) . ';';
        }

        $request['payment_term_name'] = $payment_term_name;
        $request['percentage'] = $percentage;
        $request['logs'] = 'Created by: Test';
        $data = $request->all();

        $result = PaymentTerm::create($data);
        if ($result) {
            /**
             * check for failure of event tag when insert try to rollback (DB rollback)
             * try to check if there is other way to insert multiple record
             */
            return response()->json(['success' => true, 'message' => 'The record was added successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = PaymentTerm::find($id);

        $percentage_breakdown_array = array();
        $percentage_array = explode(';', rtrim($data->percentage, ';'));
        foreach ($percentage_array as $key => $breakdown) {
            $breakdown_array = explode('^', $breakdown);
            $percentage_breakdown_array[] = array(
                'percent_code' => str_replace('_', ' ', $breakdown_array[1]),
                'percent_value' => $breakdown_array[0]
            );
        }

        $data = array(
            'id' => $data->id,
            'payment_term_name' => explode('@', $data->payment_term_name)[0],
            'percentage_breakdown' => $percentage_breakdown_array,
        );

        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = [
            'payment_term_name' => 'required',
            'percentage_breakdown.*.percent_code' => 'required_with:percentage_breakdown.*.percent_value',
            'percentage_breakdown.*.percent_value' => 'required_with:percentage_breakdown.*.percent_code',
            'percentage_total' => 'in:100'
        ];

        $custom = [
            'percentage_breakdown.*.percent_code.required_with' => 'Percent code is required.',
            'percentage_breakdown.*.percent_value.required_with' => 'Percent value is required.',
            'percentage_total.in' => 'Payment terms breakdown should total to 100 percent.'
        ];

        $request->validate($validate, $custom);

        $payment_term_name = $request['payment_term_name'] . '@';
        $percentage = '';
        foreach ($request['percentage_breakdown'] as $breakdown) {
            $payment_term_name .= $breakdown['percent_value'] . '%|';
            $percentage .= $breakdown['percent_value'] . '^' . str_replace(' ', '_', trim($breakdown['percent_code'])) . ';';
        }

        $request['payment_term_name'] = $payment_term_name;
        $request['percentage'] = $percentage;
        $request['last_modified'] = 'Last modified by: Test';
        $data = $request->all();

        $result = PaymentTerm::find($id)->update($data);
        if ($result) {
            /**
             * check for failure of event tag when insert try to rollback (DB rollback)
             * try to check if there is other way to insert multiple record
             */
            return response()->json(['success' => true, 'message' => 'The record was updated successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PaymentTerm $paymentTerm
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(PaymentTerm $paymentTerm)
    {
        $result = $paymentTerm->delete();
        if ($result) {
            return response()->json(['success' => true, 'message' => 'The record was deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Update the status of specified resource from storage
     *
     * @param PaymentTerm $paymentTerm
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update_status(PaymentTerm $paymentTerm)
    {
        $status = $paymentTerm->disabled == 'N' ? 'Y' : 'N';

        if ($status == 'Y') {
            $result = $paymentTerm->update([
                'disabled' => $status,
                'date_disabled' => now(),
                'disabled_by' => 'Disabled by: Test',
                'last_modified' => 'Last modified by: Test',
            ]);
        } else {
            $result = $paymentTerm->update([
                'disabled' => $status,
                'last_modified' => 'Last modified by: Test',
            ]);
        }

        if ($result) {
            $status = $status == 'N' ? 'enabled' : 'disabled';
            return response()->json(['success' => true, 'message' => 'The record was ' . $status . ' successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }
}
