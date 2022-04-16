<form name="edit_todo" method="POST"
    action="@isset($user) {{ route('admin.user.todo.update', $todo->id) }} @else {{ route('todo.update', $todo->id) }} @endisset">
    @csrf
    <input type="hidden" name="todo_id" value="{{ $todo->id }}">
    <div class="edit_todo">
        <div class="edit_panel" @if ($errors->any() || Session::has('error')) style="display:block;" @endif>
            <div class="form-group row">
                <div class="col-2">
                    <label class="col-form-label">Title *</label>
                </div>
                <div class="col-10">
                    <input type="text" name="edit_title" class="form-control" placeholder="Enter Title"
                        value="{{ old('edit_title') ?? $todo->title }}" required />
                    @if ($errors->has('edit_title'))
                        <div class="error">{{ $errors->first('edit_title') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group row mt-2">
                <div class="col-2">
                    <label class="col-form-label">Description</label>
                </div>
                <div class="col-10">
                    <textarea name="edit_description" class="form-control"
                        placeholder="Enter Description">{{ old('edit_description') ?? $todo->description }}</textarea>
                    @if ($errors->has('edit_description'))
                        <div class="error">{{ $errors->first('edit_description') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group row mt-2">
                <div class="col-2">
                    <label class="col-form-label">Due Date</label>
                </div>
                <div class="col-10">
                    <input type="text" name="edit_due_date" class="form-control datetimepicker"
                        placeholder="Select Due Date"
                        value="{{ old('edit_due_date') ?? readable_date($todo->due_date) }}" />
                    @if ($errors->has('due_date'))
                        <div class="error">{{ $errors->first('edit_due_date') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group row mt-2">
                <div class="col-2">
                    <label class="col-form-label">Is Completed?</label>
                </div>
                <div class="col-10">
                    <input type="checkbox" name="edit_is_completed" class="form-check-input"
                        @if ($todo->is_completed) checked @endif value="1" />
                    @if ($errors->has('edit_is_completed'))
                        <div class="error">{{ $errors->first('edit_is_completed') }}</div>
                    @endif
                </div>
            </div>
            <div class="row mt-2">
                <div class="col">
                    <button type="submit" class="btn_new_todo btn btn-primary">Edit Todo</button>
                    &nbsp;&nbsp;<button type="button" class="btn btn_cancel_todo btn btn-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</form>
