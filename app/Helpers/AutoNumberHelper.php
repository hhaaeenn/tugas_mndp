<?php
namespace App\Helpers;

use App\Models\AccountingJournals;
use App\Models\Billings;
use App\Models\Branches;
use App\Models\CashIns;
use App\Models\CashOuts;
use App\Models\Deposits;
use App\Models\CashTransfers;
use App\Models\Contacts;
use App\Models\Currencies;
use App\Models\Employees;
use App\Models\LeaveRequests;
use App\Models\Overtimes;
use App\Models\PayablePayments;
use App\Models\Payrolls;
use App\Models\PermitRequests;
use App\Models\ProductCategories;
use App\Models\Products;
use App\Models\PurchaseRequests;
use App\Models\ReceivablePayments;
use App\Models\PurchaseOrders;
use App\Models\PurchaseInvoices;
use App\Models\PurchaseReceipts;
use App\Models\SalesDeliveries;
use App\Models\SalesInvoices;
use App\Models\SalesOrders;
use App\Models\StockAdjustments;
use App\Models\StockOpnames;
use App\Models\Warehouses;
use App\Models\WarehouseTransfers;
use App\Models\Contracts;
use App\Models\Properties;
use App\Models\Rooms;
use App\Models\Services;
use App\Models\Purchases;
use App\Models\Sales;
use App\Models\Inventories;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AutoNumberHelper
{
    public static function initGenerateNumber($prefix, $date = '')
    {
        $data = [];

        if ($prefix == null || $prefix == '') {
            return response()->json(['status' => 'error', 'data' => '', 'message' => 'Prefix should exist!']);
        } else {
            switch ($prefix) {
                case "CI":
                    $data = ['class' => CashIns::class , 'field' => 'number', 'prefix' => $prefix];
                    break;
                case "CO":
                    $data = ['class' => CashOuts::class , 'field' => 'number', 'prefix' => $prefix];
                    break;
                case "BILL":
                    $data = ['class' => Billings::class , 'field' => 'number', 'prefix' => $prefix];
                    break;
                case "PO":
                    $data = ['class' => Purchases::class , 'field' => 'number', 'prefix' => $prefix];
                    break;
                case "INV":
                    $data = ['class' => Inventories::class , 'field' => 'code', 'prefix' => $prefix];
                    break;
                case "SLS":
                    $data = ['class' => Sales::class , 'field' => 'number', 'prefix' => $prefix];
                    break;
                default:
                    echo "Your favorite color is neither red, blue, nor green!";
            }
        }

        return self::generateNumber($data, $date);
    }

    /**
     * Get next code from a name
     *
     * @param string $prefix
     * @param string $name
     */
    public static function getNextCode($prefix, $name)
    {
        $data = [];

        switch ($prefix) {
            case "C":
                $data = ['class' => Contacts::class , 'field' => 'code', 'prefix' => InitialHelper::generate($name)];
                break;
            case "CR":
                $data = ['class' => Currencies::class , 'field' => 'code', 'prefix' => InitialHelper::generate($name)];
                break;
            case "CNT": // CASE KHUSUS UNTUK CONTRACTS
                $data = ['class' => Contracts::class , 'field' => 'code', 'prefix' => 'CNT'];
                break;
            case "PROP":
                $data = ['class' => Properties::class , 'field' => 'code', 'prefix' => InitialHelper::generate($name)];
                break;
            case "ROOM":
                $data = ['class' => Rooms::class , 'field' => 'code', 'prefix' => InitialHelper::generate($name)];
                break;
            case "SVC":
                $data = ['class' => Services::class , 'field' => 'code', 'prefix' => InitialHelper::generate($name)];
                break;
            default:
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid or unregistered code prefix provided. Cannot process request.',
                ], 400);
        }

        return response()->json(['code' => self::generateCode($data)]);
    }

    private static function generateNumber($params, $date)
    {
        $now = Carbon::now();
        $prefixSize = (strlen($params['prefix'])) + 10;

        $month_param = $now->month;
        $year_param = $now->year;

        if ($date != '') {
            $expl_date = explode('-', $date);
            if (count($expl_date) > 1) {
                $month_param = $expl_date[1];
                $year_param = $expl_date[0];
            }
        }

        $prefix = $params['prefix'];
        $prefix .= $year_param . sprintf('%02d', $month_param);

        $data = $params['class']::whereRaw('LENGTH(' . $params['field'] . ') = ?', $prefixSize)
            ->where($params['field'], 'ilike', $prefix . '%')->orderBy('id', 'DESC')
            ->first();

        if ($data == null) {
            $prefix .= sprintf('%04d', 1);
        } else {
            $repeat = true;
            $last = substr($data[$params['field']], -4);
            $last = ++$last;

            $new = sprintf('%04d', $last);
            while ($repeat)
            {
                $data = $params['class']::where($params['field'], $prefix . $new)->first();

                if ($data == null) {
                    $repeat = false;
                    $prefix .= sprintf('%04d', $new);
                } else {
                    $new = sprintf('%04d', ++$new);
                }
            }
        }
        return $prefix;
    }

    public static function generateCode($params)
    {
        $code = '';
        $field = $params['field'];
        $prefix = $params['prefix'];
        // TODO: Dynamically change code format based on general settings
        $width = 4;
        $padding = '0';
        $separator = '-';
        // $codeLength = (strlen($prefix)) + $width;

        $data = $params['class']::select($field)
            ->where($field, 'ilike', $prefix . $separator . '%')
            // ->whereRaw('LENGTH(' . $field . ') = ?', $codeLength) // Generate unique code where the string length is a significant part of its value (e.g., "DTC0001" != "DTC00001")
            ->orderBy($field, 'DESC')
            ->first();

        $format = '%'.$padding.$width.'d';
        if ($data == null) {
            $code = $prefix . $separator . sprintf($format, 1);
        } else {
            $last = substr($data[$field], -$width);
            $code = $prefix . $separator . sprintf($format, $last+1);
        }
        return $code;
    }
}