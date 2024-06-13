import { Injectable } from '@angular/core';
import Swal from 'sweetalert2';

@Injectable({
  providedIn: 'root'
})
export class NotificarService {

  constructor() { }
//marcamos las posibles opciones, y sera dianmico segun lo que le mandemos
  notificar(tipo: 'success' | 'error' | 'warning' | 'info' | 'question', mensaje: string): void {
    Swal.fire({
      position: 'bottom-end',
      icon: tipo,
      title: mensaje,
      showConfirmButton: false,
      timer: 1500,
    });
  }
}


