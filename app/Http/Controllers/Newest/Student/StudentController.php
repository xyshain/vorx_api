<?php

namespace App\Http\Controllers\Newest\Student;

use App\Http\Controllers\Controller;
use App\Models\AvtClientIndigenousStatus;
use App\Models\AvtCommencingCourse;
use App\Models\AvtCompletionStatus;
use App\Models\AvtCountryIdentifier;
use App\Models\AvtDeliveryMode;
use App\Models\AvtDisabilityTypes;
use App\Models\AvtFundingSourceNational;
use App\Models\AvtFundingSourceState;
use App\Models\AvtFundingType;
use App\Models\AvtHighestSchlLvlCompleted;
use App\Models\AvtLabourForceStatus;
use App\Models\AvtLangIdentifier;
use App\Models\AvtOutcomeStatus;
use App\Models\AvtPostcode;
use App\Models\AvtPredominantDlvrMode;
use App\Models\AvtPriorEducationAchievement;
use App\Models\AvtSpecificFunding;
use App\Models\AvtStateIdentifier;
use App\Models\AvtStudyReason;
use App\Models\AvtSurveyContactStatus;
use App\Models\EnglishTest;
use App\Models\FundedStudentDetails;
use App\Models\OfferLetterDiscount;
use App\Models\OfferLetterStatus;
use App\Models\Student\Student;
use App\Models\TrainingOrganisation;
use App\Models\VisaStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // student
        $student = Student::with(['party.person', 'english_test', 'contact_detail.state', 'visa_details', 'funded_detail.country'])->where('student_id', $id)->first();
        $student_details = FundedStudentDetails::with('contact', 'course', 'country')->where('student_id', $student->student_id)->first();
        if ($student_details == null) {
            $student_details = new FundedStudentDetails;
            $student_details->fill(['student_id', $student->student_id]);
            $student_details->save();
        } else {
            $student_details->prior_educational_achievement_ids = $student_details->prior_education_value;
            $student_details->disability_ids = $student_details->disability_value;
        }

        
        // post suburb state
        $postcode = AvtPostcode::with('state_identifier')->orderBy('suburb')->get();
        $arr_postcode = [];
        foreach ($postcode as $key => $value) {
            $arr_postcode[] = [
                'id' => $value->id,
                'value' => $value->postcode . ' ' . '-' . ' ' . $value->suburb . ',' . ' ' . $value->state,
                'state' => $value->state_identifier,
            ];
        }
        // visa
        $slct_visa_type = VisaStatus::select(DB::raw('id, IF(id=1, "Not Applicable", concat(type, " - ", visa)) AS value'))->orderBy('value', 'asc')->get();

        // ENROLMENT DETAILS 
        $slct_student_status = OfferLetterStatus::all()->pluck('description', 'id');
        $slct_com_course = AvtCommencingCourse::pluck('description', 'value');
        $slct_funding_source_national = AvtFundingSourceNational::pluck('description', 'value');
        $slct_funding_source_state = AvtFundingSourceState::pluck('description', 'value');
        $slct_funding_source_statecomplete = AvtFundingSourceState::all();
        $slct_outcome_status = AvtCompletionStatus::pluck('status', 'id');
        $slct_predom_dlvr_mode = AvtPredominantDlvrMode::pluck('description', 'value');
        $slct_dlvr_mode = AvtDeliveryMode::pluck('description', 'value');
        $slct_specific_fund = AvtSpecificFunding::pluck('description', 'identifier');
        $slct_study_reason = AvtStudyReason::pluck('description', 'value');
        $slc_funding_type = AvtFundingType::where('state_key', $student_details->location)->get();
        // MULTISELECT
        $slct_country = AvtCountryIdentifier::orderBy('full_name', 'asc')->get();
        $slct_disability = AvtDisabilityTypes::all();
        $slct_educ_achievement = AvtPriorEducationAchievement::all();
        $slct_state = AvtStateIdentifier::orderBy('state_name')->pluck('state_name', 'state_key');
        $slct_schl_lvl_completed = AvtHighestSchlLvlCompleted::pluck('description', 'value');
        $slct_indigenous_status = AvtClientIndigenousStatus::pluck('description', 'value');
        $slct_labour_force_status = AvtLabourForceStatus::pluck('status', 'value');
        $slct_lang_identifer = AvtLangIdentifier::orderBy('description', 'asc')->pluck('value', 'description');
        $slct_survey_contact_status = AvtSurveyContactStatus::pluck('description', 'value');
        $offer_discount = OfferLetterDiscount::all();
        $slct_state = AvtStateIdentifier::orderBy('state_name')->pluck('state_name', 'state_key');
        $arr_country_val = [];
        $arr_disability = [];
        $arr_disability_val = [];
        $arr_pea_val = [];
        $arr_country = [];

        foreach ($slct_disability as $key => $value) {
            $arr_disability[] = [
                'id'          => $value->value,
                'value'       => $value->description
            ];
        }
        // foreach ($slct_country as $key => $value) {
        //     $arr_country[] = [
        //         'id'          => $value->identifier,
        //         'value'       => $value->full_name
        //     ];
        // }
        foreach ($slct_educ_achievement as $key => $value) {
            $arr_educ_achievement[] = [
                'id'          => $value->value,
                'value'       => $value->description
            ];
        }

        // if ($student_details->disability_ids != null) {
        //     $disability = null;

        //     if ($student_details->disability_ids != '') {
        //         $disability = explode(',', $student_details->disability_ids);
        //     }

        //     if ($disability !== null) {
        //         foreach ($disability as $key => $dis) {
        //             $dis = AvtDisabilityTypes::where('value', $dis)->first();
        //             $arr_disability_val[] = [
        //                 'id'        => $dis['value'],
        //                 'value'     => $dis['description']
        //             ];
        //         }
        //     }
        // }
        // prior_educational_achievement_ids
        // if ($student_details->prior_educational_achievement_ids != null) {
        //     $pea = null;
        //     if ($student_details->prior_educational_achievement_ids != '') {
        //         $pea = explode(',', $student_details->prior_educational_achievement_ids);
        //     }
        //     if ($pea !== null) {

        //         foreach ($pea as $key => $p) {
        //             $p = AvtPriorEducationAchievement::where('value', $p)->first();
        //             $arr_pea_val[] = [
        //                 'id'        => $p['value'],
        //                 'value'     => $p['description']
        //             ];
        //         }
        //     }
        // }
        // country_id
        
        if ($student_details->country_id != null) {
            $c = AvtCountryIdentifier::where('identifier', $student_details->country_id)->first();
            $arr_country_val = [
                'id'        => $c['identifier'],
                'value'     => $c['full_name']
            ];
        }
      
        \JavaScript::put([
            'student' => $student,
            'student_details' => $student_details,
            'english_tests' => EnglishTest::all(),
            'addr_suburbs' => $arr_postcode,
            'visa_types' => $slct_visa_type,
            'app_settings' => TrainingOrganisation::first(),
            // Enrolment
            'slct_educ_achievement' => $arr_educ_achievement,
            'slct_disability' => $arr_disability,
            'slct_country' => $slct_country,
            'slc_funding_type' => $slc_funding_type,
            'slct_state' => $slct_state,
            'slct_schl_lvl_completed' => $slct_schl_lvl_completed,
            'slct_indigenous_status' => $slct_indigenous_status,
            'slct_labour_force_status' => $slct_labour_force_status,
            'slct_lang_identifer' => $slct_lang_identifer->toArray(),
            'slct_survey_contact_status' => $slct_survey_contact_status,
            // Course Enrolment
            'slct_study_reason' => $slct_study_reason,
            'slct_student_status' => $slct_student_status,
            'slct_predom_dlvr_mode' => $slct_predom_dlvr_mode,
            'slct_funding_source_state' => $slct_funding_source_state,
            'slct_funding_source_national' => $slct_funding_source_national,
            'slct_com_course' => $slct_com_course,
            'slct_specific_fund' => $slct_specific_fund,
            'slct_dlvr_mode' => $slct_dlvr_mode,
            'slct_outcome_status' => $slct_outcome_status,
            // 'report_course_statuses' => ReportCourseStatuses::all(),
            // 'student_type' => $student->student_type_id,
            // 'englishData' => $student->english_test,
            // 'slct_visa_type' => $slct_visa_type,
            // 'slct_funding_source_statecomplete' => $slct_funding_source_statecomplete,
        ]);

        return view('new.student.show');
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
