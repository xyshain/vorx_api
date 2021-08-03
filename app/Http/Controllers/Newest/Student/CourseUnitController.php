<?php

namespace App\Http\Controllers\Newest\Student;

use App\Http\Controllers\Controller;
use App\Models\FundedStudentCourse;
use App\Models\StudentCompletion;
use App\Models\StudentCompletionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseUnitController extends Controller
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
        //
        // dd($request->all());
        
        /* 
            StudentCompletion
            StudentCompletionDetail
            AvtCompletionStatus

            -> Check outcome for completed course
        */

        $completed = null;
        $last_date = null;

        try {

            DB::beginTransaction();

            if(isset($request->id)) {
                // update
                $sc = StudentCompletion::find($request->id);
                $isPartial = 0;
                $isCompleted = 1;
                $completionDate = null;
                foreach ($request->details as $v) {
                    $scd = StudentCompletionDetail::find($v['id']);
                    $scd->fill($v);
                    
                    $scd->start_date = $v['start_date'] ? Carbon::parse($v['start_date'])->setTimezone('Australia/Melbourne')->format('Y-m-d') : null;
                    $scd->end_date = $v['end_date'] ? Carbon::parse($v['end_date'])->setTimezone('Australia/Melbourne')->format('Y-m-d') : null;
                    
                    $scd->actual_start = $v['actual_start'] ? Carbon::parse($v['actual_start'])->setTimezone('Australia/Melbourne')->format('Y-m-d') : null;
                    $scd->actual_end = $v['actual_end'] ? Carbon::parse($v['actual_end'])->setTimezone('Australia/Melbourne')->format('Y-m-d') : null;
                    
                    if($completionDate == null) {
                        $completionDate = $scd->actual_end;
                    }

                    if($completionDate < $scd->actual_end) {
                        $completionDate = $scd->actual_end;
                    }

                    if(in_array($scd->completion_status_id, [3,4,6])) {
                        $isPartial = 1;
                    }
                    if(!in_array($scd->completion_status_id, [3,4,6])) {
                        $isCompleted = 0;
                    }
                    
                    // dump($scd);
                    $scd->update();
                }

                // update partial completion
                $sc->partial_completion = $isPartial;
                
                if($isCompleted == 1) {
                    // update student course completion
                    $sc->completion_status_id = 3;
                    $sc->completion_date = $completionDate;

                    // update student status
                    $fsc = FundedStudentCourse::where('course_code', $sc->course_code)->where('student_id', $sc->student_id)->first();
                    $fsc->status_id = 4;
                    $fsc->update();
                }else {
                    // update student course completion
                    $sc->completion_status_id = 5;
                    $sc->completion_date = null;

                    // update student status
                    $fsc = FundedStudentCourse::where('course_code', $sc->course_code)->where('student_id', $sc->student_id)->first();
                    if($sc->student->student_type_id == 1) {
                        $fsc->status_id = 2;
                    }else {
                        $fsc->status_id = 3;
                    }
                    $fsc->update();
                }

                $sc->update();
                
                DB::commit();
                return ['status' => 'updated', 'sc' => $sc, 'scd' => $sc->details];

            }

            
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
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
        //
    }

    public function fetch_units(Request $request)
    {
        $courseUnit = StudentCompletion::with(['details.unit','details.status','details.training_delivery_location','details.delivery_mode', 'certificate.details'])->where('student_id', $request->student_id)->where('course_code', $request->course_code)->first();
        
        // dd($this->convertPHPToMomentFormat('Y-m-d'));
        // foreach ($courseUnit as $v) {
        //     foreach ($v->details as $vv) {
        //         dump($this->convertPHPToMomentFormat('Y-m-d'));
        //     }
        // }

        return $courseUnit; 
    }

    public function convertPHPToMomentFormat($format)
    {
        $replacements = [
            'd' => 'DD',
            'D' => 'ddd',
            'j' => 'D',
            'l' => 'dddd',
            'N' => 'E',
            'S' => 'o',
            'w' => 'e',
            'z' => 'DDD',
            'W' => 'W',
            'F' => 'MMMM',
            'm' => 'MM',
            'M' => 'MMM',
            'n' => 'M',
            't' => '', // no equivalent
            'L' => '', // no equivalent
            'o' => 'YYYY',
            'Y' => 'YYYY',
            'y' => 'YY',
            'a' => 'a',
            'A' => 'A',
            'B' => '', // no equivalent
            'g' => 'h',
            'G' => 'H',
            'h' => 'hh',
            'H' => 'HH',
            'i' => 'mm',
            's' => 'ss',
            'u' => 'SSS',
            'e' => 'zz', // deprecated since version 1.6.0 of moment.js
            'I' => '', // no equivalent
            'O' => '', // no equivalent
            'P' => '', // no equivalent
            'T' => '', // no equivalent
            'Z' => '', // no equivalent
            'c' => '', // no equivalent
            'r' => '', // no equivalent
            'U' => 'X',
        ];
        $momentFormat = strtr($format, $replacements);
        return $momentFormat;
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
