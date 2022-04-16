@if (!empty($todo))
    <div class="todo @if ($todo->is_completed) completed_todo @endif">
        <input type="hidden" name="id" value="{{ $todo->id }}">
        <div class="row">
            <div class="col-2">
                <div class="d-flex justify-content-center mt-1">
                    <form
                        action="@isset($user) {{ route('admin.user.todo.change_status', $todo->id) }} @else {{ route('todo.change_status', $todo->id) }} @endisset"
                        method="post" class="change_status_form">

                        @csrf
                        <input class="form-check-input todo_complete_action" name="is_completed" type="checkbox"
                            value="1" @if ($todo->is_completed) checked @endif>
                    </form>
                </div>
            </div>
            <div class="col-8">
                <div class="row">
                    <div class="col todo_title">
                        {{ $todo->title }}
                    </div>
                </div>
                <div class="row">
                    <div class="col todo_description">
                        {{ $todo->description }}
                    </div>
                </div>
                <div class="row">
                    @if ($todo->is_completed)
                        <div class="col todo_due" title="Due date">
                            <span class="no-strike-trough">
                                <i class="fa-solid fa-calendar-days"></i>
                            </span>
                            {{ readable_date($todo->due_date) }}
                        </div>
                        <div class="col todo_completed_date" title="Completed date">
                            <span class="no-strike-trough">
                                <i class="fa-solid fa-calendar-check"></i>
                            </span>
                            {{ readable_date($todo->completed_at) }}
                        </div>
                    @else
                        <div class="col todo_due" title="Due date">
                            @if ($todo->due_date < date('Y-m-d H:i:s'))
                                <span class="overdue" title="overdue">
                                    <i class="fa-solid fa-hourglass-end"></i>
                                    {{ readable_date($todo->due_date) }}
                                </span>
                            @else
                                <i class="fa-solid fa-calendar-days"></i>
                                {{ readable_date($todo->due_date) }}
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-2">
                <div class="col todo_controls">
                    <div class="todo_edit">
                        <i class="fa-solid fa-pencil"></i>
                    </div>
                    @isset($deleted_notes)
                        <div class="todo_restore">
                            <form name="restore_todo" class="restore_todo"
                                action="@isset($user) {{ route('admin.user.todo.restore', $todo->id) }} @else {{ route('todo.restore', $todo->id) }} @endisset"
                                method="POST">
                                @csrf
                                <i class="fa-solid fa-trash-can-arrow-up"></i>
                            </form>
                        </div>
                    @else
                        <div class="todo_delete">
                            <form name="delete_todo" class="delete_todo"
                                action="@isset($user) {{ route('admin.user.todo.delete', $todo->id) }} @else {{ route('todo.delete', $todo->id) }} @endisset"
                                method="POST">
                                @csrf
                                <i class="fa-solid fa-trash-can"></i>
                            </form>
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
@endif
