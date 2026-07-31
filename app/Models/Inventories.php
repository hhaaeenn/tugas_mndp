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
 * @property string code
 * @property string name
 * @property int    stock
 * @property string price
 * @property int    created_at
 * @property int    updated_at
 * @property int    deleted_at
 */
class Inventories extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inventories';

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
        'code',
        'name',
        'stock',
        'price',
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
        'code' => 'string', 'name' => 'string', 'stock' => 'int', 'price' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'deleted_at' => 'datetime'
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
                'code' => ['column' => $model->table.'.code', 'alias' => 'code', 'type' => 'string'],
                'name' => ['column' => $model->table.'.name', 'alias' => 'name', 'type' => 'string'],
                'stock' => ['column' => $model->table.'.stock', 'alias' => 'stock', 'type' => 'int'],
                'price' => ['column' => $model->table.'.price', 'alias' => 'price', 'type' => 'string'],
                'deleted_at' => ['column' => $model->table.'.deleted_at', 'alias' => 'deleted_at', 'type' => 'date'],
                'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'date'],
                'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'date'],
            ],
            'join' => [
                
            ],
            'where' => [

            ]
        ];
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = [])
    {
        if (is_string($filter)) {
            $decoded_filter = json_decode($filter, true);
            $filter = json_last_error() === JSON_ERROR_NONE ? $decoded_filter : [];
        } elseif (is_object($filter)) {
            $filter = json_decode(json_encode($filter), true);
        }

        $schema = self::mapSchema();
        $totalData = self::count();

        $qry = ModelHelper::select($schema['field'], null, __CLASS__);
        ModelHelper::join($schema['join'], null, $qry);
        
        //FILTER
        
        // Apply custom filters
        if (!empty($filter['search'])) {
            $qry->where(function($q) use ($filter) {
                $q->where('code', 'ILIKE', '%'.$filter['search'].'%')
                  ->orWhere('name', 'ILIKE', '%'.$filter['search'].'%');
            });
        }
        
        if (!empty($filter['stock'])) {
            switch ($filter['stock']) {
                case 'available':
                    $qry->where('stock', '>', 0);
                    break;
                case 'empty':
                    $qry->where('stock', '=', 0);
                    break;
                case 'low':
                    $qry->where('stock', '<', 10)->where('stock', '>', 0);
                    break;
            }
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
            'name' => 'Nama Wajib Diisi',
            'price' => 'Harga Wajib Diisi',
            'stock' => 'Stok Wajib Diisi',
        ];

        $validator = Validator::make($params, [
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required',
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
                'name' => $params['name'],
                'price' => $params['price'],
                'stock' => $params['stock'],
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Succesfully Updated Data',
                'data' => self::getById($params['id'])->original
            ]);
        }

        if (!isset($params['code']) || empty($params['code'])) {
            $params['code'] = AutoNumberHelper::initGenerateNumber('INV', date('Y-m-d'));
        }

        $save = self::create([
            'code' => $params['code'],
            'name' => $params['name'],
            'price' => $params['price'],
            'stock' => $params['stock'],
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

    public static function importFromCsv($rows)
    {
        DB::beginTransaction();

        $errors = [];
        $inserted = 0;

        foreach ($rows as $index => $row) {
            $line = $index + 1;

            if ($index === 0 && isset($row[0]) && mb_strtolower(trim($row[0])) === 'name') {
                continue;
            }

            $name = isset($row[0]) ? trim($row[0]) : '';
            $price = isset($row[1]) ? trim($row[1]) : '';
            $stock = isset($row[2]) ? trim($row[2]) : '';

            if ($name === '' || $price === '' || $stock === '') {
                $errors[] = 'Baris '.$line.': Nama, harga dan stok wajib diisi.';
                continue;
            }

            if (!is_numeric($price) || !is_numeric($stock)) {
                $errors[] = 'Baris '.$line.': Harga dan stok harus berupa angka.';
                continue;
            }

            self::create([
                'code' => AutoNumberHelper::initGenerateNumber('INV', date('Y-m-d')),
                'name' => $name,
                'price' => $price,
                'stock' => (int) $stock,
            ]);
            $inserted++;
        }

        if (!empty($errors)) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Import gagal. Terdapat '.count($errors).' baris tidak valid.',
                'data' => $errors,
            ], 400);
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Imported Data',
            'data' => 'Sukses import '.$inserted.' data inventori.',
        ]);
    }

    public static function adjustStock($id, $params, $request)
    {
        $validator = Validator::make($params, [
            'amount' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 400);
        }

        $inventory = self::where('id', $id)->first();
        if (!$inventory) {
            return response()->json(['success' => false, 'status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
        }

        $new_stock = $inventory->stock + $params['amount'];
        if ($new_stock < 0) {
            return response()->json(['success' => false, 'status' => 'error', 'message' => 'Stok tidak boleh kurang dari 0.'], 400);
        }

        self::where('id', $id)->update([
            'stock' => $new_stock,
        ]);

        return response()->json([
            'success' => true,
            'status' => 'success',
            'message' => 'Stok berhasil disesuaikan.'
        ]);
    }
}
