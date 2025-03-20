<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
</head>
    <body class="" style="background-color: #F5F5F5;">
        @extends('layoutUser')
        @section('content')
            @include('components.homePage.homeSection')
            @include('components.homePage.brandSection')
            @include('components.homePage.categorySection')
            @include('components.homePage.productSection')
            @include('components.homePage.serviceSection')
            @include('components.homePage.reviewSection')
        @endsection
    </body>
</html>
