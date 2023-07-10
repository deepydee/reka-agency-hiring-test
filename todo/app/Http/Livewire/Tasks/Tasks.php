<?php

namespace App\Http\Livewire\Tasks;

use App\Models\Tag;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

class Tasks extends Component
{
    use WithFileUploads;

    public $list;
    public ?Task $task;
    public Collection $tasks;
    public ?array $tags = [];
    public string $taskTitle = '';
    public int $editedTaskId = 0;
    public $thumbnail;
    public bool $updateThumb = false;
    public array $searchColumns = [
        'title' => '',
    ];

    protected function rules(): array
    {
        return [
            'taskTitle' => ['required', 'string', 'min:3', 'max:255'],
            'tags' => ['nullable', 'array'],
            'thumbnail' => ['nullable', 'image'],
        ];
    }

    protected $validationAttributes = [
        'taskTitle' => 'Текст задачи',
    ];

    protected $listeners = ['delete'];

    public function updatedTaskTitle(): void
    {
        $this->validateOnly('taskTitle');
    }

    public function updatedThumbnail()
    {
        $this->validateOnly('thumbnail');
        $this->updateThumb = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editedTaskId === 0) {
            $this->task = new Task();
        }

        $this->task->title = $this->taskTitle;
        $this->task->task_list_id = $this->list->id;

        $this->task->save();

        if ($this->updateThumb) {
            $this->task->clearMediaCollection('task-image');
            $this->task
                ->addMedia($this->thumbnail)
                ->toMediaCollection('task-image');

            $this->updateThumb = false;
        }

        if (!empty($this->tags) && $this->tags[0] !== '') {
            $addedTagsIds = [];

            foreach ($this->tags as $tag) {
                $newTag = Tag::create(['title' => $tag]);
                $addedTagsIds[] = $newTag->id;
            }

            $this->task->tags()->sync($addedTagsIds);
            $this->dispatchBrowserEvent('clearStore');
        }

        $this->reset('taskTitle', 'tags');
        $this->resetValidation();
        $this->editedTaskId = 0;
    }

    function edit(Task $task): void
    {
        $this->editedTaskId = $task->id;
        $this->task = $task;
        $this->taskTitle = $task->title;
        $this->tags = $task->tags()->pluck('title')->toArray();
        $this->dispatchBrowserEvent('editTask', ['tags' => $this->tags]);
    }

    public function cancelEdit(): void
    {
        $this->resetValidation();
        $this->reset('editedTaskId', 'taskTitle');
        $this->dispatchBrowserEvent('clearStore');
    }

    public function delete(Task $task): void
    {
        $task->delete();
        $this->task = null;
    }

    public function toggleTaskCompleted(Task $task)
    {
        $task->completed_at = $task->completed_at
            ? null
            : Carbon::now();

        $task->save();
    }

    public function unmarkTaskCompleted(Task $task)
    {
        $task->completed_at = null;
        $task->save();
    }

    public function deleteConfirm($method, $id = null): void
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type'   => 'warning',
            'title'  => __('Are you sure?'),
            'text'   => '',
            'id'     => $id,
            'method' => $method,
        ]);
    }

    public function deleteTaskImage(Task $task): void
    {
        $task->clearMediaCollection('task-image');
    }

    public function render(): View
    {
        $tasks = Task::where('task_list_id', $this->list->id)->with('tags');

        foreach ($this->searchColumns as $column => $value) {
            if (!empty($value)) {
                $tasks
                    ->when($column === 'title', fn ($tasks) => $tasks->where('title', 'LIKE', '%' . $value . '%'));
            }
        }

        $this->tasks = $tasks
            ->oldest('completed_at')
            ->latest('created_at')
            ->get();

        return view('livewire.tasks.tasks');
    }
}
