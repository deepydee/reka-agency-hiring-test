<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskListRequest;
use App\Http\Requests\UpdateTaskListRequest;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $myLists = TaskList::where('owner_id', auth()->id())
            ->with('users')
            ->get();

        $sharedLists = TaskList::whereHas('users', function (Builder $query) {
            $query->where('id', auth()->id());
        })->where('owner_id', '!=', auth()->id())->with('users')->get();

        return view('lists.index', compact('myLists', 'sharedLists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = User::all()->except(auth()->id());

        return view('lists.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskListRequest $request): RedirectResponse
    {
        $attachedUsersIds = [
            auth()->id(),
        ];

        if ($request->users) {
            foreach ($request->users as $uid) {
                $user = User::findOrFail($uid);

                if ($request->canUpdate) {
                    $user->givePermissionsTo('update');
                }

                if ($request->canDelete) {
                    $user->givePermissionsTo('delete');
                }
            }

            $attachedUsersIds = array_merge($attachedUsersIds, $request->users);
        }

        $list = TaskList::create([
            ...$request->validated(),
            'owner_id' => auth()->id(),
        ]);

        $list->users()->sync($attachedUsersIds);

        return to_route('lists.index')
            ->with('message', "Task list '{$list->title}' has been successfully created");
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskList $list): View
    {
        Gate::allowIf(fn (User $user) => in_array($user->id, $list->users()->pluck('id')->toArray()));

        return view('lists.show', compact('list'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskList $list)
    {
        Gate::allowIf(fn (User $user) => $user->id === $list->owner_id);

        $list->load('users');
        $users = User::all()->except(auth()->id());

        return view('lists.edit', compact('users', 'list'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskListRequest $request, TaskList $list)
    {
        Gate::allowIf(fn (User $user) => $user->id === $list->owner_id);

        $attachedUsersIds = [
            auth()->id(),
        ];

        if ($request->users) {
            foreach ($request->users as $uid) {
                $user = User::findOrFail($uid);
                $user->flushPermissions();

                if ($request->canUpdate) {
                    $user->givePermissionsTo('update');
                }

                if ($request->canDelete) {
                    $user->givePermissionsTo('delete');
                }
            }

            $attachedUsersIds = array_merge($attachedUsersIds, $request->users);
        }

        $list->update([
            ...$request->validated(),
            'owner_id' => auth()->id(),
        ]);

        $list->users()->sync($attachedUsersIds);

        return to_route('lists.index')
            ->with('message', "Task list '{$list->title}' has been successfully updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskList $list): RedirectResponse
    {
        Gate::allowIf(fn (User $user) => $user->id === $list->owner_id);

        if ($list->tasks()->count()) {
            return to_route('lists.index')
                ->with('message', __('You should remove all child tasks before remove this list'));
        }

        $list->users()->each(function ($user) {
            $user->flushPermissions();
        });

        $list->delete();

        return to_route('lists.index')
            ->with('message', "Task list '{$list->title}' has been successfully deleted");
    }
}
