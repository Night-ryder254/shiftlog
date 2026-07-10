<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $branch->name }}
        </h2>
    </x-slot>

    <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
    <h3 class="font-semibold text-lg mb-4">Attendance — Last 7 Days</h3>

    @if ($recentAssignments->isEmpty())
        <p class="text-gray-500">No shifts scheduled in the last 7 days.</p>
    @else
        <table class="w-full text-left">
            <thead>
                <tr class="border-b text-sm text-gray-500">
                    <th class="py-2">Date</th>
                    <th class="py-2">Employee</th>
                    <th class="py-2">Shift</th>
                    <th class="py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recentAssignments as $assignment)
                    <tr class="border-b">
                        <td class="py-2">{{ \Carbon\Carbon::parse($assignment->date)->format('D, M j') }}</td>
                        <td class="py-2">{{ $assignment->employee->user->name }}</td>
                        <td class="py-2">{{ $assignment->shift->label }} ({{ $assignment->shift->start_time }} - {{ $assignment->shift->end_time }})</td>
                        <td class="py-2">
                            @php $status = $assignment->attendanceRecord->status ?? 'absent'; @endphp
                            <span @class([
                                'px-2 py-1 rounded text-xs font-medium',
                                'bg-green-100 text-green-700' => $status === 'on_time',
                                'bg-yellow-100 text-yellow-700' => $status === 'late',
                                'bg-red-100 text-red-700' => $status === 'absent',
                            ])>
                                {{ str_replace('_', ' ', ucfirst($status)) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($branch->departments as $department)
                <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-4">
                    <h3 class="font-semibold text-lg mb-2">{{ $department->name }}</h3>
                    <ul>
                        @foreach ($department->employees as $employee)
                            <li class="text-gray-700">{{ $employee->user->name }} — {{ $employee->role }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
