import DataTable from "datatables.net-bs5";
import { removeBootstrapValidation, soloNumeros, Toast } from "../funciones";
import { Modal, Dropdown } from "bootstrap";
import Swal from "sweetalert2";

const formUsuario = document.getElementById('formUsuario')

const guardarUsuario = async e => {
  e.preventDefault();
  
  try {

    const body = new FormData(formUsuario)
    const url = "/proyecto_jjjc/registro/guardar"
    const config = {
      method: 'POST',
      body
    }

    const respuesta = await fetch(url, config);
    const data = await respuesta.json();
    const { codigo, mensaje, detalle } = data;
        console.log(data)
    let icon = 'info'
    if (codigo == 1) {
      icon = 'success'
      formUsuario.reset()
      
      Swal.fire({
        title: '¡Registro exitoso!',
        text: 'Usuario registrado correctamente. ¿Desea ir al login?',
        icon: 'success',
        showCancelButton: true,
        confirmButtonText: 'Ir al login',
        cancelButtonText: 'Registrar otro',
        confirmButtonColor: '#84fab0'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = '/proyecto_jjjc/login';
        }
      });

    } else if (codigo == 2) {
      icon = 'warning'
      console.log(detalle);

    } else if (codigo == 0) {
      icon = 'error'
      console.log(detalle);

    }

    if(codigo !== 1) {
      Toast.fire({
        icon,
        title: mensaje
      });
    }

  } catch (error) {
    console.log(error);
  }

}

formUsuario.addEventListener('submit', guardarUsuario)