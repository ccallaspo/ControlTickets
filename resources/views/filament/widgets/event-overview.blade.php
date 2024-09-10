<x-filament-widgets::widget>
    <x-filament::card>
        <x-slot name="header">
            <h2 class="text-lg font-semibold text-gray-900">Event Records</h2>
        </x-slot>
        
        <div class="p-4">
            <h5 class="text-base font-medium text-gray-900 mb-4">Estados</h5>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-100 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                        <tr>
                            <th scope="col" class="py-3 px-4">Estados</th>
                            <th scope="col" class="py-3 px-4">Contabilidados</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        @foreach($terminado_count_array as $eventRecord)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                    {{ $eventRecord->name }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                    {{ $eventRecord->count }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-filament::card>
</x-filament-widgets::widget>
