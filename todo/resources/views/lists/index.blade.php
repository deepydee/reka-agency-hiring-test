@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-sm-10">
            <h1 class="display-5">{{ __('Lists') }}</h1>
        </div>
        <div class="col-sm-2">
            <a href="{{ route('lists.create') }}" class="btn btn-dark">{{ __('Add List') }}</a>
        </div>
    </div>
    <div class="row">
        <x-alert />
    </div>
    <div class="row mb-5">
        <h2>{{ __('My Lists') }}</h2>
        <div class="row g-2">
            @forelse ($myLists as $list)
            <div class="col col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">{{ $list->title }}</h5>
                        @if ($list->getUsersNamesListBelongsTo())
                        <h6 class="card-subtitle mb-2 text-muted">{{ __('This list is also belongs to') }}:</h6>
                        <p>{{ $list->getUsersNamesListBelongsTo() }}</p>
                        @endif
                    </div>
                    <div class="card-footer d-flex flex-wrap gap-2">
                        <a href="{{ route('lists.show', $list) }}" class="btn btn-dark">{{ __('View') }}</a>
                        <a href="{{ route('lists.edit', $list) }}" class="btn btn-dark">{{ __('Edit') }}</a>
                        <form action="{{ route('lists.destroy', $list) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger">{{
                                __('Delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <p>Нет собственных списков</p>
            @endforelse
        </div>
    </div>

    <div class="row">
        <h2>{{ __('My Shared Lists') }}</h2>
        <div class="row g-2">
            @forelse ($sharedLists as $list)
            <div class="col col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">{{ $list->title }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ __('List owner') }}:</h6>
                        <p>{{ $list->getOwnerName() }}</p>
                    </div>
                    <div class="card-footer d-flex flex-wrap gap-2">
                        <a href="{{ route('lists.show', $list) }}" class="btn btn-dark">{{ __('View') }}</a>
                    </div>
                </div>
            </div>
            @empty
            <p>Нет общих списков</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
