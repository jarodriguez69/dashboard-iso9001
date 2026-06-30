<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>@yield('title', 'Dashboard ISO 9001')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root { --tblr-font-sans-serif: 'Inter UI', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; }
      body { font-feature-settings: "cv03", "cv04", "cv11"; }
    </style>
    
    @yield('styles')
  </head>
  <body>
    <div class="page">
      <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
            <span class="navbar-toggler-icon"></span>
          </button>
          <h1 class="navbar-brand navbar-brand-autodark">
            <img src="https://sistemaogj.com/public/vendor/adminlte/dist/img/AdminLTELogo.png" class="brand-image img-circle" style="opacity:.8; max-width: 20%;">
            SGC Auditorías
            
          </h1>
          <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
              <li class="nav-item">
                <a class="nav-link" href="/dashboard" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-dashboard"></i></span>
                  <span class="nav-link-title">Panel Principal</span>
                </a>
              </li>
              @if(Auth::user()->rol === 'Admin')
              <li class="nav-item">
                <a class="nav-link" href="{{ route('unidades.index') }}" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-building-community"></i></span>
                  <span class="nav-link-title">Unidades</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('auditores.index') }}" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-users"></i></span>
                  <span class="nav-link-title">Equipo Auditor</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('encuestas.index') }}" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-file-text"></i></span>
                  <span class="nav-link-title">Encuestas</span>
                </a>
              </li>
              @endif
              <li class="nav-item">
                <a class="nav-link" href="{{ route('auditorias.index') }}" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-calendar-event"></i></span>
                  <span class="nav-link-title">Programa de Auditorías</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('hallazgos.index') }}" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-alert-triangle"></i></span>
                  <span class="nav-link-title">Hallazgos</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('redactor') }}" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-refresh-alert"></i></span>
                  <span class="nav-link-title">Asistente IA para Redacción de Hallazgos</span>
                </a>
              </li>
            </ul>
            @auth
            <div class="mt-auto p-3 w-100 border-top">
                <div class="d-flex align-items-center mb-3">
                    <span class="avatar avatar-sm bg-blue-lt me-2">{{ substr(Auth::user()->name, 0, 2) }}</span>
                    <div class="d-flex flex-column text-truncate">
                        <span class="font-weight-bold text-truncate">{{ Auth::user()->name }}</span>
                        <span class="text-secondary small">{{ Auth::user()->rol ?? 'Auditor' }}</span>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="ti ti-logout me-2"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
            @endauth
          </div>
        </div>
      </aside>

      <div class="page-wrapper">
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h2 class="page-title">
                  @yield('page-title', 'Dashboard')
                </h2>
              </div>
            </div>
          </div>
        </div>
        
        <div class="page-body">
          <div class="container-xl">
            @yield('content')
          </div>
        </div>

        <footer class="footer footer-transparent d-print-none">
          <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
              <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item">
                    SGC Auditorías &copy; {{ date('Y') }} - OGJ
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js" defer></script>
    
    @yield('scripts')
  </body>
</html>