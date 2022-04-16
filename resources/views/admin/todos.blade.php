@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (Session::has('message'))
                    <p class="alert alert-success">{{ Session::get('message') }}</p>
                @elseif(Session::has('error'))
                    <p class="alert alert-danger">{{ Session::get('error') }}</p>
                @endif
                <div class="row mb-4">
                    <div class="col">
                        <a href="{{ route('admin.users.list') }}">
                            <i class="fa-solid fa-left-long"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">{{ __('Upcoming Todos of ') }} {{ $user->name }}</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="todo row">
                            <div class="col-2 text-center">
                                &nbsp;
                            </div>
                            <div class="col-8">
                                Todos
                            </div>
                            <div class="col-2 text-center">
                                &nbsp;
                            </div>
                        </div>
                        @if ($todos->isNotEmpty())
                            @foreach ($todos as $todo)
                                @include('todo.todo', ['todo' => $todo])
                            @endforeach
                        @else
                            <p class="alert alert-light">There are no todos. Let's create one</p>
                        @endif

                        @include('todo.create', ['user_id' => $user->id])
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Completed Todos of ') }} {{ $user->name }}</div>
                    <div class="card-body">
                        @if ($completed_todos->isNotEmpty())
                            @foreach ($completed_todos as $completed_todo)
                                @include('todo.todo', ['todo' => $completed_todo])
                            @endforeach
                        @else
                            <p class="alert alert-light">Nothing here</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Deleted Todos of ') }} {{ $user->name }}</div>
                    <div class="card-body">
                        @if ($deleted_todos->isNotEmpty())
                            @foreach ($deleted_todos as $deleted_todos)
                                @include('todo.todo', ['todo' => $deleted_todos, 'deleted_notes' => true])
                            @endforeach
                        @else
                            <p class="alert alert-light">Nothing here</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal edit_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Todo</h5>
                    <button type="button" class="btn" data-bs-dismiss="modal"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(function() {
            $('.add_new_button').click(function() {
                let add_new_button = $(this);
                add_new_button.parents().find('.add_new_panel').show();
                add_new_button.hide();
            })

            $('.btn_cancel_todo').click(function() {
                let cancel_button = $(this);
                let new_todo = cancel_button.parents().find('.new_todo');
                new_todo.find('.add_new_button').show();
                new_todo.find('.add_new_panel').hide();
            })

            $('body').on('focus', ".datetimepicker", function() {
                var selector = $(this);
                selector.not('.flatpickr-input').flatpickr({
                    todayHighlight: true,
                    changeMonth: true,
                    changeYear: true,
                    enableTime: true,
                    dateFormat: "d-M-Y G:i K",
                    position: "auto center"
                })
            })

            $('.todo_edit').click(function() {
                let todo_div = $(this).closest('.todo');
                let todo_id = todo_div.find('input[name="id"]').val();

                $.ajax("/admin/users/todo/edit/" + todo_id)
                    .done(function(response) {
                        console.log(response);
                        if (response.success) {
                            $('.edit_modal').find('.modal-body').html(response.html);
                        } else {
                            $('.edit_modal').find('.modal-body').html("<p class='alert alert-danger'>" +
                                response.html + "</p>");
                        }

                        $('.edit_modal').modal('show');
                    })
            })

            $('.todo_delete').click(function() {
                let delete_todo_form = $(this).find('form.delete_todo');

                if (confirm('Are you sure you want to delete this todo?')) {
                    delete_todo_form.submit();
                }
            })

            $('.todo_restore').click(function() {
                let restore_todo_form = $(this).find('form.restore_todo');

                if (confirm('Are you sure you want to restore this todo?')) {
                    restore_todo_form.submit();
                }
            })

            $('.todo_complete_action').change(function() {
                let change_status_form = $(this).closest('form.change_status_form');
                change_status_form.submit();
            })

        })
    </script>
@endpush
