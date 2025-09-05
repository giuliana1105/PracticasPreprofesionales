<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
               
    <img src="{{ asset('img/logo_fica.png') }}" alt="Logo UTN" style="height: 60px;">
</a>

                </div>

                <!-- Navigation Links -->
                
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        @php
                            $persona = Auth::user()->persona ?? null;
                            $cargo = session('selected_role') ? session('selected_role') : ($persona ? $persona->cargo : (Auth::user()->cargo ?? ''));
                            $nombreCompleto = $persona ? $persona->nombres . ' ' . $persona->apellidos : Auth::user()->name;
                            $cargos = [
                                'secretario_general' => 'Secretario General',
                                'secretario' => 'Secretario/a',
                                'abogado' => 'Abogado/a',
                                'decano' => 'Decano',
                                'decano/a' => 'Decano/a',
                                'subdecano' => 'Subdecano/a',
                                'subdecano/a' => 'Subdecano/a',
                                'docente' => 'Docente',
                                'estudiante' => 'Estudiante',
                            ];
                        @endphp
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>
                                @if($cargo)
                                    {{ $cargos[$cargo] ?? ucfirst($cargo) }} - {{ $nombreCompleto }}
                                @else
                                    {{ $nombreCompleto }}
                                @endif
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Cambio rápido de rol para cargos compuestos -->
                        @php
                            $persona = Auth::user()->persona ?? null;
                            $cargoOriginal = $persona ? $persona->cargo : (Auth::user()->cargo ?? '');
                            $cargosCompuestos = ['docente-decano/a', 'docente-subdecano/a', 'docente-coordinador/a'];
                            $rolActual = session('selected_role');
                            $rolAlternativo = null;
                            if (in_array(strtolower($cargoOriginal), $cargosCompuestos) && $rolActual) {
                                if (strpos(strtolower($cargoOriginal), 'decano') !== false) {
                                    $rolAlternativo = $rolActual === 'docente' ? 'decano/a' : 'docente';
                                } elseif (strpos(strtolower($cargoOriginal), 'subdecano') !== false) {
                                    $rolAlternativo = $rolActual === 'docente' ? 'subdecano/a' : 'docente';
                                } elseif (strpos(strtolower($cargoOriginal), 'coordinador') !== false) {
                                    $rolAlternativo = $rolActual === 'docente' ? 'coordinador/a' : 'docente';
                                }
                            }
                        @endphp
                        @if($rolAlternativo)
                            <form method="POST" action="{{ route('role.select') }}" style="margin-bottom: 0;">
                                @csrf
                                <input type="hidden" name="role" value="{{ $rolAlternativo }}">
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none">
                                    Cambiar a {{ ucfirst($rolAlternativo) }}
                                </button>
                            </form>
                        @endif
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                @php
                    $persona = Auth::user()->persona ?? null;
                    $cargo = $persona ? $persona->cargo : (Auth::user()->cargo ?? '');
                    $nombreCompleto = $persona ? $persona->nombres . ' ' . $persona->apellidos : Auth::user()->name;
                    $cargos = [
                        'secretario_general' => 'Secretario General',
                        'secretario' => 'Secretario/a',
                        'abogado' => 'Abogado/a',
                        'decano' => 'Decano',
                        'subdecano' => 'Subdecano/a',
                        'docente' => 'Docente',
                        'estudiante' => 'Estudiante',
                    ];
                @endphp
                <div class="font-medium text-base text-gray-800">
                    @if($cargo)
                        {{ $cargos[$cargo] ?? ucfirst($cargo) }} - {{ $nombreCompleto }}
                    @else
                        {{ $nombreCompleto }}
                    @endif
                </div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                            this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
