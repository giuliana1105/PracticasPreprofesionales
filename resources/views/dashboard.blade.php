<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php
        // Redirigir a la ruta deseada, por ejemplo, a la página de inicio
        return redirect()->route('home');
    @endphp

</x-app-layout>
