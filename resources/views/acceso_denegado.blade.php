<x-app-layout>
    <div class="flex flex-col items-center justify-center h-screen text-center">
        <h1 class="text-4xl font-bold text-red-600 mb-4">⚠️ Acceso Denegado</h1>
        <p class="text-gray-600">No tienes permiso para acceder a esta sección según tu nivel de usuario.</p>
        <a href="{{ route('welcome') }}" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Volver al inicio
        </a>
    </div>
</x-app-layout>
