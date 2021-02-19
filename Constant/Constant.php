<?php

namespace app\Constant;
class Constant
{
    const IS_LOCKED = 1;
    const UN_LOCKED = 0;
    const MIN_ATTEMPT_COUNT = 0;
    const MAX_ATTEMPT_COUNT = 3;
    const FIFTH_LEVEL_ID = 5;
    const OFFICE_CHECK_TASK_STATUS = 3;
    const SELF_CHECK_TASK_STATUS = 2;

    const IS_WORKING_2 = 2;
    const IS_WORKING_4 = 4;
    const IS_WAITING_CONFIRM = 3;

    const CONFIRM_WORK = 4;
    const DECLINE_WORK = 5;
    const OFFICE_TASK = 2;
    const SELF_TASK = 1;

    const HIGH_PLACE = 1;
    const SHORT_PLACE = 0;
    const IMAGE_EXTENSION = '.jpg';
    const IMAGE_THUMBNAIL = 'thumbnail';
    const NO_IMAGE = 'No image available';

    const START_DATE = 'date1';
    const END_DATE = 'date2';

    const CHECK_TYPE = 'work.checkType';
    const AERIAL_WORK = 'aerialWork';
    const WORKING_EMPLOYEE = 'user';
    const RESULT = 'status';
    const WORK_CONTENT = 'name';
    const CONFIRMED = 'Confirmed';
    const COMMENT = 'Comment';
    const NOT_FINISH = 'Not finish yet';
    const BRANCH_NAME = 'organizationnamelevel.branchName';
    const OFFICE_NAME = 'organizationnamelevel.officeName';
    const FSC_NAME = 'organizationnamelevel.fscName';
    const FACILITY_NAME = 'organizationnamelevel.facilityName';



}