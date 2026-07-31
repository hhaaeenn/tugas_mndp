<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = Sales::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            $res = Sales::getAllResult($params, $request);
        } else {
            $res = Sales::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return Sales::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Sales::createOrUpdate($params, $request->method(), $request);
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Sales::createOrUpdate($params, $request->method(), $request);
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();
        return Sales::deleteById($id, $params, $request);
    }

    public function approve(Request $request, $id)
    {
        $params = $request->all();

        return Sales::approveById($id, $params, $request);
    }

    public function downloadPerDay(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:excel,pdf',
        ]);

        $date = $request->date;
        $type = $request->type;

        $data = Sales::select('sales.*', 'users.name as user_name')
            ->leftJoin('users', 'users.id', '=', 'sales.user_id')
            ->whereDate('sales.date', $date)
            ->orderBy('sales.id', 'desc')
            ->get();

        if ($type === 'excel') {
            $csv = "\xEF\xBB\xBF";
            $csv .= "Nomor,Tanggal,Total,User\n";
            foreach ($data as $row) {
                $csv .= '"'.$row->number.'","'.$row->date.'","'.$row->total_amount.'","'.str_replace('"', '""', $row->user_name).'"'."\n";
            }

            $filename = 'sales_per_day_'.$date.'.csv';
            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ]);
        }

        $pdf = Pdf::loadView('export.transactions_per_day', [
            'title' => 'Data Sales',
            'date' => $date,
            'rows' => $data,
        ]);
        return $pdf->download('sales_per_day_'.$date.'.pdf');
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = [
            'sales.id',
            'sales.number',
            'sales.date',
            'sales.total_amount',
            'users.name'
        ];

        $dataOrder = [];

        $limit = $request->length;
        $start = $request->start;

        foreach ($request->order as $row) {
            $nestedOrder['column'] = $columns[$row['column']] ?? 'sales.id';
            $nestedOrder['dir'] = $row['dir'];

            $dataOrder[] = $nestedOrder;
        }

        $order = $dataOrder;
        $dir = $request->order[0]['dir'];
        $search = $request->search['value'];
        $filter = $request->filter;

        if (is_string($filter)) {
            $decoded_filter = json_decode($filter, true);
            $filter = json_last_error() === JSON_ERROR_NONE ? $decoded_filter : [];
        } elseif (is_object($filter)) {
            $filter = json_decode(json_encode($filter), true);
        }

        if (!is_array($filter)) {
            $filter = [];
        }

        $res = Sales::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData = $row;
                $nestedData['action'] = '';
                $nestedData['action'] .= '<div class="actions">';
                $nestedData['action'] .= '<a class="btn btn-sm btn-icon btn-danger text-white" id="delete-data" data-id="'.$row['id'].'"><i class="bi bi-trash"></i></a>';
                $nestedData['action'] .= '</div>';

                $data[] = $nestedData;
            }
        }

        $json_data = [
            'draw'            => intval($request->draw),
            'recordsTotal'    => intval($res['totalData']),
            'recordsFiltered' => intval($res['totalFiltered']),
            'data'            => $data,
            'order'           => $order
        ];

        return json_encode($json_data);
    }
}
