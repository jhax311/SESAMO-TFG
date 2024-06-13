
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class LocalSService {

  constructor() { }
//servicio para guardar datos en el local storage
//guardamso como json sea o no
  
  // guardar
  guardar(clave: string, valor: any) {
    localStorage.setItem(clave, JSON.stringify(valor));
  }

  // obtener un valor 
  obtener(clave: string) {
    const valor = localStorage.getItem(clave);
    //vamos a controlar que lo que se guarde en el ls sea un json 
    //u string preparado para ello,con el find e que si en el cliente se
    //inserta de manera manuel un valor sin "", la app siga funcioanndo
    if (typeof valor === 'string') {
      try {
        return JSON.parse(valor);
      } catch (error) {
        //console.error('Error al analizar el JSON almacenado:', error);
        return null;
      }
    } else {
      return null;
    }
  }
  
  // borrar un valor
  borrar(clave: string) {
    localStorage.removeItem(clave);
  }

}
