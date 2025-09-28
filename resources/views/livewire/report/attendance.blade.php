<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Laporan Absensi Staf
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 pb-6 border-b">
                    <div>
                        <x-input-label for="startDate" value="Tanggal Mulai" />
                        <x-text-input wire:model.live="startDate" id="startDate" type="date" class="w-full mt-1" />
                    </div>
                    <div>
                        <x-input-label for="endDate" value="Tanggal Selesai" />
                        <x-text-input wire:model.live="endDate" id="endDate" type="date" class="w-full mt-1" />
                    </div>
                    <div>
                        <x-input-label for="userId" value="Filter Staf" />
                        <select wire:model.live="userId" id="userId" class="w-full mt-1 border-gray-300 rounded-md">
                            <option value="">Semua Staf</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Nama Staf</th>
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-center">Clock In</th>
                                <th class="px-4 py-2 text-center">Clock Out</th>
                                <th class="px-4 py-2 text-center">Total Jam Kerja</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse ($attendances as $attendance)
                            <tr>
                                <td class="px-4 py-2">{{ $attendance->user->name }}</td>
                                <td class="px-4 py-2">{{ $attendance->clock_in_time->format('d M Y') }}</td>
                                <td class="px-4 py-2 text-center">{{ $attendance->clock_in_time->format('H:i:s') }}</td>
                                <td class="px-4 py-2 text-center">
                                    {{ $attendance->clock_out_time ? $attendance->clock_out_time->format('H:i:s') : '-' }}
                                </td>
                                <td class="px-4 py-2 text-center font-semibold">
                                    @if($attendance->clock_out_time)
                                    {{ $attendance->clock_in_time->diff($attendance->clock_out_time)->format('%H jam %i menit') }}
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center">Tidak ada data absensi pada periode ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $attendances->links() }}</div>
            </div>
        </div>
    </div>
</div>
