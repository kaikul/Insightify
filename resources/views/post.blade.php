@extends('template.template')

@section('content')
    <div class="container">
    <button id="syncBtn" type="button" class="btn btn-primary" style="margin-left: 690px; margin-bottom: 10px">Sync Now</button>
      <div id="syncAlert"></div>
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

@section('scripts')
<script>
    $(document).ready(function() {
        $('#syncBtn').click(function() {
            // Send AJAX request to the server
            $.ajax({
                url: '{{ route("SyncAll") }}',
                type: 'get', 
                success: function(response) {
                    if (response.status === 'success') {
                         $('#syncAlert').html('<div class="alert alert-success" role="alert">' + response.message + '</div>');
                    } else {
                        $('#syncAlert').html('<div class="alert alert-info" role="alert">' + response.message + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    $('#syncAlert').html('<div class="alert alert-danger" role="alert">Failed to synchronize data: ' + error + '</div>');
                }
            });
        });
    });
</script>
@endsection
