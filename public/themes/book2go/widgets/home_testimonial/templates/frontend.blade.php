<!-- Section Testimonials -->
<section id="section-testimonials">
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <div class="promo-caption">
                <h1 class="caption-number">
                    <span>{{ __($config['number']) }}</span>
                </h1>
                <h2 class="caption-title">
                    <span>{{ get_category_by_id(2)->description }}</span>
                </h2>
            </div>
           
            <div class="row testimonials-list">
                @foreach(get_posts_by_category(2,4,0) as $v)
                <div class="col-md-6 testimonials-item">
                    <div class="testimonials-info text-center">
                        <p>
                            {{ $v->description }}
                        </p>
                        <h5>{{ $v->name }}</h5>
                    </div>
                    <div class="testimonials-img text-center">
                        <picture>
                            <a href="{{ $v->other_content }}" target="_blank">
                                <img src="{{ get_object_image($v->image, '') }}" alt="{{ $v->name }}">
                            </a>
                        </picture>
                        
                    </div>
                </div>
                @endforeach
            </div>
            <div class="btn-block text-center">
                <a class="btn btn-default btn-outline" href="{{ url('/khach-hang-nhan-xet.html') }}">Xem Tất Cả</a>
            </div>
        </div>
    </div>
</section>
<!-- End / Section Testimonials -->