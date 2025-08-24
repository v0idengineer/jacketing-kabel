<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Masuk • RnD Jacketing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#4f46e5">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* pola halus di background */
        .dots {
            background-image: radial-gradient(rgba(255, 255, 255, .4) 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* kecilkan sedikit di device sangat kecil */
        @media (max-width: 360px) {
            .text-tight {
                font-size: .95rem
            }
        }
    </style>
    <link rel="icon" href="<?php echo e(asset('logoapp.png')); ?>" type="image/png">
    <link rel="shortcut icon" href="<?php echo e(asset('logoapp.png')); ?>" type="image/png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('logoapp.png')); ?>">
</head>

<body class="min-h-screen bg-gradient-to-br from-indigo-600 via-sky-500 to-emerald-400 flex items-center justify-center p-4 sm:p-6">
    <div class="absolute inset-0 dots opacity-30 pointer-events-none"></div>

    <div class="relative w-full max-w-md">
        <!-- Brand mini -->
        <div class="mb-6 flex items-center justify-center gap-3">
            <img src="<?php echo e(asset('logoapp.png')); ?>" alt="Logo" class="h-10 w-10 rounded object-contain">
            <div class="text-white/95">
                <div class="text-lg sm:text-xl font-semibold leading-tight">RnD Jacketing</div>
                <div class="text-[11px] sm:text-xs opacity-80 -mt-0.5">Internal Access</div>
            </div>
        </div>

        <form
            method="POST"
            action="<?php echo e(route('login')); ?>"
            x-data="loginForm()"
            @submit.prevent="submit($el)"
            class="bg-white/90 backdrop-blur rounded-2xl shadow-xl ring-1 ring-black/5 p-5 sm:p-7 space-y-5">
            <?php echo csrf_field(); ?>

            <div class="space-y-1">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Masuk</h1>
                <p class="text-sm text-slate-600">Gunakan <b>username</b> dan password Anda.</p>
            </div>

            
            <?php if(session('status')): ?>
            <div class="text-sm px-3 py-2.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
                <?php echo e(session('status')); ?>

            </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
            <div class="text-sm px-3 py-2.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200">
                <?php echo e($errors->first()); ?>

            </div>
            <?php endif; ?>

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-slate-700">Username</label>
                <div class="mt-1 relative">
                    <input id="username" name="username" type="text" inputmode="text" autocomplete="username"
                        value="<?php echo e(old('username')); ?>" required autofocus
                        class="block w-full h-12 rounded-lg border border-slate-300 text-base py-3 px-4 pr-10 focus:ring-indigo-500 focus:border-indigo-500 placeholder-slate-400"
                        placeholder="contoh: rndepartment">
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="mt-1 text-sm text-rose-600"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                </div>

                <div class="mt-1 relative" @keydown.window.capture="checkCaps($event)">
                    <!-- Input password -->
                    <input :type="show ? 'text' : 'password'"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="block w-full h-12 rounded-lg border border-slate-300 text-base py-3 px-4 pr-12 focus:ring-indigo-500 focus:border-indigo-500 placeholder-slate-400"
                        placeholder="••••••••">

                    <!-- Tombol tampil/sembunyi -->
                    <button type="button" @click="show=!show"
                        class="absolute inset-y-0 right-0 px-3 text-slate-500 hover:text-slate-700 focus:outline-none"
                        :aria-label="show ? 'Sembunyikan password' : 'Tampilkan password'"
                        :title="show ? 'Sembunyikan password' : 'Tampilkan password'">
                        <!-- Ikon mata (sembunyi) -->
                        <template x-if="!show">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </template>
                        <!-- Ikon mata dicoret (tampil) -->
                        <template x-if="show">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.592M6.18 6.18A9.956 9.956 0 0112 5c4.477 0 8.268-2.943 9.542 7-.305.973-.75 1.876-1.312 2.688M15 12a3 3 0 00-3-3M3 3l18 18" />
                            </svg>
                        </template>
                    </button>
                </div>

                <!-- CapsLock hint -->
                <div x-show="caps" x-cloak
                    class="mt-2 text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-md px-2 py-1.5">
                    Caps Lock aktif
                </div>

                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="mt-1 text-sm text-rose-600"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Submit -->
            <button type="submit"
                :disabled="loading"
                class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 text-white py-2.5 font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed">
                <svg x-show="loading" x-cloak class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                </svg>
                <span x-text="loading ? 'Memproses…' : 'Login'"></span>
            </button>
        </form>

        <!-- Footnote -->
        <p class="mt-4 text-center text-xs text-white/80">© <?php echo e(date('Y')); ?> RnD Jacketing • Internal</p>
    </div>

    <script>
        function loginForm() {
            return {
                show: false,
                caps: false,
                loading: false,
                checkCaps(e) {
                    if (!e || !e.getModifierState) return;
                    this.caps = e.getModifierState('CapsLock');
                },
                async submit(form) {
                    this.loading = true;
                    form.submit(); // biarkan browser handle redirect
                    return true;
                }
            }
        }
    </script>
</body>

</html><?php /**PATH D:\cablejacketing\jacketing-kabel\resources\views/auth/login.blade.php ENDPATH**/ ?>