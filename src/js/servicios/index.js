import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';

const FormServicios = document.getElementById('FormServicios');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarServicios = document.getElementById('BtnBuscarServicios');
const TablaServicios = document.getElementById('TablaServicios');

const GuardarServicio = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    const body = new FormData(FormServicios);
    const url = '/proyecto_jjjc/servicios/guardar';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarServicios();

        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Ocurrió un problema al guardar el servicio",
            showConfirmButton: true,
        });
    }
    BtnGuardar.disabled = false;
}

const BuscarServicios = async () => {
    const url = '/proyecto_jjjc/servicios/buscar';
    const config = {
        method: 'POST'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            // Limpiar tabla
            TablaServicios.innerHTML = '';
            
            // Agregar cada servicio a la tabla
            data.forEach((servicio, index) => {
                const fila = document.createElement('tr');
                
                // Determinar categoría de precio y color de fila
                let categoria = '';
                let claseFila = '';
                const precio = parseFloat(servicio.serv_precio);
                
                if (precio >= 400) {
                    categoria = 'Premium';
                    claseFila = 'precio-alto';
                } else if (precio >= 150) {
                    categoria = 'Moderado';
                    claseFila = 'precio-medio';
                } else {
                    categoria = 'Económico';
                    claseFila = 'precio-bajo';
                }
                
                // Aplicar clase a la fila
                fila.className = claseFila;
                
                fila.innerHTML = `
                    <td>${index + 1}</td>
                    <td><strong>${servicio.serv_nombre}</strong></td>
                    <td><span class="badge bg-success">Q. ${servicio.serv_precio}</span></td>
                    <td>${servicio.serv_descripcion}</td>
                    <td><span class="badge bg-info">${categoria}</span></td>
                    <td>
                        <div class='d-flex justify-content-center gap-1'>
                            <button class='btn btn-warning btn-sm modificar' 
                                data-id="${servicio.serv_id}" 
                                data-nombre="${servicio.serv_nombre}"  
                                data-precio="${servicio.serv_precio}"  
                                data-descripcion="${servicio.serv_descripcion}">
                                <i class='bi bi-pencil-square'></i>
                            </button>
                            <button class='btn btn-danger btn-sm eliminar' 
                                data-id="${servicio.serv_id}">
                                <i class="bi bi-trash3"></i>
                            </button>
                            <button class='btn btn-info btn-sm ver-detalle' 
                                data-id="${servicio.serv_id}"
                                data-nombre="${servicio.serv_nombre}"  
                                data-precio="${servicio.serv_precio}"  
                                data-descripcion="${servicio.serv_descripcion}"
                                data-categoria="${categoria}">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                TablaServicios.appendChild(fila);
            });

            // Agregar event listeners a los botones
            document.querySelectorAll('.modificar').forEach(btn => {
                btn.addEventListener('click', llenarFormulario);
            });
            
            document.querySelectorAll('.eliminar').forEach(btn => {
                btn.addEventListener('click', EliminarServicio);
            });

            document.querySelectorAll('.ver-detalle').forEach(btn => {
                btn.addEventListener('click', verDetalleServicio);
            });

        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Info",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Ocurrió un problema al buscar los servicios",
            showConfirmButton: true,
        });
    }
}

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset

    document.getElementById('serv_id').value = datos.id
    document.getElementById('serv_nombre').value = datos.nombre
    document.getElementById('serv_precio').value = datos.precio
    document.getElementById('serv_descripcion').value = datos.descripcion

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0
    });
}

const verDetalleServicio = (event) => {
    const datos = event.currentTarget.dataset;
    
    Swal.fire({
        title: 'Detalle del Servicio',
        html: `
            <div class="text-start">
                <p><strong>Servicio:</strong> ${datos.nombre}</p>
                <p><strong>Precio:</strong> Q. ${datos.precio}</p>
                <p><strong>Categoría:</strong> ${datos.categoria}</p>
                <p><strong>Descripción:</strong> ${datos.descripcion}</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Cerrar'
    });
}

const limpiarTodo = () => {
    FormServicios.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarServicio = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    const body = new FormData(FormServicios);
    const url = '/proyecto_jjjc/servicios/modificar';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarServicios();

        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Ocurrió un problema al modificar el servicio",
            showConfirmButton: true,
        });
    }
    BtnModificar.disabled = false;
}

const EliminarServicio = async (e) => {
    const idServicio = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "warning",
        title: "¿Desea ejecutar esta acción?",
        text: '¿Está completamente seguro que desea eliminar este servicio?',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#d33',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/proyecto_jjjc/servicios/eliminar?id=${idServicio}`;
        const config = {
            method: 'GET'
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Éxito",
                    text: mensaje,
                    showConfirmButton: true,
                });

                BuscarServicios();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }

        } catch (error) {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: "Ocurrió un problema al eliminar el servicio",
                showConfirmButton: true,
            });
        }
    }
}

// Event Listeners
FormServicios.addEventListener('submit', GuardarServicio);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarServicio);
BtnBuscarServicios.addEventListener('click', BuscarServicios);

// Buscar servicios al inicio
BuscarServicios();