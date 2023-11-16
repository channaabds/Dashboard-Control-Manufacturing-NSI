@extends('layouts.main')

@section('content')
<main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-6 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <div class="logo d-flex align-items-center w-auto">
                  <img src="{{ asset('./img/logo/logo-nsi.jpg') }}" alt="logo nsi">
                  <span class="d-none d-lg-block">Dashboard Control Manufacturing</span>
                </div>
              </div>

              <div class="row row-cols-1 row-cols-md-2 g-4 border border-info rounded pb-2 mt-3">
                <a href="/maintenance">
                  <div class="col">
                    <div class="card h-100 text-bg-danger text-center">
                      <div class="card-body">
                        <h5 class="card-title">Maintenance</h5>
                      </div>
                    </div>
                  </div>
                </a>
                <a href="/purchasing">
                  <div class="col">
                    <div class="card h-100 text-bg-warning text-center">
                      <div class="card-body">
                        <h5 class="card-title">Purchasing</h5>
                      </div>
                    </div>
                  </div>
                </a>
                <a href="/quality">
                  <div class="col">
                    <div class="card h-100 text-bg-success text-center">
                      <div class="card-body">
                        <h5 class="card-title">Quality</h5>
                      </div>
                    </div>
                  </div>
                </a>
                <a href="/target">
                  <div class="col">
                    <div class="card h-100 text-bg-primary text-center">
                      <div class="card-body">
                        <h5 class="card-title">Target</h5>
                      </div>
                    </div>
                  </div>
                </a>
              </div>

              {{-- <div class="container border border-dark p-4 rounded">
                <div class="row">
                  <div class="col-md-12 card-container">
                    <form action="/maintenance" method="get">
                      <button class="card py-3 bg-success d-flex align-items-center text-center px-3">
                        <p class="text-light fs-4">Maintenance 🛠</p>
                      </button>
                    </form>
                    <form action="/purchasing" method="get">
                      <button class="card py-3 bg-warning d-flex align-items-center text-center px-3">
                        <p class="text-light fs-4">Purchasing 💵</p>
                      </button>
                    </form>
                    <form action="/quality" method="get">
                      <button class="card py-3 bg-danger d-flex align-items-center text-center px-3">
                        <p class="text-light fs-4">Quality ⚙</p>
                      </button>
                    </form>
                    <form action="/target" method="get">
                      <button class="card py-3 bg-danger d-flex align-items-center text-center px-3">
                        <p class="text-light fs-4">Target 📈</p>
                      </button>
                    </form>
                  </div>
                </div>
              </div> --}}

            </div>
          </div>
        </div>

      </section>

    </div>
  </main>
@endsection
