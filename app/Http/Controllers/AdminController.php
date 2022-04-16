<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Todo;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\EditTodoRequest;
use Illuminate\Auth\Access\AuthorizationException;
use App\Enums\UserRoleEnum;

class AdminController extends Controller
{
    //
    public function users_list()
    {
        $users = User::withCount(['todos'])->where('role_id', UserRoleEnum::User)->get();
        return view('admin.users', ['users' => $users]);
    }

    public function users_todos($user_id)
    {
        $user = User::find($user_id);
        if (empty($user)) {
            return redirect()->route('admin.users.list')->with(['error' => 'User not found, try again']);
        }
        $todos = Todo::where('user_id', $user_id)->where('is_completed', 0)->orderby('due_date')->get();
        $deleted_todos = Todo::where('user_id', $user_id)->onlyTrashed()->get();
        $completed_todos = Todo::where('user_id', $user_id)->where('is_completed', 1)->get();
        return view('admin.todos', ['todos' => $todos, 'completed_todos' => $completed_todos, 'deleted_todos' => $deleted_todos, 'user' => $user]);
    }

    public function create_todo($user_id, StoreTodoRequest $request)
    {
        $validated_data = $request->validated();
        $user = User::find($user_id);

        try {
            if (empty($user)) {
                return redirect()->route('admin.users.list')->with(['error' => 'Invalid user, please try again']);
            }
            Todo::create([
                'user_id' => $user_id,
                'title' => $validated_data['title'],
                'description' => $validated_data['description'],
                'due_date' => system_date($validated_data['due_date'])
            ]);
        } catch (Exception $e) {
            return back()->withInput()->with(['error' => 'Couldn\'t add Todo, please try again']);
        }
        return redirect()->route('admin.user.todos', ['id' => $user_id])->with(['message' => 'Todo added successfully']);
    }

    public function edit_todo($id)
    {
        $todo = Todo::withTrashed()->find($id);
        $user = $todo->user;
        try {
            if ($this->authorize('view', $todo)) {
                $html = view('todo.edit', ['user' => $user])->with('todo', $todo)->render();
                return response()->json(['success' => true, 'html' => $html]);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['success' => false, 'html' => 'You are unauthorized to access that Todo']);
        }
    }

    public function update_todo($id, EditTodoRequest $request)
    {
        $validated_data = $request->validated();
        $todo = Todo::withTrashed()->find($id);
        try {
            if ($this->authorize('update', $todo)) {
                // Update the todo
                $user_id = $todo->user_id;
                $todo->title = $validated_data['edit_title'];
                $todo->description = $validated_data['edit_description'];
                $todo->due_date = system_date($validated_data['edit_due_date']);
                $todo->is_completed = $validated_data['edit_is_completed'] ?? 0;
                $todo->completed_at = $todo->is_completed ? date('Y-m-d H:i:s') : null;
                $todo->save();

                return redirect()->route('admin.user.todos', $user_id)->with(['message' => 'Todo updated successfully']);
            }
        } catch (AuthorizationException $e) {
            return back()->with(['error' => 'You are unauthorized to access that Todo']);
        } catch (Exception $e) {
            return back()->with(['error' => 'Couldn\'t update your Todo, please try again']);
        }
    }

    public function change_status_todo($id, Request $request)
    {
        $todo = Todo::withTrashed()->find($id);
        $is_completed = $request->input('is_completed') ?? 0;

        $message = $is_completed ? "completed" : "incomplete";
        try {
            if ($this->authorize('update', $todo)) {
                $user_id = $todo->user_id;
                // Update the todo
                // Update the todo
                $todo->is_completed = $is_completed;
                $todo->completed_at = $is_completed ? date('Y-m-d H:i:s') : null;
                $todo->save();
                return redirect()->route('admin.user.todos', $user_id)->with(['message' => "Todo marked as $message successfully"]);
            }
        } catch (AuthorizationException $e) {
            return back()->with(['error' => 'You are unauthorized to access that Todo']);
        } catch (Exception $e) {
            return back()->with(['error' => 'Couldn\'t update your Todo, please try again']);
        }
    }

    public function delete_todo($id)
    {
        $todo = Todo::find($id);
        try {
            if ($this->authorize('delete', $todo)) {
                $user_id = $todo->user_id;

                $todo->delete();
                return redirect()->route('admin.user.todos', $user_id)->with(['message' => "Todo deleted successfully"]);
            }
        } catch (AuthorizationException $e) {
            return back()->with(['error' => 'You are unauthorized to access that Todo']);
        } catch (Exception $e) {
            return back()->with(['error' => 'Couldn\'t delete your Todo, please try again']);
        }
    }

    public function restore_todo($id)
    {
        $todo = Todo::withTrashed()->find($id);
        try {
            if ($this->authorize('restore', $todo)) {
                $user_id = $todo->user_id;

                $todo->restore();
                return redirect()->route('admin.user.todos', ['id' => $user_id])->with(['message' => "Todo restored successfully"]);
            }
        } catch (AuthorizationException $e) {
            return back()->with(['error' => 'You are unauthorized to access that Todo']);
        } catch (Exception $e) {
            return back()->with(['error' => 'Couldn\'t restore Todo, please try again']);
        }
    }
}
