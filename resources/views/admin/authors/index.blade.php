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
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-stone-50 border-b border-stone-200">
            <tr>
                <th class="p-4 text-left text-sm font-bold uppercase tracking-wider">Name</th>
                <th class="p-4 text-left text-sm font-bold uppercase tracking-wider">Books Written</th>
                <th class="p-4 text-left text-sm font-bold uppercase tracking-wider">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
                @forelse ($authors as $author)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="p-4 font-semibold">{{ $author->name }}</td>
                        <td class="p-4 text-stone-600">{{ $author->books_count }}</td>
                        <td class="p-4 flex items-center gap-4">
                            <a href="{{ route('admin.authors.edit', $author) }}" class="text-green-600 hover:underline flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Edit
                            </a>
                            <form action="{{ route('admin.authors.destroy', $author) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-4 text-center text-stone-500">No authors found. Add one to get started!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection