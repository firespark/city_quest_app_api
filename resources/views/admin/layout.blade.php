<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Административная панель</title>

    <link href="/admin_assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin_assets/css/select2.min.css" rel="stylesheet">
    <link href="/admin_assets/fonts/fontawesome/css/all.css" rel="stylesheet">
    <link href="/admin_assets/css/admin.css" rel="stylesheet">

</head>
<body>
    <header>
        <nav class="navbar navbar-expand navbar-light bg-light mb-4">
          <div class="container-fluid">
            <a class="navbar-brand me-auto" href="#">Административная панель</a>
            
            <div>
              
              <ul class="d-flex navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                  <a class="nav-link" href="/logout">Выход</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
    </header>
    <main>
        <div class="container-fluid">
            <div class="row">
                @include('admin._sidebar')
                <div class="col-sm-9">
                    

                  @yield('content')


                </div>
              </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer mt-4 py-3 bg-light font-small">

      <!-- Copyright -->
      <div class="footer-copyright text-center py-3">© 2020 Copyright:
        <a href="https://gagara-web.ru/"> Веб-Студия "Гагара"</a>
      </div>
      <!-- Copyright -->

    </footer>
    <!-- Footer -->

    <script src="/admin_assets/js/jquery-3.6.0.min.js"></script>
    <script src="/admin_assets/js/bootstrap.bundle.min.js"></script>
    <script src="/admin_assets/js/select2.full.min.js"></script>
    <script src="/admin_assets/js/admin.js"></script>
</body>
</html>