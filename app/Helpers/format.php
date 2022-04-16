<?php

use \Carbon\Carbon;

function readable_date($date)
{
    if (!empty($date)) {
        $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-M-Y h:i A');
        return $carbonDate;
    }
    return null;
}

function system_date($date)
{
    if (!empty($date)) {
        $carbonDate = Carbon::createFromFormat('d-M-Y h:i A', $date)->format('Y-m-d H:i:s');
        return $carbonDate;
    }
    return null;
}
