<x-guest-layout>

<div class="min-h-screen flex items-center justify-center p-6
            bg-[#0f172a] relative overflow-hidden">

    {{-- BACKGROUND EFFECT --}}
    <div class="absolute top-0 left-0 w-96 h-96
                bg-blue-600/20 rounded-full blur-3xl"></div>

    <div class="absolute bottom-0 right-0 w-96 h-96
                bg-indigo-500/20 rounded-full blur-3xl"></div>

    {{-- CARD --}}
    <div class="relative w-full max-w-md">

        <div class="bg-white rounded-[32px] shadow-2xl p-8">

            {{-- LOGO --}}
            <div class="text-center mb-8">

                <div class="w-20 h-20 mx-auto rounded-3xl
                            bg-gradient-to-br from-blue-600 to-indigo-700
                            flex items-center justify-center
                            text-white text-4xl shadow-lg mb-4">

                    📚

                </div>

                <h1 class="text-3xl font-extrabold text-slate-800">

                    Admin Login

                </h1>

                <p class="text-slate-500 mt-2">

                    Selamat datang kembali 👋

                </p>

            </div>

            {{-- SESSION --}}
            <x-auth-session-status
                class="mb-4"
                :status="session('status')" />

            {{-- FORM --}}
            <form method="POST"
                  action="{{ route('login') }}">

                @csrf

                {{-- EMAIL --}}
                <div class="mb-5">

                    <x-input-label
                        for="email"
                        :value="__('Email')" />

                    <x-text-input
                        id="email"
                        class="block mt-2 w-full rounded-2xl
                               border-slate-300
                               focus:border-blue-500
                               focus:ring-blue-500"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus />

                    <x-input-error
                        :messages="$errors->get('email')"
                        class="mt-2" />

                </div>

                {{-- PASSWORD --}}
                <div class="mb-5">

                    <x-input-label
                        for="password"
                        :value="__('Password')" />

                    <x-text-input
                        id="password"
                        class="block mt-2 w-full rounded-2xl
                               border-slate-300
                               focus:border-blue-500
                               focus:ring-blue-500"
                        type="password"
                        name="password"
                        required />

                    <x-input-error
                        :messages="$errors->get('password')"
                        class="mt-2" />

                </div>

                {{-- REMEMBER --}}
                <div class="flex items-center justify-between mb-6">

                    <label for="remember_me"
                           class="inline-flex items-center">

                        <input id="remember_me"
                               type="checkbox"
                               class="rounded border-slate-300
                                      text-blue-600 shadow-sm
                                      focus:ring-blue-500"
                               name="remember">

                        <span class="ms-2 text-sm text-slate-600">

                            Remember me

                        </span>

                    </label>

                </div>

                {{-- BUTTON --}}
                <button type="submit"
                        class="w-full py-3 rounded-2xl
                               text-white font-bold
                               bg-gradient-to-r
                               from-blue-600 to-indigo-700
                               hover:from-blue-700 hover:to-indigo-800
                               shadow-lg transition duration-300">

                    Masuk Dashboard

                </button>

            </form>

        </div>

        <p class="text-center text-slate-400 text-sm mt-6">

            © {{ date('Y') }} Perpustakaan Digital

        </p>

    </div>

</div>

</x-guest-layout>