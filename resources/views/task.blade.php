<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Staff Boom</title>
        <script src="{{ asset('js/app.js') }}"></script>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.7.1.js"integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
        <script src="https://momentjs.com/downloads/moment.min.js" integrity="sha512-hUhvpC5f8cgc04OZb55j0KNGh4eh7dLxd/dPSJ5VyzqDWxsayYbojWyl5Tkcgrmb/RVKCRJI1jNlRbVP4WWC4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        
        <!-- Styles -->
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
        <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
    </head>
    <body class="antialiased" >
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
              <a class="navbar-brand" href="#">Staff Boom</a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a class="nav-link" aria-current="page">Tareas</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Features</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Pricing</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" onclick="salir()" href="#">Cerrar sesión</a>
                  </li>
                </ul>
              </div>
            </div>
        </nav>
        <div class="container">
            <div class="row justify-content-center">
                    <div class="col-lg-8 mt-3 ml-2">
                        <h2>Gestión de tareas</h2>
                    </div>
                    <div class="col-lg-2 mt-3">
                        <button type="button" class="btn btn-primary" onclick="createModal()">Nueva tarea</button>
                    </div>
                <div class="col-lg-10">
                    <table id="dataTask" class="table table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Titulo</th>
                                <th>Descripcion</th>
                                <th>Fecha fin</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal" id="createModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h1 class="modal-title fs-5" id="createModalLabel">Crear tarea</h1>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Titulo</label>
                                <input type="text" class="form-control" id="title" placeholder="Realizar informe">
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="fec_vencimiento" class="form-label">Fecha vencimiento</label>
                                <input type="date" class="form-control" id="fec_vencimiento">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" onclick="create()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal" id="editModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h1 class="modal-title fs-5" id="editModalLabel">Editar tarea</h1>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="editTitle" class="form-label">Titulo</label>
                                <input type="text" class="form-control" id="editTitle" placeholder="Realizar informe">
                                <input type="text" class="form-control" hidden id="editID" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="editDescripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="editDescripcion" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="edit_fec_vencimiento" class="form-label">Fecha vencimiento</label>
                                <input type="date" class="form-control" id="edit_fec_vencimiento">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" onclick="edit()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal" id="statusModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h1 class="modal-title fs-5" id="statusModalLabel">Actualizar estado</h1>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="statusID" class="form-label">Estado</label>
                                <select id="statusID" class="form-control"></select>
                                <input type="text" class="form-control" hidden id="taskID" readonly>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" onclick="status()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal fin -->
        </div>
    </body>
</html>

<script>
</script>
