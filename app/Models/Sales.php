<?php

namespace App\Models;

use DB;
use Illuminate\Support\Str;
use App\Helpers\AutoNumberHelper;
use App\Helpers\ModelHelper;
use App\Models\Inventories;
use App\Models\SalesDetails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string number
 * @property string date
 * @property int    user_id
 * @property string total_amount
 * @property int    created_at
 * @property int    updated_at
 * @property int    deleted_at
 */
class Sales extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sales';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'date',
        'user_id',
        'total_amount',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'number' => 'string', 'date' => 'date', 'user_id' => 'int', 'total_amount' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'deleted_at' => 'datetime'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [

    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    public $incrementing = true;

    // Scopes...

    // Functions ...

    // Relations ...

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;
        
        return [
            'field' => [
                'id' => ['column' => $model->table.'.id', 'alias' => 'id', 'type' => 'int'],
                'number' => ['column' => $model->table.'.number', 'alias' => 'number', 'type' => 'string'],
                'date' => ['column' => $model->table.'.date', 'alias' => 'date', 'type' => 'date'],
                'user_id' => ['column' => $model->table.'.user_id', 'alias' => 'user_id', 'type' => 'int'],
                'user_name' => ['column' => 'users.name', 'alias' => 'user_name', 'type' => 'string'],
                'total_amount' => ['column' => $model->table.'.total_amount', 'alias' => 'total_amount', 'type' => 'string'],
                'deleted_at' => ['column' => $model->table.'.deleted_at', 'alias' => 'deleted_at', 'type' => 'date'],
                'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'date'],
                'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'date'],
            ],
            'join' => [
                ['table' => 'users', 'type' => 'left', 'on' => ['users.id', '=', $model->table . '.user_id']],
            ],
            'where' => [

            ]
        ];
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = [])
    {
        $schema = self::mapSchema();
        $totalData = self::count();

        $qry = ModelHelper::select($schema['field'], null, __CLASS__);
        ModelHelper::join($schema['join'], null, $qry);
        
        //FILTER

        // Date range filter (Rentera standard: start_date & end_date)
        if (isset($filter['start_date']) && $filter['start_date'] !== '' && isset($filter['end_date']) && $filter['end_date'] !== '') {
            $qry->whereBetween($schema['field']['date']['column'], [$filter['start_date'], $filter['end_date']]);
        } else if (isset($filter['start_date']) && $filter['start_date'] !== '') {
            $qry->whereDate($schema['field']['date']['column'], '>=', $filter['start_date']);
        } else if (isset($filter['end_date']) && $filter['end_date'] !== '') {
            $qry->whereDate($schema['field']['date']['column'], '<=', $filter['end_date']);
        }

        // Role restrictions
        if (auth()->guard('sanctum')->check() && auth()->guard('sanctum')->user()->role === 'Sales') {
            $qry->where($schema['field']['user_id']['column'], auth()->guard('sanctum')->id());
        }
        
        $totalFiltered = $qry->count();

        if (empty($search)) {
            if ($length > 0) {
                $qry->skip($start)->take($length);
            }
            foreach ($order as $row) {
                $qry->orderBy($row['column'], $row['dir']);
            }
        } else {
            foreach (array_values($schema['field']) as $key => $val) {
                if ($key < 1) {
                    $qry->whereRaw('('.$val['column'].'::varchar(255) ILIKE \'%'.$search.'%\'');
                } else if (count(array_values($schema['field'])) == ($key + 1)) {
                    $qry->orWhereRaw($val['column'].'::varchar(255) ILIKE \'%'.$search.'%\')');
                } else {
                    $qry->orWhereRaw($val['column'].'::varchar(255) ILIKE \'%'.$search.'%\'');
                }
            }

            $totalFiltered = $qry->count();

            if ($length > 0) {
                $qry->skip($start)->take($length);
            }

            foreach ($order as $row) {
                $qry->orderBy($row['column'], $row['dir']);
            }
        }

        $qry_result = $qry->get();
        
        return [
            'data' => $qry_result,
            'totalData' => $totalData,
            'totalFiltered' => $totalFiltered
        ];
    }

    public static function getPaginatedResult($params, $request)
    {
        $append = [];
        $schema = self::mapSchema();
        $paramsPage = isset($params['page']) ? $params['page'] : 0;
        $or = [];
        unset($params['page']);
        if (isset($params['or']) && $params['or']) {
            $or = $params['or'];
            unset($params['or']);
        }
        $db = ModelHelper::select($schema['field'], $request, __CLASS__);
        ModelHelper::join($schema['join'], $request, $db);
        if ($params) {
            ModelHelper::dynamicFilterAnd($params, $request, $db, __CLASS__);
        }
        if ($or) {
            ModelHelper::dynamicFilterOr($or, $request, $db, __CLASS__);
        }
        $results = ModelHelper::generatePagingResults($schema, $paramsPage, $params, $request, $db, $append);
        return response()->json($results);
    }

    public static function getById($id, $params = [], $request = null)
    {
        $models = new self;
        $append = [];
        $schema = self::mapSchema();
        $db = ModelHelper::select($schema['field'], $request, __CLASS__)->where($models->table.'.id', $id);
        ModelHelper::join($schema['join'], $request, $db);

        $sale = $db->first();
        if ($sale) {
            $sale->items = SalesDetails::select([
                'id',
                'sale_id',
                'inventory_id',
                'quantity',
                'price',
                'subtotal'
            ])->where('sale_id', $id)->get();
        }

        return response()->json($sale);
    }

    public static function getAllResult($params, $request)
    {
        $append = [];
        $schema = self::mapSchema();
        $or = [];
        unset($params['all']);
        if (isset($params['or']) && $params['or']) {
            $or = $params['or'];
            unset($params['or']);
        }
        $db = ModelHelper::select($schema['field'], $request, __CLASS__);
        ModelHelper::join($schema['join'], $request, $db);
        if ($params) {
            ModelHelper::dynamicFilterAnd($params, $request, $db, __CLASS__);
        }
        if ($or) {
            ModelHelper::dynamicFilterOr($or, $request, $db, __CLASS__);
        }
        $results = ModelHelper::generateAllResults($schema, $params, $request, $db, $append);
        return response()->json($results);
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();

        $filename = null;

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        $messages = [
            'date' => 'Tanggal Wajib Diisi',
            'items' => 'Minimal satu item harus dipilih',
        ];

        $validator = Validator::make($params, [
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.inventory_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation',
                'message' => $validator->errors()->first(),
                'data' => $validator->errors()->all()
            ], 400);
        }

        $total_amount = 0;
        $items_data = [];

        foreach ($params['items'] as $item) {
            $inventory = Inventories::where('id', $item['inventory_id'])->whereNull('deleted_at')->first();

            if (!$inventory || $inventory->stock < $item['quantity']) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stock tidak cukup untuk ' . ($inventory ? $inventory->name : 'Item ID '.$item['inventory_id']),
                ], 422);
            }
            
            $subtotal = $inventory->price * $item['quantity'];
            $total_amount += $subtotal;
            
            $items_data[] = [
                'inventory_id' => $item['inventory_id'],
                'quantity' => $item['quantity'],
                'price' => $inventory->price,
                'subtotal' => $subtotal,
            ];
        }

        if (!isset($params['number']) || empty($params['number'])) {
            $params['number'] = AutoNumberHelper::initGenerateNumber('SLS', date('Y-m-d'));
        }

        if (isset($params['id']) && $params['id']) {
            $sale = self::find($params['id']);
            if (!$sale) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sale tidak ditemukan.'
                ], 404);
            }

            $oldDetails = SalesDetails::where('sale_id', $sale->id)->get();
            foreach ($oldDetails as $detail) {
                Inventories::where('id', $detail->inventory_id)->increment('stock', $detail->quantity);
            }
            SalesDetails::where('sale_id', $sale->id)->delete();

            $sale->update([
                'date' => $params['date'],
                'total_amount' => $total_amount,
            ]);

            foreach ($items_data as $data) {
                SalesDetails::create([
                    'sale_id'      => $sale->id,
                    'inventory_id' => $data['inventory_id'],
                    'quantity'     => $data['quantity'],
                    'price'        => $data['price'],
                    'subtotal'     => $data['subtotal'],
                ]);

                Inventories::where('id', $data['inventory_id'])->decrement('stock', $data['quantity']);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Succesfully Updated Data',
                'data' => self::getById($sale->id)->original
            ]);
        }

        $save = self::create([
            'number' => $params['number'],
            'date' => $params['date'],
            'user_id' => auth()->guard('sanctum')->id() ?? ($params['user_id'] ?? 0),
            'total_amount' => $total_amount,
        ]);

        foreach ($items_data as $data) {
            SalesDetails::create([
                'sale_id'      => $save->id,
                'inventory_id' => $data['inventory_id'],
                'quantity'     => $data['quantity'],
                'price'        => $data['price'],
                'subtotal'     => $data['subtotal'],
            ]);

            Inventories::where('id', $data['inventory_id'])->decrement('stock', $data['quantity']);
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Added Data',
            'data' => self::getById($save->id)->original
        ]);
    }

    public static function deleteById($id, $params, $request)
    {
        // $old = self::getById($id)->original;

        DB::beginTransaction();
        
        $details = SalesDetails::where('sale_id', $id)->get();
        foreach ($details as $detail) {
            Inventories::where('id', $detail->inventory_id)->increment('stock', $detail->quantity);
        }

        SalesDetails::where('sale_id', $id)->delete();
        self::where('id', $id)->delete();
        
        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Deleted Data'
        ]);
    }

    public static function approveById($id, $params, $request)
    {
        // $data = self::getById($id)->original;

        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Approved Data',
            'data' => null
        ]);
    }
}
