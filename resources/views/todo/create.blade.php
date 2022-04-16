<form name="new_todo" method="POST"
    action="@isset($user) {{ route('admin.user.todo.create', $user->id) }} @else {{ route('todo.store') }} @endisset">
    @csrf
    <div class="new_todo">
        <div class="row add_new_button" @if ($errors->any() || Session::has('error')) style="display:none;" @endif>
            <div class="col">
                <i class="fa-solid fa-plus"></i> Add Todo
            </div>
        </div>
        <div class="add_new_panel" @if ($errors->any() || Session::has('error')) style="display:block;" @endif>
            <div class="row">
                <div class="col-2">
                    <label class="col-form-label">Title *</label>
                </div>
                <div class="col-10">
                    <input type="text" name="title" class="form-control" placeholder="Enter Title"
                        value="{{ old('title') }}" required />
                    @if ($errors->has('title'))
                        <div class="error">{{ $errors->first('title') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group row mt-1">
                <div class="col-2">
                    <label class="col-form-label">Description</label>
                </div>
                <div class="col-10">
                    <textarea name="description" class="form-control"
                        placeholder="Enter Description">{{ old('description') }}</textarea>
                    @if ($errors->has('description'))
                        <div class="error">{{ $errors->first('description') }}</div>
                    @endif
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-2">
                    <label class="col-form-label">Due Date</label>
                </div>
                <div class="col-10">
                    <input type="text" name="due_date" class="form-control datetimepicker" placeholder="Select Due Date"
                        value="{{ old('due_date') }}" />
                    @if ($errors->has('due_date'))
                        <div class="error">{{ $errors->first('due_date') }}</div>
                    @endif
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-2">
                    <button type="submit" class="btn_new_todo btn btn-primary">Add Todo</button>
                </div>
                <div class="col-2">
                    <button type="button" name="btn_cancel_todo"
                        class="btn_cancel_todo btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</form>
