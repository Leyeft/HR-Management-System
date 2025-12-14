<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Departments
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <a href="{{ route('departments.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
            + Add Department
        </a>

        <table class="mt-4 w-full border">
            <tr class="bg-gray-200 dark:bg-gray-700">
                <th class="p-2">ID</th>
                <th class="p-2">Name</th>
            </tr>

            @foreach ($departments as $department)
                <tr>
                    <td class="p-2">{{ $department->id }}</td>
                    <td class="p-2">{{ $department->name }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</x-app-layout>
