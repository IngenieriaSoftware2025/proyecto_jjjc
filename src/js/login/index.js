import Swal from 'sweetalert2';
import { validarFormulario } from '../funciones';

const FormLogin = document.getElementById('FormLogin');
const BtnIniciar = document.getElementById('BtnIniciar');

const login = async (e) => {
    e.preventDefault();
    
    BtnIniciar.disabled = true;

    if (!validarFormulario(FormLogin, [''])) {
        Swal.fire({
            title: "Campos vacíos",
            text: "Debe llenar todos los campos",
            icon: "info"
        });
        BtnIniciar.disabled = false;
        return;
    }

    try {
        const body = new FormData(FormLogin);
        const url = '/proyecto_jjjc/login/autenticar';
        
        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        console.log(data);
        const { codigo, mensaje, detalle } = data;

        if (codigo == 1) {
            Swal.fire({
                title: "¡Éxito!",
                text: mensaje,
                icon: "success",
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Redirigir al dashboard o página principal
                window.location.href = '/proyecto_jjjc/';
            });
        } else {
            Swal.fire({
                title: "Error de Autenticación",
                text: mensaje,
                icon: "error"
            });
        }

    } catch (error) {
        console.log(error);
        Swal.fire({
            title: "Error",
            text: "Error de conexión con el servidor",
            icon: "error"
        });
    } finally {
        BtnIniciar.disabled = false;
    }
}

FormLogin.addEventListener('submit', login);