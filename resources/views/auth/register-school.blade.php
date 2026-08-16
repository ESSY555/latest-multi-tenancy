<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register Your School — Bezaleel School Management</title>
    <meta name="description" content="Create your school on the Bezaleel School Management Platform. Free registration in under 2 minutes.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

        * { font-family: 'Inter', sans-serif; }

        .hero-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #0e4f8a 70%, #1d6fb5 100%);
        }

        .card-glass {
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(20px);
            box-shadow: 0 25px 60px rgba(0,0,0,0.18), 0 0 0 1px rgba(255,255,255,0.15);
        }

        .input-field {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.625rem;
            font-size: 0.925rem;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.2s ease;
            outline: none;
        }
        .input-field:focus {
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
        }
        .input-field::placeholder { color: #94a3b8; }
        .input-field.error { border-color: #ef4444; }

        .input-wrapper { position: relative; }
        .input-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.95rem;
            pointer-events: none;
        }

        .section-divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.5rem 0 1.25rem;
        }
        .section-divider::before,
        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }
        .section-label {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #94a3b8;
            white-space: nowrap;
        }

        .btn-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.9rem 1.5rem;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 0.625rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(37,99,235,0.35);
            letter-spacing: 0.01em;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
            box-shadow: 0 6px 20px rgba(37,99,235,0.45);
            transform: translateY(-1px);
        }
        .btn-primary:active { transform: translateY(0); }

        .step-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.6rem;
            height: 1.6rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #1d4ed8, #60a5fa);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.75rem;
            border-radius: 0.75rem;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
        }
        .feature-icon {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.5rem;
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.875rem;
            color: #fff;
        }

        .progress-flow {
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        .flow-step {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .flow-step-connector {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0;
        }
        .flow-dot {
            width: 0.625rem;
            height: 0.625rem;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            border: 2px solid rgba(255,255,255,0.6);
        }
        .flow-dot.active {
            background: #60a5fa;
            border-color: #60a5fa;
            box-shadow: 0 0 8px rgba(96,165,250,0.6);
        }
        .flow-line {
            width: 1px;
            height: 1.25rem;
            background: linear-gradient(to bottom, rgba(255,255,255,0.3), rgba(255,255,255,0.1));
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 0.625rem;
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
            color: #dc2626;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-in { animation: fadeInUp 0.45s ease both; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
    </style>
</head>
<body class="hero-gradient min-h-screen">

{{-- Flash alerts --}}
<div class="fixed top-4 right-4 z-[70] w-full max-w-md px-4 pointer-events-none">
    <div class="pointer-events-auto">
        <x-flash-alerts />
    </div>
</div>

<div class="min-h-screen flex flex-col lg:flex-row">

    {{-- ══════════════════════════════════════════════════
         LEFT PANEL — branding + feature highlights
    ══════════════════════════════════════════════════ --}}
    <div class="hidden lg:flex lg:w-5/12 xl:w-2/5 flex-col justify-between p-10 xl:p-14 animate-in">

        {{-- Logo --}}
        <div>
            <div class="flex items-center gap-3 mb-12">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-white text-lg"></i>
                </div>
                <span class="text-white font-bold text-xl tracking-tight">Bezaleel SMS</span>
            </div>

            <h1 class="text-white text-4xl xl:text-5xl font-extrabold leading-tight mb-4">
                Launch your<br>
                <span style="background:linear-gradient(90deg,#60a5fa,#a5f3fc); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">school platform</span><br>
                in minutes.
            </h1>
            <p class="text-blue-200 text-lg leading-relaxed mb-10">
                One registration gives your school a complete management system — attendance, results, assignments, and more.
            </p>

            {{-- Registration flow steps --}}
            <div class="mb-10">
                <p class="text-xs font-semibold uppercase tracking-widest text-blue-300 mb-4">What happens when you register</p>
                <div class="space-y-0">
                    @foreach([
                        ['icon' => 'fa-school',     'label' => 'Create your school profile'],
                        ['icon' => 'fa-code-branch', 'label' => 'Set up your default branch'],
                        ['icon' => 'fa-user-shield', 'label' => 'Get your admin account'],
                        ['icon' => 'fa-link',        'label' => 'Link admin to branch'],
                        ['icon' => 'fa-rocket',      'label' => 'Go live on your dashboard'],
                    ] as $i => $step)
                    <div class="flex items-start gap-3">
                        <div class="flex flex-col items-center">
                            <div class="flow-dot {{ $i === 0 ? 'active' : '' }}"></div>
                            @if($i < 4)
                            <div class="flow-line"></div>
                            @endif
                        </div>
                        <div class="pb-3">
                            <span class="text-white/90 text-sm font-medium">{{ $step['label'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Feature highlights --}}
            <div class="grid grid-cols-1 gap-3">
                @foreach([
                    ['icon' => 'fa-users',     'title' => 'Multi-role access',   'desc' => 'Admin, Teacher, Student & Parent'],
                    ['icon' => 'fa-chart-bar', 'title' => 'Results & Analytics', 'desc' => 'Term reports with grade scales'],
                    ['icon' => 'fa-calendar',  'title' => 'Attendance tracking', 'desc' => 'Daily student & teacher logs'],
                ] as $feat)
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas {{ $feat['icon'] }}"></i>
                    </div>
                    <div>
                        <p class="text-white text-sm font-semibold">{{ $feat['title'] }}</p>
                        <p class="text-blue-200 text-xs mt-0.5">{{ $feat['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <p class="text-blue-400 text-xs mt-8">
            &copy; {{ date('Y') }} Bezaleel International School · School Management System
        </p>
    </div>

    {{-- ══════════════════════════════════════════════════
         RIGHT PANEL — registration form
    ══════════════════════════════════════════════════ --}}
    <div class="flex-1 flex items-start lg:items-center justify-center p-4 sm:p-6 lg:p-10 py-8">
        <div class="w-full max-w-lg animate-in delay-100">

            {{-- Mobile logo --}}
            <div class="flex lg:hidden items-center gap-2 mb-6">
                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-white text-sm"></i>
                </div>
                <span class="text-white font-bold text-lg">Bezaleel SMS</span>
            </div>

            <div class="card-glass rounded-2xl p-6 sm:p-8">

                {{-- Header --}}
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="step-badge">1</span>
                        <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">New school registration</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Register your school</h2>
                    <p class="text-gray-500 text-sm mt-1">Fill in your school details and create your admin account.</p>
                </div>

                {{-- General error --}}
                @if($errors->has('general'))
                <div class="alert-error mb-5">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first('general') }}
                </div>
                @endif

                <form method="POST" action="{{ route('school.register.store') }}" novalidate>
                    @csrf

                    {{-- ── SCHOOL DETAILS ───────────────────────── --}}
                    <div class="section-divider">
                        <span class="section-label"><i class="fas fa-school mr-1"></i>School details</span>
                    </div>

                    {{-- School name --}}
                    <div class="mb-4">
                        <label for="school_name" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            School name <span class="text-red-500">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon"><i class="fas fa-school"></i></span>
                            <input
                                type="text"
                                id="school_name"
                                name="school_name"
                                value="{{ old('school_name') }}"
                                placeholder="e.g. Bezaleel International School"
                                class="input-field {{ $errors->has('school_name') ? 'error' : '' }}"
                                required
                            >
                        </div>
                        @error('school_name')
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- School code + Phone (2-col) --}}
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div>
                            <label for="school_code" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                School code <span class="text-red-500">*</span>
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon"><i class="fas fa-hashtag"></i></span>
                                <input
                                    type="text"
                                    id="school_code"
                                    name="school_code"
                                    value="{{ old('school_code') }}"
                                    placeholder="BIS"
                                    maxlength="10"
                                    class="input-field {{ $errors->has('school_code') ? 'error' : '' }}"
                                    required
                                >
                            </div>
                            @error('school_code')
                            <p class="text-red-500 text-xs mt-1"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="school_phone" class="block text-sm font-semibold text-gray-700 mb-1.5">Phone</label>
                            <div class="input-wrapper">
                                <span class="input-icon"><i class="fas fa-phone"></i></span>
                                <input
                                    type="text"
                                    id="school_phone"
                                    name="school_phone"
                                    value="{{ old('school_phone') }}"
                                    placeholder="+234 800 000 0000"
                                    class="input-field"
                                >
                            </div>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="mb-4">
                        <label for="school_address" class="block text-sm font-semibold text-gray-700 mb-1.5">Address</label>
                        <div class="input-wrapper">
                            <span class="input-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <input
                                type="text"
                                id="school_address"
                                name="school_address"
                                value="{{ old('school_address') }}"
                                placeholder="123 School Road"
                                class="input-field"
                            >
                        </div>
                    </div>

                    {{-- City + State + Country --}}
                    <div class="grid grid-cols-3 gap-3 mb-2">
                        <div>
                            <label for="school_city" class="block text-sm font-semibold text-gray-700 mb-1.5">City</label>
                            <div class="input-wrapper">
                                <span class="input-icon"><i class="fas fa-city"></i></span>
                                <input type="text" id="school_city" name="school_city" value="{{ old('school_city') }}"
                                    placeholder="Lagos" class="input-field">
                            </div>
                        </div>
                        <div>
                            <label for="school_state" class="block text-sm font-semibold text-gray-700 mb-1.5">State</label>
                            <div class="input-wrapper">
                                <span class="input-icon"><i class="fas fa-map"></i></span>
                                <input type="text" id="school_state" name="school_state" value="{{ old('school_state') }}"
                                    placeholder="Lagos" class="input-field">
                            </div>
                        </div>
                        <div>
                            <label for="school_country" class="block text-sm font-semibold text-gray-700 mb-1.5">Country</label>
                            <div class="input-wrapper">
                                <span class="input-icon"><i class="fas fa-globe"></i></span>
                                <input type="text" id="school_country" name="school_country" value="{{ old('school_country', 'Nigeria') }}"
                                    placeholder="Nigeria" class="input-field">
                            </div>
                        </div>
                    </div>

                    {{-- ── ADMIN ACCOUNT ────────────────────────── --}}
                    <div class="section-divider">
                        <span class="section-label"><i class="fas fa-user-shield mr-1"></i>Admin account</span>
                    </div>

                    {{-- Admin name --}}
                    <div class="mb-4">
                        <label for="admin_name" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Full name <span class="text-red-500">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon"><i class="fas fa-user"></i></span>
                            <input
                                type="text"
                                id="admin_name"
                                name="admin_name"
                                value="{{ old('admin_name') }}"
                                placeholder="Your full name"
                                class="input-field {{ $errors->has('admin_name') ? 'error' : '' }}"
                                required
                            >
                        </div>
                        @error('admin_name')
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Admin email --}}
                    <div class="mb-4">
                        <label for="admin_email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Email address <span class="text-red-500">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon"><i class="fas fa-envelope"></i></span>
                            <input
                                type="email"
                                id="admin_email"
                                name="admin_email"
                                value="{{ old('admin_email') }}"
                                placeholder="admin@yourschool.com"
                                class="input-field {{ $errors->has('admin_email') ? 'error' : '' }}"
                                required
                            >
                        </div>
                        @error('admin_email')
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password + Confirm --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon"><i class="fas fa-lock"></i></span>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Min. 8 characters"
                                    class="input-field {{ $errors->has('password') ? 'error' : '' }}"
                                    required
                                >
                            </div>
                            @error('password')
                            <p class="text-red-500 text-xs mt-1"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Confirm password <span class="text-red-500">*</span>
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon"><i class="fas fa-lock"></i></span>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Repeat password"
                                    class="input-field"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-primary" id="submit-btn">
                        <i class="fas fa-rocket"></i>
                        <span>Create school &amp; go to dashboard</span>
                    </button>

                    {{-- Legal note --}}
                    <p class="text-center text-xs text-gray-400 mt-4">
                        By registering, you agree to our
                        <a href="#" class="text-blue-500 hover:underline">Terms of Service</a>
                        and
                        <a href="#" class="text-blue-500 hover:underline">Privacy Policy</a>.
                    </p>
                </form>

                {{-- Footer link to login --}}
                <div class="mt-6 pt-5 border-t border-gray-100 text-center text-sm text-gray-600">
                    Already have a school account?
                    <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-800 ml-1">Sign in</a>
                </div>
            </div>

            {{-- Mobile copyright --}}
            <p class="lg:hidden text-center text-blue-300 text-xs mt-6">
                &copy; {{ date('Y') }} Bezaleel SMS
            </p>
        </div>
    </div>
</div>

<script>
    // Auto-uppercase school code
    document.getElementById('school_code').addEventListener('input', function () {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });

    // Loading state on submit
    document.querySelector('form').addEventListener('submit', function () {
        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Creating your school…</span>';
    });
</script>
</body>
</html>
