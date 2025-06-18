import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { Chart } from "chart.js/auto";

// Elementos del DOM
const botonActualizar = document.getElementById('BtnActualizarEstadisticas');

// Contextos de los gráficos
const contextoGrafico1 = document.getElementById('grafico1').getContext('2d');
const contextoGrafico2 = document.getElementById('grafico2').getContext('2d');
const contextoGrafico3 = document.getElementById('grafico3').getContext('2d');
const contextoGrafico4 = document.getElementById('grafico4').getContext('2d');

// Paletas de colores vibrantes para los gráficos
const paletaColoresBarras = [
    '#C0392B', '#D68910', '#8E44AD', '#2E86C1', '#148F77',
    '#B7950B', '#A93226', '#6C3483', '#1A5490', '#0E6655',
    '#7D6608', '#943126', '#5B2C6F', '#154360', '#0B5345',
    '#76448A', '#D35400', '#A04000', '#7E5109', '#6E2C00',
    '#922B21', '#6A1B9A', '#1565C0', '#00695C', '#E65100'
];

const paletaColoresVibrantes = [
    '#B71C1C', '#BF360C', '#E65100', '#F57F17', '#33691E',
    '#1B5E20', '#006064', '#0D47A1', '#1A237E', '#4A148C',
    '#880E4F', '#AD1457', '#C2185B', '#D81B60', '#E91E63',
    '#F44336', '#E53935', '#D32F2F', '#C62828', '#B71C1C',
    '#3F51B5', '#303F9F', '#283593', '#1976D2', '#1565C0'
];

const paletaColoresGradiente = [
    '#8E24AA', '#7B1FA2', '#6A1B9A', '#4A148C', '#3F51B5',
    '#303F9F', '#283593', '#1976D2', '#1565C0', '#0277BD',
    '#00695C', '#00796B', '#388E3C', '#689F38', '#827717',
    '#F57F17', '#FF8F00', '#FF6F00', '#E65100', '#BF360C',
    '#D84315', '#FF3D00', '#DD2C00', '#C62828', '#B71C1C'
];

// Inicializar gráficos vacíos
window.graficoProductosBarras = new Chart(contextoGrafico1, {
    type: 'bar',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            title: {
                display: true,
                text: 'Productos Más Vendidos'
            }
        }
    }
});

window.graficoProductosPie = new Chart(contextoGrafico2, {
    type: 'pie',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right'
            },
            title: {
                display: true,
                text: 'Distribución de Productos Vendidos'
            }
        }
    }
});

window.graficoClientesDoughnut = new Chart(contextoGrafico3, {
    type: 'doughnut',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
            title: {
                display: true,
                text: 'Clientes con Más Compras'
            }
        }
    }
});

window.graficoVentasLinea = new Chart(contextoGrafico4, {
    type: 'line',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            title: {
                display: true,
                text: 'Ventas Mensuales - Vista Detallada'
            }
        }
    }
});

// Función principal para buscar estadísticas
const buscarEstadisticas = async () => {
    // Mostrar indicador de carga
    botonActualizar.disabled = true;
    botonActualizar.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Cargando...';

    const urlGrafica = '/proyecto_jjjc/estadisticas/buscar';
    const configuracionPeticion = {
        method: 'GET'
    };

    try {
        const respuestaServidor = await fetch(urlGrafica, configuracionPeticion);
        
        if (!respuestaServidor.ok) {
            throw new Error(`Error del servidor: ${respuestaServidor.status}`);
        }

        const datosRespuesta = await respuestaServidor.json();
        const { codigo, mensaje, productos, clientes, ventasMes, estadisticasGenerales, anio } = datosRespuesta;

        if (codigo == 1) {
            // Actualizar tarjetas de estadísticas generales
            actualizarTarjetasEstadisticas(estadisticasGenerales);
            
            // Actualizar gráficos
            actualizarGraficoProductosBarras(productos);
            actualizarGraficoProductosPie(productos);
            actualizarGraficoClientes(clientes);
            actualizarGraficoVentasLinea(ventasMes);
            
            // Actualizar información adicional
            document.getElementById('anio-actual').textContent = anio;

            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Estadísticas Actualizadas!",
                text: mensaje,
                showConfirmButton: true,
                timer: 2000
            });

        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error al Cargar Estadísticas",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (errorCapturado) {
        let mensajeError = "Error inesperado al cargar las estadísticas";
        
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
        
        console.error('Detalles del error en buscarEstadisticas:', {
            error: errorCapturado,
            mensaje: errorCapturado.message
        });
    }

    // Restaurar botón
    botonActualizar.disabled = false;
    botonActualizar.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Actualizar Datos';
}

// Función para actualizar las tarjetas de estadísticas generales
const actualizarTarjetasEstadisticas = (estadisticas) => {
    document.getElementById('total-ventas').textContent = estadisticas.total_ventas_anio || 0;
    document.getElementById('total-productos').textContent = estadisticas.total_productos_stock || 0;
    document.getElementById('total-clientes').textContent = estadisticas.total_clientes_activos || 0;
    document.getElementById('total-reparaciones').textContent = estadisticas.total_reparaciones_activas || 0;
    
    // Formatear ingresos con comas
    const ingresosTotales = parseFloat(estadisticas.ingresos_totales_anio || 0);
    document.getElementById('ingresos-totales').textContent = 'Q.' + ingresosTotales.toLocaleString('es-GT', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    document.getElementById('cantidad-inventario').textContent = estadisticas.cantidad_total_inventario || 0;
}

// Función para obtener colores según cantidad vendida
const obtenerColorPorCantidad = (cantidad) => {
    if (cantidad > 50) {
        return paletaColoresVibrantes[Math.floor(Math.random() * 5)];
    }
    if (cantidad > 15 && cantidad < 50) {
        return paletaColoresBarras[Math.floor(Math.random() * 10)];
    }
    if (cantidad <= 15) {
        return paletaColoresGradiente[Math.floor(Math.random() * 10)];
    }
    return paletaColoresBarras[Math.floor(Math.random() * paletaColoresBarras.length)];
}

// Función para generar colores aleatorios de una paleta
const generarColoresAleatorios = (cantidad, paleta = paletaColoresBarras) => {
    const coloresSeleccionados = [];
    for (let i = 0; i < cantidad; i++) {
        const indiceAleatorio = Math.floor(Math.random() * paleta.length);
        coloresSeleccionados.push(paleta[indiceAleatorio]);
    }
    return coloresSeleccionados;
}

// Actualizar gráfico de barras de productos
const actualizarGraficoProductosBarras = (productos) => {
    if (!productos || productos.length === 0) return;

    const etiquetasProductos = productos.map(p => p.producto);
    const cantidadesProductos = productos.map(p => p.cantidad);
    
    // Generar colores según las cantidades
    const coloresBarras = cantidadesProductos.map(cantidad => obtenerColorPorCantidad(cantidad));

    window.graficoProductosBarras.data = {
        labels: etiquetasProductos,
        datasets: [{
            label: 'Cantidad Vendida',
            data: cantidadesProductos,
            backgroundColor: coloresBarras,
            borderColor: coloresBarras.map(color => color),
            borderWidth: 3,
            borderRadius: 6,
            borderSkipped: false,
        }]
    };
    window.graficoProductosBarras.update();
}

// Actualizar gráfico de pie de productos
const actualizarGraficoProductosPie = (productos) => {
    if (!productos || productos.length === 0) return;

    const etiquetasProductos = productos.map(p => p.producto);
    const cantidadesProductos = productos.map(p => p.cantidad);
    
    // Generar colores vibrantes para cada segmento
    const coloresPie = generarColoresAleatorios(cantidadesProductos.length, paletaColoresVibrantes);

    window.graficoProductosPie.data = {
        labels: etiquetasProductos,
        datasets: [{
            data: cantidadesProductos,
            backgroundColor: coloresPie,
            borderColor: '#ffffff',
            borderWidth: 4,
            hoverOffset: 8
        }]
    };
    window.graficoProductosPie.update();
}

// Actualizar gráfico de clientes (doughnut)
const actualizarGraficoClientes = (clientes) => {
    if (!clientes || clientes.length === 0) return;

    // Tomar solo los primeros 10 clientes para mejor visualización
    const clientesTop = clientes.slice(0, 10);
    const etiquetasClientes = clientesTop.map(c => c.cliente);
    const cantidadesClientes = clientesTop.map(c => c.cantidad_total_prod);
    
    // Usar paleta de colores gradiente para el gráfico de dona
    const coloresClientes = generarColoresAleatorios(cantidadesClientes.length, paletaColoresGradiente);

    window.graficoClientesDoughnut.data = {
        labels: etiquetasClientes,
        datasets: [{
            data: cantidadesClientes,
            backgroundColor: coloresClientes,
            borderColor: '#ffffff',
            borderWidth: 4,
            hoverOffset: 10
        }]
    };
    window.graficoClientesDoughnut.update();
}

// Actualizar gráfico de línea de ventas mensuales
const actualizarGraficoVentasLinea = (ventasMes) => {
    if (!ventasMes || ventasMes.length === 0) return;

    const etiquetasMeses = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
    
    const datosVentas = ventasMes[0];
    const ventasPorMes = [
        parseFloat(datosVentas.enero || 0), parseFloat(datosVentas.febrero || 0), 
        parseFloat(datosVentas.marzo || 0), parseFloat(datosVentas.abril || 0), 
        parseFloat(datosVentas.mayo || 0), parseFloat(datosVentas.junio || 0),
        parseFloat(datosVentas.julio || 0), parseFloat(datosVentas.agosto || 0), 
        parseFloat(datosVentas.septiembre || 0), parseFloat(datosVentas.octubre || 0), 
        parseFloat(datosVentas.noviembre || 0), parseFloat(datosVentas.diciembre || 0)
    ];

    // Colores para los puntos de cada mes
    const coloresLineaPuntos = [
        '#B71C1C', '#D32F2F', '#1976D2', '#388E3C', '#F57F17',
        '#FF6F00', '#E65100', '#5D4037', '#455A64', '#BF360C',
        '#4A148C', '#00695C'
    ];

    window.graficoVentasLinea.data = {
        labels: etiquetasMeses,
        datasets: [{
            label: 'Ventas Mensuales (Q.)',
            data: ventasPorMes,
            fill: true,
            backgroundColor: 'rgba(183, 28, 28, 0.2)',
            borderColor: '#B71C1C',
            pointBackgroundColor: coloresLineaPuntos,
            pointBorderColor: '#ffffff',
            pointBorderWidth: 4,
            pointRadius: 10,
            pointHoverRadius: 14,
            tension: 0.4,
            borderWidth: 4
        }]
    };
    window.graficoVentasLinea.update();
}

// Event listeners
botonActualizar.addEventListener('click', buscarEstadisticas);

// Cargar estadísticas al iniciar la página
buscarEstadisticas();