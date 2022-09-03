<?php


function getDateColumn($modelObject, $attributeName = 'updated_at')
{
    $html = '<p data-toggle="tooltip" data-placement="bottom" title="${date}">${dateHuman}</p>';

    if (!isset($modelObject[$attributeName])) {
        return '';
    }
    $dateObj = new Carbon\Carbon($modelObject[$attributeName]);
    $replace = preg_replace('/\$\{date\}/', $dateObj->format('l jS F Y (h:i:s)'), $html);
    $replace = preg_replace('/\$\{dateHuman\}/', $dateObj->diffForHumans(), $replace);
    return $replace;
}

function getActiveColumn($column, $attributeName)
{
    if (isset($column)) {
        if ($column[$attributeName] == 0) {
            return "<span class='badge badge-danger'>Inactive</span>";
        }
        else {
            return "<span class='badge badge-success'>Active</span>";
        }
    }
}

function getIdColumn($column, $attributeName)
{
    if (isset($column)) {
        if ($column[$attributeName] == 0) {
            return "#" . $column[$attributeName];
        }
    }

    return "#";
}

function getStringId($prefix, $id)
{
    $strId = $prefix;
    switch (strlen($id)) {
        case 1:
            $strId .= '00' . $id;
            break;
        case 2:
            $strId .= '0' . $id;
            break;
        default:
            $strId .= $id;
            break;

    }

    return $strId;
}

function getNameAvatarColumn($modelObject, $attributeName = 'name')
{
    if ($modelObject->hasMedia('avatar')) {
        return "<img class='img-circle elevation-2' style='width:50px' src='" . $modelObject->getFirstMediaUrl('avatar', 'icon') . "' alt='" . $modelObject[$attributeName] . "'>" . "<span class='pl-2'>" . $modelObject[$attributeName] ."</span>";
    }else{
        return "<img class='img-circle elevation-2' style='width:50px' src='" . asset('images/avatar_default.png') . "' alt='image_default'>" . "<span class='pl-2'>" . $modelObject[$attributeName] ."</span>";
    }
}

function getPhoneColumn($modelObject, $attributeName = 'phone')
{
    return $modelObject[$attributeName];
}

function randomOtp($digits = 6)
{
    return substr(str_shuffle("0123456789"), 0, $digits);
}

function getDayOfWeek($schedule)
{
    $keyDayofWeek = [
        'mon' => 'thứ 2',
        'tue' => 'thứ 3',
        'wed' => 'thứ 4',
        'thu' => 'thứ 5',
        'fri' => 'thứ 6',
        'sat' => 'thứ 7',
        'sun' => 'chủ nhật',
    ];
    $data = '';
    foreach ($schedule as $key=>$item) {
        if ($item == 1) {
            $data .= $keyDayofWeek[$key] . ',';
        }
    }
    return rtrim($data, ",");
}
