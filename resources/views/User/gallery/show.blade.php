@extends('layouts.user')

@section('title', 'news')

@section('content')
    <section class="gallery-section min-vh-100">
        <div class="container">
            <div class="section-header">
                <h2>Gallery</h2>
            </div>
            <div class="gallery-grid">
                @foreach ($galleries as $gallery)
                    <!-- Gallery Item 1 (Big Square) -->
                    <div class="gallery-item medium-square" data-title="{{ $gallery->title }}">
                        <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->title }}"
                            class="img-fluid" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Add this right before the closing </section> tag -->
    <div class="fullscreen-overlay" id="fullscreenOverlay">
        <span class="close-overlay" id="closeOverlay">&times;</span>
        <img src="" alt="Zoomed Image" class="zoomed-image" id="zoomedImage" />
    </div>
@endsection
