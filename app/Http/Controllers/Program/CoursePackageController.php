<?php

namespace App\Http\Controllers\Program;

use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use App\Models\AvtStateIdentifier;
use Illuminate\Http\Request;

use App\Models\CoursePackage;
use App\Models\CoursePackageDetail;
use App\Models\CourseMatrix;
use App\Models\TrainingDeliveryLoc;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class CoursePackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $dlvr_location = TrainingDeliveryLoc::select('id', 'train_org_dlvr_loc_name')->orderBy('train_org_dlvr_loc_name', 'asc')->get();
        \JavaScript::put([
            'dlvr_location' => $dlvr_location,
        ]);

        return view('program.course-package.index');
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
    {   //
        $this->validate($request, [
            'name' => 'required',
            // 'shore_type' => 'required',
            // 'location' => 'required',
        ]);

        $package = new CoursePackage();
        $data = [
            'date_created' => Carbon::parse($request->date_created)->format('Y-m-d'),
            'shore_type' => $request->shore_type,
            'location' => $request->location,
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->is_active ? 1 : 0,
        ];
        $package->fill($data);
        $package->user()->associate(\Auth::user());
        $package->save();
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
        $coursePackage = CoursePackage::find($id);
        $packageDetails = CoursePackageDetail::with(['course.detail'])->where('course_package_id', $coursePackage->id)->orderBy('order')->get();
        $courseMatrix = CourseMatrix::with('detail')->select(['id', 'course_code', 'location'])->get();
        $dlvr_location = TrainingDeliveryLoc::select('id', 'train_org_dlvr_loc_name')->orderBy('train_org_dlvr_loc_name', 'asc')->get();
        $state = AvtStateIdentifier::all();
        // dd($courseMatrix);
        \JavaScript::put([
            'course_package' => $coursePackage,
            'package_details' => $packageDetails,
            'course_matrix' => $courseMatrix,
            'dlvr_location' => $dlvr_location,
            'state' => $state,
        ]);

        return view('program.course-package.show');
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
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'shore_type' => 'required',
            // 'location' => 'required',
            // 'delivery_location_id' => 'required',
        ]);

        $package = CoursePackage::find($id);
        $package->date_created = Carbon::parse($request->date_created)->format('Y-m-d');
        // $package->shore_type = $request->shore_type;
        // $package->location = $request->location;
        // $package->delivery_location_id = $request->delivery_location_id;
        $package->name = $request->name;
        $package->description = $request->description;
        $package->is_active = $request->is_active ? 1 : 0;
        $package->update();
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
        $getCoursePackage = CoursePackage::find($id);
        $getCoursePackage->delete();
    }

    public function course_package_list()
    {
        if (\Auth::user()->hasRole('Demo')) {
            return CoursePackage::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();
        } else {
            return CoursePackage::orderBy('id', 'DESC')->get();
        }
    }

    public function store_course_package_detail(Request $request)
    {
        // dd($request->all());

        // check if already exist
        $check = CoursePackageDetail::where('course_matrix_id', $request->course_matrix_id)->where('course_package_id', $request->course_package_id)->first();
        if($check) {
            return ['status'=>'error', 'msg' => 'Course already exist in the package'];
        }
        // check course packages count 
        $coursePackage = CoursePackage::where('id', $request->course_package_id)->get()->count();

        // Create new course package detail
        $cpd = new CoursePackageDetail;
        $cpd->fill($request->all());
        $cpd->course_matrix_id = $request->course_matrix_id;
        $cpd->course_package_id = $request->course_package_id;
        $cpd->order = $coursePackage + 1;
        $cpd->save();

        return ['status' => 'success', 'pckg' => CoursePackageDetail::with(['course.detail', 'package'])->where('id', $cpd->id)->first()];
    }

    public function change_order_package_detail(Request $request)
    {
        foreach($request->all() as $key => $v) {
            $cpd = CoursePackageDetail::find($v['id']);
            if($cpd) {
                $cpd->order = $key + 1;
                $cpd->update();
            }
        }
        return ['status' => 'success'];
    }

    public function course_package_store_update(Request $request)
    {

        $package_id = $request->course_package_id;
        if (!isset($request->is_update)) {
            $this->validate($request, [
                // 'course_start_date' => 'required',
                // 'course_end_date' => 'required',
                'course_matrix_id' => [
                    'required',
                    Rule::unique('course_package_details')->where(function ($query) use ($package_id) {
                        $query->where('course_package_id', $package_id);
                    })
                ],
                'order' => [
                    'required',
                    Rule::unique('course_package_details')->where(function ($query) use ($package_id) {
                        $query->where('course_package_id', $package_id);
                    })
                ]
            ]);
        } else {
            $this->validate($request, [
                // 'course_matrix_id' => 'required',
                // 'course_start_date' => 'required',
                'course_end_date' => 'required',
                'order' => [
                    'required',
                ]
            ]);
        }

        CoursePackageDetail::updateOrCreate(
            [
                'course_matrix_id' => $request->course_matrix_id,
            ],
            [
                'course_package_id' => $package_id,
                'order' => $request->order,
                'course_matrix_id' => $request->course_matrix_id,
                // 'course_start_date' => Carbon::parse($request->course_start_date)->format('Y-m-d'),
                // 'course_end_date' => Carbon::parse($request->course_end_date)->format('Y-m-d'),
                // ''
            ]
        );
    }

    public function course_package_details_destroy($id)
    {
        $getPackageDetails = CoursePackageDetail::find($id);
        $getPackageDetails->delete();
    }

    public function update_package_detail(Request $request)
    {   
        $validate = Validator::make($request->all(), [
            'wk_duration' => 'required|numeric',
            'tuition_fee' => 'required|numeric',
            'application_fee' => 'required|numeric',
            'material_fee' => 'required|numeric',
        ]);

        if($validate->fails()) {
            return response()->json(['status' => 'errors', 'errors' => $validate->messages()]);
        }

        $pd = CoursePackageDetail::find($request->id);
        $pd->fill($request->all());
        $pd->update();
        return ['status' => 'success'];
    }
}
