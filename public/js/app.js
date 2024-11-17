window.onload = function() {
    cargar()
    $.fn.dataTable.ext.errMode = function(settings, helpPage, message) {
        Swal.fire({
            title: 'Error!',
            text: 'Debe iniciar sesión',
            icon: 'error',
            confirmButtonText: 'Ok'
        })
        window.location.href = "/";
    };
};

function createModal(){
    $('#createModal').show();
}

function statusModal(id,id_status){
    $("#taskID").val(id);
    $.ajax({
        url: '/api/getStatus',
        headers: {
            'Authorization':'Bearer ' + localStorage.getItem('token')
        },
        type: 'get',
        accepts: "application/json",
        crossDomain: true,
        success: function (result) {
            $("#statusID").empty()
            $.each(result,function(key, registro) {
                $("#statusID").append('<option value='+registro.id+'>'+registro.name+'</option>');
            });
        },
        error: function (e) {
            console.log(e.message);
        }
    });
    $('#statusModal').show();
}

function editModal(id){
    $.ajax({
        url: '/api/task/show/'+id,
        headers: {
            'Authorization':'Bearer ' + localStorage.getItem('token')
        },
        type: 'GET',
        accepts: "application/json",
        crossDomain: true,
        success: function (result) {
            $('#editID').val(id);
            $('#editTitle').val(result.titulo);
            $('#editDescripcion').val(result.descripcion);
            fec_vencimiento = moment(result.fec_vencimiento, "yyyy-MM-dd").format('yyyy-MM-DD');
            console.log(fec_vencimiento);
            $('#edit_fec_vencimiento').val(fec_vencimiento);
        },
        error: function (e) {
            console.log(e.message);
        }
    });
    $('#editModal').show();
}

function deleteModal(){
    $('#deleteModal').show();
}

function cerrarModal(){
    $('#createModal').hide();
    $('#editModal').hide();
    $('#deleteModal').hide();
    $('#title').val();
    $('#descripcion').val();
    $('#fec_vencimiento').val();
    $('#statusModal').hide();
}

function cargar(){
    tabla = $('#dataTask').DataTable({
        ajax: {
            type: "GET",
            url: 'http://127.0.0.1:8000/api/task/',
            headers: {"Authorization": "Bearer "+localStorage.getItem('token')}
        },
        columns: [
            {"data" : "id"},
            {"data" : "titulo"},
            {"data" : "descripcion"},
            {"data" : "fec_vencimiento"},
            {"data" : "name"},
            {"data" : "name"},
            {"data" : "name"},
            {"data" : "name"},
            {"data" : "name"},
        ],columnDefs:[
            {
                targets:-4,
                render: function (data, type, row, meta) {
                    let boton = "<button type='button' class='btn btn-primary' data-toggle='tooltip' title='Editar' onclick='editModal("+row.id+")'><i class='fa fa-edit' aria-hidden='true'></i></button>"
                    return boton
                }
            },
            {
                targets:-3,
                render: function (data, type, row, meta) {
                    let boton = "<button type='button' class='btn btn-secondary' data-toggle='tooltip' title='Cambiar estado' onclick='statusModal("+row.id+")'><i class='fa fa-arrow-right' aria-hidden='true'></i></button>"
                    return boton
                }
            },
            {
                targets:-2,
                render: function (data, type, row, meta) {
                    let boton = "<button type='button' class='btn btn-success' data-toggle='tooltip' title='Finalizar' onclick='finishTask("+row.id+",3)'><i class='fa fa-check' aria-hidden='true'></i></button>"
                    return boton
                }
            },
            {
                targets:-1,
                render: function (data, type, row, meta) {
                    let boton = "<button type='button' class='btn btn-danger' data-toggle='tooltip' title='Eliminar' onclick='deleteTask("+row.id+")'><i class='fa fa-trash' aria-hidden='true'></i></button>"
                    return boton
                }
            }
        ],
        layout: {
            topStart: false
        },
        destroy: true,
        async: true,
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(xhr, code);
        }
    });
}

function create(){
    title = $('#title').val();
    descripcion = $('#descripcion').val();
    fec_vencimiento = $('#fec_vencimiento').val();
    if(title == "" || descripcion == "" || fec_vencimiento == ""){
        Swal.fire({
            title: 'Error!',
            text: 'Valide la información ingresada',
            icon: 'error',
            confirmButtonText: 'Ok'
        })
        return 1;
    }
    axios.post('/api/task/', {
            "titulo": title,
            "descripcion": descripcion,
            "fec_vencimiento": fec_vencimiento
    },{headers: {
        Authorization: 'Bearer ' + localStorage.getItem('token') //the token is a variable which holds the token
    }})
    .then(function (response) {
        Swal.fire({
            title: 'Succes!',
            text: 'Ingresado correctamente',
            icon: 'success',
            confirmButtonText: 'Ok'
        })
        cargar();
        cerrarModal();
    })
    .catch(function (error) {
        Swal.fire({
            title: 'Error!',
            text: 'Información incorrecta',
            icon: 'error',
            confirmButtonText: 'Ok'
        })
    });
}

function edit(){
    id = $('#editID').val();
    title = $('#editTitle').val();
    descripcion = $('#editDescripcion').val();
    fec_vencimiento = $('#edit_fec_vencimiento').val();
    if(id == "" || title == "" || descripcion == "" || fec_vencimiento == ""){
        Swal.fire({
            title: 'Error!',
            text: 'Valide la información ingresada',
            icon: 'error',
            confirmButtonText: 'Ok'
        })
        return 1;
    }
    axios.put('/api/task/update', {
            "id": id,
            "titulo": title,
            "descripcion": descripcion,
            "fec_vencimiento": fec_vencimiento
    },{headers: {
        Authorization: 'Bearer ' + localStorage.getItem('token') //the token is a variable which holds the token
    }})
    .then(function (response) {
        Swal.fire({
            title: 'Succes!',
            text: 'Registro actualizado correctamente',
            icon: 'success',
            confirmButtonText: 'Ok'
        })
        tabla.destroy();
        cerrarModal();
        cargar();
    })
    .catch(function (error) {
        Swal.fire({
            title: 'Error!',
            text: 'Fallo al actualizar la información',
            icon: 'error',
            confirmButtonText: 'Ok'
        })
    });
}

function deleteTask(id){
    Swal.fire({
    title: 'Seguro que desea eliminar esta tarea?',
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: 'Yes',
    denyButtonText: 'No',
    customClass: {
        actions: 'my-actions',
        cancelButton: 'order-1 right-gap',
        confirmButton: 'order-2',
        denyButton: 'order-3',
    },
    }).then((result) => {
    if (result.isConfirmed) {
        $.ajax({
            url: '/api/task/delete/'+id,
            headers: {
                'Authorization':'Bearer ' + localStorage.getItem('token')
            },
            type: 'DELETE',
            accepts: "application/json",
            crossDomain: true,
            success: function (result) {
                Swal.fire('Eliminado con Exito!', '', 'success')
                tabla.destroy();
                cerrarModal();
                cargar();
            },
            error: function (e) {
                if(e.status == 403){
                    Swal.fire('No esta autorizado para esta acción!', '', 'alert')
                }
            }
        });
    }
    })
}

function status(){
    id = $('#taskID').val();
    id_status = $('#statusID').val();
    axios.post('/api/task/updateStatus', {
            "id": id,
            "id_status": id_status
    },{headers: {
        Authorization: 'Bearer ' + localStorage.getItem('token') //the token is a variable which holds the token
    }})
    .then(function (response) {
        Swal.fire({
            title: 'Succes!',
            text: 'Registro actualizado correctamente',
            icon: 'success',
            confirmButtonText: 'Ok'
        })
        tabla.destroy();
        cerrarModal();
        cargar();
    })
    .catch(function (error) {
        Swal.fire({
            title: 'Error!',
            text: 'Fallo al actualizar la información',
            icon: 'error',
            confirmButtonText: 'Ok'
        })
    });
}

function finishTask(id,status){
    Swal.fire({
    title: 'Seguro que desea finalizar esta tarea?',
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: 'Yes',
    denyButtonText: 'No',
    customClass: {
        actions: 'my-actions',
        cancelButton: 'order-1 right-gap',
        confirmButton: 'order-2',
        denyButton: 'order-3',
    },
    }).then((result) => {
        if (result.isConfirmed) {
                axios.post('/api/task/updateStatus', {
                    "id": id,
                    "id_status": status
            },{headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token') //the token is a variable which holds the token
            }})
            .then(function (response) {
                Swal.fire({
                    title: 'Succes!',
                    text: 'Registro actualizado correctamente',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                })
                tabla.destroy();
                cerrarModal();
                cargar();
            })
            .catch(function (error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Fallo al actualizar la información',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                })
            });
        }
    })
}

function salir(){
    localStorage.setItem('token', null);
    window.location.href = "/";
}