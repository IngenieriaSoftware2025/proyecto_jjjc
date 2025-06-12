import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';

const FormEmpleados = document.getElementById('FormEmpleados');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarEmpleados = document.getElementById('BtnBuscarEmpleados');
const TablaEmpleados = document.getElementById('TablaEmpleados');

const GuardarEmpleado = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    const body = new FormData(FormEmpleados);
    const url = '/proyecto_jjjc/empleados/guardar';
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
            BuscarEmpleados();

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
            text: "Ocurrió un problema al guardar el empleado",
            showConfirmButton: true,
        });
    }
    BtnGuardar.disabled = false;
}

const BuscarEmpleados = async () => {
    const url = '/proyecto_jjjc/empleados/buscar';
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
            TablaEmpleados.innerHTML = '';
            
            // Agregar cada empleado a la tabla
            data.forEach((empleado, index) => {
                const fila = document.createElement('tr');
                
                // Determinar color de especialidad
                let colorEspecialidad = 'bg-secondary';
                switch(empleado.empleado_especialidad) {
                    case 'Reparacion de Pantallas':
                        colorEspecialidad = 'bg-primary';
                        break;
                    case 'Software y Formateo':
                        colorEspecialidad = 'bg-info';
                        break;
                    case 'Cambio de Baterias':
                        colorEspecialidad = 'bg-warning';
                        break;
                    case 'Reparacion de Placas':
                        colorEspecialidad = 'bg-danger';
                        break;
                    case 'Liberacion de Equipos':
                        colorEspecialidad = 'bg-success';
                        break;
                    case 'Tecnico Senior':
                        colorEspecialidad = 'bg-dark';
                        break;
                }
                
                fila.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${empleado.empleado_nombres}</td>
                    <td>${empleado.empleado_apellidos}</td>
                    <td><span class="badge bg-info">${empleado.empleado_telefono}</span></td>
                    <td><span class="badge ${colorEspecialidad}">${empleado.empleado_especialidad}</span></td>
                    <td>${empleado.empleado_fecha_ingreso}</td>
                    <td>
                        <div class='d-flex justify-content-center gap-1'>
                            <button class='btn btn-warning btn-sm modificar' 
                                data-id="${empleado.empleado_id}" 
                                data-nombres="${empleado.empleado_nombres}"  
                                data-apellidos="${empleado.empleado_apellidos}"  
                                data-telefono="${empleado.empleado_telefono}"  
                                data-especialidad="${empleado.empleado_especialidad}">
                                <i class='bi bi-pencil-square'></i>
                            </button>
                            <button class='btn btn-danger btn-sm eliminar' 
                                data-id="${empleado.empleado_id}">
                                <i class="bi bi-trash3"></i>
                            </button>
                            <button class='btn btn-info btn-sm ver-detalle' 
                                data-id="${empleado.empleado_id}"
                                data-nombres="${empleado.empleado_nombres}"  
                                data-apellidos="${empleado.empleado_apellidos}"  
                                data-telefono="${empleado.empleado_telefono}"  
                                data-especialidad="${empleado.empleado_especialidad}"
                                data-fecha="${empleado.empleado_fecha_ingreso}">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                TablaEmpleados.appendChild(fila);
            });

            // Agregar event listeners a los botones
            document.querySelectorAll('.modificar').forEach(btn => {
                btn.addEventListener('click', llenarFormulario);
            });
            
            document.querySelectorAll('.eliminar').forEach(btn => {
                btn.addEventListener('click', EliminarEmpleado);
            });

            document.querySelectorAll('.ver-detalle').forEach(btn => {
                btn.addEventListener('click', verDetalleEmpleado);
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
            text: "Ocurrió un problema al buscar los empleados",
            showConfirmButton: true,
        });
    }
}

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset

    document.getElementById('empleado_id').value = datos.id
    document.getElementById('empleado_nombres').value = datos.nombres
    document.getElementById('empleado_apellidos').value = datos.apellidos
    document.getElementById('empleado_telefono').value = datos.telefono
    document.getElementById('empleado_especialidad').value = datos.especialidad

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0
    });
}

const verDetalleEmpleado = (event) => {
    const datos = event.currentTarget.dataset;
    
    Swal.fire({
        title: 'Detalle del Empleado',
        html: `
            <div class="text-start">
                <p><strong>Nombres:</strong> ${datos.nombres}</p>
                <p><strong>Apellidos:</strong> ${datos.apellidos}</p>
                <p><strong>Teléfono:</strong> ${datos.telefono}</p>
                <p><strong>Especialidad:</strong> ${datos.especialidad}</p>
                <p><strong>Fecha de Ingreso:</strong> ${datos.fecha}</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Cerrar'
    });
}

const limpiarTodo = () => {
    FormEmpleados.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarEmpleado = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    const body = new FormData(FormEmpleados);
    const url = '/proyecto_jjjc/empleados/modificar';
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
            BuscarEmpleados();

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
            text: "Ocurrió un problema al modificar el empleado",
            showConfirmButton: true,
        });
    }
    BtnModificar.disabled = false;
}

const EliminarEmpleado = async (e) => {
    const idEmpleado = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "warning",
        title: "¿Desea ejecutar esta acción?",
        text: '¿Está completamente seguro que desea eliminar este empleado?',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#d33',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/proyecto_jjjc/empleados/eliminar?id=${idEmpleado}`;
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

                BuscarEmpleados();
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
                text: "Ocurrió un problema al eliminar el empleado",
                showConfirmButton: true,
            });
        }
    }
}

// Event Listeners
FormEmpleados.addEventListener('submit', GuardarEmpleado);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarEmpleado);
BtnBuscarEmpleados.addEventListener('click', BuscarEmpleados);

// Buscar empleados al inicio
BuscarEmpleados();