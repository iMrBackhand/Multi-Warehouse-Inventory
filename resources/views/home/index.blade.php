@extends('home.home_master')

@section('home')
@include('home.home-layouts.finances')
  <!-- end hero -->
  <div class="lonyo-content-shape1">
    <img src="{{ asset('frontend/assets/images/shape/shape1.svg') }}" alt="">
  </div>
@include('home.home-layouts.Features')
  <!-- end content -->

@include('home.home-layouts.clarifies')
  <!-- end content -->

@include('home.home-layouts.financial-updates')
  <div class="lonyo-content-shape3">
    <img src="{{ asset('frontend/assets/images/shape/shape2.svg') }}" alt="">
  </div>
  <!-- end content -->

@include('home.home-layouts.usability')
  <div class="lonyo-content-shape1">
    <img src="{{ asset('frontend/assets/images/shape/shape3.svg') }}" alt="">
  </div>
  <!-- end video -->

@include('home.home-layouts.review')
  <!-- end testimonial -->
@include('home.home-layouts.answers')

  <div class="lonyo-content-shape3">
    <img src="{{ asset('frontend/assets/images/shape/shape2.svg"') }} alt="">
  </div>
  <!-- end faq -->

@include('home.home-layouts.section8')
@endsection
