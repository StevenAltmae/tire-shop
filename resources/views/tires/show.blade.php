<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @if($tire->image)
                            <div>
                                <img src="{{ Storage::url($tire->image) }}" alt="{{ $tire->firma }}" class="w-full rounded-lg">
                            </div>
                        @endif
                        <div>
                            <h1 class="text-3xl font-bold mb-4">{{ $tire->firma }}</h1>
                            <div class="space-y-4">
                                <div>
                                    <h2 class="text-xl font-semibold mb-2">Rehvi andmed</h2>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="font-medium">Rehvitüüp</p>
                                            <p>{{ $tire->rehvitüüp }}</p>
                                        </div>
                                        <div>
                                            <p class="font-medium">Hooaeg</p>
                                            <p>{{ $tire->hooaeg }}</p>
                                        </div>
                                        <div>
                                            <p class="font-medium">Suurus</p>
                                            <p>{{ $tire->suurus }}</p>
                                        </div>
                                        <div>
                                            <p class="font-medium">Seisund</p>
                                            <p>{{ $tire->seisund }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h2 class="text-xl font-semibold mb-2">Hind</h2>
                                    <p class="text-3xl font-bold text-blue-600">{{ number_format($tire->hind, 2) }} €</p>
                                </div>
                                <div>
                                    <h2 class="text-xl font-semibold mb-2">Kirjeldus</h2>
                                    <p class="text-gray-700">{{ $tire->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 