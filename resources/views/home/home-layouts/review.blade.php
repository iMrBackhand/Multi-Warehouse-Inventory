<div class="lonyo-section-padding position-relative overflow-hidden">
  <div class="container">
    <div class="lonyo-section-title">
      <div class="row">
        <div class="col-xl-8 col-lg-8">
          <h2>Don't take our word for it, check user reviews</h2>
        </div>
        <div class="col-xl-4 col-lg-4 d-flex align-items-center justify-content-end">
          <div class="lonyo-title-btn">
            <a class="lonyo-default-btn t-btn" href="#">Read Customer Stories</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- SINGLE SLIDER WRAPPER ONLY -->
  <div class="lonyo-testimonial-slider-init">

    @foreach($reviews as $review)
      <div class="lonyo-t-wrap wrap2 light-bg">

            <div class="lonyo-t-ratting">
                @for($i = 1; $i <= 5; $i++)

                    @if($i <= $review->rating)

                        <img
                        src="{{ asset('frontend/assets/images/shape/star.png') }}"
                        alt="star"
                        style="width:20px; height:20px; object-fit:contain; display:inline-block;">

                    @else

                        <img
                        src="{{ asset('frontend/assets/images/shape/notshaded.png') }}"
                        alt="star"
                        style="width:20px; height:20px; object-fit:contain; display:inline-block;">

                    @endif

                @endfor
            </div>

        <div class="lonyo-t-text">
          <p>{{ $review->message }}</p>
        </div>

        <div class="lonyo-t-author">
          <div class="lonyo-t-author-thumb">
            <img src="{{ asset('storage/' . $review->image) }}"
                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
          </div>

          <div class="lonyo-t-author-data">
            <p>{{ $review->name }}</p>
            <span>{{ $review->position }}</span>
          </div>
        </div>
      </div>
    @endforeach

  </div>

  <div class="lonyo-t-overlay2">
    <img src="{{ asset('frontend/assets/images/v2/overlay.png') }}" alt="">
  </div>
</div>
