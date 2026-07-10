<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Branches
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <ul class="divide-y divide-gray-200">
                    @foreach ($branches as $branch)
                        <li class="py-4 flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-900">{{ $branch->name }}</p>
                                <p class="text-sm text-gray-500">{{ $branch->location }}</p>
                            </div>
                            <a href="{{ route('branches.show', $branch) }}" class="text-indigo-600 hover:text-indigo-900">
                                View →
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
