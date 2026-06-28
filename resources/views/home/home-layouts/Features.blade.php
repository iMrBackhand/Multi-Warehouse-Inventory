<div class="lonyo-section-padding2 position-relative">
    <div class="container">

        <div class="lonyo-section-title center">
            <h2>Features that make spending smarter</h2>
        </div>

        <div class="row">

            @foreach ($features as $feature)
                <div class="col-xl-4 col-lg-6 col-md-6 d-flex">
                    <div class="lonyo-service-wrap light-bg">

                        <div class="lonyo-service-title">
                            <h4>{{ $feature->title }}</h4>
                        </div>

                        <div class="lonyo-service-data">
                            <p>{{ $feature->description }}</p>
                        </div>

                    </div>
                </div>
            @endforeach

        </div>

    </div>
    <div class="lonyo-feature-shape"></div>
</div>
