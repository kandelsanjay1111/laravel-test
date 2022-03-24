@component('mail::message')
# Introduction

The body of your message.
You are assigned the following tasks
@foreach($tasks as $work)
<ul>{{$work}}</ul>
@endforeach

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
