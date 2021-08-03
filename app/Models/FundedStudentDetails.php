<?php

namespace App\Models;

use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use \DB;

class FundedStudentDetails extends Model implements AuditableContract
{
    //
    use SoftDeletes;
    use Auditable;
    protected $table = "funded_student_details";
    protected $dates = ['created_at'];

    protected $fillable = [
        // 'id',
        'student_id',
        'vetrack_id',
        'location',
        'funded_student_contact_detail_id',
        'name_for_encryption',
        // 'commence_prg_identifier',
        'highest_school_level_completed_id',
        'indigenous_status_id',
        'language_id',
        'labour_force_status_id',
        'country_id',
        'disability_flag',
        'disability_ids',
        'prior_educational_achievement_flag',
        'prior_educational_achievement_ids',
        'at_school_flag',
        'unique_student_id',
        'survey_contact_status',
        'statistical_area_level_1_id',
        'statistical_area_level_2_id',
        'verified',
        'verified_date',
        'verified_by',
        'year_completed',
        'institute'
        // 'full_time_leaning_option'
    ];

    public function party()
    {
        return $this->belongsTo(Party::class, 'student_id');
    }
    public function country()
    {
        return $this->belongsTo(AvtCountryIdentifier::class, 'country_id', 'identifier');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'student_type_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    public function contact()
    {
        return $this->belongsTo(\App\Models\FundedStudentContactDetails::class, 'student_id', 'student_id');
    }
    public function course()
    {
        return $this->hasMany(\App\Models\FundedStudentCourse::class, 'student_id', 'student_id');
    }
    public function extra_units()
    {
        return $this->belongsTo(\App\Models\FundedStudentExtraUnits::class, 'student_id', 'student_id');
    }
    public function highest_school_level()
    {
        return $this->belongsTo(AvtHighestSchlLvlCompleted::class, 'highest_school_level_completed_id', 'value');
    }
    public function language()
    {
        return $this->belongsTo(AvtLangIdentifier::class, 'language_id', 'value');
    }
    public function labor_force()
    {
        return $this->belongsTo(AvtLabourForceStatus::class, 'labour_force_status_id', 'value');
    }

    public function getDisabilityValueAttribute()
    {
        $disability = AvtDisabilityTypes::whereIn('value', explode(',', $this->disability_ids))->get();
        $d = [];
        foreach ($disability as $key => $value) {
            $d[] = [
                'id' => $value->value,
                'value' => $value->description,
            ];
        }
        return $d;

        // if (isset($disability[0])) {
        //     return implode(', ', $disability->pluck('description'));
        // } else {
        //     return null;
        // }
    }

    public function getPriorEducationValueAttribute()
    // public function getPriorEducationalAchievenebtIdsAttribute()
    {
        $prior = AvtPriorEducationAchievement::whereIn('value', explode(',', $this->prior_educational_achievement_ids))->get();
        // dd($prior);
        $p = [];
        foreach ($prior as $key => $value) {
            $p[] = [
                'id' => $value->value,
                'value' => $value->description,
            ];
        }
        return $p;

        // if (isset($prior[0])) {
        //     // return implode(', ', $prior->pluck('description'));
        //     return $prior;
        // } else {
        //     return null;
        // }
    }



    public static function getEnumColumnValues()
    {
        // dd($this);
        $type = DB::select(DB::raw("SHOW COLUMNS FROM vrx_funded_student_details WHERE Field = 'disability_flag'"))[0]->Type;

        preg_match('/^enum\((.*)\)$/', $type, $matches);

        $enum_values = [];
        $value = explode(',', $matches[1]);
        foreach ($value as $enum_v) {
            $v = trim($enum_v, "'");
            $enum_values[] = [
                'type' => $v
            ];
        }

        return $enum_values;
    }
}
