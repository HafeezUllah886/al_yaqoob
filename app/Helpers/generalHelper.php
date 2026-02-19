<?php

use App\Models\attachment;
use App\Models\ref;
use Carbon\Carbon;

function getRef()
{
    $ref = ref::first();
    if ($ref) {
        $ref->ref = $ref->ref + 1;
    } else {
        $ref = new ref;
        $ref->ref = 1;
    }
    $ref->save();

    return $ref->ref;
}

function firstDayOfMonth()
{
    $startOfMonth = Carbon::now()->startOfMonth();

    return $startOfMonth->format('Y-m-d');
}
function lastDayOfMonth()
{

    $endOfMonth = Carbon::now()->endOfMonth();

    return $endOfMonth->format('Y-m-d');
}

function firstDayOfCurrentYear()
{
    $startOfYear = Carbon::now()->startOfYear();

    return $startOfYear->format('Y-m-d');
}

function lastDayOfCurrentYear()
{
    $endOfYear = Carbon::now()->endOfYear();

    return $endOfYear->format('Y-m-d');
}

function firstDayOfPreviousYear()
{
    $startOfPreviousYear = Carbon::now()->subYear()->startOfYear();

    return $startOfPreviousYear->format('Y-m-d');
}

function lastDayOfPreviousYear()
{
    $endOfPreviousYear = Carbon::now()->subYear()->endOfYear();

    return $endOfPreviousYear->format('Y-m-d');
}

function deleteAttachment($ref)
{
    $attachment = attachment::where('refID', $ref)->first();
    if (file_exists(public_path($attachment->path))) {
        unlink(public_path($attachment->path));
    }
    $attachment->delete();
}

function createAttachment($file, $ref)
{
    $filename = time().'.'.$file->getClientOriginalExtension();
    $file->move('attachments', $filename);

    attachment::create(
        [
            'path' => 'attachments/'.$filename,
            'refID' => $ref,
        ]
    );
}

function projectName()
{
    return 'Al-Yaqoob International Business';
}

function projectNameMedium()
{
    return 'Al-Yaqoob Business';
}
function projectNameShort()
{
    return 'AYB';
}
function projectType()
{
    return 'Business Management System';
}
function projectTypeShort()
{
    return 'BMS';
}
