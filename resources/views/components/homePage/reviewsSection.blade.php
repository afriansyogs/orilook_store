<div id="reviewSection" class="py-12">
  <div class="container mx-auto px-5">
      <!-- Section Header -->
      <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
              Review
          </h2>
          <div class="w-20 h-1 bg-gradient-to-r from-red-600 to-red-800 mx-auto rounded-full"></div>
      </div>

      <!-- Reviews Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          @foreach ($review as $review)
              <div class="group">
                  <div
                      class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 hover:-translate-y-1">
                      <!-- User Info Section -->
                      <div class="flex items-start space-x-4">
                          <div class="flex-shrink-0">
                              <div class="relative">
                                  <div
                                      class="w-14 h-14 rounded-full overflow-hidden border-4 border-white ring-2 ring-red-100">
                                      @if (!empty($review->user->user_img))
                                          <img src="{{ asset('storage/profile/' . $review->user->user_img) }}"
                                              alt="{{ $review->user->name }}" class="w-full h-full object-cover">
                                      @else
                                          <img src="{{ asset('assets/img/userImgDefault.png') }}"
                                              alt="{{ $review->user->name }}" class="w-full h-full object-cover">
                                      @endif
                                  </div>
                                  <!-- Verification Badge -->
                                  <div class="absolute -bottom-1 -right-1 bg-green-500 rounded-full p-1">
                                      <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                          <path
                                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z">
                                          </path>
                                      </svg>
                                  </div>
                              </div>
                          </div>

                          <div class="flex-1">
                              <h3 class="text-lg font-bold text-gray-900 group-hover:text-red-600 transition-colors">
                                  {{ $review->user->name }}
                              </h3>

                              <!-- Star Rating -->
                              <div class="flex items-center gap-1 mt-2">
                                  @for ($i = 1; $i <= 5; $i++)
                                      @if ($i <= $review->rating)
                                          <svg class="w-5 h-5 text-yellow-400" fill="currentColor"
                                              viewBox="0 0 20 20">
                                              <path
                                                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                          </svg>
                                      @else
                                          <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                              <path
                                                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                          </svg>
                                      @endif
                                  @endfor
                              </div>
                          </div>
                      </div>

                      <!-- Review Content -->
                      <div class="mt-4">
                          <div class="relative">
                              <svg class="absolute -top-2 -left-2 w-8 h-8 text-red-600 transform -rotate-12"
                                  fill="currentColor" viewBox="0 0 32 32">
                                  <path
                                      d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z" />
                              </svg>
                              <p class="relative pl-6 text-gray-600 text-base leading-relaxed">
                                  {{ $review->review }}
                              </p>
                          </div>
                      </div>

                      <!-- Review Footer -->
                      <div class="mt-6 flex items-center justify-between">
                          <span class="text-sm text-gray-500">
                              {{ $review->created_at->diffForHumans() }}
                          </span>

                          <!-- Helpful Button -->
                          <button
                              class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-red-600 transition-colors">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                              </svg>
                              Helpful
                          </button>
                      </div>
                  </div>
              </div>
          @endforeach
      </div>
  </div>
</div>
