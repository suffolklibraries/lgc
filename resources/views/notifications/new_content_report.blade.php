@component('mail::message')
# New Inappropriate Content Report Submitted

A user has reported inappropriate content on the following event:

**{{ $entryTitle }}**

**Reason:** {{ $reason }}

Use the button below to review the report:

@component('mail::button', ['url' => $reportReviewUrl])
Review Report
@endcomponent

@if($otherReportsCount > 0)
{{ trans_choice(
    "{1} There is another report requiring review.|[2,*] There are :count reports requiring review.",
    $otherReportsCount,
    ['count' => $otherReportsCount]
) }}
@endif

All reports can be reviewed by clicking the button below:

@component('mail::button', ['url' => $allReportsReviewUrl])
Review all reports
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
