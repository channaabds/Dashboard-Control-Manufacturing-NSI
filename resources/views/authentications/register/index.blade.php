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

                <form class="row g-3 needs-validation" novalidate method="POST" action="/menu/register">
                  @csrf

                  <div class="col-12">
                    <label for="yourUsername" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="yourUsername" required>
                    <div class="invalid-feedback">Masukkan username anda</div>
                  </div>
                  <div class="col-12">
                    <label for="departement" class="form-label">Departement</label>
                    <select class="form-control" name="departement" id="departement">
                      <option value="maintenance">Maintenance</option>
                      <option value="purchasing">Purchasing</option>
                      <option value="pd">Production</option>
                      <option value="qc">QC</option>
                      <option value="qa">QA</option>
                      <option value="it">IT</option>
                    </select>
                  </div>
                  <div class="col-12">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-control" name="role" id="role">
                      <option value="member">Member</option>
                      <option value="leader">Leader</option>
                      <option value="manager">Manager</option>
                      <option value="admin">Admin</option>
                    </select>
                  </div>
                  <div class="col-12">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                    <div class="invalid-feedback">Masukkan password anda</div>
                  </div>

                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Create Account</button>
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
