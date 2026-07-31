Pada project saya harus ada standarisasi sebagai berikut

1. Jika php maka menggunakan variabel snake_case kecuali totalData dan totalFiltered, function tetap camelCase
2. Jika javascript maka menggunakan variabel dan function camelCase
3. jangan pernah menggunakan relasi belong to
4. jangan pernah menggunakan try catch
5. standarisasi migrate adalah tidak pernah menggunakan on cascade, jika relasi langsung panggil id nya dan tipe data nya integer, contoh:
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEmployeeCompetencyDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_competency_details', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_competency_id')->nullable();
            $table->string('department_code')->nullable();
            $table->integer('employee_id')->nullable();
            $table->string('employee_code')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('attendance_score')->nullable();
            $table->string('discipline_score')->nullable();
            $table->string('attitude_score')->nullable();
            $table->string('neatness_score')->nullable();
            $table->string('leadership_score')->nullable();
            $table->string('evaluation_score')->nullable();
            $table->text('attendance_description')->nullable();
            $table->text('discipline_description')->nullable();
            $table->text('attitude_description')->nullable();
            $table->text('neatness_description')->nullable();
            $table->text('leadership_description')->nullable();
            $table->text('evaluation_description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_competency_details');
    }
}
6. contoh standarisasi model
<?php

namespace App\Models;

use App\Helpers\AutoNumberHelper;
use App\Helpers\DateHelper;
use App\Helpers\GlobalHelper;
use App\Helpers\ModelHelper;
use App\Models\Departments;
use App\Models\Employees;
use App\Models\Holidays;
use App\Models\LeaveRequestDates;
use App\Models\LeaveRequestDetails;
use App\Models\LeaveRequests;
use App\Models\OvertimeDetails;
use App\Models\Overtimes;
use App\Models\PayrollDetails;
use App\Models\Payrolls;
use App\Models\PermitDetails;
use App\Models\VAttendances;
use App\Models\WorkScheduleDetails;
use App\Models\WorkScheduleUsers;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * @property string employee_code
 * @property string employee_name
 * @property string type
 * @property string department_code
 * @property Date   date
 * @property int    deleted_at
 * @property int    created_at
 * @property int    updated_at
 */
class Attendances extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attendances';

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
        'attendance_reference_id',
        'employee_code',
		'employee_name',
		'date',
		'scheduled_clock_in',
		'scheduled_clock_out',
		'clock_in',
		'clock_out',
		'late_clock_in',
		'early_clock_out',
		'type',
		'department_code',
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
                'attendance_reference_id' => ['column' => $model->table.'.attendance_reference_id', 'alias' => 'attendance_reference_id', 'type' => 'string'],
                'employee_id' => ['column' => 'employees.id', 'alias' => 'employee_id', 'type' => 'int'],
				'employee_code' => ['column' => $model->table.'.employee_code', 'alias' => 'employee_code', 'type' => 'string'],
				'employee_name' => ['column' => $model->table.'.employee_name', 'alias' => 'employee_name', 'type' => 'string'],
				'date' => ['column' => $model->table.'.date', 'alias' => 'date', 'type' => 'date'],
                'scheduled_clock_in' => ['column' => $model->table.'.scheduled_clock_in', 'alias' => 'scheduled_clock_in', 'type' => 'int'],
                'scheduled_clock_out' => ['column' => $model->table.'.scheduled_clock_out', 'alias' => 'scheduled_clock_out', 'type' => 'int'],
                'clock_in' => ['column' => $model->table.'.clock_in', 'alias' => 'clock_in', 'type' => 'int'],
                'clock_out' => ['column' => $model->table.'.clock_out', 'alias' => 'clock_out', 'type' => 'int'],
                'late_clock_in' => ['column' => $model->table.'.late_clock_in', 'alias' => 'late_clock_in', 'type' => 'int'],
                'early_clock_out' => ['column' => $model->table.'.early_clock_out', 'alias' => 'early_clock_out', 'type' => 'int'],
				'type' => ['column' => $model->table.'.type', 'alias' => 'type', 'type' => 'string'],
				'department_code' => ['column' => $model->table.'.department_code', 'alias' => 'department_code', 'type' => 'string'],
				'deleted_at' => ['column' => $model->table.'.deleted_at', 'alias' => 'deleted_at', 'type' => 'date'],
				'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'date'],
				'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'date'],
            ],
            'join' => [
                ['table' => 'employees', 'type' => 'left', 'on' => ['employees.code', '=', $model->table . '.employee_code']],
            ],
            'where' => [

            ]
        ];
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = [])
    {
        $schema = self::mapSchema();

        $totalData = self::count();

        $is_no_clock_only = false;

        if (isset($filter['is_no_clock_only']) && $filter['is_no_clock_only']) {
            $is_no_clock_only = $filter['is_no_clock_only'] == 'true' ? true : false;
            unset($filter['is_no_clock_only']);
        }

        $qry = ModelHelper::select($schema['field'], null, __CLASS__);
        ModelHelper::join($schema['join'], null, $qry);
        
        //FILTER
        if (isset($filter['start_date']) && isset($filter['end_date'])) {
            $qry->where('date', '>=', $filter['start_date']);
            $qry->where('date', '<=', $filter['end_date']);
        }
        if ($is_no_clock_only) {
            // (data['type'] == '' || data['type'] == 'True') && !data['clock_in'] || !data['clock_out']
            $qry->whereRaw("(type = '' OR type = 'True') AND (clock_in IS NULL OR clock_out IS NULL)");
        }

        $totalFiltered = $qry->count();

        if (empty($search)) {
            
            if ($length > 0) {
                $qry->skip($start)
                    ->take($length);
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
                $qry->skip($start)
                    ->take($length);
            }

            foreach ($order as $row) {
                $qry->orderBy($row['column'], $row['dir']);
            }
        }

        $qryResult = $qry->get();

        foreach ($qryResult as $item) {
            $schClockIn = Carbon::parse($item->scheduled_clock_in);
            $schClockOut = Carbon::parse($item->scheduled_clock_out);
            $secondFormatTotalHour = $schClockIn->diffInSeconds($schClockOut);
            $hoursWithDecimalTotal = $secondFormatTotalHour / 3600;
            $item->total_jam = number_format($hoursWithDecimalTotal - 1, 1);
            $overtimeData = OvertimeDetails::select('start_time', 'end_time')
                ->where('overtime_date', $item->date)
                ->where('employee_id', $item->employee_id)
                ->first();
            if (!empty($item->late_clock_in)) {
                $totalMinutesLate = GlobalHelper::convert_to_minutes($item->late_clock_in);
                $item->permit = ($totalMinutesLate > 15) ? number_format(1, 1) : number_format(0, 1);
                $item->meal = number_format(1, 1);
            } else if(!empty($item->early_clock_out)) {
                $hour_permit_to_minutes = GlobalHelper::convert_to_minutes($item->early_clock_out);
                $calculate_permit_in_minutes = GlobalHelper::calculatePermitRules($hour_permit_to_minutes);
                if ($calculate_permit_in_minutes <= 240) {
                    $item->meal = number_format(1, 1);
                }
                $item->permit = number_format(0, 1);
            }else{
                $item->permit = number_format(0, 1);
                $item->meal = number_format(1, 1);
            }
            if ($overtimeData) {
                $startTime = Carbon::parse($overtimeData->start_time);
                $endTime = Carbon::parse($overtimeData->end_time);
        
                $seconds = $endTime->diffInSeconds($startTime);
                $hoursWithDecimal = $seconds / 3600;
                $item->overtime = number_format($hoursWithDecimal, 1);
            } else {
                $item->overtime = number_format(0, 1);
            }
        }
        
        return [
            'data' => $qryResult,
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

        $is_payroll = false;
        $is_detail = false;
        $cutoff_overtime_start_date = '';
        $cutoff_overtime_end_date = '';
        
        unset($params['all']);

        if (isset($params['or']) && $params['or']) {
            $or = $params['or'];
            unset($params['or']);
        }

        if (isset($params['is_payroll']) && $params['is_payroll'] == 'true') {
            $is_payroll = true;
            unset($params['is_payroll']);
        }

        if (isset($params['is_detail']) && $params['is_detail'] == 'true') {
            $is_detail = true;
            unset($params['is_detail']);
        }

        if (isset($params['cutoff_overtime_start_date']) && $params['cutoff_overtime_start_date']) {
            $cutoff_overtime_start_date = $params['cutoff_overtime_start_date'];
            unset($params['cutoff_overtime_start_date']);
        }

        if (isset($params['cutoff_overtime_end_date']) && $params['cutoff_overtime_end_date']) {
            $cutoff_overtime_end_date = $params['cutoff_overtime_end_date'];
            unset($params['cutoff_overtime_end_date']);
        }

        $db = ModelHelper::select($schema['field'], $request, __CLASS__);
        ModelHelper::join($schema['join'], $request, $db);

        if ($params) {
            ModelHelper::dynamicFilterAnd($params, $request, $db, __CLASS__);
        }

        if ($or) {
            ModelHelper::dynamicFilterOr($or, $request, $db, __CLASS__);
        }

        $data = [];

        $results = ModelHelper::generateAllResults($schema, $params, $request, $db, $append);

        if ($results) {
            $data = $results->toArray();

            if ($is_detail) {
                $attendance_by_date = [];
                $overtime_by_date = [];
                $date_ranges = DateHelper::getDateTimeRanges($params['date']['$gte'], $params['date']['$lte'], 'day', 'Y-m-d');
                $employee_code = '';
                $employee_name = '';
                $employee_id = '';
                $department_code = '';

                foreach ($data as $key => $row) {
                    if ($key < 1) {
                        $employee_code = $row['employee_code'];
                        $employee_name = $row['employee_name'];
                        $employee_id = $row['employee_id'];
                        $department_code = $row['department_code'];
                    }
                    $attendance_by_date[$row['date']] = $row;
                }

                $overtimes = OvertimeDetails::select([
                    'id',
                    'overtime_date',
                    'start_time',
                    'end_time',
                    'rest_time',
                    DB::raw('extract(epoch from end_time - start_time)/3600 AS total_hour')
                ])
                ->where('employee_code', $employee_code)
                ->where('overtime_date', '>=', $cutoff_overtime_start_date)
                ->where('overtime_date', '<=', $cutoff_overtime_end_date)
                ->get();

                $work_schedule_user = WorkScheduleUsers::select('work_schedule_id', 'department_code', 'employee_code', 'efective_date')
                    ->where('employee_code', $employee_code)
                    ->first();

                $work_schedule_details = WorkScheduleDetails::select([
                    'work_schedule_id', 
                    'patern_number', 
                    'work_shifts.check_in', 
                    'work_shifts.check_out', 
                    'work_shifts.code'
                ])
                ->where('work_schedule_id', $work_schedule_user['work_schedule_id'])
                ->leftJoin('work_shifts', 'work_shifts.id', '=', 'work_schedule_details.work_shift_id')
                ->get();

                $work_schedule_detail_by_work_schedule_id = [];

                foreach ($work_schedule_details as $work_schedule_detail) {
                    $work_schedule_detail_by_work_schedule_id[$work_schedule_detail['work_schedule_id']][] = $work_schedule_detail->toArray();
                }

                $employee_work_schedules = $work_schedule_detail_by_work_schedule_id[$work_schedule_user['work_schedule_id']];

                $schedules = [];

                foreach ($employee_work_schedules as $employee_work_schedule) {
                        $schedules[$employee_work_schedule['patern_number']] = $employee_work_schedule['check_in'] != '00:00:00' && $employee_work_schedule['check_out'] != '00:00:00' ? 'work' : 'off';
                }

                $public_holidays = [];

                $holiday_data = Holidays::select([
                    'id', 
                    'name', 
                    'date', 
                    'is_national_holiday'
                ])
                ->whereYear('date', '>=', explode('-', $params['date']['$gte'])[0])
                ->get();

                foreach ($holiday_data as $holiday_row) {
                    $public_holidays[$holiday_row['date']] = $holiday_row->toArray();
                }

                foreach ($overtimes as $overtime) {
                    $ov = GlobalHelper::calculateOvertime($overtime, $schedules, $public_holidays, $work_schedule_user['efective_date']);

                    $overtime['overtime_1'] = $ov['addition_count_overtime_1_in_hours'];
                    $overtime['overtime_2'] = $ov['addition_count_overtime_2_in_hours'];
                    $overtime['overtime_3'] = $ov['addition_count_overtime_weekend_in_hours'];
                    $overtime['overtime_4'] = $ov['addition_count_overtime_ph_in_hours'];
                    $overtime_by_date[$overtime['overtime_date']] = $overtime->toArray();
                }

                $data_temps = [];

                foreach ($date_ranges as $date) {
                    if (!isset($attendance_by_date[$date])) {
                        $data_temps[] = [
                            'id' => 0,
                            'attendance_reference_id' => 0,
                            'employee_id' => $employee_id,
                            'employee_code' => $employee_code,
                            'employee_name' => $employee_name,
                            'date' => $date,
                            'scheduled_clock_in' => null,
                            'scheduled_clock_out' => null,
                            'clock_in' => null,
                            'clock_out' => null,
                            'late_clock_in' => null,
                            'early_clock_out' => null,
                            'type' => 'LBR',
                            'department_code' => $department_code,
                            'deleted_at' => null,
                            'created_at' => null,
                            'updated_at' => null,
                            'overtime_1' => 0,
                            'overtime_2' => 0,
                            'overtime_3' => isset($overtime_by_date[$date]) ? floatval($overtime_by_date[$date]['overtime_3']) : 0,
                            'overtime_4' => isset($overtime_by_date[$date]) ? floatval($overtime_by_date[$date]['overtime_4']) : 0,
                        ];
                    } else {
                        $attendance_by_date[$date]['overtime_1'] = isset($overtime_by_date[$date]) ? floatval($overtime_by_date[$date]['overtime_1']) : 0;
                        $attendance_by_date[$date]['overtime_2'] = isset($overtime_by_date[$date]) ? floatval($overtime_by_date[$date]['overtime_2']) : 0;
                        $attendance_by_date[$date]['overtime_3'] = isset($overtime_by_date[$date]) ? floatval($overtime_by_date[$date]['overtime_3']) : 0;
                        $attendance_by_date[$date]['overtime_4'] = isset($overtime_by_date[$date]) ? floatval($overtime_by_date[$date]['overtime_4']) : 0;
                        $data_temps[] = $attendance_by_date[$date];
                    }
                }

                $data = $data_temps;
            }
        }
        // foreach ($data)
        return response()->json($data);
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();

        $filename = null;

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        $holiday_data = [];
        $holidays = Holidays::where('is_national_holiday', 1)->get();

        foreach ($holidays as $holiday) {
            $holiday_data[$holiday['date']] = $holiday['name'];
        }
        
        $not_attend_statuses = ['SAKIT', 'CTL', 'DNS', 'CUTI', 'ALPA', 'IZIN'];

        $schedules = [];

        $effective_date = '';

        $work_schedule_users = WorkScheduleDetails::select('work_schedule_users.efective_date', 'work_shifts.check_in', 'work_shifts.check_out', 'work_schedule_details.patern_number')
            ->where('work_schedule_users.employee_code', $params['employee_code'])
            ->leftJoin('work_schedule_users', 'work_schedule_users.work_schedule_id', '=', 'work_schedule_details.work_schedule_id')
            ->leftJoin('work_shifts', 'work_schedule_details.work_shift_id', '=', 'work_shifts.id')
            ->get();

        foreach ($work_schedule_users as $work_schedule_user) {
            if (!$effective_date) {
                $effective_date = $work_schedule_user['efective_date'];
            }
            $schedules[$work_schedule_user['patern_number']] = $work_schedule_user['check_in'] != '00:00:00' && $work_schedule_user['check_out'] != '00:00:00' ? 'work' : 'off';
        }

        $is_scheduled_work = GlobalHelper::isScheduledWork($effective_date, $schedules, $params['date']);

        if (isset($params['id']) && $params['id']) {
            $old_overtime_hours = 0;
            $overtime_hours = 0;

            // $old = self::getById($params['id'])->original;
            $old = VAttendances::getById($params['id'])->original;

            $_scheduled_clock_in = isset($params['scheduled_clock_in']) ? $params['scheduled_clock_in'] : $old['scheduled_clock_in'];
            $_scheduled_clock_out = isset($params['scheduled_clock_out']) ? $params['scheduled_clock_out'] : $old['scheduled_clock_out'];
            $_clock_in = isset($params['clock_in']) ? $params['clock_in'] : $old['clock_in'];
            $_clock_out = isset($params['clock_out']) ? $params['clock_out'] : $old['clock_out'];
            // $old_late_clock_in = $old['late_clock_in'];
            // $old_early_clock_out = $old['early_clock_out'];
            $_late_clock_in = '00:00:00';
            $_early_clock_out = '00:00:00';

            $_scheduled_clock_in = GlobalHelper::formatTime($_scheduled_clock_in);
            $_scheduled_clock_out = GlobalHelper::formatTime($_scheduled_clock_out);
            $_clock_in = GlobalHelper::formatTime($_clock_in);
            $_clock_out = GlobalHelper::formatTime($_clock_out);

            if ($_scheduled_clock_in == '14:00:00') {
                $rest_start_time = '18:00:00';
                $rest_end_time = '18:30:00';
                $total_rest_time = '00:30';
            } else if ($_scheduled_clock_in == '05:30:00') {
                $rest_start_time = '09:30:00';
                $rest_end_time = '10:00:00';
                $total_rest_time = '00:30';
            } else {
                if ($_scheduled_clock_in == '07:30:00' && $_scheduled_clock_out == '16:00:00') {
                    $rest_start_time = '12:00:00';
                    $rest_end_time = '12:30:00';
                    $total_rest_time = '00:30';
                } else if ($_scheduled_clock_in == '07:00:00' && $_scheduled_clock_out == '15:30:00') {
                    $rest_start_time = '12:00:00';
                    $rest_end_time = '12:30:00';
                    $total_rest_time = '00:30';
                } else {
                    $rest_start_time = '11:30:00';
                    $rest_end_time = '12:30:00';
                    $total_rest_time = '01:00';
                }
            }

            if ($_clock_in) {
                $_late_clock_in = GlobalHelper::calculateTimeDiff($_scheduled_clock_in, $_clock_in, false);
                if ($_clock_in > $rest_start_time) {
                    $_late_clock_in = DateHelper::addTime($_late_clock_in, $total_rest_time, 'subtract');
                }
            }
            if ($_clock_out) {
                $_early_clock_out = GlobalHelper::calculateTimeDiff($_scheduled_clock_out, $_clock_out, true);
                if ($_clock_out <= $rest_start_time) {
                    $_early_clock_out = DateHelper::addTime($_early_clock_out, $total_rest_time, 'subtract');
                }
            }

            $params['late_clock_in'] = $_late_clock_in == '00:00:00' ? null : $_late_clock_in;
            $params['early_clock_out'] = $_early_clock_out == '00:00:00' ? null : $_early_clock_out;

            $day_integer = date('N', strtotime($params['date']));

            //CALCULATE IF OLD HAS OVERTIME
            $ot = OvertimeDetails::where('overtime_date', $params['date'])->where('employee_code', $params['employee_code'])->get();

            if (!in_array($params['type'], $not_attend_statuses)) {
                //CALCULATE IF NEW HAS OVERTIME
                if (!$is_scheduled_work && ($day_integer == 6 || $day_integer == 7 || (isset($holiday_data[trim($params['date'])]) && $holiday_data[trim($params['date'])]))) {
                    $overtime_start_time = Carbon::parse($_clock_in);
                } else {
                    if ($_scheduled_clock_out == '16:30') { // Peraturan Baru Istirahat lembur setengah jam dari 16.30, sehingga mulai OT nya 17:00
                        $_scheduled_clock_out = '17:00';
                    }
                    $overtime_start_time = Carbon::parse($_scheduled_clock_out);
                }

                $overtime_end_time = Carbon::parse($_clock_out);
                $calculate_diff = GlobalHelper::calculateTimeDiff($overtime_start_time, $overtime_end_time, false, true);
                if ($calculate_diff != '00:00:00') {
                    $seconds = $overtime_end_time->diffInSeconds($overtime_start_time);
                    $hoursWithDecimal = $seconds / 3600;
                    $overtime_hours = GlobalHelper::customRoundOvertime($hoursWithDecimal);

                    if (!$is_scheduled_work && ($day_integer == 6 || $day_integer == 7 || (isset($holiday_data[trim($params['date'])]) && $holiday_data[trim($params['date'])]))) {
                        if ($_clock_out >= $rest_end_time) {
                            $overtime_hours = $overtime_hours - GlobalHelper::convertTimeToDecimal($total_rest_time);
                        }
                    } else {
                        if ($overtime_hours >= 3.5 && $_clock_out >= $rest_start_time) {
                            // Peraturan Baru Istirahat lembur setengah jam dari 16.30, sehingga mulai OT nya 17:00, jadi ini dihapus karena sdh tdk ada istirahat lagi di tengah2 lembur
                            // $overtime_hours = $overtime_hours - 0.5;
                        }
                    }
                }
            }

            if (count($ot) > 0 && $overtime_hours < 0.0000000001) {
                foreach ($ot as $row) {
                    $row->forceDelete();
                }
            } else if ($day_integer > 5) {
                $params['late_clock_in'] = null;
                $params['early_clock_out'] = null;

                $overtime = OvertimeDetails::where('overtime_date', $params['date'])->where('employee_code', $params['employee_code'])->first();

                if ($overtime) {
                    $overtime->start_time = $_clock_in;
                    $overtime->end_time = $_clock_out;
                    $overtime->scheduled_clock_in = $_scheduled_clock_in;
                    $overtime->scheduled_clock_out = $_scheduled_clock_out;
                    $overtime->clock_in = $_clock_in;
                    $overtime->clock_out = $_clock_out;
                    $overtime->update();
                } else {
                    $check_overtime = Overtimes::where('date', $params['date'])->first();

                    $employee = Employees::select('id', 'code', 'name', 'department_code')->where('code', $params['employee_code'])->first();

                    if ($check_overtime) {
                        OvertimeDetails::create([
                            'overtime_id' => $check_overtime['id'],
                            'employee_id' => $employee['id'],
                            'overtime_date' => $params['date'],
                            'start_time' => $overtime_start_time->format('H:i:s'),
                            'end_time' => $overtime_end_time->format('H:i:s'),
                            'department_code' => $employee['department_code'],
                            'rest_time' => '00:00',
                            'employee_code' => $employee['code'],
                            'employee_name' => $employee['name'],
                            'scheduled_clock_in' => $_scheduled_clock_in,
                            'scheduled_clock_out' => $_scheduled_clock_out,
                            'clock_in' => $_clock_in,
                            'clock_out' => $_clock_out,
                        ]);
                    } else {
                        $overtime_data = Overtimes::create([
                            'date' => $params['date'],
                            'number' => AutoNumberHelper::initGenerateNumber('OR', $params['date']),
                            'created_by' => config('user.id')
                        ]);

                        OvertimeDetails::create([
                            'overtime_id' => $overtime_data['id'],
                            'employee_id' => $employee['id'],
                            'overtime_date' => $params['date'],
                            'start_time' => $overtime_start_time->format('H:i:s'),
                            'end_time' => $overtime_end_time->format('H:i:s'),
                            'department_code' => $employee['department_code'],
                            'rest_time' => '00:00',
                            'employee_code' => $employee['code'],
                            'employee_name' => $employee['name'],
                            'scheduled_clock_in' => $_scheduled_clock_in,
                            'scheduled_clock_out' => $_scheduled_clock_out,
                            'clock_in' => $_clock_in,
                            'clock_out' => $_clock_out,
                        ]);
                    }
                }
            } else if ($overtime_hours >= 0.0000000001) {
                $overtime = OvertimeDetails::where('overtime_date', $params['date'])->where('employee_code', $params['employee_code'])->first();

                if ($overtime) {
                    $overtime->start_time = $overtime_start_time->format('H:i:s');
                    $overtime->end_time = $overtime_end_time->format('H:i:s');
                    $overtime->scheduled_clock_in = $_scheduled_clock_in;
                    $overtime->scheduled_clock_out = $_scheduled_clock_out;
                    $overtime->clock_in = $_clock_in;
                    $overtime->clock_out = $_clock_out;
                    $overtime->update();
                } else {
                    $check_overtime = Overtimes::where('date', $params['date'])->first();

                    $employee = Employees::select('id', 'code', 'name', 'department_code')->where('code', $params['employee_code'])->first();

                    if ($check_overtime) {
                        OvertimeDetails::create([
                            'overtime_id' => $check_overtime['id'],
                            'employee_id' => $employee['id'],
                            'overtime_date' => $params['date'],
                            'start_time' => $overtime_start_time->format('H:i:s'),
                            'end_time' => $overtime_end_time->format('H:i:s'),
                            'department_code' => $employee['department_code'],
                            'rest_time' => '00:00',
                            'employee_code' => $employee['code'],
                            'employee_name' => $employee['name'],
                            'scheduled_clock_in' => $_scheduled_clock_in,
                            'scheduled_clock_out' => $_scheduled_clock_out,
                            'clock_in' => $_clock_in,
                            'clock_out' => $_clock_out,
                        ]);
                    } else {
                        $overtime_data = Overtimes::create([
                            'date' => $params['date'],
                            'number' => AutoNumberHelper::initGenerateNumber('OR', $params['date']),
                            'created_by' => config('user.id')
                        ]);

                        OvertimeDetails::create([
                            'overtime_id' => $overtime_data['id'],
                            'employee_id' => $employee['id'],
                            'overtime_date' => $params['date'],
                            'start_time' => $overtime_start_time->format('H:i:s'),
                            'end_time' => $overtime_end_time->format('H:i:s'),
                            'department_code' => $employee['department_code'],
                            'rest_time' => '00:00',
                            'employee_code' => $employee['code'],
                            'employee_name' => $employee['name'],
                            'scheduled_clock_in' => $_scheduled_clock_in,
                            'scheduled_clock_out' => $_scheduled_clock_out,
                            'clock_in' => $_clock_in,
                            'clock_out' => $_clock_out,
                        ]);
                    }
                }
            }

            if ($params['type'] == 'CUTI' && $old['type'] != 'CUTI') {
                $employee = Employees::select('id', 'code', 'name', 'department_code')->where('code', $params['employee_code'])->first();
                
                $check_balance = LeaveRequests::checkLeaveBalance([
                    'start_date' => $params['date'],
                    'end_date' => $params['date'],
                    'employee_code' => $params['employee_code'],
                    'employee_name' => $old['employee_name']
                ]);

                if ($check_balance) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Terdapat error',
                        'data' => $check_balance
                    ], 400);
                }

                $leave_request = LeaveRequests::where('date', $params['date'])->first();
                if (!$leave_request) {
                    $leave_request = LeaveRequests::create([
                        'date' => $params['date'],
                        'number' => AutoNumberHelper::initGenerateNumber('LR', $params['date']),
                        'created_by' => config('user.id'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }

                $leave_request_detail = LeaveRequestDetails::where('leave_request_id', $leave_request['id'])->where('employee_code', $params['employee_code'])->first();

                if (!$leave_request_detail) {
                    $leave_request_detail = LeaveRequestDetails::create([
                        'leave_request_id' => $leave_request['id'],
                        'employee_id' => $employee['id'],
                        'leave_type_id' => 1,
                        'reason' => 'Change Manual From Attendance',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'start_date' => $params['date'],
                        'end_date' => $params['date'],
                        'employee_code' => $params['employee_code'],
                        'employee_name' => $employee['name']
                    ]);

                    LeaveRequestDates::create([
                        'leave_request_id' => $leave_request['id'],
                        'leave_request_detail_id' => $leave_request_detail->id,
                        'employee_code' => $params['employee_code'],
                        'employee_id' => $employee['id'],
                        'date' => $params['date'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                } else {
                    // public static function isDateInRange($selectedDate, $startDate, $endDate) {
                    $is_in_range = DateHelper::isDateInRange($params['date'], $leave_request_detail['start_date'], $leave_request_detail['end_date']);

                    // if (!$is_in_range) {

                    // }

                    $leave_request_date = LeaveRequestDates::where('leave_request_id', $leave_request['id'])->where('employee_code', $params['employee_code'])->where('date', $old['date'])->first();

                    $leave_request_date->date = $params['date'];
                    $leave_request_date->update();
                }
            } else if ($old['type'] == 'CUTI' && $params['type'] != 'CUTI') {
                $leave_request = LeaveRequests::where('date', $params['date'])->first();

                $leave_request_detail = LeaveRequestDetails::where('leave_request_id', $leave_request['id'])->where('employee_code', $params['employee_code'])->first();

                LeaveRequestDates::where('leave_request_id', $leave_request['id'])->where('employee_code', $params['employee_code'])->where('date', $params['date'])->delete();

                if ($leave_request_detail && $leave_request) {
                    $leave_request_dates = LeaveRequestDates::where('leave_request_id', $leave_request['id'])->where('leave_request_detail_id', $leave_request_detail['id'])->get();

                    if (count($leave_request_dates) < 1) {
                        $leave_request_detail->delete();
                    }
                } else if ($leave_request_detail) {
                    $leave_request_detail->delete();
                }

                if ($leave_request) {
                    $leave_request_details = LeaveRequestDetails::where('leave_request_id', $leave_request['id'])->get();

                    if (count($leave_request_details) < 1) {
                        $leave_request->delete();
                    }
                }

                $leave_request_dates = LeaveRequestDates::where('employee_code', $params['employee_code'])->where('date', $params['date'])->get();

                $leave_request_detail_ids = [];

                foreach ($leave_request_dates as $leave_request_date) {
                    $leave_request_detail_ids[] = $leave_request_date['leave_request_detail_id'];
                    $leave_request_date->delete();
                }

                foreach ($leave_request_detail_ids as $leave_request_detail_id) {
                    $check_leave_request_dates = LeaveRequestDates::where('leave_request_detail_id', $leave_request_detail_id)->count();

                    $count_leave_request_details = 0;
                    $leave_request_id = 0;

                    if ($check_leave_request_dates < 1) {
                        $check_leave_request_detail = LeaveRequestDetails::where('id', $leave_request_detail_id)->first();
                        $leave_request_id = $check_leave_request_detail['leave_request_id'];
                        $check_leave_request_detail->delete();

                        $count_leave_request_details = LeaveRequestDetails::where('leave_request_id', $leave_request_id)->count();
                    }

                    if ($count_leave_request_details < 1) {
                        LeaveRequests::where('id', $leave_request_id)->delete();
                    }
                }
            }

            if (isset($params['type']) && in_array($params['type'], $not_attend_statuses) ) {
                if ($params['type'] != 'SAKIT') {
                    $params['clock_in'] = null;
                    $params['clock_out'] = null;
                }
                $params['late_clock_in'] = null;
                $params['early_clock_out'] = null;
            }

            if ($params['type'] == 'HDR') {
                $params['type'] = '';

                if (!$params['clock_in']) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Jam Masuk harap diisi',
                        'data' => null
                    ], 400);
                }

                if (!$params['clock_out']) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Jam Keluar harap diisi',
                        'data' => null
                    ], 400);
                }
            }

            self::where('id', $params['id'])->update($params);

            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Succesfully Updated Data',
                'data' => self::getById($params['id'])->original
            ]);
        }

        $messages = [
            'employee_code' => 'Kode Karyawan Wajib Diisi',
            'date' => 'Tanggal Wajib Diisi',
            'type' => 'Tipe Kehadiran Wajib Diisi',
            'scheduled_clock_in' => 'Jadwal Masuk Wajib Diisi',
            'scheduled_clock_out' => 'Jadwal Keluar Wajib Diisi'
        ];

        $validator = Validator::make($params, [
            'employee_code' => 'required',
            'date' => 'required',
            'type' => 'required',
            'scheduled_clock_in' => 'required',
            'scheduled_clock_out' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation',
                'message' => $validator->errors()->first(),
                'data' => $validator->errors()->all()
            ], 400);
        }

        $employee = Employees::select('id', 'code', 'name', 'department_code')->where('code', $params['employee_code'])->first();

        $overtime_hours = 0;

        $_scheduled_clock_in = $params['scheduled_clock_in'];
        $_scheduled_clock_out = $params['scheduled_clock_out'];
        $_clock_in = $params['clock_in'];
        $_clock_out = $params['clock_out'];
        $_late_clock_in = '00:00:00';
        $_early_clock_out = '00:00:00';

        $_scheduled_clock_in = GlobalHelper::formatTime($_scheduled_clock_in);
        $_scheduled_clock_out = GlobalHelper::formatTime($_scheduled_clock_out);
        $_clock_in = GlobalHelper::formatTime($_clock_in);
        $_clock_out = GlobalHelper::formatTime($_clock_out);

        if ($_scheduled_clock_in == '14:00:00') {
            $rest_start_time = '18:00:00';
            $rest_end_time = '18:30:00';
            $total_rest_time = '00:30';
        } else if ($_scheduled_clock_in == '05:30:00') {
            $rest_start_time = '09:30:00';
            $rest_end_time = '10:00:00';
            $total_rest_time = '00:30';
        } else {
            if ($_scheduled_clock_in == '07:30:00' && $_scheduled_clock_out == '16:00:00') {
                $rest_start_time = '12:00:00';
                $rest_end_time = '12:30:00';
                $total_rest_time = '00:30';
            } else if ($_scheduled_clock_in == '07:00:00' && $_scheduled_clock_out == '15:30:00') {
                $rest_start_time = '12:00:00';
                $rest_end_time = '12:30:00';
                $total_rest_time = '00:30';
            } else {
                $rest_start_time = '11:30:00';
                $rest_end_time = '12:30:00';
                $total_rest_time = '01:00';
            }
        }

        if ($_clock_in) {
            $_late_clock_in = GlobalHelper::calculateTimeDiff($_scheduled_clock_in, $_clock_in, false);
            if ($_clock_in > $rest_start_time) {
                $_late_clock_in = DateHelper::addTime($_late_clock_in, $total_rest_time, 'subtract');
            }
        }
        if ($_clock_out) {
            $_early_clock_out = GlobalHelper::calculateTimeDiff($_scheduled_clock_out, $_clock_out, true);
            if ($_clock_out < $rest_start_time) {
                $_early_clock_out = DateHelper::addTime($_early_clock_out, $total_rest_time, 'subtract');
            }
        }

        $params['late_clock_in'] = $_late_clock_in == '00:00:00' ? null : $_late_clock_in;
        $params['early_clock_out'] = $_early_clock_out == '00:00:00' ? null : $_early_clock_out;

        $day_integer = date('N', strtotime($params['date']));

        //CALCULATE IF OLD HAS OVERTIME
        $ot = OvertimeDetails::where('overtime_date', $params['date'])->where('employee_code', $params['employee_code'])->get();

        if (!in_array($params['type'], $not_attend_statuses)) {
            //CALCULATE IF NEW HAS OVERTIME
            if (!$is_scheduled_work && ($day_integer == 6 || $day_integer == 7 || (isset($holiday_data[trim($params['date'])]) && $holiday_data[trim($params['date'])]))) {
                $overtime_start_time = Carbon::parse($_clock_in);
            } else {
                if ($_scheduled_clock_out == '16:30') { // Peraturan Baru Istirahat lembur setengah jam dari 16.30, sehingga mulai OT nya 17:00
                    $_scheduled_clock_out = '17:00';
                }
                $overtime_start_time = Carbon::parse($_scheduled_clock_out);
            }

            $overtime_end_time = Carbon::parse($_clock_out);
            $calculate_diff = GlobalHelper::calculateTimeDiff($overtime_start_time, $overtime_end_time, false, true);
            if ($calculate_diff != '00:00:00') {
                $seconds = $overtime_end_time->diffInSeconds($overtime_start_time);
                $hoursWithDecimal = $seconds / 3600;
                $overtime_hours = GlobalHelper::customRoundOvertime($hoursWithDecimal);

                if (!$is_scheduled_work && ($day_integer == 6 || $day_integer == 7 || (isset($holiday_data[trim($params['date'])]) && $holiday_data[trim($params['date'])]))) {
                    if ($_clock_out >= $rest_end_time) {
                        $overtime_hours = $overtime_hours - GlobalHelper::convertTimeToDecimal($total_rest_time);
                    }
                } else {
                    if ($overtime_hours >= 3.5 && $_clock_out >= $rest_start_time) {
                        // Peraturan Baru Istirahat lembur setengah jam dari 16.30, sehingga mulai OT nya 17:00, jadi ini dihapus karena sdh tdk ada istirahat lagi di tengah2 lembur
                        // $overtime_hours = $overtime_hours - 0.5;
                    }
                }
            }
        }

        if (count($ot) > 0 && $overtime_hours < 0.0000000001) {
            foreach ($ot as $row) {
                $row->forceDelete();
            }
        } else if ($overtime_hours >= 0.0000000001) {
            if ($day_integer > 5) {
                $params['late_clock_in'] = null;
                $params['early_clock_out'] = null;

                $overtime = OvertimeDetails::where('overtime_date', $params['date'])->where('employee_code', $params['employee_code'])->first();

                if ($overtime) {
                    $overtime->start_time = $_clock_in;
                    $overtime->end_time = $_clock_out;
                    $overtime->scheduled_clock_in = $_scheduled_clock_in;
                    $overtime->scheduled_clock_out = $_scheduled_clock_out;
                    $overtime->clock_in = $_clock_in;
                    $overtime->clock_out = $_clock_out;
                    $overtime->update();
                } else {
                    $check_overtime = Overtimes::where('date', $params['date'])->first();

                    $employee = Employees::select('id', 'code', 'name', 'department_code')->where('code', $params['employee_code'])->first();

                    if ($check_overtime) {
                        OvertimeDetails::create([
                            'overtime_id' => $check_overtime['id'],
                            'employee_id' => $employee['id'],
                            'overtime_date' => $params['date'],
                            'start_time' => $overtime_start_time->format('H:i:s'),
                            'end_time' => $overtime_end_time->format('H:i:s'),
                            'department_code' => $employee['department_code'],
                            'rest_time' => '00:00',
                            'employee_code' => $employee['code'],
                            'employee_name' => $employee['name'],
                            'scheduled_clock_in' => $_scheduled_clock_in,
                            'scheduled_clock_out' => $_scheduled_clock_out,
                            'clock_in' => $_clock_in,
                            'clock_out' => $_clock_out,
                        ]);
                    } else {
                        $overtime_data = Overtimes::create([
                            'date' => $params['date'],
                            'number' => AutoNumberHelper::initGenerateNumber('OR', $params['date']),
                            'created_by' => config('user.id')
                        ]);

                        OvertimeDetails::create([
                            'overtime_id' => $overtime_data['id'],
                            'employee_id' => $employee['id'],
                            'overtime_date' => $params['date'],
                            'start_time' => $overtime_start_time->format('H:i:s'),
                            'end_time' => $overtime_end_time->format('H:i:s'),
                            'department_code' => $employee['department_code'],
                            'rest_time' => '00:00',
                            'employee_code' => $employee['code'],
                            'employee_name' => $employee['name'],
                            'scheduled_clock_in' => $_scheduled_clock_in,
                            'scheduled_clock_out' => $_scheduled_clock_out,
                            'clock_in' => $_clock_in,
                            'clock_out' => $_clock_out,
                        ]);
                    }
                }
            } else {
                $overtime = OvertimeDetails::where('overtime_date', $params['date'])->where('employee_code', $params['employee_code'])->first();

                if ($overtime) {
                    $overtime->start_time = $overtime_start_time->format('H:i:s');
                    $overtime->end_time = $overtime_end_time->format('H:i:s');
                    $overtime->scheduled_clock_in = $_scheduled_clock_in;
                    $overtime->scheduled_clock_out = $_scheduled_clock_out;
                    $overtime->clock_in = $_clock_in;
                    $overtime->clock_out = $_clock_out;
                    $overtime->update();
                } else {
                    $check_overtime = Overtimes::where('date', $params['date'])->first();

                    if ($check_overtime) {
                        OvertimeDetails::create([
                            'overtime_id' => $check_overtime['id'],
                            'employee_id' => $employee['id'],
                            'overtime_date' => $params['date'],
                            'start_time' => $overtime_start_time->format('H:i:s'),
                            'end_time' => $overtime_end_time->format('H:i:s'),
                            'department_code' => $employee['department_code'],
                            'rest_time' => '00:00',
                            'employee_code' => $employee['code'],
                            'employee_name' => $employee['name'],
                            'scheduled_clock_in' => $_scheduled_clock_in,
                            'scheduled_clock_out' => $_scheduled_clock_out,
                            'clock_in' => $_clock_in,
                            'clock_out' => $_clock_out,
                        ]);
                    } else {
                        $overtime_data = Overtimes::create([
                            'date' => $params['date'],
                            'number' => AutoNumberHelper::initGenerateNumber('OR', $params['date']),
                            'created_by' => config('user.id')
                        ]);

                        OvertimeDetails::create([
                            'overtime_id' => $overtime_data['id'],
                            'employee_id' => $employee['id'],
                            'overtime_date' => $params['date'],
                            'start_time' => $overtime_start_time->format('H:i:s'),
                            'end_time' => $overtime_end_time->format('H:i:s'),
                            'department_code' => $employee['department_code'],
                            'rest_time' => '00:00',
                            'employee_code' => $employee['code'],
                            'employee_name' => $employee['name'],
                            'scheduled_clock_in' => $_scheduled_clock_in,
                            'scheduled_clock_out' => $_scheduled_clock_out,
                            'clock_in' => $_clock_in,
                            'clock_out' => $_clock_out,
                        ]);
                    }
                }
            }
        }

        if ($params['type'] == 'CUTI') {
            $check_balance = LeaveRequests::checkLeaveBalance([
                'start_date' => $params['date'],
                'end_date' => $params['date'],
                'employee_code' => $params['employee_code'],
                'employee_name' => $employee['name']
            ]);


            if ($check_balance) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terdapat error',
                    'data' => $check_balance
                ], 400);
            }

            $leave_request = LeaveRequests::where('date', $params['date'])->first();

            if (!$leave_request) {
                $leave_request = LeaveRequests::create([
                    'date' => $params['date'],
                    'number' => AutoNumberHelper::initGenerateNumber('LR', $params['date']),
                    'created_by' => config('user.id'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            $leave_request_detail = LeaveRequestDetails::where('leave_request_id', $leave_request['id'])->where('employee_code', $params['employee_code'])->first();

            if (!$leave_request_detail) {
                $leave_request_detail = LeaveRequestDetails::create([
                    'leave_request_id' => $leave_request['id'],
                    'employee_id' => $employee['id'],
                    'leave_type_id' => 1,
                    'reason' => 'Change Manual From Attendance',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'start_date' => $params['date'],
                    'end_date' => $params['date'],
                    'employee_code' => $params['employee_code'],
                    'employee_name' => $employee['name']
                ]);

                LeaveRequestDates::create([
                    'leave_request_id' => $leave_request['id'],
                    'leave_request_detail_id' => $leave_request_detail->id,
                    'employee_code' => $params['employee_code'],
                    'employee_id' => $employee['id'],
                    'date' => $params['date'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        if (isset($params['type']) && in_array($params['type'], $not_attend_statuses) ) {
            $params['clock_in'] = null;
            $params['clock_out'] = null;
            $params['late_clock_in'] = null;
            $params['early_clock_out'] = null;
        }

        if ($params['type'] == 'HDR') {
            $params['type'] = '';

            if (!$params['clock_in']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jam Masuk harap diisi',
                    'data' => null
                ], 400);
            }

            if (!$params['clock_out']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jam Keluar harap diisi',
                    'data' => null
                ], 400);
            }
        }

        $params['employee_name'] = $employee['name'];
        $save = self::create($params);

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

7. contoh standarisasi controller
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendances;
use App\Models\VAttendances;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function get(Request $request, $id=null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = VAttendances::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            $res = VAttendances::getAllResult($params, $request);
        } else {
            $res = VAttendances::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return Attendances::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Attendances::createOrUpdate($params, $request->method(), $request);
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Attendances::createOrUpdate($params, $request->method(), $request);
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();

        return Attendances::deleteById($id, $params, $request);
    }

    public function approve(Request $request, $id)
    {
        $params = $request->all();

        return Attendances::approveById($id, $params, $request);
    }

    public function getStatus(Request $request)
    {
        $params = $request->all();

        return VAttendances::statusData($params);   
    }
    
    public function getSummaryAttendanceEmployee($id,Request $request)
    {
        $params = $request->all();

        return VAttendances::summariesEmployee($id,$params);   
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = [
            'id',
            'employee_code',
            'employee_name',
            'date',
            'scheduled_clock_in',
            'scheduled_clock_out',
            'clock_in',
            'clock_out',
            'department_code',
            'type',
            'overtime',
            'total_jam',
            'late_clock_in',
            'early_clock_out',
            'permit',
            'meal'
        ];

        $dataOrder = [];

        $limit = $request->length;

        $start = $request->start;

        foreach ($request->order as $row) {
            $nestedOrder['column'] = $columns[$row['column']];
            $nestedOrder['dir'] = $row['dir'];

            $dataOrder[] = $nestedOrder;
        }

        $order = $dataOrder;

        $dir = $request->order[0]['dir'];

        $search = $request->search['value'];

        $filter = $request->filter;

        $res = VAttendances::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData = $row;
                $nestedData['action'] = '';
                $nestedData['action'] .= '<div class="actions">';
                $nestedData['action'] .= '<a class="btn btn-sm btn-icon btn-warning text-white" id="edit-data" data-id="'.$row['id'].'"><i class="fa-light fa-pencil"></i></a>';
                $nestedData['action'] .= '&nbsp;';
                $nestedData['action'] .= '<a class="btn btn-sm btn-icon btn-danger text-white" id="delete-data" data-id="'.$row['id'].'"><i class="fa-light fa-trash"></i></a>';
                $nestedData['action'] .= '</div>';

                $data[] = $nestedData;
            }
        }

        $json_data = [
            'draw'  => intval($request->draw),
            'recordsTotal'  => intval($res['totalData']),
            'recordsFiltered' => intval($res['totalFiltered']),
            'data'  => $data,
            'order' => $order
        ];

        return json_encode($json_data);
    }
}

8. contoh standarisasi main.blade

<!DOCTYPE html>
<html lang="en" data-menu="horizontal" data-nav-size="nav-default">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }} - @yield('title')</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/flatpickr.min.css') }}">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> --}}

    @yield('style')
    
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" id="primaryColor" href="{{ asset('assets/css/blue-color.css') }}">
    <link rel="stylesheet" id="rtlStyle" href="#">

    <style type="text/css">
        .select2-container .select2-selection--single {
            height: auto !important;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            white-space: normal !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-top: 4px;
        }

        .select2-container.select2-container--default {
            border: 1px solid rgba(0, 0, 0, 0.15);
            color: #464646;
            min-height: calc(1.5em + 0.5rem + calc(var(--bs-border-width) * 2));
            padding: 0.25rem 0.5rem;
            font-size: .875rem;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            padding-left: 20px !important;
            padding-right: 1px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            border-right: none !important;
            margin-right: 5px;
        }

        ul.sidebar-link-group {
            display: none;
        }

        .table .select2-container {
            max-width: 350px !important;
        }

        input[type=radio]:checked, input[type=checkbox]:checked {
            background-color: #0D99FF !important;
            border-color: #0D99FF !important;
        }

        .light-theme .form-check:not(.form-switch) .form-check-input:checked {
            background-image: url({{ asset('assets/images/check-mark.png') }});
        }

        .badge {
            border-radius: 0px !important;
        }

        .select2-container.select2-container--default.select2-container--open {
/*            border: none;*/
        }

        .scrollable-custom {
            overflow-y: auto;
            max-height: 300px;
        }

        #short-div {
            -webkit-transition: width 0.37s ease, height 0.37s ease, margin 0.37s ease, padding 0.37s ease, opacity 0.37s ease;
            -moz-transition: width 0.37s ease, height 0.37s ease, margin 0.37s ease, padding 0.37s ease, opacity 0.37s ease;
            -o-transition: width 0.37s ease, height 0.37s ease, margin 0.37s ease, padding 0.37s ease, opacity 0.37s ease;
            transition: width 0.37s ease, height 0.37s ease, margin 0.37s ease, padding 0.37s ease, opacity 0.37s ease;
        }

        #long-div {
            -webkit-transition: width 0.37s ease, height 0.37s ease, margin 0.37s ease, padding 0.37s ease, opacity 0.37s ease;
            -moz-transition: width 0.37s ease, height 0.37s ease, margin 0.37s ease, padding 0.37s ease, opacity 0.37s ease;
            -o-transition: width 0.37s ease, height 0.37s ease, margin 0.37s ease, padding 0.37s ease, opacity 0.37s ease;
            transition: width 0.37s ease, height 0.37s ease, margin 0.37s ease, padding 0.37s ease, opacity 0.37s ease;
        }

        .w-600-px {
            width: 600px !important;
        }
        .w-400-px {
            width: 400px !important;
        }

        .select2-container {
            width: unset !important;
        }

        .select2-dropdown.select2-dropdown--below {
            margin-top: -5.2px;
        }

        .select2-results__options {
            max-width: 350px !important;
        }

        .title-print {
            font-size: 20px !important;
            text-align: center;
        }

        .export-print {
            color: #000000 !important;
        }

        .table.export-print > tbody > tr:nth-of-type(odd) > * {
            color: #000000 !important;
        }

        .table.export-print tr:hover td {
            color: inherit;
        }

        table.export-print thead th, table.export-print thead td {
            padding: 0px !important;
            text-align: center;
        }

        table.export-print tbody th, table.export-print tbody td {
            padding: 3px !important;
            text-align: center;
        }

        .table-responsive {
            overflow-y: hidden;
        }

        .custom-left--100 {
            left: -100px !important;
        }
    </style>

    <script type="text/javascript">
        let BASE_URL = '{{ env('APP_URL') }}';
        let TOKEN = 'Bearer {{ Session::get('_access_token') }}';
        let USER_ID = '{{ Session::get('_id') }}';
        let ROLE_ID = '{{ Session::get('_role_id') }}';
        let ROLE_NAME = '{{ Session::get('_role_name') }}';
        let USER_EMAIL = '{{ Session::get('_email') }}';
        let USER_NAME = '{{ Session::get('_name') }}';
    </script>
</head>

<body class="body-p-top light-theme has-horizontal">
    @php
        $user = App\Models\User::find(Session::get('_id'));
    @endphp
    <!-- preloader start -->
    <div class="preloader d-none">
        <div class="loader">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <!-- preloader end -->

    <!-- header start -->
    @include('layouts.header')
    <!-- header end -->

    <!-- right sidebar start -->

    <!-- right sidebar end -->

    <!-- main sidebar start -->
    @include('layouts.navbar')
    <!-- main sidebar end -->

    <!-- main content start -->
    <div class="main-content">

        @yield('content')
        <!-- footer start -->
        <div class="footer">
            @include('layouts.footer')
        </div>
        <!-- footer end -->
    </div>
    <!-- main content end -->

    @yield('modal')

    <script src="{{ asset('assets/vendor/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/apexcharts.js') }}"></script>
    
    <script src="{{ asset('assets/vendor/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/datatables/jszip.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

    <script src="{{ asset('assets/vendor/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/moment/min/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/flatpickr.bundle.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.serialize-object.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/accounting.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        let roles = [];
        let users = [];
        let provinces = [];
        let cities = [];
        let districts = [];
        let villages = [];
        let departments = [];
        let workShifts = [];
        let employees = [];
        let leaveTypes = [];
        let payrollComponents = [];
        let departmentTransfers = [];
        let jobPositionTransfers = [];
        let employeePayrollComponents = [];
        let warningLetters = [];
        let generalSettings = [];
        let ter = [];
        let terDetails = [];

        moment.locale('id');

        var windowHeight = $(window).height();
        var windowWidth = $(window).width();

        $(document).ready(function() {
            if ($(window).width() < 990) {
                enableVerticalMenu();
            }
        });

        $(window).resize(function() {
            if ($(window).width() < 990) {
                enableVerticalMenu();
            } else {
                enableHorizontalMenu();
            }
        });

        const flatPickrConfigOnlyTime = {
            noCalendar: true,
            enableTime: true,
            time_24hr: true,
            allowInput: true,
            dateFormat: "H:i"
        };

        function getExportOptions() {   
            return [function(idx, data, node) {
                var tableId = $(node).closest('table').attr('id');
                if (!$('#' + tableId).DataTable().column(idx).visible() || 
                    $(node).text().includes("Action") || 
                    $(node).text().includes("Aksi") || 
                    $(node).text() === '#' || 
                    $(node).hasClass('col-dt-hidden')) {
                    return false;
                }

                return true;
            }];
        }

        // let buttonExportInput = {
        //     exportOptions: {
        //         stripHtml: true,
        //         stripNewlines: false,
        //         columns: getExportOptions(),
        //         format: {
        //             body: function (data, row, column, node) {
        //                 if (!node.firstChild) {
        //                     return data;
        //                 }

        //                 if (node.firstChild.tagName == 'SELECT') {
        //                     return $(node.firstChild).find(':selected').text();
        //                 } else if (node.firstChild.tagName == 'INPUT') {
        //                     if (node.firstElementChild) {
        //                         return node.firstElementChild.value;
        //                     }
        //                     return '';
        //                 } else if (node.firstChild.tagName == 'TEXTAREA') {
        //                     return node.firstElementChild.value;
        //                 } else {
        //                     return data;
        //                 }
        //             }
        //         }
        //     }
        // };

        let buttonExportInput = {
            exportOptions: {
                stripHtml: true,
                stripNewlines: false,
                columns: getExportOptions(),
                format: {
                    body: function(data, row, column, node) {
                        if (!node.firstChild) {
                            // Coba parse sebagai angka
                            return parseNumberExportDT(data);
                        }
                        
                        let cellValue = '';
                        
                        if (node.firstChild.tagName == 'SELECT') {
                            cellValue = $(node.firstChild).find(':selected').text();
                        } else if (node.firstChild.tagName == 'INPUT') {
                            if (node.firstElementChild) {
                                cellValue = node.firstElementChild.value;
                            } else {
                                cellValue = '';
                            }
                        } else if (node.firstChild.tagName == 'TEXTAREA') {
                            cellValue = node.firstElementChild.value;
                        } else {
                            cellValue = data;
                        }

                        // Parse nilai sebagai angka jika memungkinkan
                        return parseNumberExportDT(cellValue);
                    }
                }
            }
        };

        // Fungsi helper untuk mengecek dan mengkonversi angka
        function parseNumberExportDT(value) {
            // Hapus whitespace di awal dan akhir
            let cleanValue = value.toString().trim();
            
            // Cek apakah string mengandung format angka Indonesia (koma sebagai pemisah ribuan, titik sebagai desimal)
            // Pattern: angka dengan koma sebagai pemisah ribuan dan opsional titik untuk desimal
            const indonesianNumberPattern = /^-?\d{1,3}(,\d{3})*(\.\d+)?$/;
            
            if (indonesianNumberPattern.test(cleanValue)) {
                // Hapus koma (pemisah ribuan) dan konversi ke number
                let numericValue = cleanValue.replace(/,/g, '');
                return parseFloat(numericValue);
            }
            
            // Jika bukan format angka yang dikenali, return string asli
            return cleanValue;
        }

        
        const enableVerticalMenu = () => {
            $('html').attr('data-menu', 'vertical');
            if ($('html').attr('data-menu') === ('vertical')) {
                $('.main-sidebar').removeClass(
                    'horizontal-menu two-column-menu sub-menu-collapsed collapsed flush-menu hoverable sidebar-hover'
                );
                $(".scrollable").overlayScrollbars({});
                $('.sidebar-link-group-title').addClass('show');
                $('.sidebar-link-group').unbind('hide').show();
                $('.nav-close-btn').show();
                $('body').addClass('body-padding').removeClass(
                    'has-horizontal expanded has-fixed-sidebar hover-menu has-two-column-menu');
                $('.header').removeClass('expanded');
                $('.has-sub').removeClass('show');
                $('.sidebar-dropdown-menu').hide();
                $('.sidebar-dropdown-item > .has-sub').each(function(e) {
                    $('[data-dropdown="' + $(this).attr('data-dropdown') + '"]').parent().append($('#' + $(this)
                        .attr('data-dropdown')));
                });
                $('#navBarSizeGroup').show();
                $('#sidebarSmall, #sidebarHover').removeClass('active');
                $('#sidebarDefault').addClass('active');
                $('.main-content').css('min-height', windowHeight - 70);
                if ($(window).width() < 1800) {
                    $('.main-content').css('min-height', windowHeight - 60);
                }
                if ($(window).width() < 992) {
                    $('.main-content').css('min-height', windowHeight - 50);
                }
            }
        };

        const enableHorizontalMenu = () => {
            $('html').attr('data-menu', 'horizontal');
            if ($('html').attr('data-menu') === ('horizontal')) {
                $('.main-sidebar').addClass('horizontal-menu').removeClass(
                    'collapsed two-column-menu sub-menu-collapsed sidebar-hover hoverable flush-menu');
                if ($('.sidebar-menu').overlayScrollbars()) {
                    $('.sidebar-menu').overlayScrollbars().destroy();
                }
                $('.sidebar-link-group-title').removeClass('show');
                $('.sidebar-link-group').unbind('show').hide();
                $('.sidebar-link.has-sub').removeClass('show');
                $('.header').removeClass('expanded');
                $('.nav-close-btn').hide();
                $('body').removeClass('body-padding hover-menu has-two-column-menu').addClass('has-horizontal');
                $('.sidebar-dropdown-menu').hide();
                $('.sidebar-dropdown-item > .has-sub').each(function(e) {
                    $('[data-dropdown="' + $(this).attr('data-dropdown') + '"]').parent().append($('#' + $(this)
                        .attr('data-dropdown')));
                });
                $('#navBarSizeGroup').hide().find('.dashboard-icon').removeClass('active');
                $('#navBarSizeGroup').hide().find('.dashboard-icon').first().addClass('active');
                $('.main-content').css('min-height', windowHeight - 119);
                if ($(window).width() < 1800) {
                    $('.main-content').css('min-height', windowHeight - 107);
                }
                if ($(window).width() < 992) {
                    $('.main-content').css('min-height', windowHeight - 50);
                }
            }
        };

        $('.modal').on("hidden.bs.modal", function () {
            $('body').css('padding-right', 0);
        });
        function resetAllInputOnForm(formId) {
            $(formId).find('input, textarea').val('');
            $(formId).find('input[type="checkbox"]').prop('checked', false);
            $(formId).find('input[type="radio"]').prop('checked', false);
            $(formId).find('select').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy').val("").select2({
                        // width: '100%',
                        dropdownParent: $(formId)
                    });
                } else {
                    $(formId).find('select').val("");
                }
            });
        }

        function showAlertOnSubmit(params, modal, table, reload, reloadBlank) {
            if (params.status == 'success') {
                setTimeout(function() {
                    Swal.fire({
                        title: "Sukses",
                        text: params.message,
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1000
                    }).then((result) => {
                        if (modal) {
                            $(modal).modal('hide');
                        }
                        if (table) {
                            $(table).DataTable().ajax.reload(null, false);
                        }
                        if (reload) {
                            window.location.replace(reload);
                        }
                        if (reloadBlank) {
                            window.open(reloadBlank, '_blank');
                        }
                    });
                }, 200);
            } else {
                showFailedAlert(params.message);
            }
        }

        function showFailedAlert(msg) {
            Swal.fire({
                title: "Failed",
                showConfirmButton: true,
                confirmButtonColor: '#0760ef',
                icon: "error",
                html: msg
            });
        }

        function showPopupWithAction(title, subtitle, icon, method, data, url, modal, table, reload) {
            Swal.fire({
                title: title,
                html: subtitle,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#4DCA88',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        "headers": {
                            'Authorization': TOKEN,
                            'Content-Type': 'application/json'
                        },
                        data: data,
                        type: method,
                        beforeSend: function() {
                            showLoading('Harap Menunggu!', 'Sedang menyimpan data');
                        },
                    })
                    .done(function(data) {
                        Swal.close();
                        if (data.status == 'success') {
                            Swal.fire({
                                title: "Ok!",
                                text: data.message,
                                icon: "success",
                                showConfirmButton: false,  
                                timer: 1500  
                            });
                            if (modal) {
                                $(modal).modal('hide');
                            }
                            if (table) {
                                $.each(table, function(index, item) {
                                    $(item).DataTable().ajax.reload(null, false);
                                })
                            }
                            if (reload) {
                                window.location.replace(reload);
                            }
                        } else {
                            Swal.fire("Error!", data.message, "error");
                        }
                    })
                    .fail(function(data) {
                        Swal.close();
                        let errorRes = data.responseJSON;

                        showFailedAlert(errorRes.message);
                    });
                }
            });
        }

        function showLoading(title, message, timer = 0) {
            Swal.fire({
                title: title,
                html: message,
                didOpen: () => {
                    Swal.showLoading();
                },
                timer: timer
            });
        }

        function makeTitle(slug, separator = '-') {
            var words = slug.split(separator);

            for (var i = 0; i < words.length; i++) {
                var word = words[i];
                words[i] = word.charAt(0).toUpperCase() + word.slice(1);
            }

            return words.join(' ');
        }

        function numberToDay(number) {
            switch (number) {
                case 1:
                    return 'Monday';
                    break;
                case 2:
                    return 'Tuesday';
                    break;
                case 3:
                    return 'Wednesday';
                    break;
                case 4:
                    return 'Thursday';
                    break;
                case 5:
                    return 'Friday';
                    break;
                case 6:
                    return 'Saturday';
                    break;
                case 7:
                    return 'Sunday';
                    break;
                default:
                    return '-'
            }
        }

        function numberToMonth(number) {
            switch (number) {
                case 1:
                    return 'Januari';
                    break;
                case 2:
                    return 'Februari';
                    break;
                case 3:
                    return 'Maret';
                    break;
                case 4:
                    return 'April';
                    break;
                case 5:
                    return 'Mei';
                    break;
                case 6:
                    return 'Juni';
                    break;
                case 7:
                    return 'Juli';
                    break;
                case 8:
                    return 'Agustus';
                    break;
                case 9:
                    return 'September';
                    break;
                case 10:
                    return 'Oktober';
                    break;
                case 11:
                    return 'Nopember';
                    break;
                case 12:
                    return 'Desember';
                    break;
                default:
                    return '-'
            }
        }

        function getRoles(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/roles?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    roles = res;
                    if (element != '' && element != undefined && element != null) {
                        buildRoles(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildRoles(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Choose Role</option>'
            $.each(roles, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.name + '</option>'
                }
            })

            $(element).html(html)
        }
        

        function getJobPositions(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/job_positions?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    jobPositions = res;
                    if (element != '' && element != undefined && element != null) {
                        buildJobPositions(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildJobPositions(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Choose Job Positions</option>'
            $.each(jobPositions, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-job_position_name="' + item.name + '" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-job_position_name="' + item.name + '">' + item.name + '</option>'
                }
            })

            $(element).html(html)
        }

        function getJobLevels(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/job_levels?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    users = res;
                    if (element != '' && element != undefined && element != null) {
                        buildJobLevels(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildJobLevels(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Choose Job Levels</option>'
            $.each(users, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.name + '</option>'
                }
            })

            $(element).html(html)
        }

        function getCompensations(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/compensations?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    compensations = res;
                    if (element != '' && element != undefined && element != null) {
                        buildCompensations(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildCompensations(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Choose Compensations</option>'
            $.each(compensations, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '"  data-severance="'+item.severance+'" data-award_money="'+item.award_money+'" data-compensation="'+item.compensation+'" data-separation_pay="'+item.separation_pay+'" data-default_procedure="'+item.default_procedure+'" data-multiple_from_total="'+item.multiple_from_total+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-severance="'+item.severance+'" data-award_money="'+item.award_money+'" data-compensation="'+item.compensation+'" data-separation_pay="'+item.separation_pay+'" data-default_procedure="'+item.default_procedure+'" data-multiple_from_total="'+item.multiple_from_total+'" >' + item.name + '</option>'
                }
            })

            $(element).html(html)
        }


        function getUsers(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/users?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    users = res;
                    if (element != '' && element != undefined && element != null) {
                        buildUsers(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }
        function buildUsers(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Choose User</option>'
            $.each(users, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + ' ' + item.email + '</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.name + ' ' + item.email + '</option>'
                }
            })

            $(element).html(html)
        }
        function getEmploymentStatus(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/employment_statuses?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    employmentStatus = res;
                    if (element != '' && element != undefined && element != null) {
                        buildEmploymentStatus(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildEmploymentStatus(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Choose</option>'
            $.each(employmentStatus, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-employment_status_name="' + item.name + '"  selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-employment_status_name="' + item.name + '">' + item.name + ' </option>'
                }
            })

            $(element).html(html)
        }

        function getDepartments(element, filter = '', isAsync = true, selectedVal = '', defaultKey = 'id', needAll = false) {
            $.ajax({
                url: BASE_URL + "/api/departments?order[name]=asc&" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    departments = res;
                    if (element != '' && element != undefined && element != null) {
                        buildDepartments(element, selectedVal, defaultKey, needAll);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildDepartments(element, selectedVal = "", defaultKey = 'id', needAll = false) {
            let html = ''
            html += '<option value="">Choose Departments</option>'
            if (needAll) {
                html += '<option value="all">All Departments</option>';
            }
            $.each(departments, function(index, item) {
                let key = item[defaultKey];

                if (selectedVal == key) {
                    html += '<option value="' + key + '" data-code="'+item.code+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + key + '" data-code="'+item.code+'" >' + item.name + '</option>'
                }
            })

            $(element).html(html)
        }

        function getCountries(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/countries?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    countries = res;
                    if (element != '' && element != undefined && element != null) {
                        buildCountries(element, selectedVal);
                    }
                  
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildCountries(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Choose Country</option>'
            $.each(countries, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.name + '</option>'
                }
            })
            
            $(element).html(html)
        }

        function getProvinces(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/provinces?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    provinces = res;
                    if (element != '' && element != undefined && element != null) {
                        buildProvinces(element, selectedVal);
                    }
                  
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildProvinces(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Choose Province</option>'
            $.each(provinces, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.name + '</option>'
                }
            })
            
            $(element).html(html)
        }

        function getCities(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/regencies?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    cities = res.data;
                    if (element != '' && element != undefined && element != null) {
                        buildCities(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildCities(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Chosee Regency</option>'
            $.each(cities, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.name + '</option>'
                }
            })

            $(element).html(html)
        }

        function getCitySearch(element, dropdownParentEl = '', selectedValObject = [], filter = '', placeholderText = 'Please select city', defaultKey = 'id') {
            $(element).select2({
                width: '100%',
                minimumInputLength: 2,
                placeholder: placeholderText,
                dropdownParent: dropdownParentEl,
                language: { 
                    inputTooShort: function (val) {
                        return 'Ketikan minimal '+val.minimum+' karakter.'; 
                    } 
                },
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/regencies?per_page=15&"+filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                                name: params.term
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    text: item.name,
                                    id: defaultKey == 'code' ? item.code : item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                var preselectedValue = new Option(selectedValObject.text, selectedValObject.id, false, false);
                $(element).html(preselectedValue);
            }
        }

        function getDistricts(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/districts?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    districts = res.data;
                    if (element != '' && element != undefined && element != null) {
                        buildDistricts(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildDistricts(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Chosee District</option>'
            $.each(districts, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.name + '</option>'
                }
            })

            $(element).html(html)
        }

        function getVillages(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/villages?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    villages = res.data;
                    if (element != '' && element != undefined && element != null) {
                        buildVillages(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildVillages(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Chosee Sub District</option>'
            $.each(villages, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.name + '</option>'
                }
            })

            $(element).html(html)
        }

        function getWorkShifts(element, isAsync = true, filter = '', selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/work_shifts?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    workShifts = res;
                    if (element != '' && element != undefined && element != null) {
                        buildWorkShifts(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildWorkShifts(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Choose Working Hours</option>'
            $.each(workShifts, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + ' '+item.check_in+' - '+item.check_out+'</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.name + ' '+item.check_in+' - '+item.check_out+'</option>'
                }
            })

            $(element).html(html)
        }

        function getEmployeeSearch(element, selectedValObject = [], filter = '', placeholderText = 'Please select an employee', dropdownParentEl = '', defaultKey = 'id') {
            $(element).select2({
                width: '100%',
                minimumInputLength: 2,
                placeholder: placeholderText,
                dropdownParent: dropdownParentEl,
                language: { 
                    inputTooShort: function (val) {
                        return 'Ketikan minimal '+val.minimum+' karakter.'; 
                    } 
                },
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/employees?per_page=15&"+filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            or: {
                                name: params.term,
                                code: params.term
                            },
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    name: item.name,
                                    code: item.code,
                                    text: (item.code ? (item.code +' | ') : '') + item.name,
                                    id: defaultKey == 'code' ? item.code : item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                var preselectedValue = new Option(selectedValObject.text, selectedValObject.id, false, false);
                $(element).html(preselectedValue);
            }
        }

        function getEmployees(element, filter = '', isAsync = true, selectedVal = '', defaultKey = 'id') {
            $.ajax({
                url: BASE_URL + "/api/employees?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    employees = res;
                    if (element != '' && element != undefined && element != null) {
                        buildEmployees(element, selectedVal, defaultKey);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildEmployees(element, selectedVal = "", defaultKey = 'id') {
            let html = ''
            html += '<option value="">Choose Employees</option>'
            $.each(employees, function(index, item) {
                let key = item[defaultKey];

                if (selectedVal == key) {
                    html += '<option value="' + key + '" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + key + '">' + item.name + '</option>'
                }
            })

            $(element).html(html)
        }

        function getBanks(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/banks?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    banks = res;
                    if (element != '' && element != undefined && element != null) {
                        buildBanks(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildBanks(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Chosee Banks</option>'
            $.each(banks, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.name + '</option>'
                }
            })

            $(element).html(html)
        }

        function getLeaveTypes(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/leave_types?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    leaveTypes = res;
                    if (element != '' && element != undefined && element != null) {
                        buildLeaveTypes(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildLeaveTypes(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Choose Leave Type</option>'
            $.each(leaveTypes, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '"  data-quota="'+item.quota+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-quota="'+item.quota+'" >' + item.name + '</option>'
                }
            })

            $(element).html(html)
        }

        function getPayrollComponents(element, filter = '', isAsync = true, selectedVal = '', defaultKey = 'id') {
            $.ajax({
                url: BASE_URL + "/api/payroll_components?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    payrollComponents = res;
                    if (element != '' && element != undefined && element != null) {
                        buildPayrollComponents(element, selectedVal, defaultKey);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildPayrollComponents(element, selectedVal = "", defaultKey = 'id') {
            let html = ''
            $.each(payrollComponents, function(index, item) {
                let key = item[defaultKey];

                if (selectedVal == key) {
                    html += '<option value="' + key + '"  data-code="'+item.code+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + key + '" data-code="'+item.code+'" >' + item.name + '</option>'
                }
            })

            $(element).html(html)
        }

        function calculateAges(birthDate) {
            // Mendapatkan tanggal hari ini
            var nowDate = new Date();

            // Mendapatkan tanggal lahir dari parameter
            var born = new Date(birthDate);

            // Menghitung selisih tahun
            var yearDiff = nowDate.getFullYear() - born.getFullYear();

            // Periksa apakah ulang tahun sudah berlalu atau belum
            if (
                nowDate.getMonth() < born.getMonth() ||
                (nowDate.getMonth() === born.getMonth() &&
                    nowDate.getDate() < born.getDate())
            ) {
                yearDiff--;
            }

            return yearDiff;
        }

        function getReimburse(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/reimburse_types?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    reimburse = res;
                    if (element != '' && element != undefined && element != null) {
                        buildReimburse(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildReimburse(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Chosee Reimburse Type</option>'
            $.each(reimburse, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.name + '</option>'
                }
            })

            $(element).html(html)
        }

        function getDepartmentTransfers(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/department_transfer_details?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    departmentTransfers = res;
                    if (element != '' && element != undefined && element != null) {
                        buildDepartmentTransfers(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function getTradeUnion(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/trade_unions?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    tradeUnion = res;
                    if (element != '' && element != undefined && element != null) {
                        buildTradeUnion(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildTradeUnion(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Chosee Trade Union</option>'
            $.each(tradeUnion, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.name + '</option>'
                }
            })

            $(element).html(html)
        }
        
        function getPositionTransfers(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/job_position_transfer_details?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    jobPositionTransfers = res;
                    if (element != '' && element != undefined && element != null) {
                        buildPositionTransfers(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function getEmployeePayrollComponents(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/employee_payroll_components?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    employeePayrollComponents = res;
                    if (element != '' && element != undefined && element != null) {
                        buildEmployeePayrollComponents(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function calculateTotalDays(startDate, endDate) {
            // Menggunakan Moment.js untuk memudahkan perhitungan tanggal
            var startMoment = moment(startDate);
            var endMoment = moment(endDate);

            // Menghitung selisih hari
            var totalDays = endMoment.diff(startMoment, 'days') + 1;

            return totalDays;
        }

        function getWarningLetter(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/warning_letters?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    warningLetters = res;
                    if (element != '' && element != undefined && element != null) {
                        buildWarningLetter(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildWarningLetter(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Chosee Warning Letter</option>'
            $.each(warningLetters, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.name + '</option>'
                }
            });
            $(element).html(html)
        }

        function getTer(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/pph21_ter?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    ter = res;
                    if (element != '' && element != undefined && element != null) {
                        buildTer(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildTer(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Pilih TER</option>'
            $.each(ter, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.ter_type + ' - '+item.ptkp+'</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.ter_type + ' - '+item.ptkp+'</option>'
                }
            });
            $(element).html(html)
        }

        function getTerDetails(element, filter = '', isAsync = true, selectedVal = '') {
            $.ajax({
                url: BASE_URL + "/api/pph21_ter_details?" + filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    terDetails = res;
                    if (element != '' && element != undefined && element != null) {
                        buildTerDetails(element, selectedVal);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {

                },
            });
        }

        function buildTerDetails(element, selectedVal = "") {
            let html = ''
            html += '<option value="">Pilih Persentase TER</option>'
            $.each(terDetails, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.ter_type + ' - '+parseFloat(item.gross_income_from).toLocaleString('en')+' s/d '+parseFloat(item.gross_income_to).toLocaleString('en')+' ('+percentage+')</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.ter_type + ' - '+parseFloat(item.gross_income_from).toLocaleString('en')+' s/d '+parseFloat(item.gross_income_to).toLocaleString('en')+' ('+percentage+')</option>'
                }
            });
            $(element).html(html)
        }

        function isDateInRange(selectedDate, startDate, endDate) {
            // Parsing tanggal menggunakan Moment.js
            const selectedMoment = moment(selectedDate);
            const startMoment = moment(startDate);
            const endMoment = moment(endDate);

            // Memeriksa apakah tanggal dipilih berada dalam rentang yang ditentukan
            return selectedMoment.isBetween(startMoment, endMoment, null, '[]');
        }

        function getValueForGeneralSetting(selectedData,element,modalId='', selectedVal = '') {
            let urlValueGS = '';
            if (selectedData == 'employment_status') {
                urlValueGS = 'employment_statuses?all=true';
            }else{
                urlValueGS = 'payroll_components?all=true';
            }
            $.ajax({
                url: BASE_URL + "/api/" + urlValueGS,
                type: 'GET',
                "headers": {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                success: function (data, textStatus, jqXHR) {
                    generalSettings = data;
                    if (element != '' && element != undefined && element != null) {
                        buildGeneralSetting(element, selectedVal);
                            $('#input-value').select2({
                                dropdownParent: $(modalId),
                            });
                    }
                    
                    
                },
                error: function (jqXHR, textStatus, errorThrown) {

                },
            });
            
        }
        function buildGeneralSetting(element, selectedVal = "") {
                    let html = '';
                    html += '<select name="value" class="form-control form-control-sm" id="input-value">'
                    html += '<option value="" selected disabled>Chosee </option>'
                        $.each(generalSettings, function(index, item) {
                            console.log(selectedVal);
                            if (selectedVal == item.code) {
                                html += '<option value="' + item.code + '" selected>['+item.name+'] - ' + item.code + '</option>'
                            }else{
                                html += '<option value="' + item.code + '">['+item.name+'] - ' + item.code + '</option>' 
                            }
                        });
                    html += '<option value="off">OFF</option>'
                    html += '</select>'
                    $(element).html(html)
            // let html = ''
            // html += '<option value="">Chosee Trade Union</option>'

            // $.each(tradeUnion, function(index, item) {
            //     if (selectedVal == item.id) {
            //         html += '<option value="' + item.id + '" selected>' + item.name + '</option>'
            //     } else {
            //         html += '<option value="' + item.id + '">' + item.name + '</option>'
            //     }
            // })

            // $(element).html(html)
        }

        function addSeparator(nStr, inD = '.', outD = '.', sep = ',') {
            nStr += '';
            var dpos = nStr.indexOf(inD);
            var nStrEnd = '';
            if (dpos != -1) {
                nStrEnd = outD + nStr.substring(dpos + 1, nStr.length);
                nStr = nStr.substring(0, dpos);
            }
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(nStr)) {
                nStr = nStr.replace(rgx, '$1' + sep + '$2');
            }
            return nStr + nStrEnd;
        }

        function detectFloat(source) {
            let float = accounting.unformat(source);
            let posComma = source.indexOf('.');
            if (posComma > -1) {
                let posDot = source.indexOf(',');
                if (posDot > -1 && posComma > posDot) {
                    let germanFloat = accounting.unformat(source, '.');
                    if (Math.abs(germanFloat) > Math.abs(float)) {
                        float = germanFloat;
                    }
                } else {
                    // source = source.replace(/,/g, '.');
                    float = accounting.unformat(source, '.');
                }
            }
            return float;
        }

        function addThousandSeparator() {
            $(".tseparator").each( function () {
                $(this).on("blur", function (e) {
                  var val = this.value;

                  $(this).val(addSeparator(val));
                });

                $(this).on("focus", function (e) {
                  var val = this.value;

                  $(this).val(detectFloat(val));
                    $(this).select();
                });
            });
        }

        addThousandSeparator();

        function calculateTimeDifferenceInHours(startTime, endTime) {
            const [startHour, startMinute] = startTime.split(':').map(Number);
            const [endHour, endMinute] = endTime.split(':').map(Number);

            const totalStartMinutes = startHour * 60 + startMinute;
            const totalEndMinutes = endHour * 60 + endMinute;

            const timeDifferenceInMinutes = totalEndMinutes - totalStartMinutes;

            // Konversi selisih waktu ke dalam jam dengan desimal
            const timeDifferenceInHours = timeDifferenceInMinutes / 60;

            return timeDifferenceInHours;
        }

        function customRound(number) {
            // Jika nilai desimal lebih besar atau sama dengan 0.5, bulatkan ke 0.5
            if (number - Math.floor(number) >= 0.5) {
                return Math.floor(number) + 0.5;
            }
            // Jika nilai desimal kurang dari 0.5, bulatkan ke bawah
            else {
                return Math.floor(number);
            }
        }

        function calculateTerPercentage(grossIncome, terType, terRanges) {
            const terTypeRanges = terRanges[terType];
            for (const range of terTypeRanges) {
                if (grossIncome >= range.gross_income_from && grossIncome <= range.gross_income_to) {
                    return range.percentage;
                }
            }

            return 0; // Default jika tidak ada rentang yang cocok
        }

        function getHeaderNames(table) {
            // Gets header names.
            //params:
            //  table: table ID.
            //Returns:
            //  Array of column header names.

            var header = $(table).DataTable().columns().header().toArray();

            var names = [];
            header.forEach(function(th) {
                names.push($(th).html());
            });
                
            return names;
        }

        function buildCols(data) {
            // Builds cols XML.
            //To do: deifne widths for each column.
            //Params:
            //  data: row data.
            //Returns:
            //  String of XML formatted column widths.

            var cols = '<cols>';

            for (i=0; i<data.length; i++) {
                colNum = i + 1;
                cols += '<col min="' + colNum + '" max="' + colNum + '" width="20" customWidth="1"/>';
            }

            cols += '</cols>';

            return cols;
        }

        function buildRow(data, rowNum, styleNum) {
            // Builds row XML.
            // Params:
            //   data: Row data.
            //   rowNum: Excel row number.
            //   styleNum: style number or empty string for no style.
            // Returns:
            //   String of XML formatted row.
            
            var style = styleNum ? ' s="' + styleNum + '"' : '';
            var row = '<row r="' + rowNum + '">';
            
            for (var i = 0; i < data.length; i++) {
                var colNum = (i + 10).toString(36).toUpperCase();  // Konversi ke format huruf (A, B, C, ...)
                var cr = colNum + rowNum;
                var cellValue = data[i];
                
                // Parse dan format data
                if (isNumericValue(cellValue)) {
                    // Nilai numeric - hapus koma dan konversi ke number
                    var numericValue = parseNumberExportDT(cellValue);
                    row += '<c r="' + cr + '"' + style + '>' +
                           '<v>' + numericValue + '</v>' +
                           '</c>';
                } else {
                    // Nilai string - escape karakter khusus
                    var stringValue = String(cellValue || '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#39;');
                    
                    row += '<c t="inlineStr" r="' + cr + '"' + style + '>' +
                           '<is><t>' + stringValue + '</t></is>' +
                           '</c>';
                }
            }
            
            row += '</row>';
            return row;
        }

        function isNumericValue(value) {
            if (value === null || value === undefined || value === '') {
                return false;
            }
            
            // Cek jika sudah berupa number
            if (typeof value === 'number') {
                return !isNaN(value);
            }
            
            let cleanValue = value.toString().trim();
            
            // Pattern untuk angka Indonesia atau regular
            const indonesianNumberPattern = /^-?\d{1,3}(,\d{3})*(\.\d+)?$/;
            const regularNumberPattern = /^-?\d+(\.\d+)?$/;
            
            return indonesianNumberPattern.test(cleanValue) || regularNumberPattern.test(cleanValue);
        }

        // function getTableData(table, title) {
        //     // Processes Datatable row data to build sheet.
        //     //Params:
        //     //  table: table ID.
        //     //  title: Title displayed at top of SS or empty str for no title.
        //     //Returns:
        //     //  String of XML formatted worksheet.

        //     var header = getHeaderNames(table);
        //     var table = $(table).DataTable();
        //     var rowNum = 1;
        //     var mergeCells = '';
        //     var ws = '';

        //     ws += buildCols(header);
        //     ws += '<sheetData>';

        //     if (title.length > 0) {
        //         ws += buildRow([title], rowNum, 51);
        //         rowNum++;
              
        //         mergeCol = ((header.length - 1) + 10).toString(36).toUpperCase();
              
        //         mergeCells = '<mergeCells count="1">'+
        //         '<mergeCell ref="A1:' + mergeCol + '1"/>' +
        //                    '</mergeCells>';
        //     }
                              
        //     ws += buildRow(header, rowNum, 2);
        //     rowNum++;

        //     // Loop through each row to append to sheet.    
        //     table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
        //         var data = this.data();
        //         // If data is object based then it needs to be converted 
        //         // to an array before sending to buildRow()
        //         ws += buildRow(data, rowNum, '');
              
        //         rowNum++;
        //     } );

        //     ws += '</sheetData>' + mergeCells;
                
        //     return ws;

        // }

        function shouldExportColumnByIndex(columnIndex, table) {
            // Cek visibility kolom di DataTable
            if (!table.column(columnIndex).visible()) {
                return false;
            }
            
            // Dapatkan header text
            var headerNode = $(table.column(columnIndex).header());
            var headerText = headerNode.text();
            
            if (headerText.includes("Action") || 
                headerText.includes("Aksi") || 
                headerText === '#' || 
                headerNode.hasClass('col-dt-hidden')) {
                return false;
            }
            
            return true;
        }

        function getTableData(tableSelector, title) {
            // Dapatkan DataTable instance
            var table = $(tableSelector).DataTable();
            var header = getHeaderNames(tableSelector);
            var rowNum = 1;
            var mergeCells = '';
            var ws = '';
            
            // Filter header berdasarkan kolom yang visible
            var visibleHeaders = [];
            var visibleColumnIndexes = [];
            
            header.forEach(function(headerName, index) {
                if (shouldExportColumnByIndex(index, table)) {
                    visibleHeaders.push(headerName);
                    visibleColumnIndexes.push(index);
                }
            });
            
            ws += buildCols(visibleHeaders);
            ws += '<sheetData>';
            
            if (title.length > 0) {
                ws += buildRow([title], rowNum, 51);
                rowNum++;
              
                mergeCol = ((visibleHeaders.length - 1) + 10).toString(36).toUpperCase();
              
                mergeCells = '<mergeCells count="1">'+
                '<mergeCell ref="A1:' + mergeCol + '1"/>' +
                           '</mergeCells>';
            }
                                  
            ws += buildRow(visibleHeaders, rowNum, 2);
            rowNum++;
            
            // Loop through each row to append to sheet
            table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                var data = this.data();
                var formattedData = [];
                
                // Format setiap cell data sesuai dengan kolom yang visible
                visibleColumnIndexes.forEach(function(columnIndex) {
                    var cellData = Array.isArray(data) ? data[columnIndex] : data[Object.keys(data)[columnIndex]];
                    var formattedCellData = formatCellData(cellData, rowIdx, columnIndex, table);
                    formattedData.push(formattedCellData);
                });
                
                ws += buildRow(formattedData, rowNum, '');
                rowNum++;
            });
            
            ws += '</sheetData>' + mergeCells;
                
            return ws;
        }

        function formatCellData(data, rowIndex, columnIndex, table) {
            // Dapatkan node DOM untuk cell ini
            var $table = $(table.table().node());
            var $row = $table.find('tbody tr').eq(rowIndex);
            var $cell = $row.find('td').eq(columnIndex);
            
            if ($cell.length > 0) {
                var node = $cell[0];
                
                if (!node.firstChild) {
                    return parseNumberExportDT(data);
                }
                
                let cellValue = '';
                
                if (node.firstChild.tagName == 'SELECT') {
                    cellValue = $(node.firstChild).find(':selected').text();
                } else if (node.firstChild.tagName == 'INPUT') {
                    if (node.firstElementChild) {
                        cellValue = node.firstElementChild.value;
                    } else {
                        cellValue = '';
                    }
                } else if (node.firstChild.tagName == 'TEXTAREA') {
                    cellValue = node.firstElementChild.value;
                } else {
                    cellValue = data;
                }
                
                return parseNumberExportDT(cellValue);
            }
            
            // Fallback jika tidak ada DOM node, format data langsung
            return parseNumberExportDT(data);
        }

        function setSheetName(xlsx, name) {
            // Changes tab title for sheet.
            //Params:
            //  xlsx: xlxs worksheet object.
            //  name: name for sheet.
            if (name.length > 0) {
                var source = xlsx.xl['workbook.xml'].getElementsByTagName('sheet')[0];
                source.setAttribute('name', name);
            }
        }

        function addSheet(xlsx, table, title, name, sheetId) {
            //Clones sheet from Sheet1 to build new sheet.
            //Params:
            //  xlsx: xlsx object.
            //  table: table ID.
            //  title: Title for top row or blank if no title.
            //  name: Name of new sheet.
            //  sheetId: string containing sheetId for new sheet.
            //Returns:
            //  Updated sheet object.

            //Add sheet2 to [Content_Types].xml => <Types>
            //============================================
            var source = xlsx['[Content_Types].xml'].getElementsByTagName('Override')[1];
            var clone = source.cloneNode(true);
            clone.setAttribute('PartName','/xl/worksheets/sheet' + sheetId + '.xml');
            xlsx['[Content_Types].xml'].getElementsByTagName('Types')[0].appendChild(clone);

            //Add sheet relationship to xl/_rels/workbook.xml.rels => Relationships
            //=====================================================================
            var source = xlsx.xl._rels['workbook.xml.rels'].getElementsByTagName('Relationship')[0];
            var clone = source.cloneNode(true);
            clone.setAttribute('Id','rId'+sheetId+1);
            clone.setAttribute('Target','worksheets/sheet' + sheetId + '.xml');
            xlsx.xl._rels['workbook.xml.rels'].getElementsByTagName('Relationships')[0].appendChild(clone);

            //Add second sheet to xl/workbook.xml => <workbook><sheets>
            //=========================================================
            var source = xlsx.xl['workbook.xml'].getElementsByTagName('sheet')[0];
            var clone = source.cloneNode(true);
            clone.setAttribute('name', name);
            clone.setAttribute('sheetId', sheetId);
            clone.setAttribute('r:id','rId'+sheetId+1);
            xlsx.xl['workbook.xml'].getElementsByTagName('sheets')[0].appendChild(clone);

            //Add sheet2.xml to xl/worksheets
            //===============================
            var newSheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'+
              '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac" mc:Ignorable="x14ac">'+
              getTableData(table, title) +
              
              '</worksheet>';

            xlsx.xl.worksheets['sheet' + sheetId + '.xml'] = $.parseXML(newSheet);
        }

        function getMonthDateRange(year, month) {
            // month in moment is 0 based, so 9 is actually october, subtract 1 to compensate
            // array is 'year', 'month', 'day', etc
            var startDate = moment([year, month - 1]);

            // Clone the value before .endOf()
            var endDate = moment(startDate).endOf('month');

            // just for demonstration:
            console.log(startDate.toDate());
            console.log(endDate.toDate());

            // make sure to call toDate() for plain JavaScript date type
            return { start: startDate, end: endDate };
        }

        function truncate(num, decimals = 4) {
            const multiplier = Math.pow(10, decimals);
            return Math.floor(num * multiplier) / multiplier;
        }
    </script>
    @yield('script')
</body>

</html>

9. contoh standarisasi blade nya

@extends('layouts.main')

@section('title', $title)

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/dropzone.min.css') }}">

    <style>
        .dz-preview.dz-file-preview {
            width: 100%;
        }

        .dropzone .dz-preview .dz-image {
            width: 100% !important;
        }

        /*.table-responsive {
            overflow-y: scroll !important;
            overflow-x: unset !important;
        }*/
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>{{$title}}</h5>
                    <div class="btn-box d-flex gap-2">
                        <button class="btn btn-sm btn-primary" id="add-button"><i class="fa fa-plus"></i> Add</button>
                        <button class="btn btn-sm btn-success" id="import-button"><i class="fas fa-file-import"></i> Import</button>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check-noclock">
                            <label class="form-check-label" for="check-noclock">
                                Jam Kosong
                            </label>
                        </div>
                        <div class="input-group dashboard-filter">
                            <input type="text" class="form-control" name="basic" id="daterange-filter" readonly>
                            <label for="daterange-filter" class="input-group-text"><i class="fa-light fa-calendar-days"></i></label>
                        </div>
                        {{-- <button class="btn btn-sm btn-icon btn-outline-primary"><i class="fa-light fa-arrows-rotate"></i></button> --}}
                        <button class="btn btn-sm btn-warning" id="download-excel-button"><i class="fa-solid fa-file-excel"></i> Per Hari</button>
                        <button class="btn btn-sm btn-warning" id="download-pdf-button"><i class="fa-solid fa-file-pdf"></i> Per Hari</button>
                        <div class="digi-dropdown dropdown">
                            <button class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"><i class="fa-regular fa-ellipsis-vertical"></i></button>
                            <ul class="digi-dropdown-menu dropdown-menu" id="main-table_colvis">
                                <li class="dropdown-title">Show Table Title</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-filter-option">
                        <div class="row g-3">
                            <div class="col-xl-10 col-9 col-xs-12">
                                <div class="row g-3">
                                    <div class="col">
                                        <div class="row g-2">
                                            <div class="col" id="main-table_btn">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-3 col-3 col-xs-12 d-flex justify-content-end">
                                <div id="attendanceTableLength"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive"> 
                                        <table class="table table-dashed table-hover digi-dataTable table-striped" id="main-table">
                                            <thead>
                                                <tr>
                                                    <th class="no-sort">#</th>
                                                    <th>Empl. Code</th>
                                                    <th>Empl. Name</th>
                                                    <th>Date</th>
                                                    <th>Sch. In</th>
                                                    <th>Sch. Out</th>
                                                    <th>Clock In</th>
                                                    <th>Clock Out</th>
                                                    <th>Dept.</th>
                                                    <th>Status</th>
                                                    <th>Lembur</th>
                                                    <th>JK.</th>
                                                    <th>Telat</th>
                                                    <th>Early Out</th>
                                                    <th>Ijin</th>
                                                    <th>Makan</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade" id="add-modal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog call-modal modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="main-form">
                    <input type="hidden" name="id" id="input-id">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6 col-sm-12"> 
                                <div class="mt-2"> 
                                    <label class="form-label" for="email">Nama</label>
                                    <select name="employee_code" id="input-employee_code" class="form-control form-control-sm"></select>
                                </div>
                                <div class="mt-2"> 
                                    <label class="form-label" for="email">Date</label>
                                    <input class="form-control form-control-sm" type="text" name="date" id="input-date" placeholder="Masukkan Tanggal masuk...">
                                </div>
                                <div class="mt-2"> 
                                    <label class="form-label" for="email">Status</label>
                                    <select name="type" id="input-type" class="form-control form-control-sm">
                                        <option value="HDR">HADIR</option>
                                        <option value="IZIN">IZIN</option>
                                        <option value="CUTI">CUTI</option>
                                        <option value="ALPA">ALPA</option>
                                        <option value="CTL">CTL</option>
                                        <option value="SAKIT">SAKIT</option>
                                        <option value="DNS">DINAS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12"> 
                                <div class="mt-2"> 
                                    <label class="form-label" for="email">Jadwal Masuk</label>
                                    <input class="form-control form-control-sm" type="text" name="scheduled_clock_in" id="input-scheduled_clock_in" placeholder="Masukkan jam jadwal masuk..." autocomplete="off">
                                </div>
                                <div class="mt-2"> 
                                    <label class="form-label" for="email">Jadwal Pulang</label>
                                    <input class="form-control form-control-sm" type="text" name="scheduled_clock_out" id="input-scheduled_clock_out" placeholder="Masukkan jam jadwal keluar..." autocomplete="off">
                                </div>

                                <div class="mt-2"> 
                                    <label class="form-label" for="email">Jam Masuk</label>
                                    <input class="form-control form-control-sm" type="text" name="clock_in" id="input-clock_in" placeholder="Masukkan jam jadwal masuk..." autocomplete="off">
                                </div>
                                <div class="mt-2"> 
                                    <label class="form-label" for="email">Jam Pulang</label>
                                    <input class="form-control form-control-sm" type="text" name="clock_out" id="input-clock_out" placeholder="Masukkan jam jadwal keluar..." autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="import-modal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog call-modal modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="import-form">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-12 col-sm-12"> 
                                <div class="mt-2"> 
                                    <label class="form-label" for="email">Upload</label>
                                    <div class="dropzone" id="input-files"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submit-import">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="download-modal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog call-modal modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-download_title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input type="hidden" id="print-download_type">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12 col-sm-12"> 
                            <div class="mt-2"> 
                                <label class="form-label" for="print-attendance_date">Pilih Tgl. Absensi</label>
                                <input id="print-attendance_date" class="form-control form-control-sm" value="{{date('Y-m-d')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="download-btn">Download</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="{{ asset('assets/vendor/js/dropzone.min.js') }}"></script>

<script type="text/javascript">
    // console.log(window.screen.height);
    // console.log(window.screen.width);
    let dt;
    let endpoint = 'attendances';
    let isNoClockOnlyChecked = false;
    
    let startDate = moment().startOf('month').format("YYYY-MM-DD");
    let endDate = moment().endOf("month").format("YYYY-MM-DD");

    drawDatatable();

    // Mendapatkan tahun saat ini
    let currentYear = moment().year();

    // Menambahkan rentang untuk setiap bulan
    let monthRanges = {};
    for (let i = 0; i < 12; i++) {
        let startOfMonth = moment().month(i).year(currentYear).startOf('month');
        let endOfMonth = moment().month(i).year(currentYear).endOf('month');
        let monthName = startOfMonth.format('MMMM');
        monthRanges[monthName + ' ' + currentYear] = [startOfMonth, endOfMonth];
    }

    // Menggabungkan rentang bulan dengan rentang lainnya
    let allRanges = $.extend({}, monthRanges, {

    });

    $(document).on('click', '#check-noclock', function(e) {
        isNoClockOnlyChecked = $(this).is(':checked');

        drawDatatable();
    })
    $('#daterange-filter').daterangepicker({
        ranges: allRanges,
        // {
        //     'Today': [moment(), moment()],
        //     'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        //     'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        //     'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        //     'This Month': [moment().startOf('month'), moment().endOf('month')],
        //     'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        // },
        opens:'left',
    }, function(start, end, label) {
        startDate = moment(start).format('YYYY-MM-DD');
        endDate = moment(end).format('YYYY-MM-DD');

        // if (dt) {
        //     dt.destroy();
        // }
        drawDatatable();
    });

    $(document).on("click", "button#add-button", function () {
        resetAllInputOnForm('#main-form');
        getEmployeeSearch('#input-employee_code', [], '', 'Please select an employee', $('#add-modal'), 'code');
        $('h5#modal-title').text('Add {{$title}}');
        $('#input-type').select2({
            placeholder:'Pilih Tipe',
            dropdownParent : $('#add-modal')
        });

        flatpickr('#input-date', {
            mode: "single",
            disableMobile: true,
            dateFormat: "Y-m-d"
        });
        $('#add-modal').modal('show');
    });

    flatpickr('#print-attendance_date', {
        mode: "single",
        disableMobile: true,
        dateFormat: "Y-m-d"
    });

    $(document).on('click', 'button#download-excel-button', function(e) {
        $('#modal-download_title').text('Download EXCEL');
        $('#print-download_type').val('excel');
        $('#download-modal').modal('show');
    });

    $(document).on('click', 'button#download-pdf-button', function(e) {
        $('#modal-download_title').text('Download PDF');
        $('#print-download_type').val('pdf');
        $('#download-modal').modal('show');
    });

    $(document).on('click', 'button#download-btn', function(e) {
        let downloadType = $('#print-download_type').val();
        let downloadDate = $('#print-attendance_date').val();

        e.preventDefault();

        showLoading('Harap Menunggu!', 'Sedang mendownload data, tunggu sampai hasil download selesai dan loading pada tab browser anda selesai!', 8000);

        window.location.href = `${BASE_URL}/download-all-attendance-per-day?date=${downloadDate}&type=${downloadType}`;

        $('#download-modal').modal('hide');
    });

    $(document).on("click", "button#import-button", function () {
        $('#import-modal').modal('show');
    });

    // $('#import-modal').on('shown.bs.modal', function (e) {
    //     $("#input-file").dropzone({ url: "/file/post" });
    // });

    Dropzone.options.inputFiles = { // The camelized version of the ID of the form element
        // The configuration we've talked about above
        autoProcessQueue: false,
        uploadMultiple: false,
        // parallelUploads: 5,
        // maxFiles: 5,
        addRemoveLinks: true,
        url: BASE_URL + "/api/attendance_references",
        headers: {
            'Authorization': TOKEN
        },
        paramName: 'files',
        dictResponseError: 'Error uploading file!',

        // The setting up of the dropzone
        init: function() {
            var myDropzone = this;

            // First change the button to actually tell Dropzone to process the queue.
            document.getElementById("submit-import").addEventListener("click", function(e) {
                // Make sure that the form isn't actually being sent.
                e.preventDefault();
                e.stopPropagation();
                myDropzone.processQueue();
            });

            this.on('addedfile', function(file) {
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
            });

            this.on('sending', function(file) {
                showLoading('Harap Menunggu!', 'Sedang mengunggah data');
            });
            // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
            // of the sending event because uploadMultiple is set to true.
            this.on("sendingmultiple", function(data, xhr, formData) {
                $(":input[name]", $("#import-form")).each(function () {
                     formData.append(this.name, $(':input[name=' + this.name + ']', $("#import-form")).val());
                 }); 
                // Gets triggered when the form is actually being sent.
                // Hide the success button or the complete form.
            });
            this.on("successmultiple", function(files, response) {
                console.log('successmultiple');
                console.log(response);
                // $('#import-modal').modal('hide');
                // Gets triggered when the files have successfully been sent.
                // Redirect user or notify of success.
            });
            this.on("errormultiple", function(files, response) {
                console.log('errormultiple');
                // Gets triggered when there was an error sending the files.
                // Maybe show form again, and notify user of error
            });

            this.on("success", function(files, response) {
                Swal.close();
                if (response.data) {
                    console.log(typeof response.data);
                    Swal.fire({
                        title: response.status.toUpperCase(),
                        html: (typeof response.data === 'object' && response.status != 'error' ? 'Sukses Import Data Absensi' : (typeof response.data === 'string') ? '<div class="text-start">'+response.data+'</div>' : '<div class="text-start">'+response.data.join('<br>')+'</div>'),
                        showConfirmButton: true,
                        confirmButtonColor: '#0760ef',
                        icon: response.status,
                        allowOutsideClick: false,
                        width: '600px'
                    }).then((result) => {
                        $('#import-modal').modal('hide');
                        $('#main-table').DataTable().ajax.reload(null, false);
                    });
                } else {
                    showAlertOnSubmit(response, '#import-modal', '#main-table');
                }
                // Gets triggered when there was an error sending the files.
                // Maybe show form again, and notify user of error
            });

            this.on("error", function(files, response) {
                Swal.close();

                Swal.fire({
                    title: response.message,
                    html: (typeof response.data === 'string') ? '<div class="text-start">'+response.data+'</div>' : '<div class="text-start">'+response.data.join('<br>')+'</div>',
                    showConfirmButton: true,
                    confirmButtonColor: '#0760ef',
                    icon: response.status,
                    allowOutsideClick: false,
                    width: '600px'
                });
                // Gets triggered when there was an error sending the files.
                // Maybe show form again, and notify user of error
            });
        }

    }
    flatpickr("#input-clock_in, #input-clock_out, #input-scheduled_clock_in, #input-scheduled_clock_out" ,flatPickrConfigOnlyTime);

    $(document).on("click", "a#edit-data", function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            url: BASE_URL + "/api/" + endpoint + "/" + id,
            type: 'GET',
            "headers": {
                'Authorization': TOKEN
            },
            dataType: 'JSON',
            beforeSend: function () {
                // $('#input-parent_id').empty();
                resetAllInputOnForm('#main-form');
                showLoading('Harap Menunggu!', 'Sedang mengambil data');
            },
            success: function (data, textStatus, jqXHR) {
                $('#input-id').val(data.id);

                $('#input-date').val(data.date);
                flatpickr('#input-date', {
                    mode: "single",
                    disableMobile: true,
                    dateFormat: "Y-m-d",
                    defaultValue: data.date,
                    clickOpens: false
                });
                $('#input-type').select2({
                    placeholder:'Pilih Tipe',
                    dropdownParent : $('#add-modal')
                });
                // $('#input-type').find('option').each(function() {
                //     $('#input-type').val(data.type).trigger('change');
                //     if ($(this).val() === data.type) {
                //         $(this).prop('selected', true);
                //     }
                // });
                
                $('#input-type').val(data.type == '' ? 'HDR' : data.type).trigger('change');
                $('#input-clock_in').val(data.clock_in);
                flatPickrConfigOnlyTime['defaultDate'] = data.clock_in;
                flatpickr('#input-clock_in', flatPickrConfigOnlyTime);
                $('#input-clock_out').val(data.clock_out);
                flatPickrConfigOnlyTime['defaultDate'] = data.check_out;
                flatpickr('#input-clock_out', flatPickrConfigOnlyTime);

                $('#input-scheduled_clock_in').val(data.scheduled_clock_in);
                flatPickrConfigOnlyTime['defaultDate'] = data.scheduled_clock_in;
                flatpickr('#input-scheduled_clock_in', flatPickrConfigOnlyTime);
                $('#input-scheduled_clock_out').val(data.scheduled_clock_out);
                flatPickrConfigOnlyTime['defaultDate'] = data.check_out;
                flatpickr('#input-scheduled_clock_out', flatPickrConfigOnlyTime);
                // flatpickr('#input-check_out', flatPickrConfigOnlyTime);
                // $('#input-late_tolerance').val(data.late_tolerance);
                // flatPickrConfigOnlyTime['defaultDate'] = data.late_tolerance;
                // flatpickr('#input-late_tolerance', flatPickrConfigOnlyTime);
                getEmployeeSearch('#input-employee_code', {
                    'id': data.employee_code,
                    'text': data.employee_name
                }, '', 'Please select an employee', $('#add-modal'), 'code');
                $('#modal-title').text('Edit {{$title}}');
                $('#add-modal').modal('show');
                Swal.close();
            },
            error: function (jqXHR, textStatus, errorThrown) {

            },
        });
    });

    $(document).on('click', 'a#delete-data', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        
        showPopupWithAction(
            'Apakah Anda Yakin ?', 
            'Menghapus data ini ?',
            'warning',
            'DELETE',
            null,
            BASE_URL+'/api/'+endpoint+'/'+id,
            '',
            ['#main-table']
        );
    });

    function drawDatatable() {
        dt = $("#main-table").DataTable({
            dom: 'ZBfrltip',
            buttons: [
                { 
                    extend: "excel", 
                    className: "btn btn-sm btn-primary border-0", 
                    text: '<i class="fa-solid fa-file-excel"></i> Excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                { 
                    extend: "pdf", 
                    className: "btn btn-sm btn-primary border-0", 
                    text: '<i class="fa-solid fa-file-pdf"></i> PDF',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                $.extend( true, {}, buttonExportInput, {
                    extend: "print", 
                    className: "btn btn-sm btn-primary border-0",  
                    text: '<i class="fa-solid fa-print"></i> Print' ,
                    orientation: "portrait",
                    customize: function (win) {
                        $(win.document.body)
                            .find('table')
                            .removeClass('table-dashed')
                            .removeClass('table-hover')
                            .removeClass('digi-dataTable')
                            .removeClass('table-striped');

                        $(win.document.body)
                            .find('table')
                            .addClass('export-print');

                        $(win.document.body)
                            .find('h1')
                            .addClass('title-print');
                    }
                }),
            ],
            "destroy": true,
            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "searchDelay": 750,
            "ajax": {
                "url": BASE_URL + "/api/" + endpoint + "_datatables",
                "headers": {
                    'Authorization': TOKEN
                },
                "dataType": "json",
                "type": "POST",
                "data": function (d) {
                    d.filter = {
                        'start_date': startDate,
                        'end_date': endDate,
                        'is_no_clock_only': isNoClockOnlyChecked
                    }
                },
            },
            "columns": [
                {
                    data: 'id', 
                    name: 'id', 
                    width: '5%', 
                    visible: false
                },
                {
                    data: 'employee_code', 
                    name: 'employee_code'
                },
                {
                    data: 'employee_name', 
                    name: 'employee_name'
                },
                {
                    data: 'date', 
                    name: 'date',
                    className: 'text-center',
                    render: function(data, type, row) {
                        return '<span class="badge bg-primary">'+moment(data).format('dddd')+'</span><br>'+moment(data).format('DD MMM YYYY');
                    }
                },
                {
                    data: 'scheduled_clock_in', 
                    name: 'scheduled_clock_in'
                },
                {
                    data: 'scheduled_clock_out', 
                    name: 'scheduled_clock_out'
                },
                {
                    data: 'clock_in', 
                    name: 'clock_in'
                },
                {
                    data: 'clock_out', 
                    name: 'clock_out'
                },
                {
                    data: 'department_code', 
                    name: 'department_code'
                },
                {
                    data: 'type',
                    name: 'type',
                    render: function (data, type, row, meta) {
                        return (data === 'SAKIT') ? '<span class="badge bg-primary">'+data+'</span>' : (data === 'CTL' || data === 'CUTI' ) ? '<span class="badge bg-warning">'+data+'</span>' : (data === 'IZIN' || data === 'DNS' ) ? '<span class="badge bg-success">'+data+'</span>' : (data === 'ALPA' ) ? '<span class="badge bg-danger">'+data+'</span>': '' ;
                    }
                },
                {
                    data: 'overtime',
                    name: 'overtime',
                    orderable: false,
                },
                {
                    data: 'total_jam',
                    name: 'total_jam',
                    orderable: false,
                },
                {
                    data: 'late_clock_in', 
                    name: 'late_clock_in'
                },
                {
                    data: 'early_clock_out', 
                    name: 'early_clock_out'
                },
                {
                    data: 'permit',
                    name: 'permit',
                    render: function (data, type, row, meta) {
                        return formatDecimal(data);
                    },
                    orderable: false,
                },
                {
                    data: 'meal',
                    name: 'meal',
                    orderable: false,
                },
                {
                    data: 'action', 
                    name: 'action', 
                    className: 'text-end'
                }
            ],
            "order": [0, 'desc'],
            initComplete: function(settings, json) {
                let html = '';

                $("#main-table th").each(function(index) {
                    html += '<li>';
                    html += '    <div class="form-check">';
                    html += '        <input class="form-check-input main-toggle-vis" type="checkbox" id="main-table_'+(index+1)+'" data-column="'+(index+1)+'" checked>';
                    html += '        <label class="form-check-label" for="main-table_'+(index+1)+'">';
                    html += $(this).text();
                    html += '        </label>';
                    html += '    </div>';
                    html += '</li>';
                });

                $('#main-table_colvis').html(html);

                $('input.main-toggle-vis').each(function() {
                    $(this).on('click', function (e) {
                        
                        let columnIdx = $(this).data('column');
                        let column = dt.column(columnIdx);
                 
                        // Toggle the visibility
                        column.visible(!column.visible());
                    });
                });
            },
            "createdRow": function(row, data, dataIndex) {
                if ((data['type'] == '' || data['type'] == 'True') && (!data['clock_in'] || !data['clock_out'])) {
                    $(row).removeClass('odd');
                    $(row).removeClass('even');
                    $(row).css("background-color", "red");
                    $(row).find('td').css("color", "white");
                }
                // if (debtStatus == "unpaid" && dateNow > dueDate) {
                //     $(row).find('.material-icons').css("color", "white");
                //     $(row).find('.bg-inverse-warning').css("cssText", "color: white !important; background-color: rgba(80, 0, 0,0.6) !important; font-size: 13px !important;");
                // }
            },
        });
        $('#main-table_filter input').addClass('form-control').attr("placeholder", "Search...").addClass('form-control-sm');
        $('#main-table_length').prependTo('#attendanceTableLength').find('select').addClass('form-control form-control-sm px-3');
        // $('#main-table_filter').prependTo('#tableSearch');
        $('.dt-buttons').prependTo('#main-table_btn');
    }

    $('form#main-form').submit(function (e) {
        e.preventDefault();
        var form_data = new FormData(this);
        console.log($('#input-employee_code').val());
        $.ajax({
            type: 'post',
            url: BASE_URL + "/api/" + endpoint,
            "headers": {
                'Authorization': TOKEN
            },
            data: form_data,
            cache: false,   
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function () {
                showLoading('Harap Menunggu!', 'Sedang mengambil data');
            },
            success: function (res) {
                Swal.close();
                showAlertOnSubmit(res, '#add-modal', '#main-table');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                let errorRes = jqXHR.responseJSON;
                Swal.fire({
                    title: errorRes.message,
                    html: (errorRes.data ? ((typeof errorRes.data === 'string') ? '<div class="text-start">'+errorRes.data+'</div>' : '<div class="text-start">'+errorRes.data.join('<br>')) : '')+'</div>',
                    showConfirmButton: true,
                    confirmButtonColor: '#0760ef',
                    icon: errorRes.status,
                    allowOutsideClick: false,
                    width: '600px'
                });
            },
        })
    });

    function formatDecimal(data) {
      // Cek apakah data berisi desimal
      if (data % 1 !== 0) {
        // Jika desimal, atur angka 0 di belakang koma menjadi 4
        return parseFloat(data).toFixed(4).toLocaleString('en');
      } else {
        // Jika tidak desimal, hapus koma belakang
        return parseFloat(data).toLocaleString('en', { maximumFractionDigits: 0 });
      }
    }
</script>
@endsection

