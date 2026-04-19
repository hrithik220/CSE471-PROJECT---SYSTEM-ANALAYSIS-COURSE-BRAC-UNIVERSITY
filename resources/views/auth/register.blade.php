<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — CampusShare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body{font-family:'Plus Jakarta Sans',sans-serif;}</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </div>
            <h1 class="text-3xl font-bold text-white">CampusShare</h1>
            <p class="text-blue-300 mt-1">Join your campus community</p>
        </div>

        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8">
            <h2 class="text-xl font-bold text-white mb-6">Create account</h2>

            @if($errors->any())
            <div class="mb-4 bg-red-500/20 border border-red-500/30 text-red-200 px-4 py-3 rounded-xl text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-blue-200 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full bg-white/10 border border-white/20 text-white placeholder-blue-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Your full name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-blue-200 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full bg-white/10 border border-white/20 text-white placeholder-blue-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="you@campus.edu">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-blue-200 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="w-full bg-white/10 border border-white/20 text-white placeholder-blue-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="+880...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-blue-200 mb-1">Campus</label>
                        <input type="text" name="campus" value="{{ old('campus') }}"
                            class="w-full bg-white/10 border border-white/20 text-white placeholder-blue-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Main Campus">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-blue-200 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full bg-white/10 border border-white/20 text-white placeholder-blue-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Min 8 characters">
                </div>
                <div>
                    <label class="block text-sm font-medium text-blue-200 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full bg-white/10 border border-white/20 text-white placeholder-blue-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Repeat password">
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 rounded-xl transition-colors">
                    Create Account
                </button>
            </form>

            <p class="text-center text-blue-300 text-sm mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-white font-semibold hover:underline">Sign in</a>
            </p>
        </div>
    </div>
</body>
</html>
