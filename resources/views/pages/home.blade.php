      <div class=" overflow-x-hidden w-full">
        @extends('layoutUser')
        @section('content')
          @include('components.homePage.homeSection')
          @include('components.homePage.brandSection')
          @include('components.homePage.categorySection')
          @include('components.homePage.productSection')
          @include('components.homePage.serviceSection')
          @include('components.homePage.reviewSection')
        @endsection
      </div>