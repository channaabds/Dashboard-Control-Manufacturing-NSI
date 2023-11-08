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

              <div class="container border border-dark p-4 rounded">
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
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main>
@endsection
