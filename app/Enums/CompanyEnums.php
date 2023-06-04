<?php

namespace App\Enums;

/**
 * Company
 */

final class CompanyEnums
{
    // 特定事業者
    const TYPE_SPECIFIED = 10;

    // 特定連鎖化事業者_一般企業
    const TYPE_GENERAL = 21;

    // 特定連鎖化事業者_福祉施設
    const TYPE_WELFARE_FACILITY = 22;

    // 特定連鎖化事業者_学校
    const TYPE_SCHOOL = 23;

    // 特定連鎖化事業者_その他
    const TYPE_OTHERS = 24;

    // 上場区分 - 非上表
    const NON_LIST = 0;

    // 上場区分 - 上表
    const TABLE_LIST = 1;

    const INACTIVE_STATUS = 0;
    const ACTIVE_STATUS = 1;

    const NOT_EXPORT_REPORT = 0;
    const HAVE_EXPORT_REPORT = 1;
}
