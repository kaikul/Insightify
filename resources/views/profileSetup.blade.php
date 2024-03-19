@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Platofmrs API Tokens') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('apiToken') }}">
                          
                        @foreach($applicationNames as $appId => $appName)
                                @php
                                  $inputName = strtolower($appName . '_' . $appId);
                                @endphp
                                <div class="row mb-3">
                                    <label for="{{ $appId }}" class="col-md-4 col-form-label text-md-end">{{ $appName }}</label>
                                    <div class="col-md-6">
                                        <input id="{{ $appId }}" type="text" class="form-control @error($inputName) is-invalid @enderror" name="{{ $inputName }}" value="{{ old($appName) }}" autofocus>
                                        @error($appName)
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
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
