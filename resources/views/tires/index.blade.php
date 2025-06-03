<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($tires as $tire)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        @if($tire->image)
                            <img src="{{ Storage::url($tire->image) }}" alt="{{ $tire->firma }}" class="w-full h-48 object-cover">
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2">{{ $tire->firma }}</h3>
                            <div class="space-y-2">
                                <p><strong>Rehvitüüp:</strong> {{ $tire->rehvitüüp }}</p>
                                <p><strong>Hooaeg:</strong> {{ $tire->hooaeg }}</p>
                                <p><strong>Suurus:</strong> {{ $tire->suurus }}</p>
                                <p><strong>Seisund:</strong> {{ $tire->seisund }}</p>
                                <p class="text-xl font-bold text-blue-600">{{ number_format($tire->hind, 2) }} €</p>
                            </div>
                            <a href="{{ route('tires.show', $tire) }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Vaata detailsemalt
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $tires->links() }}
            </div>
        </div>
    </div>
</x-app-layout> 