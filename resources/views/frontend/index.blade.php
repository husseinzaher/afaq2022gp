@extends('frontend.layouts.master')

@section('content')


@include('frontend.Sections.mainHeader')

@include('frontend.Sections.candidates')
@include('frontend.Sections.brief')
@include('frontend.Sections.electoral_program')
@include('frontend.Sections.electoral_target')
@include('frontend.Sections.afaqGroup')
@include('frontend.Sections.afaqTweet')
@include('frontend.Sections.campaignNews')
@include('frontend.Sections.campianActivites')
@include('frontend.Sections.eventDetails')
@include('frontend.Sections.tech_support')


@endsection