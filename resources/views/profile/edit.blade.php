@extends('layout.master')

@section('title', 'Profil Saya')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header bg-primary rounded-top">
                <h5 class="mb-0 text-white"><i class="bx bx-user me-1"></i> Profil Saya</h5>
            </div>
            <div class="card-body pt-4">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="text-center mb-4">
                        <img
                            src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('img/avatars/1.png') }}"
                            alt="Foto profil {{ $user->name }}"
                            class="rounded-circle border"
                            style="width: 140px; height: 140px; object-fit: cover;"
                        >
                        <p class="small text-muted mt-2 mb-0">Foto JPG, PNG, GIF, atau WEBP. Maksimal 2 MB.</p>
                    </div>

                    <div class="mb-3">
                        <label for="avatar" class="form-label fw-bold">Foto Profil</label>
                        <input
                            type="file"
                            name="avatar"
                            id="avatar"
                            class="form-control @error('avatar') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/gif,image/webp"
                        >
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nama Lengkap</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold">Alamat Email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="border-top pt-3 mb-4">
                        <h6 class="fw-bold mb-1">Ganti Password</h6>
                        <p class="small text-muted mb-3">Kosongkan bagian ini jika tidak ingin mengganti password. Password baru minimal 8 karakter.</p>

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-bold">Password Saat Ini</label>
                            <input
                                type="password"
                                name="current_password"
                                id="current_password"
                                class="form-control @error('current_password') is-invalid @enderror"
                                autocomplete="current-password"
                            >
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Password Baru</label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                autocomplete="new-password"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password Baru</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                autocomplete="new-password"
                            >
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between border-top pt-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bx bx-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
