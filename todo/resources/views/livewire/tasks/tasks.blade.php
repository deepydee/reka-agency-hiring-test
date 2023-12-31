<div class="row justify-content-center">
    <div class="col col-md-8 bg-white py-2 px-2">
            <div class="col">
                <div class="input-group mb-3">
                    <div class="form-floating">
                        <input wire:model.lazy="taskTitle" wire:keydown.enter="save" type="text" id="addTask"
                            class="form-control py-2 @error('taskTitle') is-invalid @enderror"
                            placeholder="{{ __('What needs to be added?') }}" aria-label="taskTitle">
                        <label for="addTask">{{ __('What needs to be added?') }}</label>
                    </div>
                    <button wire:click="save" class="btn btn-dark px-2" @if ($editedTaskId===0)
                        title="{{ __('Add') }}">{{ __('Add') }}
                        @else
                        title="{{ __('Edit') }}">{{ __('Edit') }}
                        @endif
                    </button>
                    @error('taskTitle')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <x-choices wire:model="tags" class="flex-grow-1" id="tags" name="tags" />
                @if($updateThumb)
                <img src="{{ $thumbnail->temporaryURL() }}" alt="" class="img-thumbnail mr-3" width="150" height="150">
                @endif

                <input wire:model="thumbnail" id="thumbnail" name="thumbnail" type="file" class="custom-file-input mb-3"
                    @error('thumbnail') is-invalid @enderror>
                @error('thumbnail')
                <span class="invalid-feedback">
                    {{ $message }}
                </span>
                @enderror
            </div>

            <div class="col">
                <div class="input-group mb-3">
                    <div class="form-floating">
                        <input wire:model="searchColumns.title" type="text" id="searchTask" class="form-control py-2"
                            placeholder="{{ __('Search...') }}" aria-label="search">
                        <label for="addTask">{{ __('Search...') }}</label>
                    </div>
                </div>
            </div>

        <div class="row">
            <div class="col">
                <ul class="list-group list-group-flush">
                    @if ($tasks->count())
                    @foreach ($tasks as $task)
                    <li class="list-group-item" :wire:key="{{'task-details-'.$task->id }}">
                        <div class="d-flex justify-content-between align-items-end">
                            <span {{ $editedTaskId===$task->id ? 'hidden' : '' }}>
                                <input wire:click="toggleTaskCompleted({{ $task->id }})" type="checkbox"
                                    @checked($task->completed_at)
                                class="form-check-input me-1 p-2 align-sub"
                                >

                                @if ($task->getFirstMedia('task-image'))
                                <a href="{{ $task->getFirstMediaURL('task-image') }}" class="link-transparent" target="_blank">
                                    <img src="{{ $task->getFirstMediaURL('task-image', 'thumb') }}"
                                        alt="{{ $task->title }}" width="150" height="150"
                                        title="{{ __('View full sized photo') }}" @class(['img-thumbnail', 'done'=>
                                    $task->completed_at])>
                                </a>
                                <i wire:click="deleteTaskImage({{ $task }})" class="bi bi-trash3 text-danger"
                                    role="button" title="{{ __('Delete image') }}"></i>
                                @endif

                                <span @class(['todo__title', 'd-block', 'mb-2', 'text-decoration-line-through'=> $task->completed_at])>
                                    {{ $task->title }}
                                </span>
                                @if ($task->tags)
                                @foreach ($task->tags as $tag)
                                <span class="tag__item">#{{ $tag->title }}</span>
                                @endforeach
                                @endif
                            </span>
                            <div>
                                @if ($editedTaskId === $task->id)
                                <button wire:click="cancelEdit" class="btn btn-dark" type="button" tabindex="0"
                                    aria-hidden="true" title="{{ __('Cancel') }}">
                                    {{ __('Cancel') }}
                                </button>
                                @else
                                @if ($task->completed_at)
                                <i wire:click="unmarkTaskCompleted({{ $task->id }})" class="bi bi-arrow-return-left"
                                    role="button" tabindex="0" aria-hidden="true" title="{{ __('Undo') }}">
                                </i>
                                @endif
                                @can('update', $list)
                                <i wire:click="edit({{ $task->id }})" class="bi bi-pencil-square" role="button"
                                    tabindex="0" aria-hidden="true" title="{{ __('Edit') }}">
                                </i>
                                @endcan
                                @can('delete', $list)
                                <i wire:click="deleteConfirm('delete', {{ $task->id }})"
                                    class="bi bi-trash3 text-danger" role="button" tabindex="0" aria-hidden="true"
                                    title="{{ __('Delete') }}">
                                </i>
                                @endcan
                                @endif
                            </div>
                        </div>
                    </li>
                    @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
