<?php
function isdataImage($str)
{
    return starts_with($str, 'data:image');
}

function getUriFromUrl($url)
{
    return parse_url($url, PHP_URL_PATH);
}

function convertTimeFormat($time, $format = DATE_FORMAT)
{
    return date($format, strtotime($time));
}

function getMonthOfYear()
{
    return [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
}

function getModelByTablename($tableName) {
    return "App\\Models\\" . studly_case(strtolower(str_singular($tableName)));
}