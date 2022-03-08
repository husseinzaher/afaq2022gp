@extends('frontend.layouts.master')

@section('content')


@include('frontEnd.Sections.mainHeader')

@include('frontEnd.Sections.candidates')
@include('frontEnd.Sections.brief')
@include('frontEnd.Sections.electoral_program')
@include('frontEnd.Sections.electoral_target')
@include('frontEnd.Sections.afaqGroup')
@include('frontEnd.Sections.afaqTweet')
@include('frontEnd.Sections.campaignNews')
@include('frontEnd.Sections.campianActivites')
@include('frontEnd.Sections.eventDetails')
@include('frontEnd.Sections.tech_support')


@endsection