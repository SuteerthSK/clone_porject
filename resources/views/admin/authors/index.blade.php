@extends('layouts.admin')

@section('header-title', 'Authors')

@section('header-actions')
    <a href="{{ route('admin.authors.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700 transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined">add</span>
        <span>Add Author</span>
    </a>
@endsection

@section('content')
    @if(session('success'))
        <div class="bg-green-700/20 border border-green-500 text-green-300 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-700 border-b border-gray-600">
            <tr>
                <th class="p-4 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">Name</th>
                <th class="p-4 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">Books Written</th>
                <th class="p-4 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-600">
                @forelse ($authors as $author)
                    <tr class="hover:bg-gray-700 transition-colors">
                        <td class="p-4 font-semibold text-gray-200">{{ $author->name }}</td>
                        <td class="p-4 text-gray-400">{{ $author->books_count }}</td>
                        <td class="p-4 flex items-center gap-4">
                            <a href="{{ route('admin.authors.edit', $author) }}" class="text-green-400 hover:text-green-500 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Edit
                            </a>
                            <form action="{{ route('admin.authors.destroy', $author) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-500 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-4 text-center text-gray-500">No authors found. Add one to get started!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
