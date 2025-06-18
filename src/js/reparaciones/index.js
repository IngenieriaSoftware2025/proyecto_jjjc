import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';

// Elementos principales del formulario
const formularioReparaciones = document.getElementById('FormReparaciones');
const botonGuardar = document.getElementById('BtnGuardar');
const botonModificar = document.getElementById('BtnModificar');
const botonLimpiar = document.getElementById('BtnLimpiar');
const botonBuscarOrdenes = document.getElementById('BtnBuscarOrdenes');
const tablaOrdenes = document.getElementById('TablaOrdenes');

// Elementos select del formulario
const selectorCliente = document.getElementById('orden_cli_id');
const selectorEmpleado = document.getElementById('orden_empleado_id');
const selectorServicio = document.getElementById('orden_serv_id');
const selectorEstado = document.getElementById('orden_estado');
const campoMostrarPrecio = document.getElementById('mostrar_precio');

// Función principal para guardar una nueva orden de reparación
const guardarReparacion = async (evento) => {
    evento.preventDefault();
    botonGuardar.disabled = true;

    const datosFormulario = new FormData(formularioReparaciones);
    const urlDestino = '/proyecto_jjjc/reparaciones/guardar';
    const configuracionPeticion = {
        method: 'POST',
        body: datosFormulario
    };

    try {
        const respuestaServidor = await fetch(urlDestino, configuracionPeticion);
        
        // Verificar si la respuesta es válida
        if (!respuestaServidor.ok) {
            throw new Error(`Error del servidor: ${respuestaServidor.status}`);
        }

        const datosRespuesta = await respuestaServidor.json();
        const { codigo, mensaje } = datosRespuesta;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Orden Creada Exitosamente!",
                text: mensaje,
                showConfirmButton: true,
                timer: 2000
            });

            limpiarFormulario();
            buscarOrdenes();

        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error al Crear Orden",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (errorCapturado) {
        // Manejo más específico de errores
        let mensajeError = "Error inesperado al guardar la orden";
        
        if (errorCapturado.name === 'TypeError') {
            mensajeError = "Problema de conexión con el servidor";
        } else if (errorCapturado.message.includes('404')) {
            mensajeError = "La ruta del servidor no fue encontrada";
        } else if (errorCapturado.message.includes('500')) {
            mensajeError = "Error interno del servidor";
        }

        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de Conexión",
            text: mensajeError,
            showConfirmButton: true,
        });
        
        console.error('Detalles del error en guardarReparacion:', {
            error: errorCapturado,
            mensaje: errorCapturado.message,
            pila: errorCapturado.stack
        });
    }
    
    botonGuardar.disabled = false;
}

// Función para buscar y mostrar todas las órdenes de reparación
const buscarOrdenes = async () => {
    const urlDestino = '/proyecto_jjjc/reparaciones/buscar';
    const configuracionPeticion = {
        method: 'POST'
    };

    try {
        const respuestaServidor = await fetch(urlDestino, configuracionPeticion);
        
        if (!respuestaServidor.ok) {
            throw new Error(`Error del servidor: ${respuestaServidor.status}`);
        }

        const datosRespuesta = await respuestaServidor.json();
        const { codigo, mensaje, data } = datosRespuesta;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Datos Cargados",
                text: mensaje,
                showConfirmButton: true,
                timer: 1500
            });

            // Limpiar contenido anterior de la tabla
            tablaOrdenes.innerHTML = '';
            
            // Generar las filas de la tabla con los datos obtenidos
            data.forEach((ordenActual, indiceOrden) => {
                const filaOrden = document.createElement('tr');
                
                // Determinar el color del estado según el progreso
                let colorEstado = 'bg-secondary';
                switch(ordenActual.orden_estado) {
                    case 'RECIBIDO':
                        colorEstado = 'bg-info';
                        break;
                    case 'EN_PROCESO':
                        colorEstado = 'bg-warning';
                        break;
                    case 'TERMINADO':
                        colorEstado = 'bg-success';
                        break;
                    case 'ENTREGADO':
                        colorEstado = 'bg-primary';
                        break;
                }
                
                filaOrden.innerHTML = `
                    <td>${indiceOrden + 1}</td>
                    <td>${ordenActual.cliente_completo}</td>
                    <td>${ordenActual.celular_completo}</td>
                    <td>${ordenActual.serv_nombre}</td>
                    <td>${ordenActual.empleado_completo}</td>
                    <td><span class="badge ${colorEstado}">${ordenActual.orden_estado}</span></td>
                    <td><span class="badge bg-success">Q. ${parseFloat(ordenActual.precio_formateado).toFixed(2)}</span></td>
                    <td>${ordenActual.fecha_formateada}</td>
                    <td>
                        <div class='d-flex justify-content-center gap-1'>
                            <button class='btn btn-warning btn-sm modificar' 
                                data-id="${ordenActual.orden_id}" 
                                data-cliente="${ordenActual.orden_cli_id}"  
                                data-empleado="${ordenActual.orden_empleado_id}"  
                                data-servicio="${ordenActual.orden_serv_id}"  
                                data-modelo="${ordenActual.orden_modelo_celular}"  
                                data-marca="${ordenActual.orden_marca_celular}"  
                                data-motivo="${ordenActual.orden_motivo_ingreso}"  
                                data-diagnostico="${ordenActual.orden_diagnostico || ''}"  
                                data-estado="${ordenActual.orden_estado}"  
                                data-observaciones="${ordenActual.orden_observaciones || ''}"
                                title="Modificar orden">
                                <i class='bi bi-pencil-square'></i>
                            </button>
                            <button class='btn btn-danger btn-sm eliminar' 
                                data-id="${ordenActual.orden_id}"
                                data-estado="${ordenActual.orden_estado}"
                                title="Eliminar orden">
                                <i class="bi bi-trash3"></i>
                            </button>
                            <button class='btn btn-info btn-sm ver-detalle' 
                                data-id="${ordenActual.orden_id}"
                                data-cliente="${ordenActual.cliente_completo}"
                                data-celular="${ordenActual.celular_completo}"
                                data-servicio="${ordenActual.serv_nombre}"
                                data-empleado="${ordenActual.empleado_completo}"
                                data-estado="${ordenActual.orden_estado}"
                                data-precio="${ordenActual.precio_formateado}"
                                data-fecha="${ordenActual.fecha_formateada}"
                                data-motivo="${ordenActual.orden_motivo_ingreso}"
                                data-diagnostico="${ordenActual.orden_diagnostico || 'Sin diagnóstico'}"
                                data-observaciones="${ordenActual.orden_observaciones || 'Sin observaciones'}"
                                title="Ver detalle completo">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                tablaOrdenes.appendChild(filaOrden);
            });

            // Agregar los event listeners a los botones
            document.querySelectorAll('.modificar').forEach(botonModificar => {
                botonModificar.addEventListener('click', llenarFormulario);
            });
            
            document.querySelectorAll('.eliminar').forEach(botonEliminar => {
                botonEliminar.addEventListener('click', eliminarOrden);
            });
            
            document.querySelectorAll('.ver-detalle').forEach(botonDetalle => {
                botonDetalle.addEventListener('click', verDetalleOrden);
            });

        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Sin Datos",
                text: mensaje,
                showConfirmButton: true,
            });
            
            tablaOrdenes.innerHTML = '';
        }

    } catch (errorCapturado) {
        let mensajeError = "Error al cargar las órdenes de reparación";
        
        if (errorCapturado.name === 'TypeError') {
            mensajeError = "Problema de conexión con el servidor";
        }

        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de Conexión",
            text: mensajeError,
            showConfirmButton: true,
        });
        
        console.error('Detalles del error en buscarOrdenes:', {
            error: errorCapturado,
            mensaje: errorCapturado.message
        });
        
        tablaOrdenes.innerHTML = '';
    }
}

// Función para llenar el formulario con datos existentes para modificación
const llenarFormulario = (evento) => {
    const datosOrden = evento.currentTarget.dataset;

    document.getElementById('orden_id').value = datosOrden.id;
    document.getElementById('orden_cli_id').value = datosOrden.cliente;
    document.getElementById('orden_empleado_id').value = datosOrden.empleado;
    document.getElementById('orden_serv_id').value = datosOrden.servicio;
    document.getElementById('orden_modelo_celular').value = datosOrden.modelo;
    document.getElementById('orden_marca_celular').value = datosOrden.marca;
    document.getElementById('orden_motivo_ingreso').value = datosOrden.motivo;
    document.getElementById('orden_diagnostico').value = datosOrden.diagnostico;
    document.getElementById('orden_estado').value = datosOrden.estado;
    document.getElementById('orden_observaciones').value = datosOrden.observaciones;

    // Actualizar el precio según el servicio seleccionado
    actualizarPrecioServicio();

    botonGuardar.classList.add('d-none');
    botonModificar.classList.remove('d-none');

    window.scrollTo({ top: 0 });
}

// Función para modificar una orden existente
const modificarReparacion = async (evento) => {
    evento.preventDefault();
    botonModificar.disabled = true;

    const datosFormulario = new FormData(formularioReparaciones);
    const urlDestino = '/proyecto_jjjc/reparaciones/modificar';
    const configuracionPeticion = {
        method: 'POST',
        body: datosFormulario
    };

    try {
        const respuestaServidor = await fetch(urlDestino, configuracionPeticion);
        
        if (!respuestaServidor.ok) {
            throw new Error(`Error del servidor: ${respuestaServidor.status}`);
        }

        const datosRespuesta = await respuestaServidor.json();
        const { codigo, mensaje } = datosRespuesta;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Orden Actualizada!",
                text: mensaje,
                showConfirmButton: true,
                timer: 2000
            });

            limpiarFormulario();
            buscarOrdenes();

        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error al Actualizar",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (errorCapturado) {
        let mensajeError = "Error inesperado al modificar la orden";
        
        if (errorCapturado.name === 'TypeError') {
            mensajeError = "Problema de conexión con el servidor";
        }

        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de Conexión",
            text: mensajeError,
            showConfirmButton: true,
        });
        
        console.error('Detalles del error en modificarReparacion:', {
            error: errorCapturado,
            mensaje: errorCapturado.message
        });
    }
    
    botonModificar.disabled = false;
}

// Función para cargar la lista de clientes disponibles
const cargarClientes = async () => {
    const urlDestino = '/proyecto_jjjc/reparaciones/clientes';
    const configuracionPeticion = {
        method: 'POST'
    };

    try {
        const respuestaServidor = await fetch(urlDestino, configuracionPeticion);
        const datosRespuesta = await respuestaServidor.json();
        const { codigo, data } = datosRespuesta;

        if (codigo == 1) {
            selectorCliente.innerHTML = '<option value="">Seleccione un cliente</option>';
            
            data.forEach(clienteActual => {
                const nombreCompletoCliente = `${clienteActual.cli_nombres} ${clienteActual.cli_apellidos}`;
                const opcionCliente = document.createElement('option');
                opcionCliente.value = clienteActual.cli_id;
                opcionCliente.textContent = nombreCompletoCliente;
                selectorCliente.appendChild(opcionCliente);
            });
        }

    } catch (errorCapturado) {
        console.error('Error al cargar clientes:', {
            error: errorCapturado,
            mensaje: errorCapturado.message
        });
    }
}

// Función para cargar la lista de empleados disponibles
const cargarEmpleados = async () => {
    const urlDestino = '/proyecto_jjjc/reparaciones/empleados';
    const configuracionPeticion = {
        method: 'POST'
    };

    try {
        const respuestaServidor = await fetch(urlDestino, configuracionPeticion);
        const datosRespuesta = await respuestaServidor.json();
        const { codigo, data } = datosRespuesta;

        if (codigo == 1) {
            selectorEmpleado.innerHTML = '<option value="">Seleccione un empleado</option>';
            
            data.forEach(empleadoActual => {
                const nombreCompletoEmpleado = `${empleadoActual.empleado_nombres} ${empleadoActual.empleado_apellidos} - ${empleadoActual.empleado_especialidad}`;
                const opcionEmpleado = document.createElement('option');
                opcionEmpleado.value = empleadoActual.empleado_id;
                opcionEmpleado.textContent = nombreCompletoEmpleado;
                selectorEmpleado.appendChild(opcionEmpleado);
            });
        }

    } catch (errorCapturado) {
        console.error('Error al cargar empleados:', {
            error: errorCapturado,
            mensaje: errorCapturado.message
        });
    }
}

// Función para cargar la lista de servicios disponibles
const cargarServicios = async () => {
    const urlDestino = '/proyecto_jjjc/reparaciones/servicios';
    const configuracionPeticion = {
        method: 'POST'
    };

    try {
        const respuestaServidor = await fetch(urlDestino, configuracionPeticion);
        const datosRespuesta = await respuestaServidor.json();
        const { codigo, data } = datosRespuesta;

        if (codigo == 1) {
            selectorServicio.innerHTML = '<option value="">Seleccione un servicio</option>';
            
            data.forEach(servicioActual => {
                const nombreServicio = `${servicioActual.serv_nombre} - Q.${servicioActual.serv_precio}`;
                const opcionServicio = document.createElement('option');
                opcionServicio.value = servicioActual.serv_id;
                opcionServicio.textContent = nombreServicio;
                opcionServicio.dataset.precio = servicioActual.serv_precio;
                selectorServicio.appendChild(opcionServicio);
            });
        }

    } catch (errorCapturado) {
        console.error('Error al cargar servicios:', {
            error: errorCapturado,
            mensaje: errorCapturado.message
        });
    }
}

// Función para cargar los estados disponibles para las órdenes
const cargarEstados = async () => {
    const urlDestino = '/proyecto_jjjc/reparaciones/estados';
    const configuracionPeticion = {
        method: 'POST'
    };

    try {
        const respuestaServidor = await fetch(urlDestino, configuracionPeticion);
        const datosRespuesta = await respuestaServidor.json();
        const { codigo, data } = datosRespuesta;

        if (codigo == 1) {
            selectorEstado.innerHTML = '<option value="">Seleccione un estado</option>';
            
            data.forEach(estadoActual => {
                const opcionEstado = document.createElement('option');
                opcionEstado.value = estadoActual.id;
                opcionEstado.textContent = estadoActual.nombre;
                selectorEstado.appendChild(opcionEstado);
            });
        }

    } catch (errorCapturado) {
        console.error('Error al cargar estados:', {
            error: errorCapturado,
            mensaje: errorCapturado.message
        });
    }
}

// Función para actualizar automáticamente el precio cuando se selecciona un servicio
const actualizarPrecioServicio = () => {
    const servicioSeleccionado = selectorServicio.selectedOptions[0];
    if (servicioSeleccionado && servicioSeleccionado.dataset.precio) {
        campoMostrarPrecio.value = `Q. ${parseFloat(servicioSeleccionado.dataset.precio).toFixed(2)}`;
    } else {
        campoMostrarPrecio.value = '';
    }
}

// Función para eliminar una orden de reparación
const eliminarOrden = async (evento) => {
    const idOrden = evento.currentTarget.dataset.id;
    const estadoOrden = evento.currentTarget.dataset.estado;

    // Verificar si la orden ya está entregada
    if (estadoOrden === 'ENTREGADO') {
        await Swal.fire({
            position: "center",
            icon: "warning",
            title: "Acción No Permitida",
            text: "No se puede eliminar una orden que ya ha sido entregada",
            showConfirmButton: true,
        });
        return;
    }

    // Confirmación de eliminación
    const confirmacionEliminacion = await Swal.fire({
        position: "center",
        icon: "warning",
        title: "¿Confirmar Eliminación?",
        html: `
            <div class="text-center">
                <p>¿Está completamente seguro que desea eliminar esta orden de reparación?</p>
                <div class="alert alert-warning mt-3">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>La orden será desactivada y ya no aparecerá en la lista</strong>
                </div>
                <small class="text-muted">Nota: La orden se mantendrá en el sistema para fines de auditoría</small>
            </div>
        `,
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true,
        cancelButtonColor: '#6c757d'
    });

    if (confirmacionEliminacion.isConfirmed) {
        const urlDestino = `/proyecto_jjjc/reparaciones/eliminar?id=${idOrden}`;
        const configuracionPeticion = {
            method: 'GET'
        };

        try {
            const respuestaServidor = await fetch(urlDestino, configuracionPeticion);
            
            if (!respuestaServidor.ok) {
                throw new Error(`Error del servidor: ${respuestaServidor.status}`);
            }

            const datosRespuesta = await respuestaServidor.json();
            const { codigo, mensaje } = datosRespuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "¡Orden Eliminada!",
                    text: mensaje,
                    showConfirmButton: true,
                    timer: 2000
                });

                // Actualizar la lista de órdenes
                buscarOrdenes();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error al Eliminar",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }

        } catch (errorCapturado) {
            let mensajeError = "Error inesperado al eliminar la orden";
            
            if (errorCapturado.name === 'TypeError') {
                mensajeError = "Problema de conexión con el servidor";
            } else if (errorCapturado.message.includes('404')) {
                mensajeError = "La orden no fue encontrada en el servidor";
            }

            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error de Conexión",
                text: mensajeError,
                showConfirmButton: true,
            });
            
            console.error('Detalles del error en eliminarOrden:', {
                error: errorCapturado,
                mensaje: errorCapturado.message
            });
        }
    }
}

// Función para mostrar el detalle completo de una orden
const verDetalleOrden = (evento) => {
    const datosOrden = evento.currentTarget.dataset;
    
    Swal.fire({
        title: 'Detalle Completo de la Orden',
        html: `
            <div class="text-start">
                <p><strong>Cliente:</strong> ${datosOrden.cliente}</p>
                <p><strong>Celular:</strong> ${datosOrden.celular}</p>
                <p><strong>Servicio:</strong> ${datosOrden.servicio}</p>
                <p><strong>Empleado Asignado:</strong> ${datosOrden.empleado}</p>
                <p><strong>Estado Actual:</strong> <span class="badge bg-info">${datosOrden.estado}</span></p>
                <p><strong>Precio del Servicio:</strong> Q. ${parseFloat(datosOrden.precio).toFixed(2)}</p>
                <p><strong>Fecha de Ingreso:</strong> ${datosOrden.fecha}</p>
                <hr>
                <p><strong>Motivo de Ingreso:</strong></p>
                <p class="text-muted">${datosOrden.motivo}</p>
                <p><strong>Diagnóstico:</strong></p>
                <p class="text-muted">${datosOrden.diagnostico}</p>
                <p><strong>Observaciones:</strong></p>
                <p class="text-muted">${datosOrden.observaciones}</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Cerrar',
        width: '600px'
    });
}

// Función para limpiar completamente el formulario
const limpiarFormulario = () => {
    formularioReparaciones.reset();
    campoMostrarPrecio.value = '';
    botonGuardar.classList.remove('d-none');
    botonModificar.classList.add('d-none');
}

// Event listeners principales
formularioReparaciones.addEventListener('submit', guardarReparacion);
botonLimpiar.addEventListener('click', limpiarFormulario);
botonModificar.addEventListener('click', modificarReparacion);
botonBuscarOrdenes.addEventListener('click', buscarOrdenes);

// Event listener para actualizar precio cuando cambia el servicio
selectorServicio.addEventListener('change', actualizarPrecioServicio);

// Inicialización del módulo - cargar datos necesarios
cargarClientes();
cargarEmpleados();
cargarServicios();
cargarEstados();
buscarOrdenes();