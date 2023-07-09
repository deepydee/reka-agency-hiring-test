<?php

namespace App\Http\Livewire\Tasks;

use App\Models\Tag;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class TaskList extends Component
{
    use WithPagination, WithFileUploads;

    public ?Task $task;
    public Collection $tasks;
    public ?array $tags = [];
    public string $taskTitle = '';
    public int $editedTaskId = 0;

    protected function rules(): array
    {
        return [
            'taskTitle' => ['required', 'string', 'min:3', 'max:255'],
            'tags' => ['nullable', 'array'],
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

    public function save(): void
    {
        dd($this->tags);
        $this->validate();

        if ($this->editedTaskId === 0) {
            $this->task = new Task();
        }

        $this->task->title = $this->taskTitle;
        $this->task->user_id = auth()->user()->id;

        $this->task->save();

        if (! empty($this->tags) && $this->tags[0] !== '') {
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

    public function markTaskCompleted(Task $task)
    {
        $task->completed_at = Carbon::now();
        $task->save();
    }

    public function unmarkTaskCompleted(Task $task)
    {
        $task->completed_at = null;
        $task->save();
    }

    public function deleteConfirm($method, $id = null)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type'   => 'warning',
            'title'  => 'Вы уверены?',
            'text'   => '',
            'id'     => $id,
            'method' => $method,
        ]);
    }

    public function render(): View
    {
        $this->tasks = Task::where('user_id', auth()->user()->id)
            ->with('tags')
            ->oldest('completed_at')
            ->latest('created_at')
            ->get();

        return view('livewire.tasks.task-list');
    }
}
