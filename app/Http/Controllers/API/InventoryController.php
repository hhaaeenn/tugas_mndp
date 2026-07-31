<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Inventories;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = Inventories::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            $res = Inventories::getAllResult($params, $request);
        } else {
            $res = Inventories::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return Inventories::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Inventories::createOrUpdate($params, $request->method(), $request);
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Inventories::createOrUpdate($params, $request->method(), $request);
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();
        return Inventories::deleteById($id, $params, $request);
    }

    public function approve(Request $request, $id)
    {
        $params = $request->all();

        return Inventories::approveById($id, $params, $request);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,plain',
        ]);

        $file = $request->file('file');

        $rows = [];
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                $rows[] = $row;
            }
            fclose($handle);
        }

        if (empty($rows)) {
            return response()->json([
                'status' => 'error',
                'message' => 'File kosong atau tidak dapat dibaca.',
                'data' => ['File kosong atau tidak dapat dibaca.'],
            ], 400);
        }

        return Inventories::importFromCsv($rows);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = ['inventories.id', 'inventories.code', 'inventories.name', 'inventories.stock', 'inventories.price'];
        
        $dataOrder = [];

        $limit = $request->length;
        $start = $request->start;

        foreach ($request->order as $row) {
            $nestedOrder['column'] = $columns[$row['column']] ?? 'inventories.id';
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

        $res = Inventories::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData = $row;
                $nestedData['action'] = '';
                $nestedData['action'] .= '<div class="actions">';
                $nestedData['action'] .= '<a class="btn btn-sm btn-icon btn-info text-white" id="view-data" data-id="'.$row['id'].'"><i class="bi bi-eye"></i></a> ';
                $nestedData['action'] .= '<a class="btn btn-sm btn-icon btn-warning text-white" id="edit-data" data-id="'.$row['id'].'"><i class="bi bi-pencil"></i></a> ';
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
