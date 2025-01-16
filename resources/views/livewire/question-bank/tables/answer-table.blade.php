
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
                            <div class="font-semibold text-center">Question</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Answer</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Explanation</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">isCorrect</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">isEnabled</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Position</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Action</div>
                        </th>
                    </tr>
                </thead>
                <!-- Table body -->
                <tbody class="text-sm font-medium divide-y divide-gray-100 dark:divide-gray-700/60">
                    <!-- Row -->
                    @foreach ($this->answers as $answer)
                        <tr>
                            <td class="p-2">
                                <div class="flex items-center">
                                    <div class="text-gray-800 dark:text-gray-100">{{ $answer->id }}</div>
                                </div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $answer->question->description }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $answer->description }}</div>
                            </td>

                            <td class="p-2">
                                <div class="text-center">{{ $answer->explanation }}</div>
                            </td>

                            <td class="p-2">
                                <div class="text-center text-green-500">
                                    @if ($answer->is_right == 1)
                                        <i class="fas fa-check-circle" style="color:rgb(0, 128, 68)"></i>
                                    @else
                                        <i class="fas fa-times-circle" style="color:purple"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">
                                    @if ($answer->enabled == 1)
                                        <i class="fas fa-check-circle" style="color:rgb(0, 128, 68)"></i>
                                    @else
                                        <i class="fas fa-times-circle" style="color:purple"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="p-2">
                                <div class="text-center">{{ $answer->position }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center text-sky-500">
                                    <a><i class="fas fa-edit" style="color:cyan"></i></a>
                                    <a style="cursor: pointer;">
                                        <i class="fas fa-trash" style="color:purple"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $this->answers->links('vendor.pagination.tailwind') }}
        </div>

<!-- topic Table End-->
