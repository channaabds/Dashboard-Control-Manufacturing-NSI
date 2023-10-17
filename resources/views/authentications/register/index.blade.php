@extends('layouts.main')

@section('content')
<main>
  <div class="container">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
            <div class="d-flex justify-content-center py-4">
              <div class="logo d-flex align-items-center w-auto">
                <img src="{{ asset('./img/logo/logo-nsi.jpg') }}" alt="logo nsi">
                <span class="d-none d-lg-block">Dashboard Control Manufacturing</span>
              </div>
            </div>

            <div class="card mb-3">
              <div class="card-body">
                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Buat akun baru</h5>
                  <p class="text-center small">Masukkan username akun</p>
                </div>

                @if (session()->has('success'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                @endif
                @if (session()->has('fail'))
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('fail') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                @endif

                <form class="row g-3 needs-validation" novalidate method="POST" action="/register">
                  @csrf
                  <div class="col-12">
                    <label for="yourUsername" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="yourUsername" required>
                    <div class="invalid-feedback">Masukkan username anda</div>
                  </div>

                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Create Account</button>
                  </div>

                  <div class="col-4">
                    <button class="btn btn-success" type="button"><a href="/login" class="text-light">Login</a></button>
                  </div>
                </form>
              </div>
            </div>

          </div>
        </div>
      </div>

    </section>

  </div>
</main>
@endsection
