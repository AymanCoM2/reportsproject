@extends('dash')
@section('main-content')
    @foreach ($allUsers as $user)
        <div class="card d-flex justify-content-center mx-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="font-weight-bold">
                            <a href="{{ route('users.manage.show', $user->id) }}" class="">{{ $user->name }}</a>
                        </p>
                        <p>{{ $user->role->role_name }}</p>
                    </div>

                    <div>
                        <a href="{{ route('users.manage.edit', $user->id) }}" class="mx-3">Edit User</a>
                        @php
                            if (!$user->approved && $user->email_verified_at):
                                echo "<span class='text-success font-weight-bold'>Verified</span> <span class='font-weight-bold text-danger'>Not Approved</span>";
                            elseif (!$user->approved && !$user->email_verified_at):
                                echo "<span class='text-danger font-weight-bold'>Not Verified</span> <span class='font-weight-bold text-danger'>Not Approved</span>";
                            endif;
                        @endphp
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('creation-link')
@endsection
