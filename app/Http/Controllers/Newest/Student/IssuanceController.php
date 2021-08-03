<?php

namespace App\Http\Controllers\Newest\Student;

use App\Http\Controllers\Controller;
use App\Models\AvtCompletionStatus;
use App\Models\CertificateIssuanceDetail;
use App\Models\StudentCertificateIssuance;
use App\Models\TrainingOrganisation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IssuanceController extends Controller
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

    public function fetchIssuance(Request $request)
    {
        // dd($request->all());
        // $sci = StudentCertificateIssuance::where('student_id', $request->student_id)->where('')
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {

            DB::beginTransaction();

            if(isset($request->id)) {
                // update
                $cid = CertificateIssuanceDetail::with('certificte_issuance')->where('id', $request->id)->first();
                $cid->release_date = $request->release_date ? Carbon::parse($request->release_date)->setTimezone('Australia/Melbourne')->format('Y-m-d') : null;
                $cid->reissued_date = $request->reissued_date ? Carbon::parse($request->reissued_date)->setTimezone('Australia/Melbourne')->format('Y-m-d') : null;
                $cid->manual_issuance_number = $request->manual_issuance_number;

                $cid->update();
                
            }else {
                // create
                dd($request->course['cc']['id']);
                $scd = StudentCertificateIssuance::where('student_completion_id', $request->course['cc']['id'])->first();

                if(!$scd) {
                    // if student certificate issuance table is not been created
                    $scd = new StudentCertificateIssuance;
                    $cert_num = $this->generate_certificate_number();
                    $scd->student_id = $request->course['cc']['student_id'];
                    $scd->generated_cert_num = $cert_num;
                    $scd->manual_cert_num = $cert_num;
                    $scd->issued_date = $request->course['cc']['completion_date'];
                    $scd->save();
                }
                
                // create certificate issuance details
                $cert_units = [];
                foreach ($request->course['cc']['details'] as $key => $unit) {
                    if(isset($unit['status'])){
                        if (in_array($unit['status'], [3, 4, 6, 7])) {
                            $cert_units[] = $unit;
                        }
                    }else{
                        if (in_array($unit['completion_status_id'], [3, 4, 6, 7])) {
                            $cert_units[] = $unit;
                        }
                    }
                    
                }
                

            }

            DB::commit();
            return ['status' => 'success'];
            
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

    public function generate_pdf($id)
    {
        try {
            $certificate = CertificateIssuanceDetail::with('certificte_issuance.completion.details', 'certificte_issuance.completion', 'certificte_issuance.completion.course', 'certificte_issuance.student.party.person', 'certificte_issuance.student.funded_detail')->findOrFail($id);
            $com_status = AvtCompletionStatus::all();
            $statuses = [];
            foreach ($com_status as $cs) {
                $statuses[$cs->id] = $cs->status_code;
            }
            $base_units = $certificate->certificte_issuance->completion->details->count();
            
            $fullname = $certificate->certificte_issuance->student->party->name;
            
            if(isset($certificate->certificte_issuance->student->party->person->full_name)) {
                $fullname = $certificate->certificte_issuance->student->party->person->full_name;
            }

            $student = [
                'student_id' => $certificate->certificte_issuance->student->student_id,
                'vetrack' => ($certificate->certificte_issuance->student->funded_detail != null) ? $certificate->certificte_issuance->student->funded_detail->vetrack_id : null,
                'name'       => $fullname,
                'dob'       => $certificate->certificte_issuance->student->party->person->date_of_birth,
                'cert_num'   => $base_units != count($certificate->units) || count($certificate->units)  <= 5 ?  $certificate->soa_number : $certificate->certificte_issuance->manual_cert_num,
                'soa_num'   => $certificate->soa_number,
                'course_code'     => !in_array($certificate->certificte_issuance->completion->course_code, ['@@@@', '1111'])  ? $certificate->certificte_issuance->completion->course->code : '',
                'course_name'     => !in_array($certificate->certificte_issuance->completion->course_code, ['@@@@', '1111']) ? $certificate->certificte_issuance->completion->course->name : 'Unit of Competency',
                'release'   => $certificate->release,
                'release_date'   => $certificate->release_date,
                'reissued'   => $certificate->reissue,
                'reissued_date'   => $certificate->reissued_date,
            ];
            //  dd($student);

            $getUnits = $certificate->units;
            // dump($getUnits);
            // $first = array_slice($getUnits, 0,8);
            // $second = array_slice($getUnits, 8);
            // dump($first);
            // dd($second);

            if (count($certificate->units)  > 15 && count($certificate->units) < 15) {
                $units = array_chunk($certificate->units, 20);
                $font = 10;
            } elseif (count($certificate->units) >= 15) {
                $units = array_chunk($certificate->units, 20);
                $font = 10;
            } else {
                $units[] = $certificate->units;
                $font = 10;
            }
            
            if (isset($certificate->certificte_issuance->completion->course->code)) {
                if ($certificate->certificte_issuance->completion->course->code == 'CHC30113') {
                    $units = array_chunk($certificate->units, 20);
                    $font = 14;
                }

                if ($certificate->certificte_issuance->completion->course->code == 'CPC50210') {
                    $units = array_chunk($certificate->units, 20);
                    $font = 14;
                }
            } else {
                $units = array_chunk($certificate->units, 20);
                $font = 14;
            }

            // for statement of attainment units
            if( in_array($student['course_code'], ['1111', '@@@@']) || $certificate->certificte_issuance->completion->details->count() != count($certificate->units)) {
                $units = array_chunk($certificate->units, 12);
            }

            // dump($certificate);
            // dd($units);
            
            // if($student['course_code'] == '1111'){
            //     $student['course_code'] = $units[0][0]['code'];
            //     $student['course_name'] = $units[0][0]['description'];
            // }

            // dump($statuses);
            // dump($student);
            // dd($units);
            //code...
            if(env('APP_NAME') == 'Phoenix'){
                if(count($certificate->units)  <= 5){
                    $cert_template = (count($certificate->units)  <= 5 ? 'custom.pca.certificates.statement_of_attainment' : 'custom.pca.certificates.certificate');
                    
                }else{
                    $cert_template = ($base_units != count($certificate->units) ? 'custom.pca.certificates.statement_of_attainment' : 'custom.pca.certificates.certificate');

                }
                $file_name = $student['name'] . ' - (' . $student['course_code'] . ') ' . $student['course_name'] . ".pdf";
                return \PDF::loadView($cert_template, compact('student', 'units', 'font', 'statuses'))->setPaper('A4')->stream($file_name);

            }else{
                // $cert_template = ($base_units != count($certificate->units) ? 'certificates.statement-of-attainment' : 'certificates.certificate');
                // $cert_template = (count($certificate->units)  <= 5 ? 'certificates.statement-of-attainment' : 'certificates.certificate');

                // $cert_template = (in_array($student['course_code'], ['1111', '@@@@']) ? 'certificates.statement-of-attainment' : $cert_template);
                
                $stud_course =  in_array($student['course_code'], ['1111', '@@@@']) ? ' ' : ' ( '.$student['course_code'].' ) ';
                $stud_name =  in_array($student['course_name'], ['1111', '@@@@']) ? ' Unit of Competency' : ' ( '.$student['course_name'].' ) ';
                
                $file_name = $student['name'] . ' -' . $stud_course . '' . $stud_name . ".pdf";
                

                if( !in_array($student['course_code'], ['1111', '@@@@']) && $certificate->certificte_issuance->completion->details->count() == count($certificate->units)) {
                    $cert_template = 'certificates.new.certificate';
                }else {
                    $cert_template = 'certificates.new.statement-of-attainment';
                }

                return \PDF::loadView($cert_template, compact('student', 'units', 'getUnits', 'font', 'statuses'))->setPaper(array(0, 0, 595, 925))->stream($file_name);

            }
        } catch (\Throwable $th) {
            // dd('error');
            throw $th;
        }
    
    }

    public function generate_certificate_number()
    {
        $number = mt_rand(0, 9999);
        $mustFiveDigit = sprintf('%04d', $number);
        $to = TrainingOrganisation::first();
        $studIDPrefix = in_array($to->student_id_prefix, [null, '']) ? 'VRX' : $to->student_id_prefix;
        $cert_no = $studIDPrefix[0] . $mustFiveDigit;

        $check = StudentCertificateIssuance::where('manual_cert_num', $cert_no)->orWhere('generated_cert_num', $cert_no)->first();
        if ($check) {
            $this->generate_certificate_number();
        }
        return $cert_no;
    }

    public function genereate_soa_number()
    {

        $number = mt_rand(0, 9999);
        $mustFiveDigit = sprintf('%04d', $number);
        $to = TrainingOrganisation::first();
        $studIDPrefix = in_array($to->student_id_prefix, [null, '']) ? 'VRX' : $to->student_id_prefix;
        $cert_no = $studIDPrefix[0] . 'SOA' . $mustFiveDigit;

        $check = CertificateIssuanceDetail::where('soa_number', $cert_no)->first();
        if ($check) {
            $this->genereate_soa_number();
        }
        return $cert_no;
    }
}
