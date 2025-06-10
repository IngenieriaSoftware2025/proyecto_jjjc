import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';

const FormClientes = document.getElementById('FormClientes');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarClientes = document.getElementById('BtnBuscarClientes');
const TablaClientes = document.getElementById('TablaClientes');

const GuardarCliente = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    const body = new FormData(FormClientes);
    const url = '/proyecto_jjjc/clientes/guardar';
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
            BuscarClientes();

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
            text: "Ocurrió un problema al guardar el cliente",
            showConfirmButton: true,
        });
    }
    BtnGuardar.disabled = false;
}

const BuscarClientes = async () => {
    const url = '/proyecto_jjjc/clientes/buscar';
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
            TablaClientes.innerHTML = '';
            
            // Agregar cada cliente a la tabla
            data.forEach((cliente, index) => {
                const fila = document.createElement('tr');
                
                fila.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${cliente.cli_nombres}</td>
                    <td>${cliente.cli_apellidos}</td>
                    <td><span class="badge bg-info">${cliente.cli_telefono}</span></td>
                    <td>${cliente.cli_nit || 'N/A'}</td>
                    <td>${cliente.cli_correo || 'N/A'}</td>
                    <td>${cliente.cli_fecha_registro}</td>
                    <td>
                        <div class='d-flex justify-content-center gap-1'>
                            <button class='btn btn-warning btn-sm modificar' 
                                data-id="${cliente.cli_id}" 
                                data-nombres="${cliente.cli_nombres}"  
                                data-apellidos="${cliente.cli_apellidos}"  
                                data-telefono="${cliente.cli_telefono}"  
                                data-nit="${cliente.cli_nit || ''}"  
                                data-correo="${cliente.cli_correo || ''}"  
                                data-direccion="${cliente.cli_direccion}">
                                <i class='bi bi-pencil-square'></i>
                            </button>
                            <button class='btn btn-danger btn-sm eliminar' 
                                data-id="${cliente.cli_id}">
                                <i class="bi bi-trash3"></i>
                            </button>
                            <button class='btn btn-info btn-sm ver-detalle' 
                                data-id="${cliente.cli_id}"
                                data-nombres="${cliente.cli_nombres}"  
                                data-apellidos="${cliente.cli_apellidos}"  
                                data-telefono="${cliente.cli_telefono}"  
                                data-nit="${cliente.cli_nit || 'N/A'}"  
                                data-correo="${cliente.cli_correo || 'N/A'}"  
                                data-direccion="${cliente.cli_direccion}"
                                data-fecha="${cliente.cli_fecha_registro}">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                TablaClientes.appendChild(fila);
            });

            // Agregar event listeners a los botones
            document.querySelectorAll('.modificar').forEach(btn => {
                btn.addEventListener('click', llenarFormulario);
            });
            
            document.querySelectorAll('.eliminar').forEach(btn => {
                btn.addEventListener('click', EliminarCliente);
            });

            document.querySelectorAll('.ver-detalle').forEach(btn => {
                btn.addEventListener('click', verDetalleCliente);
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
            text: "Ocurrió un problema al buscar los clientes",
            showConfirmButton: true,
        });
    }
}

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset

    document.getElementById('cli_id').value = datos.id
    document.getElementById('cli_nombres').value = datos.nombres
    document.getElementById('cli_apellidos').value = datos.apellidos
    document.getElementById('cli_telefono').value = datos.telefono
    document.getElementById('cli_nit').value = datos.nit
    document.getElementById('cli_correo').value = datos.correo
    document.getElementById('cli_direccion').value = datos.direccion

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0
    });
}

const verDetalleCliente = (event) => {
    const datos = event.currentTarget.dataset;
    
    Swal.fire({
        title: 'Detalle del Cliente',
        html: `
            <div class="text-start">
                <p><strong>Nombres:</strong> ${datos.nombres}</p>
                <p><strong>Apellidos:</strong> ${datos.apellidos}</p>
                <p><strong>Teléfono:</strong> ${datos.telefono}</p>
                <p><strong>NIT:</strong> ${datos.nit}</p>
                <p><strong>Correo:</strong> ${datos.correo}</p>
                <p><strong>Dirección:</strong> ${datos.direccion}</p>
                <p><strong>Fecha de Registro:</strong> ${datos.fecha}</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Cerrar'
    });
}

const limpiarTodo = () => {
    FormClientes.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarCliente = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    const body = new FormData(FormClientes);
    const url = '/proyecto_jjjc/clientes/modificar';
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
            BuscarClientes();

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
            text: "Ocurrió un problema al modificar el cliente",
            showConfirmButton: true,
        });
    }
    BtnModificar.disabled = false;
}

const EliminarCliente = async (e) => {
    const idCliente = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "warning",
        title: "¿Desea ejecutar esta acción?",
        text: '¿Está completamente seguro que desea eliminar este cliente?',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#d33',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/proyecto_jjjc/clientes/eliminar?id=${idCliente}`;
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

                BuscarClientes();
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
                text: "Ocurrió un problema al eliminar el cliente",
                showConfirmButton: true,
            });
        }
    }
}

// Event Listeners
FormClientes.addEventListener('submit', GuardarCliente);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarCliente);
BtnBuscarClientes.addEventListener('click', BuscarClientes);

// Buscar clientes al inicio
BuscarClientes();