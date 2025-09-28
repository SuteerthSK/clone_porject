@extends('layouts.admin')

@section('content')
<main class="flex-1 p-8 bg-gray-900 min-h-screen">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-4xl font-bold mb-8 text-gray-100" style="font-family: 'Newsreader', serif;">Add New Author</h2>

        <div class="bg-gray-800 rounded-lg shadow-md p-8">
            <form action="{{ route('admin.authors.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
                        <input type="text" name="name" id="name" required
                               class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-100 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-300">Biography</label>
                        <textarea name="bio" id="bio" rows="4" 
                                  class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-100 shadow-sm focus:border-green-500 focus:ring-green-500"></textarea>
                    </div>
                    
                    {{-- Uncomment if photo upload is needed
                    <div>
                        <label for="photo" class="block text-sm font-medium text-gray-300">Photo</label>
                        <input type="file" name="photo" id="photo"
                               class="mt-1 block w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-600 file:text-white hover:file:bg-green-700">
                    </div>
                    --}}
                </div>

                <div class="mt-8 flex gap-4">
                    <button type="submit" 
                            class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-green-700 transition-colors">
                        Save Author
                    </button>
                    <a href="{{ route('admin.authors.index') }}" 
                       class="text-gray-300 px-6 py-2 rounded-lg border border-gray-600 hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
