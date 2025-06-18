import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';

const FormVentas = document.getElementById('FormVentas');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarVentas = document.getElementById('BtnBuscarVentas');
const TablaVentas = document.getElementById('TablaVentas');
const selectCliente = document.getElementById('venta_cliente_id');
const selectProducto = document.getElementById('producto_selector');
const contenedorProductos = document.getElementById('productos_venta');
const totalVentaElemento = document.getElementById('total_venta');

// Variables para manejo de productos
let productosEnVenta = [];
let totalVenta = 0;

const ProcesarVenta = async (evento) => {
    evento.preventDefault();
    BtnGuardar.disabled = true;

    try {
        // Validar que haya productos
        if (productosEnVenta.length === 0) {
            throw new Error('Debe agregar al menos un producto a la venta');
        }

        // Validar cliente
        if (!selectCliente.value) {
            throw new Error('Debe seleccionar un cliente');
        }

        const datosVenta = new FormData(FormVentas);
        datosVenta.append('productos', JSON.stringify(productosEnVenta));

        const urlDestino = '/proyecto_jjjc/ventas/guardar';
        const configuracion = {
            method: 'POST',
            body: datosVenta
        };

        const respuesta = await fetch(urlDestino, configuracion);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Venta Exitosa!",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarFormulario();
            BuscarVentas();

        } else {
            throw new Error(mensaje);
        }

    } catch (excepcion) {
        let mensajeError = 'Error al procesar la venta';
        let codigoError = 'ERROR_VENTA';

        if (excepcion.message.includes('productos')) {
            mensajeError = 'Carrito vacío';
            codigoError = 'ERROR_PRODUCTOS';
            
            await Swal.fire({
                icon: 'warning',
                title: mensajeError,
                text: 'Debe agregar al menos un producto para realizar la venta',
                confirmButtonText: 'Agregar productos'
            });
        } else if (excepcion.message.includes('cliente')) {
            mensajeError = 'Cliente no seleccionado';
            codigoError = 'ERROR_CLIENTE';
            
            await Swal.fire({
                icon: 'warning',
                title: mensajeError,
                text: 'Debe seleccionar un cliente para la venta',
                confirmButtonText: 'Seleccionar cliente'
            });
        } else {
            await Swal.fire({
                icon: 'error',
                title: mensajeError,
                text: excepcion.message,
                confirmButtonText: 'Reintentar'
            });
        }

        if (window.DEBUG_MODE) {
            console.error('Error en ProcesarVenta:', excepcion);
        }
    }
    
    BtnGuardar.disabled = false;
}

const BuscarVentas = async () => {
    const urlDestino = '/proyecto_jjjc/ventas/buscar';
    const configuracion = {
        method: 'POST'
    };

    try {
        const respuesta = await fetch(urlDestino, configuracion);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            // Limpiar tabla
            TablaVentas.innerHTML = '';
            
            // Agregar cada venta a la tabla
            data.forEach((venta, indice) => {
                const fila = document.createElement('tr');
                
                fila.innerHTML = `
                    <td>${indice + 1}</td>
                    <td>${venta.cli_nombres} ${venta.cli_apellidos}</td>
                    <td><span class="badge bg-success">Q. ${parseFloat(venta.venta_total).toFixed(2)}</span></td>
                    <td>${venta.venta_fecha}</td>
                    <td><span class="badge bg-info">${venta.venta_estado}</span></td>
                    <td><span class="badge bg-primary">${venta.venta_tipo}</span></td>
                    <td>
                        <div class='d-flex justify-content-center gap-1'>
                            <button class='btn btn-info btn-sm ver-detalle' 
                                data-id="${venta.venta_id}">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                TablaVentas.appendChild(fila);
            });

            // Agregar event listeners a los botones
            document.querySelectorAll('.ver-detalle').forEach(boton => {
                boton.addEventListener('click', verDetalleVenta);
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

    } catch (excepcion) {
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Error al buscar ventas: " + excepcion.message,
            showConfirmButton: true,
        });

        if (window.DEBUG_MODE) {
            console.error('Error en BuscarVentas:', excepcion);
        }
    }
}

const CargarClientes = async () => {
    const urlDestino = '/proyecto_jjjc/ventas/clientes';
    const configuracion = {
        method: 'POST'
    };

    try {
        const respuesta = await fetch(urlDestino, configuracion);
        const datos = await respuesta.json();
        const { codigo, data } = datos;

        if (codigo == 1) {
            selectCliente.innerHTML = '<option value="">Seleccione un cliente</option>';
            
            data.forEach(cliente => {
                const nombreCompleto = `${cliente.cli_nombres} ${cliente.cli_apellidos}`;
                const opcion = document.createElement('option');
                opcion.value = cliente.cli_id;
                opcion.textContent = nombreCompleto;
                selectCliente.appendChild(opcion);
            });
        }

    } catch (excepcion) {
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "No se pudieron cargar los clientes",
            showConfirmButton: true,
        });

        if (window.DEBUG_MODE) {
            console.error('Error en CargarClientes:', excepcion);
        }
    }
}

const CargarProductos = async () => {
    const urlDestino = '/proyecto_jjjc/ventas/productos';
    const configuracion = {
        method: 'POST'
    };

    try {
        const respuesta = await fetch(urlDestino, configuracion);
        const datos = await respuesta.json();
        const { codigo, data } = datos;

        if (codigo == 1) {
            selectProducto.innerHTML = '<option value="">Seleccione un producto</option>';
            
            data.forEach(producto => {
                const nombreProducto = `${producto.marca_nombre} ${producto.invent_modelo} - Q.${producto.invent_precio_venta} (Stock: ${producto.invent_cantidad_disponible})`;
                const opcion = document.createElement('option');
                opcion.value = producto.invent_id;
                opcion.dataset.modelo = producto.invent_modelo;
                opcion.dataset.marca = producto.marca_nombre;
                opcion.dataset.precio = producto.invent_precio_venta;
                opcion.dataset.stock = producto.invent_cantidad_disponible;
                opcion.textContent = nombreProducto;
                selectProducto.appendChild(opcion);
            });
        }

    } catch (excepcion) {
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "No se pudieron cargar los productos",
            showConfirmButton: true,
        });

        if (window.DEBUG_MODE) {
            console.error('Error en CargarProductos:', excepcion);
        }
    }
}

const agregarProducto = () => {
    const productoSeleccionado = selectProducto.options[selectProducto.selectedIndex];
    
    if (!productoSeleccionado.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Producto no seleccionado',
            text: 'Debe seleccionar un producto',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Verificar si ya está agregado
    const yaExiste = productosEnVenta.find(p => p.inventario_id === productoSeleccionado.value);
    if (yaExiste) {
        Swal.fire({
            icon: 'warning',
            title: 'Producto duplicado',
            text: 'Este producto ya está en la venta',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    const nuevoProducto = {
        inventario_id: productoSeleccionado.value,
        modelo: productoSeleccionado.dataset.modelo,
        marca: productoSeleccionado.dataset.marca,
        precio: parseFloat(productoSeleccionado.dataset.precio),
        stock_disponible: parseInt(productoSeleccionado.dataset.stock),
        cantidad: 1,
        subtotal: parseFloat(productoSeleccionado.dataset.precio)
    };

    productosEnVenta.push(nuevoProducto);
    actualizarVistaProductos();
    calcularTotal();
    
    // Limpiar selector
    selectProducto.value = '';
}

const actualizarVistaProductos = () => {
    if (productosEnVenta.length === 0) {
        contenedorProductos.innerHTML = '<p class="text-muted">No hay productos agregados</p>';
        return;
    }

    let htmlProductos = '';
    productosEnVenta.forEach((producto, indice) => {
        htmlProductos += `
            <div class="producto-item">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <strong>${producto.marca} ${producto.modelo}</strong>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-sm">
                            <button class="btn btn-outline-secondary" type="button" onclick="cambiarCantidad(${indice}, -1)">-</button>
                            <input type="number" class="form-control text-center" value="${producto.cantidad}" min="1" max="${producto.stock_disponible}" 
                                   onchange="actualizarCantidad(${indice}, this.value)">
                            <button class="btn btn-outline-secondary" type="button" onclick="cambiarCantidad(${indice}, 1)">+</button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        Q. ${producto.precio.toFixed(2)}
                    </div>
                    <div class="col-md-2">
                        <strong>Q. ${producto.subtotal.toFixed(2)}</strong>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-danger btn-sm" onclick="eliminarProducto(${indice})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });

    contenedorProductos.innerHTML = htmlProductos;
}

// Funciones globales para manejo de productos
window.cambiarCantidad = (indice, cambio) => {
    const producto = productosEnVenta[indice];
    const nuevaCantidad = producto.cantidad + cambio;
    
    if (nuevaCantidad >= 1 && nuevaCantidad <= producto.stock_disponible) {
        producto.cantidad = nuevaCantidad;
        producto.subtotal = producto.precio * nuevaCantidad;
        actualizarVistaProductos();
        calcularTotal();
    }
}

window.actualizarCantidad = (indice, nuevaCantidad) => {
    const producto = productosEnVenta[indice];
    const cantidad = parseInt(nuevaCantidad);
    
    if (cantidad >= 1 && cantidad <= producto.stock_disponible) {
        producto.cantidad = cantidad;
        producto.subtotal = producto.precio * cantidad;
        actualizarVistaProductos();
        calcularTotal();
    } else {
        // Revertir a la cantidad anterior
        actualizarVistaProductos();
        Swal.fire({
            icon: 'warning',
            title: 'Cantidad inválida',
            text: `La cantidad debe estar entre 1 y ${producto.stock_disponible}`,
            confirmButtonText: 'Entendido'
        });
    }
}

window.eliminarProducto = (indice) => {
    productosEnVenta.splice(indice, 1);
    actualizarVistaProductos();
    calcularTotal();
}

const calcularTotal = () => {
    totalVenta = productosEnVenta.reduce((suma, producto) => suma + producto.subtotal, 0);
    totalVentaElemento.textContent = totalVenta.toFixed(2);
}

const verDetalleVenta = async (evento) => {
    const ventaId = evento.currentTarget.dataset.id;
    
    try {
        const urlDestino = `/proyecto_jjjc/ventas/detalle?id=${ventaId}`;
        const configuracion = {
            method: 'GET'
        };

        const respuesta = await fetch(urlDestino, configuracion);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            let htmlDetalle = `
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            let totalDetalle = 0;
            data.forEach(detalle => {
                htmlDetalle += `
                    <tr>
                        <td>${detalle.marca_nombre} ${detalle.invent_modelo}</td>
                        <td>${detalle.detalle_cantidad}</td>
                        <td>Q. ${parseFloat(detalle.detalle_precio_unitario).toFixed(2)}</td>
                        <td>Q. ${parseFloat(detalle.detalle_subtotal).toFixed(2)}</td>
                    </tr>
                `;
                totalDetalle += parseFloat(detalle.detalle_subtotal);
            });

            htmlDetalle += `
                        </tbody>
                        <tfoot>
                            <tr class="table-success">
                                <th colspan="3">TOTAL:</th>
                                <th>Q. ${totalDetalle.toFixed(2)}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;

            document.getElementById('contenido_detalle').innerHTML = htmlDetalle;
            
            // Mostrar modal
            const modal = new bootstrap.Modal(document.getElementById('ModalDetalleVenta'));
            modal.show();

        } else {
            throw new Error(mensaje);
        }

    } catch (excepcion) {
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo cargar el detalle de la venta: ' + excepcion.message,
            confirmButtonText: 'Entendido'
        });

        if (window.DEBUG_MODE) {
            console.error('Error en verDetalleVenta:', excepcion);
        }
    }
}

const limpiarFormulario = () => {
    FormVentas.reset();
    productosEnVenta = [];
    totalVenta = 0;
    actualizarVistaProductos();
    calcularTotal();
    selectCliente.value = '';
    selectProducto.value = '';
}

// Event Listeners
FormVentas.addEventListener('submit', ProcesarVenta);
BtnLimpiar.addEventListener('click', limpiarFormulario);
BtnBuscarVentas.addEventListener('click', BuscarVentas);

// Event listener para agregar producto
selectProducto.addEventListener('change', function() {
    if (this.value) {
        agregarProducto();
    }
});

// Variables globales para debugging
window.DEBUG_MODE = true;

// Cargar datos al inicio
document.addEventListener('DOMContentLoaded', function() {
    CargarClientes();
    CargarProductos();
    BuscarVentas();
});