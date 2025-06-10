import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';

const FormInventario = document.getElementById('FormInventario');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarInventario = document.getElementById('BtnBuscarInventario');
const TablaInventario = document.getElementById('TablaInventario');
const selectMarca = document.getElementById('invent_marca_id');

// Campos para calcular ganancia
const precioCompra = document.getElementById('invent_precio_compra');
const precioVenta = document.getElementById('invent_precio_venta');
const gananciaCalculada = document.getElementById('ganancia_calculada');

const GuardarProducto = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    const body = new FormData(FormInventario);
    const url = '/proyecto_jjjc/inventario/guardar';
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
            BuscarInventario();

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
            text: "Ocurrió un problema al guardar el producto",
            showConfirmButton: true,
        });
    }
    BtnGuardar.disabled = false;
}

const BuscarInventario = async () => {
    const url = '/proyecto_jjjc/inventario/buscar';
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
            TablaInventario.innerHTML = '';
            
            // Agregar cada producto a la tabla
            data.forEach((producto, index) => {
                const fila = document.createElement('tr');
                
                // Calcular ganancia
                const ganancia = (producto.invent_precio_venta - producto.invent_precio_compra).toFixed(2);
                
                // Calcular valor total del stock
                const valorTotal = (producto.invent_precio_venta * producto.invent_cantidad_disponible).toFixed(2);
                
                // Determinar color según stock
                let claseStock = '';
                if (producto.invent_cantidad_disponible >= 10) {
                    claseStock = 'stock-alto';
                } else if (producto.invent_cantidad_disponible >= 5) {
                    claseStock = 'stock-medio';
                } else {
                    claseStock = 'stock-bajo';
                }
                
                // Aplicar clase a toda la fila
                fila.className = claseStock;
                
                fila.innerHTML = `
                    <td>${index + 1}</td>
                    <td><span class="badge bg-primary">${producto.marca_nombre}</span></td>
                    <td>${producto.invent_modelo}</td>
                    <td>Q. ${producto.invent_precio_compra}</td>
                    <td>Q. ${producto.invent_precio_venta}</td>
                    <td>Q. ${ganancia}</td>
                    <td><span class="badge bg-info">${producto.invent_cantidad_disponible}</span></td>
                    <td>Q. ${valorTotal}</td>
                    <td>${producto.invent_fecha_ingreso}</td>
                    <td>
                        <div class='d-flex justify-content-center gap-1'>
                            <button class='btn btn-warning btn-sm modificar' 
                                data-id="${producto.invent_id}" 
                                data-marca="${producto.invent_marca_id}"  
                                data-modelo="${producto.invent_modelo}"  
                                data-precio-compra="${producto.invent_precio_compra}"  
                                data-precio-venta="${producto.invent_precio_venta}"  
                                data-cantidad="${producto.invent_cantidad_disponible}"  
                                data-descripcion="${producto.invent_descripcion}">
                                <i class='bi bi-pencil-square'></i>
                            </button>
                            <button class='btn btn-danger btn-sm eliminar' 
                                data-id="${producto.invent_id}">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                TablaInventario.appendChild(fila);
            });

            // Agregar event listeners a los botones
            document.querySelectorAll('.modificar').forEach(btn => {
                btn.addEventListener('click', llenarFormulario);
            });
            
            document.querySelectorAll('.eliminar').forEach(btn => {
                btn.addEventListener('click', EliminarProducto);
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
            text: "Ocurrió un problema al buscar el inventario",
            showConfirmButton: true,
        });
    }
}

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset

    document.getElementById('invent_id').value = datos.id
    document.getElementById('invent_marca_id').value = datos.marca
    document.getElementById('invent_modelo').value = datos.modelo
    document.getElementById('invent_precio_compra').value = datos.precioCompra
    document.getElementById('invent_precio_venta').value = datos.precioVenta
    document.getElementById('invent_cantidad_disponible').value = datos.cantidad
    document.getElementById('invent_descripcion').value = datos.descripcion

    // Calcular ganancia automáticamente
    calcularGanancia();

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0
    });
}

const limpiarTodo = () => {
    FormInventario.reset();
    gananciaCalculada.value = '';
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarProducto = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    const body = new FormData(FormInventario);
    const url = '/proyecto_jjjc/inventario/modificar';
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
            BuscarInventario();

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
            text: "Ocurrió un problema al modificar el producto",
            showConfirmButton: true,
        });
    }
    BtnModificar.disabled = false;
}

const EliminarProducto = async (e) => {
    const idProducto = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "warning",
        title: "¿Desea ejecutar esta acción?",
        text: '¿Está completamente seguro que desea eliminar este producto del inventario?',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#d33',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/proyecto_jjjc/inventario/eliminar?id=${idProducto}`;
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

                BuscarInventario();
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
                text: "Ocurrió un problema al eliminar el producto",
                showConfirmButton: true,
            });
        }
    }
}

const CargarMarcas = async () => {
    const url = '/proyecto_jjjc/inventario/marcas';
    const config = {
        method: 'POST'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            selectMarca.innerHTML = '<option value="">Seleccione una marca</option>';
            
            data.forEach(marca => {
                const option = document.createElement('option');
                option.value = marca.marca_id;
                option.textContent = marca.marca_nombre;
                selectMarca.appendChild(option);
            });
        }

    } catch (error) {
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "No se pudieron cargar las marcas",
            showConfirmButton: true,
        });
    }
}

// Función para calcular ganancia automáticamente
const calcularGanancia = () => {
    const compra = parseFloat(precioCompra.value) || 0;
    const venta = parseFloat(precioVenta.value) || 0;
    
    if (compra > 0 && venta > 0) {
        const ganancia = venta - compra;
        gananciaCalculada.value = 'Q. ' + ganancia.toFixed(2);
        
        // Cambiar color según la ganancia
        if (ganancia > 0) {
            gananciaCalculada.style.color = 'green';
        } else {
            gananciaCalculada.style.color = 'red';
        }
    } else {
        gananciaCalculada.value = '';
    }
}

// Event Listeners
FormInventario.addEventListener('submit', GuardarProducto);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarProducto);
BtnBuscarInventario.addEventListener('click', BuscarInventario);

// Calcular ganancia cuando cambien los precios
precioCompra.addEventListener('input', calcularGanancia);
precioVenta.addEventListener('input', calcularGanancia);

// Cargar marcas e inventario al inicio
CargarMarcas();
BuscarInventario();