<?php

namespace App\Http\Controllers\Newest\Student;

use App\Http\Controllers\Controller;
use App\Models\AvtFundingType;
use App\Models\AvtStateIdentifier;
use App\Models\CompletionStudentCourse;
use App\Models\Course;
use App\Models\Unit;
use App\Models\CourseMatrix;
use App\Models\CourseProspectus;
use App\Models\FundedCourseMatrices;
use App\Models\FundedStudentCourse;
use App\Models\FundedStudentCourseDetail;
use App\Models\StudentCompletion;
use App\Models\StudentCompletionDetail;
use App\Models\TrainingDeliveryLoc;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if(!isset($request->id)) {
            $errors = [];

            // check if Course is already exist
            $chkfsc = FundedStudentCourse::where('course_code', $request->course['course_code'])->where('student_id', $request->course['student_id'])->first();
            if($request->course['course_code'] != '@@@@' && $chkfsc){
                return ['status' => 'error', 'errors' => [], 'msg' => 'Course already existed in the student\'s profile'];
            }

            // check for available units
            if(count($request->units) < 1) {
                return ['status' => 'error', 'errors' => [], 'msg' => 'No avaiable units'];
            }
    
            $attributes = [
                'course_code' => 'Course Code',
                'course_fee' => 'Course Fee',
                'eligibility' => 'Eligibility',
                'location' => 'Location',
                'course_fee_type' => 'Course Fee Type',
                'shore_type' => 'Shore Type',
                'start_date' => 'Start Date',
                'end_date' => 'End Date',
            ];
    
            $validate = Validator::make($request->course, [
                'course_code' => 'required',
                'course_fee' => 'required|numeric',
                'eligibility' => 'required',
                'location' => 'required',
                'course_fee_type' => 'required',
                'shore_type' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ], [], $attributes);
    
            if ($validate->fails()) {
                return response()->json(['status' => 'errors', 'errors' => $validate->messages(), 'msg' => 'Please fill-in required fields']);
            }
        }

        try {
            DB::beginTransaction();

            if(isset($request->id)) {
                // update
                $c = FundedStudentCourse::with(['detail'])->where('id', $request->id)->first();
                $c->fill($request->all());
                $c->start_date = $request->start_date ? Carbon::parse($request->start_date)->setTimezone('Australia/Melbourne')->format('Y-m-d') : null;
                $c->end_date = $request->end_date ? Carbon::parse($request->end_date)->setTimezone('Australia/Melbourne')->format('Y-m-d') : null;
                $c->aiss_date = $request->aiss_date ? Carbon::parse($request->aiss_date)->setTimezone('Australia/Melbourne')->format('Y-m-d') : null;
                $c->update();
                
                $c->detail->fill($request->detail);
                $c->detail->update();
                // dd($c);
            } else {
                // create
                // FundedStudentCourse
                $fsc = new FundedStudentCourse;
                $fsc->fill($request->course);
                $fsc->start_date = $request->course['start_date'] ? Carbon::parse($request->course['start_date'])->setTimezone('Australia/Melbourne')->format('Y-m-d') : null;
                $fsc->end_date = $request->course['end_date'] ? Carbon::parse($request->course['end_date'])->setTimezone('Australia/Melbourne')->format('Y-m-d') : null;
                $fsc->status_id = 1;
                $fsc->user()->associate(Auth::user());
                $fsc->save();

                // FundedStudentCourseDetial
                $fscd = new FundedStudentCourseDetail;
                $fscd->funded_student_course()->associate($fsc);
                $fscd->save();

                // StudentCompletion
                $sc = new StudentCompletion;
                $sc->student_id = $request->course['student_id'];
                $sc->course_code = $request->course['course_code'];
                $sc->user()->associate(Auth::user());
                $sc->save();

                // StudentCompletionDetail
                foreach ($request->units as $k => $v) {
                    $scd = new StudentCompletionDetail;
                    $scd->course_unit_code = $v['code'];
                    $scd->start_date = $request->course['start_date'] ? Carbon::parse($request->course['start_date'])->setTimezone('Australia/Melbourne')->format('Y-m-d') : null;
                    $scd->end_date = $request->course['end_date'] ? Carbon::parse($request->course['end_date'])->setTimezone('Australia/Melbourne')->format('Y-m-d') : null;
                    $scd->extra_unit = $v['extra_unit'];
                    $scd->extra_unit = $v['scheduled_hours'] ? $v['scheduled_hours'] : $v['nominal_hours'];
                    $scd->completion()->associate($sc);
                    $scd->save();
                }

                // CompletionStudentCourse (for the sake of the old student profile UI structure)
                $csc = new CompletionStudentCourse;
                $csc->funded_course()->associate($fsc);
                $csc->completion()->associate($sc);
                $csc->student_type = 2; // 2 will be used even if its for domestic or international students
                $csc->save();
            }

            DB::commit();

            return ['status' => 'updated'];

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $courses = FundedStudentCourse::with(['detail.funding_type_detail', 'course.agent_commission_setting_mains.agent_detail', 'course_completion', 'payment_details', 'status', ])->where('student_id', $id)->get();
        // dd($courses);
        foreach ($courses as $key => $value) {
            $prosp = CourseProspectus::where('course_code', $value->course_code)->pluck('location');
            $states = [];
            if($prosp) {
                $states = AvtStateIdentifier::whereIn('state_key', explode(',', implode(',',$prosp->toArray())))->get();
            }
            $courses[$key]->states = $states;

            // get delivery locations based on state
            $s = AvtStateIdentifier::where('state_key', $value->location)->first();
            $delivery_locations = TrainingDeliveryLoc::where('state_id', $s->id)->get();
            $courses[$key]->delivery_locations = $delivery_locations;

            // get course completion, course issuance
            $sc = StudentCompletion::with(['details.unit', 'certificate.details'])->where('student_id', $id)->where('course_code', $value->course_code)->first();
            $courses[$key]->cc = $sc;
        };
        
        return $courses;
    }

    public function fetch_funding($location) 
    {
        $funding = AvtFundingType::where('state_key', $location)->get();
        return $funding;
    }

    public function fetch_all_courses()
    {
        $courses = Course::where('is_active', 1)->get();

        foreach ($courses as $key => $value) {
            $prosp = CourseProspectus::where('course_code', $value->code)->pluck('location');
            $states = [];
            if($prosp) {
                $states = AvtStateIdentifier::whereIn('state_key', explode(',', implode(',',$prosp->toArray())))->get();
            }
            $courses[$key]->states = $states;
        };

        $extra_units = Unit::where('extra_unit', 1)->where('active', 1)->get();

        return ['course' => $courses, 'extra_units' => $extra_units];
    }

    public function fetch_course_units(Request $request)
    {
        $units = [];

        // get units
        $prospectus = CourseProspectus::where('course_code', $request->course['course_code'])->where('location', 'like', '%'.$request->course['location'].'%')->first();
        
        if($prospectus) {
            $units = $prospectus->unit_selected;
        }

        return ['units'=>$units, 'prospectus'=>$prospectus];
    }

    public function fetch_course_fee(Request $request)
    {
        $fee = 0;

        // get fee
        if($request->student_type == 1) {
            // international
            $matrix = CourseMatrix::where('course_code', $request->course['course_code'])->where('location', $request->course['location'])->first();
            if($matrix) {
                $fee = $request->course['shore_type'] == 'ONSHORE' ? $matrix->onshore_tuition_individual : $matrix->offshore_tuition_individual;
            }
        } else {
            // domestic
            $matrix = FundedCourseMatrices::where('course_code', $request->course['course_code'])->where('location', $request->course['location'])->first();
            if($matrix) {
                switch ($request->course['course_fee_type']) {
                    case 'FF':
                        // full fee
                        $fee = $matrix->full_fee;
                        break;
                    case 'C':
                        // full fee
                        $fee = $matrix->concessional_fee;
                        break;
                    case 'NC':
                        // full fee
                        $fee = $matrix->non_concessional_fee;
                        break;
                }
            }
        }

        return ['fee' => $fee];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
