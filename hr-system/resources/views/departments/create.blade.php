<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Create Department
        </h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto">
        <form method="POST" action="{{ route('departments.store') }}">
            @csrf

            <div>
                <label class="block">Department Name</label>
                <input type="text" name="name" class="w-full border p-2 mt-1" required>
            </div>

            <button class="mt-4 bg-green-600 text-white px-4 py-2 rounded">
                Save
            </button>
        </form>
    </div>
</x-app-layout>
