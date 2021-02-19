<?php


namespace app\models;

use kartik\date\DatePicker;
use yii\base\Model;
use app\Constant\Constant;

/**
 * This is the model class for table "work_type".
 *
 * @property DatePicker $startDate
 * @property DatePicker $endDate
 * @property DatePicker $startFinishTime
 * @property DatePicker $endFinishTime
 * @property string $workId
 * @property int $workType
 * @property string $branch
 * @property string $office
 * @property string $fsc
 * @property int $facility
 * @property int $employee
 * @property int $workingEmployee
 * @property int $workContent
 * @property int $kyDivision
 * @property int $result
 */

class FilterForm extends Model
{
    public $startDate;
    public $endDate;
    public $startFinishTime;
    public $endFinishTime;
    public $projectId;
    public $workType;
    public $identifyPlace;
    public $branch;
    public $office;
    public $fsc;
    public $facility;
    public $employee;
    public $workingEmployee;
    public $workContent;
    public $kyDivision;
    public $result;

    public function rules(){
        return [
            ['startDate', 'required',
                'whenClient' => 'startDateRequired'
            ],

            ['startDate', 'compare', 'compareAttribute' => 'endDate', 'operator' => '<=',
                'whenClient' => 'compareStartDate'
            ],

            ['startFinishTime', 'compare', 'compareAttribute' => 'endFinishTime', 'operator' => '<=',
                'whenClient' => 'compareFinishDate'
            ],
            ['startFinishTime', 'required',
                'whenClient' => 'startFinishTimeRequired'
            ],
            ['startDate', 'compare', 'compareAttribute' => 'startFinishTime', 'operator' => '<=',
                'whenClient' => 'compareDate'
            ],
            ['endDate', 'compare', 'compareAttribute' => 'startFinishTime', 'operator' => '<=',
                'whenClient' => 'compareDateFinish'
            ],
            [['endFinishTime','workId','workType','identifyPlace','branch','office','fsc','facility',
                'employee','workingEmployee','workContent','kyDivision','result'],'safe']
        ];
    }

    public static function convertPropertyFilter(){
        return [
            'startDate' => Constant::START_DATE,
            'endDate' =>  Constant::END_DATE,
            'workType' =>  Constant::CHECK_TYPE,//Office check or self check
            'identifyPlace' => Constant::AERIAL_WORK,
            'branch' => Constant::BRANCH_NAME,
            'office' => Constant::OFFICE_NAME,
            'fsc' => Constant::FSC_NAME,
            'facility' => Constant::FACILITY_NAME,
            'workingEmployee' => Constant::WORKING_EMPLOYEE,
            'workContent' => Constant::WORK_CONTENT,//confirm_type.name,work_type.name
            'result' => Constant::RESULT,
            // 'startFinishTime' => '',FA data
            //'endFinishTime' => '',FA data,
            //'projectId' => '',
            //'kyDivision' => '',
            //'employee' => ''
        ];
    }
}