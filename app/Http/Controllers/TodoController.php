<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Todo;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\EditTodoRequest;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;

class TodoController extends Controller
{
    //
    public function index()
    {
        $todos = Todo::where('user_id', Auth::id())->where('is_completed', 0)->orderby('due_date')->get();
        $completed_todos = Todo::where('user_id', Auth::id())->where('is_completed', 1)->get();

        return view('user.index', ['todos' => $todos, 'completed_todos' => $completed_todos]);
    }

    public function store(StoreTodoRequest $request)
    {
        $validated_data = $request->validated();

        try {
            Todo::create([
                'user_id' => Auth::id(),
                'title' => $validated_data['title'],
                'description' => $validated_data['description'],
                'due_date' => system_date($validated_data['due_date'])
            ]);
            return redirect()->route('home')->with(['message' => 'Todo added successfully']);
        } catch (Exception $e) {
            return back()->withInput()->with(['error' => 'Couldn\'t add Todo, please try again']);
        }
    }

    public function edit($id)
    {
        $todo = Todo::find($id);
        try {
            if ($this->authorize('view', $todo)) {
                $html = view('todo.edit')->with('todo', $todo)->render();
                return response()->json(['success' => true, 'html' => $html]);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['success' => false, 'html' => 'You are unauthorized to access that Todo']);
        }
    }

    public function update($id, EditTodoRequest $request)
    {
        $validated_data = $request->validated();
        $todo = Todo::find($id);

        try {
            if ($this->authorize('update', $todo)) {
                // Update the todo
                $todo->title = $validated_data['edit_title'];
                $todo->description = $validated_data['edit_description'];
                $todo->due_date = system_date($validated_data['edit_due_date']);
                $todo->is_completed = $validated_data['edit_is_completed'] ?? 0;
                $todo->completed_at = $todo->is_completed ? date('Y-m-d H:i:s') : null;
                $todo->save();
                return redirect()->route('home')->with(['message' => 'Todo updated successfully']);
            }
        } catch (AuthorizationException $e) {
            return back()->with(['error' => 'You are unauthorized to access that Todo']);
        } catch (Exception $e) {
            return back()->with(['error' => 'Couldn\'t update your Todo, please try again']);
        }
    }

    public function change_status($id, Request $request)
    {
        $todo = Todo::find($id);
        $is_completed = $request->input('is_completed') ?? 0;
        $message = $is_completed ? "completed" : "incomplete";
        try {
            if ($this->authorize('update', $todo)) {
                // Update the todo
                // Update the todo
                $todo->is_completed = $is_completed;
                $todo->completed_at = $is_completed ? date('Y-m-d H:i:s') : null;
                $todo->save();
                return redirect()->route('home')->with(['message' => "Todo marked as $message successfully"]);
            }
        } catch (AuthorizationException $e) {
            return back()->with(['error' => 'You are unauthorized to access that Todo']);
        } catch (Exception $e) {
            return back()->with(['error' => 'Couldn\'t update your Todo, please try again']);
        }
    }

    public function delete($id)
    {
        $todo = Todo::find($id);
        try {
            if ($this->authorize('delete', $todo)) {
                $todo->delete();
                return redirect()->route('home')->with(['message' => "Todo deleted successfully"]);
            }
        } catch (AuthorizationException $e) {
            return back()->with(['error' => 'You are unauthorized to access that Todo']);
        } catch (Exception $e) {
            return back()->with(['error' => 'Couldn\'t delete your Todo, please try again']);
        }
    }

    public function restore($id)
    {
        $todo = Todo::withTrashed()->find($id);
        try {
            if ($this->authorize('restore', $todo)) {
                $todo->restore();
                return redirect()->route('home')->with(['message' => "Todo restored successfully"]);
            }
        } catch (AuthorizationException $e) {
            return back()->with(['error' => 'You are unauthorized to access that Todo']);
        } catch (Exception $e) {
            return back()->with(['error' => 'Couldn\'t delete your Todo, please try again']);
        }
    }
}
