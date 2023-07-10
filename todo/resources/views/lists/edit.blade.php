@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>{{ __('Edit List') }}</h1>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{ route('lists.update', $list) }}" method="POST">
                @csrf
                @method('PUT')
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="form-group mb-3">
                    <label for="title">{{ __('List title') }}</label>
                    <input type="text" class="form-control" id="title" name="title"
                        placeholder="{{ __('My new TODO') }}" aria-describedby="List title" value="{{ $list->title }}" @error('title') is-invalid
                        @enderror>
                    @error('title')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="usersToShare">{{ __('Share with') }}</label>
                    <select multiple class="form-control" id="usersToShare" name="users[]">
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(in_array($user->id, $list->users->pluck('id')->toArray()))>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-dark">{{ __('Edit') }}</button>
                <a href="{{ route('lists.index') }}" class="btn btn-dark">{{ __('Cancel') }}</a>
            </form>
        </div>
    </div>
</div>
@endsection
