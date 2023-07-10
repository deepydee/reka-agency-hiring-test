@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-sm-10">
            <h1 class="display-5">{{ $list->title }}</h1>
        </div>
        <div class="col-sm-2">
            <a href="{{ route('lists.index') }}" class="btn btn-dark">{{ __('Back') }}</a>
        </div>
    </div>

    <div class="row mb-5">
        @livewire('tasks.tasks', ['list' => $list])
    </div>
</div>
@endsection
