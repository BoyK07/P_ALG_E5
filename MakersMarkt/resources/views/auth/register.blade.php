<x-guest-layout>
    <div class="min-h-screen bg-[#3b3b3f] text-white" x-data="{
        activeView: '{{ session('_form_view', old('_form_view', $method)) }}',
        formStep: {{ session('_form_step', old('_form_step', 1)) }},
        maxStep: 3,
        isAnimating: false,
        formData: {
            roles: {{ json_encode(old('roles', [])) }}
        },
        changeView(view) {
            if (this.isAnimating) return;
            this.isAnimating = true;

            // First, hide everything with opacity 0
            document.getElementById('register-view').style.opacity = '0';
            document.getElementById('login-view').style.opacity = '0';

            // After a short delay, change the view and show only the active one
            setTimeout(() => {
                this.activeView = view;

                // Force DOM update before adding transition back
                setTimeout(() => {
                    if (view === 'register') {
                        document.getElementById('register-view').style.opacity = '1';
                    } else {
                        document.getElementById('login-view').style.opacity = '1';
                    }
                    this.isAnimating = false;
                }, 50);
            }, 300);
        },
        changeStep(step) {
            if (this.isAnimating) return;
            this.isAnimating = true;

            // Hide current step
            document.getElementById(`step-${this.formStep}`).style.opacity = '0';

            // After animation completes, change step and show new one
            setTimeout(() => {
                this.formStep = step;

                // Force DOM update before adding transition back
                setTimeout(() => {
                    document.getElementById(`step-${step}`).style.opacity = '1';
                    this.isAnimating = false;
                }, 50);
            }, 300);
        }
    }" x-init="() => {
        // Make sure steps are visible based on the form step
        $nextTick(() => {
            document.querySelectorAll('[id^=step-]').forEach(el => {
                el.style.opacity = '0';
            });
            if (document.getElementById(`step-${formStep}`)) {
                document.getElementById(`step-${formStep}`).style.opacity = '1';
            }

            // Determine which tab should be active based on errors
            @if ($errors->hasAny(['username', 'email', 'password', 'name', 'roles', 'terms', 'general']))
                // Check if these are login-specific errors
                @if (session('_form_view') === 'login' || $errors->has('auth'))
                    activeView = 'login';
                @else
                    activeView = 'register';
                @endif
            @endif

            // Set initial opacity for the active view
            if (activeView === 'register') {
                document.getElementById('register-view').style.opacity = '1';
                document.getElementById('login-view').style.opacity = '0';
            } else {
                document.getElementById('register-view').style.opacity = '0';
                document.getElementById('login-view').style.opacity = '1';
            }
        });
    }" class="relative overflow-hidden">
        <div class="flex min-h-screen">
            <!-- Hoofdinhoud container -->
            <div class="flex items-center justify-center w-full">
                <div class="w-full max-w-md p-6">
                    <!-- Header met Logo en Schakelaar -->
                    <div class="mb-8 text-center">
                        <h1 class="text-3xl font-bold text-[#eda566] mb-2">MakersMarkt</h1>

                        <!-- Schakel tussen Registreren/Inloggen -->
                        <div class="flex justify-center mt-4 space-x-4 border-b border-gray-700">
                            <button @click="changeView('register')"
                                :class="{ 'text-[#eda566] border-b-2 border-[#eda566] -mb-px': activeView === 'register' }"
                                class="px-4 pb-2 font-medium transition-all duration-300 focus:outline-none">
                                Registreren
                            </button>
                            <button @click="changeView('login')"
                                :class="{ 'text-[#eda566] border-b-2 border-[#eda566] -mb-px': activeView === 'login' }"
                                class="px-4 pb-2 font-medium transition-all duration-300 focus:outline-none">
                                Inloggen
                            </button>
                        </div>
                    </div>

                    <!-- Formulier container -->
                    <div class="relative w-full">
                        <!-- Registratie Formulier Weergave -->
                        <div id="register-view" x-show="activeView === 'register'"
                            class="transition-opacity duration-300" style="opacity: 1;">
                            <div class="mb-6">
                                <h2 class="text-2xl font-semibold text-white">Account Aanmaken</h2>
                                <p class="mt-1 text-gray-400">Word lid van onze gemeenschap van makers en kopers</p>
                            </div>

                            <!-- Display general registration errors -->
                            @if ($errors->has('general') && session('_form_view') === 'register')
                                <div class="p-4 mb-6 text-sm text-red-100 bg-red-500 rounded-md">
                                    {{ $errors->first('general') }}
                                </div>
                            @endif

                            <!-- Stap Indicator (Alleen Mobiel) -->
                            <div class="mb-6 sm:hidden">
                                <div class="flex items-center justify-between">
                                    <div class="flex space-x-2">
                                        <template x-for="step in maxStep" :key="step">
                                            <div :class="{ 'bg-[#eda566] text-[#3b3b3f]': formStep >= step, 'bg-gray-600': formStep <
                                                    step }"
                                                class="flex items-center justify-center w-8 h-8 text-sm font-bold transition-all duration-300 rounded-full">
                                                <span x-text="step"></span>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="text-sm text-gray-400">Stap <span x-text="formStep"></span> van <span
                                            x-text="maxStep"></span></div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('register') }}" class="relative">
                                @csrf
                                <!-- Hidden fields to preserve form state -->
                                <input type="hidden" name="_form_view" x-bind:value="activeView">
                                <input type="hidden" name="_form_step" x-bind:value="formStep">

                                <!-- Stap 1: Basisinformatie -->
                                <div id="step-1" x-show="formStep === 1" class="transition-opacity duration-300"
                                    style="opacity: 1;">
                                    <div class="space-y-4">
                                        <!-- Gebruikersnaam -->
                                        <div>
                                            <label for="username"
                                                class="block text-sm font-medium text-gray-300">Gebruikersnaam</label>
                                            <input id="username"
                                                class="mt-1 block w-full rounded-md bg-[#4a4a50] border-[#5a5a60] text-white shadow-sm focus:border-[#eda566] focus:ring focus:ring-[#eda566] focus:ring-opacity-50 {{ $errors->has('username') ? 'border-red-500' : '' }}"
                                                type="text" name="username" value="{{ old('username') }}" required />
                                            @error('username')
                                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- E-mail -->
                                        <div>
                                            <label for="email"
                                                class="block text-sm font-medium text-gray-300">E-mail</label>
                                            <input id="email"
                                                class="mt-1 block w-full rounded-md bg-[#4a4a50] border-[#5a5a60] text-white shadow-sm focus:border-[#eda566] focus:ring focus:ring-[#eda566] focus:ring-opacity-50 {{ $errors->has('email') ? 'border-red-500' : '' }}"
                                                type="email" name="email" value="{{ old('email') }}" required />
                                            @error('email')
                                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Wachtwoord -->
                                        <div>
                                            <label for="password"
                                                class="block text-sm font-medium text-gray-300">Wachtwoord</label>
                                            <input id="password"
                                                class="mt-1 block w-full rounded-md bg-[#4a4a50] border-[#5a5a60] text-white shadow-sm focus:border-[#eda566] focus:ring focus:ring-[#eda566] focus:ring-opacity-50 {{ $errors->has('password') ? 'border-red-500' : '' }}"
                                                type="password" name="password" required />
                                            @error('password')
                                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Bevestig Wachtwoord -->
                                        <div>
                                            <label for="password_confirmation"
                                                class="block text-sm font-medium text-gray-300">Bevestig Wachtwoord</label>
                                            <input id="password_confirmation"
                                                class="mt-1 block w-full rounded-md bg-[#4a4a50] border-[#5a5a60] text-white shadow-sm focus:border-[#eda566] focus:ring focus:ring-[#eda566] focus:ring-opacity-50"
                                                type="password" name="password_confirmation" required />
                                        </div>

                                        <!-- Stap Navigatie -->
                                        <div>
                                            <button type="button" @click="changeStep(2)"
                                                class="w-full bg-[#eda566] hover:bg-[#e09656] text-[#3b3b3f] py-3 px-4 font-bold rounded-md shadow-sm focus:outline-none transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]">
                                                Doorgaan
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stap 2: Persoonlijke Informatie -->
                                <div id="step-2" x-show="formStep === 2" class="transition-opacity duration-300"
                                    style="opacity: 0;">
                                    <div class="space-y-4">
                                        <!-- Naam -->
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-300">Volledige
                                                Naam</label>
                                            <input id="name"
                                                class="mt-1 block w-full rounded-md bg-[#4a4a50] border-[#5a5a60] text-white shadow-sm focus:border-[#eda566] focus:ring focus:ring-[#eda566] focus:ring-opacity-50 {{ $errors->has('name') ? 'border-red-500' : '' }}"
                                                type="text" name="name" value="{{ old('name') }}" required />
                                            @error('name')
                                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Profiel Bio (Optioneel) -->
                                        <div>
                                            <label for="profile_bio" class="block text-sm font-medium text-gray-300">
                                                Profiel Bio <span class="text-xs text-gray-500">(Optioneel)</span>
                                            </label>
                                            <textarea id="profile_bio" name="profile_bio" rows="2"
                                                class="mt-1 block w-full rounded-md bg-[#4a4a50] border-[#5a5a60] text-white shadow-sm focus:border-[#eda566] focus:ring focus:ring-[#eda566] focus:ring-opacity-50">{{ old('profile_bio') }}</textarea>
                                        </div>

                                        <!-- Contactinformatie (Optioneel) -->
                                        <div>
                                            <label for="contact_info" class="block text-sm font-medium text-gray-300">
                                                Contactinformatie <span class="text-xs text-gray-500">(Optioneel)</span>
                                            </label>
                                            <input id="contact_info"
                                                class="mt-1 block w-full rounded-md bg-[#4a4a50] border-[#5a5a60] text-white shadow-sm focus:border-[#eda566] focus:ring focus:ring-[#eda566] focus:ring-opacity-50"
                                                type="text" name="contact_info" value="{{ old('contact_info') }}" />
                                        </div>

                                        <!-- Stap Navigatie -->
                                        <div class="flex space-x-3">
                                            <button type="button" @click="changeStep(1)"
                                                class="w-1/3 bg-gray-700 hover:bg-gray-600 text-white py-3 px-4 font-medium rounded-md shadow-sm focus:outline-none transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]">
                                                Terug
                                            </button>
                                            <button type="button" @click="changeStep(3)"
                                                class="w-2/3 bg-[#eda566] hover:bg-[#e09656] text-[#3b3b3f] py-3 px-4 font-bold rounded-md shadow-sm focus:outline-none transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]">
                                                Doorgaan
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stap 3: Laatste Informatie -->
                                <div id="step-3" x-show="formStep === 3" class="transition-opacity duration-300"
                                    style="opacity: 0;">
                                    <div class="space-y-4">
                                        <!-- Account Type -->
                                        <div class="mb-6">
                                            <p class="block mb-3 text-sm font-medium text-gray-300">Ik wil MakersMarkt gebruiken als:</p>

                                            <div class="space-y-3">
                                                <div class="flex items-start">
                                                    <div class="flex items-center h-5">
                                                        <input id="role_buyer" name="roles[]" value="buyer"
                                                            type="checkbox" x-model="formData.roles"
                                                            class="focus:ring-[#eda566] h-4 w-4 text-[#eda566] bg-[#4a4a50] border-[#5a5a60] rounded">
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <label for="role_buyer"
                                                            class="font-medium text-gray-200">Koper</label>
                                                        <p class="text-gray-400">Handgemaakte producten kopen</p>
                                                    </div>
                                                </div>

                                                <div class="flex items-start">
                                                    <div class="flex items-center h-5">
                                                        <input id="role_maker" name="roles[]" value="maker"
                                                            type="checkbox" x-model="formData.roles"
                                                            class="focus:ring-[#eda566] h-4 w-4 text-[#eda566] bg-[#4a4a50] border-[#5a5a60] rounded">
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <label for="role_maker"
                                                            class="font-medium text-gray-200">Maker</label>
                                                        <p class="text-gray-400">Verkoop je handgemaakte producten</p>
                                                    </div>
                                                </div>

                                                @error('roles')
                                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                                @enderror
                                                <div x-show="formData.roles.length === 0"
                                                    class="mt-2 text-sm text-red-400">
                                                    Selecteer minimaal één accounttype.
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Algemene Voorwaarden -->
                                        <div class="mb-4">
                                            <div class="flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input id="terms" name="terms" type="checkbox" required
                                                        class="focus:ring-[#eda566] h-4 w-4 text-[#eda566] bg-[#4a4a50] border-[#5a5a60] rounded
                                                        {{ $errors->has('terms') ? 'border-red-500' : '' }}">
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label for="terms" class="font-medium text-gray-200">Ik ga akkoord met
                                                        de <a href="#"
                                                            class="text-[#eda566] hover:underline">Algemene Voorwaarden</a> en <a
                                                            href="#"
                                                            class="text-[#eda566] hover:underline">Privacybeleid</a>.</label>
                                                    @error('terms')
                                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Stap Navigatie -->
                                        <div class="flex space-x-3">
                                            <button type="button" @click="changeStep(2)"
                                                class="w-1/3 bg-gray-700 hover:bg-gray-600 text-white py-3 px-4 font-medium rounded-md shadow-sm focus:outline-none transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]">
                                                Terug
                                            </button>
                                            <button type="submit" x-bind:disabled="formData.roles.length === 0"
                                                class="w-2/3 bg-[#eda566] hover:bg-[#e09656] text-[#3b3b3f] py-3 px-4 font-bold rounded-md shadow-sm focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]">
                                                Account Aanmaken
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Heb je al een account prompt (Desktop) -->
                            <div class="hidden mt-8 text-center sm:block">
                                <p class="text-sm text-gray-400">
                                    Heb je al een account?
                                    <button type="button" @click="changeView('login')"
                                        class="text-[#eda566] hover:underline font-medium focus:outline-none transition-all duration-300">
                                        Inloggen
                                    </button>
                                </p>
                            </div>
                        </div>

                        <!-- Inlogformulier Weergave -->
                        <div id="login-view" x-show="activeView === 'login'" x-data="{ loginMethod: 'email' }"
                            class="transition-opacity duration-300"
                            :style="activeView === 'login' ? 'opacity: 1' : 'opacity: 0'">
                            <div class="mb-6">
                                <h2 class="text-2xl font-semibold text-white">Welkom Terug</h2>
                                <p class="mt-1 text-gray-400">Log in op je MakersMarkt account</p>
                            </div>

                            <!-- Streamlined error display - just one section -->
                            @if(session('_form_view') === 'login')
                                @if(session('status'))
                                    <div class="p-4 mb-6 text-sm text-blue-100 bg-blue-500 rounded-md">
                                        {{ session('status') }}
                                    </div>
                                @elseif($errors->any())
                                    <div class="p-4 mb-6 text-sm text-red-100 bg-red-500 rounded-md">
                                        {{ $errors->first() }}
                                    </div>
                                @endif
                            @endif

                            <!-- Inlogmethode keuze -->
                            <div class="flex justify-center mb-6 bg-[#4a4a50] rounded-lg p-1">
                                <button type="button" @click="loginMethod = 'email'"
                                    :class="{ 'bg-[#eda566] text-[#3b3b3f]': loginMethod === 'email' }"
                                    class="flex-1 px-4 py-2 text-sm font-medium transition-all duration-300 rounded-md">
                                    E-mail
                                </button>
                                <button type="button" @click="loginMethod = 'username'"
                                    :class="{ 'bg-[#eda566] text-[#3b3b3f]': loginMethod === 'username' }"
                                    class="flex-1 px-4 py-2 text-sm font-medium transition-all duration-300 rounded-md">
                                    Gebruikersnaam
                                </button>
                            </div>

                            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                                @csrf

                                <!-- Inlogveld -->
                                <div>
                                    <label :for="loginMethod + '-input'"
                                        class="block text-sm font-medium text-gray-300 transition-all duration-300"
                                        x-text="loginMethod === 'email' ? 'E-mail' : 'Gebruikersnaam'">
                                    </label>
                                    <input :id="loginMethod + '-input'"
                                        :type="loginMethod === 'email' ? 'email' : 'text'" :name="loginMethod"
                                        :placeholder="loginMethod === 'email' ? 'Voer je e-mail in' : 'Voer je gebruikersnaam in'"
                                        class="mt-1 block w-full rounded-md bg-[#4a4a50] border-[#5a5a60] text-white shadow-sm focus:border-[#eda566] focus:ring focus:ring-[#eda566] focus:ring-opacity-50 transition-all duration-300"
                                        required autofocus />
                                </div>

                                <!-- Wachtwoord -->
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label for="login-password"
                                            class="block text-sm font-medium text-gray-300">Wachtwoord</label>
                                        @if (Route::has('password.request'))
                                            <a class="text-sm text-[#eda566] hover:underline"
                                                href="{{ route('password.request') }}">
                                                {{ __('Wachtwoord vergeten?') }}
                                            </a>
                                        @endif
                                    </div>
                                    <input id="login-password"
                                        class="mt-1 block w-full rounded-md bg-[#4a4a50] border-[#5a5a60] text-white shadow-sm focus:border-[#eda566] focus:ring focus:ring-[#eda566] focus:ring-opacity-50"
                                        type="password" name="password" required autocomplete="current-password" />
                                </div>

                                <!-- Onthoud Mij -->
                                <div class="flex items-center">
                                    <input id="remember_me" type="checkbox"
                                        class="rounded bg-[#4a4a50] border-[#5a5a60] text-[#eda566] shadow-sm focus:ring-[#eda566]"
                                        name="remember">
                                    <label for="remember_me" class="block ml-2 text-sm text-gray-400">
                                        {{ __('Onthoud mij') }}
                                    </label>
                                </div>

                                <!-- Verzendknop -->
                                <div>
                                    <button type="submit"
                                        class="w-full bg-[#eda566] hover:bg-[#e09656] text-[#3b3b3f] py-3 px-4 font-bold rounded-md shadow-sm focus:outline-none transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]">
                                        Inloggen
                                    </button>
                                </div>
                            </form>

                            <!-- Geen account prompt -->
                            <div class="mt-8 text-center">
                                <p class="text-sm text-gray-400">
                                    Nog geen account?
                                    <button type="button" @click="changeView('register'); formStep = 1"
                                        class="text-[#eda566] hover:underline font-medium focus:outline-none transition-all duration-300">
                                        Maak er een aan
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achtergrond animatie elementen -->
        <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-10">
            <div class="absolute top-0 left-0 w-96 h-96 rounded-full bg-[#eda566] opacity-5 filter blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 rounded-full bg-[#eda566] opacity-5 filter blur-3xl"></div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-guest-layout>
