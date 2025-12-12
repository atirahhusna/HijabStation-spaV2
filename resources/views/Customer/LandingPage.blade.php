<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Welcome to HijabStation 🌸') }}
        </h2>
    </x-slot>

    <div class="min-h-screen flex flex-col bg-pink-50">
      
        <!-- Footer sticks to bottom -->
        @include('layouts.footer')
    
</x-app-layout>

