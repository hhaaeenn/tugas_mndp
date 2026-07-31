<?php

namespace App\Models;

use DB;
use Illuminate\Support\Str;
use App\Helpers\AutoNumberHelper;
use App\Helpers\ModelHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    sale_id
 * @property int    inventory_id
 * @property int    quantity
 * @property string price
 * @property string subtotal
 * @property int    created_at
 * @property int    updated_at
 * @property int    deleted_at
 */
class SalesDetails extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sales_details';

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
        'sale_id',
        'inventory_id',
        'quantity',
        'price',
        'subtotal',
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
        'sale_id' => 'int', 'inventory_id' => 'int', 'quantity' => 'int', 'price' => 'string', 'subtotal' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'deleted_at' => 'datetime'
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
                'sale_id' => ['column' => $model->table.'.sale_id', 'alias' => 'sale_id', 'type' => 'int'],
                'inventory_id' => ['column' => $model->table.'.inventory_id', 'alias' => 'inventory_id', 'type' => 'int'],
                'inventory_name' => ['column' => 'inventories.name', 'alias' => 'inventory_name', 'type' => 'string'],
                'quantity' => ['column' => $model->table.'.quantity', 'alias' => 'quantity', 'type' => 'int'],
                'price' => ['column' => $model->table.'.price', 'alias' => 'price', 'type' => 'string'],
                'subtotal' => ['column' => $model->table.'.subtotal', 'alias' => 'subtotal', 'type' => 'string'],
                'deleted_at' => ['column' => $model->table.'.deleted_at', 'alias' => 'deleted_at', 'type' => 'date'],
                'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'date'],
                'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'date'],
            ],
            'join' => [
                ['table' => 'inventories', 'type' => 'left', 'on' => ['inventories.id', '=', $model->table . '.inventory_id']],
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
        return response()->json($db->first());
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
            'sale_id' => 'Sale ID Wajib Diisi',
            'inventory_id' => 'Barang Wajib Diisi',
            'quantity' => 'Quantity Wajib Diisi',
            'price' => 'Harga Wajib Diisi',
            'subtotal' => 'Subtotal Wajib Diisi',
        ];

        $validator = Validator::make($params, [
            'sale_id' => 'required',
            'inventory_id' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'subtotal' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation',
                'message' => $validator->errors()->first(),
                'data' => $validator->errors()->all()
            ], 400);
        }

        if (isset($params['id']) && $params['id']) {
            self::where('id', $params['id'])->update([
                'sale_id' => $params['sale_id'],
                'inventory_id' => $params['inventory_id'],
                'quantity' => $params['quantity'],
                'price' => $params['price'],
                'subtotal' => $params['subtotal'],
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Succesfully Updated Data',
                'data' => self::getById($params['id'])->original
            ]);
        }

        $save = self::create([
            'sale_id' => $params['sale_id'],
            'inventory_id' => $params['inventory_id'],
            'quantity' => $params['quantity'],
            'price' => $params['price'],
            'subtotal' => $params['subtotal'],
        ]);

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

        self::where('id', $id)->delete();
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
