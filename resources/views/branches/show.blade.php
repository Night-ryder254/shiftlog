<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $branch->name }}
        </h2>
    </x-slot>

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
