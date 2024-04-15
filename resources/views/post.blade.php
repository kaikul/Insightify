@extends('template.template')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Fetched Instagram Posts') }}</div>
                    <div class="card-body">
                      
                    @foreach($posts as $post)
                            <div class="mb-4">
                                @if($post['media_type'] === 'IMAGE')
                                    <img src="{{ $post['media_url'] }}" alt="Instagram Post" class="img-fluid">
                                @elseif($post['media_type'] === 'VIDEO')
                                    <video controls class="img-fluid">
                                        <source src="{{ $post['media_url'] }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                     </video>
                                @endif
                            <h4>{{ $post['caption'] }}</h4>
                            <p>Posted on: {{ $post['timestamp'] }}</p>
                            <a href="{{ $post['permalink'] }}" target="_blank" class="btn btn-primary">View on Instagram</a>
                            </div>
                        @endforeach
                        
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection