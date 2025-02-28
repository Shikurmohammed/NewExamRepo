  <!--Module Table Start -->
  <div class="col-span-full xl:col-span-8 bg-white dark:bg-gray-800 shadow-sm rounded-xl">

      <header class="flex justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
          <h2 class="font-semibold text-gray-800 dark:text-gray-100">Test List</h2>

          <div class="flex justify-between">
              @livewire('exams.test-modal')
              @livewire('exams.question-assignment-modal')

          </div>
      </header>
      <div class="p-3">
          <!-- Table -->
          <div class="overflow-x-auto">
              <table id="test_table" class="table-auto w-full dark:text-gray-300">
                  <!-- Table header -->
                  <thead
                      class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-sm">
                      <tr>
                          <th class="p-2">
                              <div class="font-semibold text-left">#</div>
                          </th>
                          <th class="p-2">
                              <div class="font-semibold text-center">Exam name</div>
                          </th>
                          <th class="p-2">
                              <div class="font-semibold text-center">Description</div>
                          </th>
                          <th class="p-2">
                              <div class="font-semibold text-center">Start</div>
                          </th>
                          <th class="p-2">
                              <div class="font-semibold text-center">End</div>
                          </th>
                          <th class="p-2">
                              <div class="font-semibold text-center">Action</div>
                          </th>
                      </tr>
                  </thead>
                  <!-- Table body -->
                  <tbody class="text-sm font-medium divide-y divide-gray-100 dark:divide-gray-700/60">
                      <!-- Row -->
                      @foreach ($this->tests as $test)
                          <tr wire:key={{ $test->id }}>
                              <td class="p-2">
                                  <div class="flex items-center">
                                      <div class="text-gray-800 dark:text-gray-100">{{ $test->id }}</div>
                                  </div>
                              </td>
                              <td class="p-2">
                                  <div class="text-center">{{ $test->name }}</div>
                              </td>
                              <td class="p-2">
                                  <div class="text-center text-green-500">{{ $test->description }}</div>
                              </td>
                              <td class="p-2">
                                  <div class="text-center">{{ $test->start }}</div>
                              </td>
                              <td class="p-2">
                                  <div class="text-center">{{ $test->end }}</div>
                              </td>
                              <td class="p-2">
                                  <div class="text-center text-sky-500">
                                      <a href="{{ url('edit_category', $test->id) }}"><i class="fas fa-edit"
                                              style="color:cyan" title="Edit"></i></a>

                                      @if ($test->isLocked == 0)
                                          <a wire:click="lockTest({{ $test->id }})"><i class="fas fa-unlock"
                                                  style="color:cyan" title="Lock Test"></i></a>
                                      @else
                                          <a wire:click="unLockTest({{ $test->id }})"><i class="fas fa-lock"
                                                  style="color:crimson" title="Unlock Test"></i></a>
                                      @endif

                                      <a wire:click="delete({{ $test->id }})" style="cursor: pointer;"
                                          title="Delete">
                                          <i class="fas fa-trash" style="color:purple"></i></a>

                                  </div>
                              </td>
                          </tr>
                      @endforeach
                  </tbody>
                  @if (!$this->tests || count($this->tests) == 0)
                      <h3 class="flex justify-center font-semibold text-gray-800 dark:text-gray-100">No data
                          is found!
                      </h3>
                  @endif
              </table>
          </div>
      </div>
  </div>
