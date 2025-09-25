<x-layout>

    <main class="min-h-screen flex items-center justify-center bg-base-200">


        <div class="card w-full max-w-md shadow-2xl bg-base-100">
            <div class="card-body">
                <h2 class="text-4xl font-bold text-center mb-4">N.O.C</h2>
                <p class="text-center text-sm text-gray-500 mb-6">Entre para continuar</p>

                @if ($errors->any())
                    <div class="alert alert-error shadow-lg mb-4">
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Email</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="input input-bordered w-full" />
                    </div>

                    <!-- Password -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Senha</span>
                        </label>
                        <input type="password" name="password" required class="input input-bordered w-full" />
                    </div>

                    <!-- Remember me -->
                    <div class="form-control">
                        <label class="cursor-pointer label justify-start gap-2">
                            <input type="checkbox" name="remember" class="checkbox checkbox-primary" />
                            <span class="label-text">Lembrar</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <div class="form-control mt-6">
                        <button type="submit" class="btn btn-primary w-full">Login</button>
                    </div>
                </form>

            </div>
        </div>
    </main>

</x-layout>
