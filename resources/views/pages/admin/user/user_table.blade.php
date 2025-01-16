<div class="col-span-full xl:col-span-8 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
    <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Module List</h2>
    </header>
    <div class="p-3">

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full dark:text-gray-300">
                <!-- Table header -->
                <thead
                    class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-sm">
                    <tr>
                        <th class="p-2">
                            <div class="font-semibold text-left">#</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-left"><input type="checkbox" id="select-all"></div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Name</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">UserName</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Eamil</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Email Verified At</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Access-Level</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Group</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Action</div>
                        </th>
                    </tr>
                </thead>
                <!-- Table body -->
                <tbody class="text-sm font-medium divide-y divide-gray-100 dark:divide-gray-700/60">
                    <!-- Row -->
                    @foreach ($user_data as $user)
                        <tr>
                            <td class="p-2">
                                <div class="flex items-center">
                                    <div class="text-gray-800 dark:text-gray-100">{{ $user->id }}</div>
                                </div>
                            </td>
                            <td class="p-2">
                                <div class="text-center"><input type="checkbox" class="user-checkbox"
                                        name="selected_users[]" value="{{ $user->id }}"></div>
                            </td>
                            <td class="p-2">
                                <div class="text-center  text-green-500">{{ $user->name }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $user->username }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $user->email }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $user->email_verified_at }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $user->access_level }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">
                                    @if ($user->groups->isEmpty())
                                        None
                                    @else
                                        @foreach ($user->groups as $group)
                                            {{ $group->name }},
                                        @endforeach
                                    @endif
                            </td>
                            <td class="p-2">
                                <div class="text-center text-sky-500">
                                    <a href="{{ url('edit_user', $user->id) }}"><i class="fas fa-edit"
                                            style="color:cyan"></i></a>
                                    <a href="{{ url('delete_user', $user->id) }}" onclick="confirmDelete(event)">
                                        <i class="fas fa-trash" style="color:purple"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>

        </div>
    </div>

    <div class="p-3">
        <form method="post" id="user_groups_form" action="{{ route('users.updateSelected') }}">
            @csrf
            <input type="hidden" name="user_id" id="selected_users" value="">

            <div class="form-group row">
                <div class="col-lg-5">
                    <label class="form-control-label">From </label>
                    <select name="group_id[]" id="group_id " class="form-control group_id" required multiple>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-5">
                    <label class="form-control-label">To</label>
                    <select name="group_id1[]" id="group_id1" class="form-control group_id" multiple>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row" style="display: flex; justify-content:center;">
                <div class="col-lg-1">
                    <button type="button" class="btn btn-danger btn-sm"
                        onclick="deleteUserFromGroups(event)">Delete</button>

                </div>
                <div class="col-lg-1">
                    <input type="submit" value="MOVE" class="btn btn-primary btn-sm">
                </div>
                <div class="col-lg-1">
                    <button type="submit" value="ADD" class="btn btn-success btn-sm">Add</button>
                    {{--  onclick="addSelectedUsersTo(event)" --}}
                </div>
            </div>
        </form>
    </div>
</div>
