  <!--Module Table Start -->
  <div class="col-span-full xl:col-span-8 bg-white dark:bg-gray-800 shadow-sm rounded-xl">

      <header class="flex justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
          <h2 class="font-semibold text-gray-800 dark:text-gray-100">Backup List</h2>

          <div class="flex justify-between">
              <!-- Restore Backup -->
              <div class="mt-4">
                  <button wire:click="createBackup" class="mt-2 bg-slate-400 text-white px-2 py-1 rounded"> <i
                          class="fa fa-plus-circle"></i> Create Backup</button>

                  <button wire:click="restoreBackup" class="mt-2 bg-slate-400 text-white px-2 py-1 rounded"> <i
                          class="fa fa-plus-circle"></i>
                      Restore Backup
                  </button>
                  <input type="file" wire:model="backupFile" accept=".sql">
              </div>

          </div>
      </header>
      <div class="p-3">
          <!-- Table -->
          <div class="overflow-x-auto">
              <table id="backuprestore_table" class="table-auto w-full dark:text-gray-300">
                  <!-- Table header -->
                  <thead
                      class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-sm">
                      <tr>
                          <th class="p-2">
                              <div class="font-semibold text-left">#</div>
                          </th>
                          <th class="p-2">
                              <div class="font-semibold text-center">Action</div>
                          </th>

                          <th class="p-2">
                              <div class="font-semibold text-center">File type</div>
                          </th>
                          <th class="p-2">
                              <div class="font-semibold text-center">File size</div>
                          </th>
                          <th class="p-2">
                              <div class="font-semibold text-center">Created At</div>
                          </th>
                          <th class="p-2">
                              <div class="font-semibold text-center">Creatd By</div>
                          </th>
                          <th class="p-2">
                              <div class="font-semibold text-center">File path</div>
                          </th>

                      </tr>
                  </thead>
                  <!-- Table body -->
                  <tbody class="text-sm font-medium divide-y divide-gray-100 dark:divide-gray-700/60">
                      <!-- Row -->

                      @foreach ($this->backups as $backup)
                          <tr>
                              <td class="p-2">
                                  <div class="flex items-center">
                                      <div class="text-gray-800 dark:text-gray-100">{{ $backup->id }}</div>
                                  </div>
                              </td>
                              <td class="p-2">
                                  <div class="text-center text-sky-500">
                                      <a><i class="fas fa-edit" style="color:cyan" title="Edit"></i></a>
                                      <a wire:click="delete({{ $backup->id }})" style="cursor: pointer;"
                                          title="Delete">
                                          <i class="fas fa-trash" style="color:purple"></i></a>
                                  </div>
                              </td>


                              <td class="p-2">
                                  <div class="text-center">{{ $backup->file_type }}</div>
                              </td>
                              <td class="p-2">
                                  <div class="text-center">{{ $backup->file_size }}</div>
                              </td>
                              <td class="p-2">
                                  <div class="text-center">{{ $backup->created_at }}</div>
                              </td>
                              <td class="p-2">
                                  <div class="text-center text-green-500">{{ $backup->username }}</div>
                              </td>
                              <td class="p-2">
                                  <div class="text-center">{{ $backup->file_path }}</div>
                              </td>


                          </tr>
                      @endforeach

                      @if (!$this->backups || count($this->backups) == 0)
                          <h3 class="flex justify-center font-semibold text-gray-800 dark:text-gray-100">No data
                              is found!
                          </h3>
                      @endif
                  </tbody>
          </div>
      </div>
  </div>
  <!-- Module Table End-->
  @script()
  <script>
      document.addEventListener('livewire:initialized', () => {
          datatable('#backuprestore_table');
      });
  </script>
  @endscript()
