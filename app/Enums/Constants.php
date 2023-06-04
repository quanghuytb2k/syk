<?php

namespace App\Enums;

class Constants
{
    const PER_PAGE = 10;
    const AGENCY_STATUS = ["無効", "有効"];

    const DEFAULT_AGENCY_ID = 1;

    const USER_DISABLED = 0;
    const USER_WAITING_ACTIVATION = 1;
    const USER_ACTIVATED = 2;
    const AGENCY_STATUS_INACTIVE = 0;
    const AGENCY_STATUS_ACTIVE = 1;
}
