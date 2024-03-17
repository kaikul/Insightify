@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Platofmrs API Tokens') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('apiToken') }}">

                            <div class="row mb-3">
                                <label for="tiktok" class="col-md-4 col-form-label text-md-end">{{ __('TikTok API Token') }}</label>

                                <div class="col-md-6">
                                    <input id="tiktok" type="text" class="form-control @error('text') is-invalid @enderror" name="tiktok" value="{{ old('tiktok') }}"  autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

{{--                            IG--}}
                            <div class="row mb-3">
                                <label for="instagram" class="col-md-4 col-form-label text-md-end">{{ __('Instagram API Token') }}</label>

                                <div class="col-md-6">
                                    <input id="instagram" type="text" class="form-control @error('text') is-invalid @enderror" name="instagram" value="{{ old('instagram') }}"  autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            {{--                            Youtube--}}
                            <div class="row mb-3">
                                <label for="youtube" class="col-md-4 col-form-label text-md-end">{{ __('Youtube API Token') }}</label>

                                <div class="col-md-6">
                                    <input id="youtube" type="text" class="form-control @error('text') is-invalid @enderror" name="youtube" value="{{ old('youtube') }}"  autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Save') }}
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
