import { Injectable } from '@angular/core';
import { LoginI } from '../modelos/login.interface';
import { ResponseI } from '../modelos/response.interface';
import { ListarCentrosI } from '../modelos/listarCentros.interface';
import { HttpClient, HttpRequest, HttpHeaders, HttpEvent, HttpEventType } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { LocalSService } from './local-s.service';
import { Router } from '@angular/router';
import Swal from 'sweetalert2';
import { map, catchError } from 'rxjs/operators';
import { NotificarService } from './notificar.service';
import { RegistroI } from '../modelos/registro.interface';
import { ListarPerfilesI } from '../modelos/listarPerfiles.interface';
import { ListarAreasI } from '../modelos/listarAreas.interface';
import { ListarZonasI } from '../modelos/listarZonas.interface';
import { ListarPacientesI } from '../modelos/listarPacientes.interface';
import { ListarcomunidadesI } from '../modelos/listarComunidades.interface';
import { ListarProvinciasI } from '../modelos/listarProvincias.interface';
import { ListarPlantasI } from '../modelos/listarPlantas.interface';
import { ListarCamasI } from '../modelos/listarCamas.interface';
import { infoPacienteI } from '../modelos/infoPaciente.interface';
import { ingresoPacienteI } from '../modelos/ingresoPaciente.interface';
@Injectable({
  providedIn: 'root',
})
export class ApiService {
  url: string = 'https://sesamo.sytes.net/sesamo/';
  //asi para que el proxy las reenvie
  //url: string = '/sesamo/';
  private userRol: string | null = null;
  constructor(
    private http: HttpClient,
    private ls: LocalSService,
    private router: Router,
    private notificar: NotificarService
  ) { }

  //funcion para el login , recibe el fomulario de tipo de la inetrfaz de login I
  //la respuestas sera un objeto de tipo response i
  login(form: LoginI): Observable<ResponseI> {
    let direccion = this.url + 'usuarios/login';
    return this.http.post<ResponseI>(direccion, form);
  }
  //defasada

  checkLogin(): Observable<boolean> {
    const token = this.ls.obtener('token');
    const rol = this.ls.obtener('rol');

    if (!token || !rol) {
      return of(false);
    }

    return this.verificarLogin(token).pipe(
      map((respuesta: ResponseI) => {
        const result = typeof respuesta.result === 'string' ? JSON.parse(respuesta.result) : respuesta.result;
        if (respuesta.status == 'ok' && result.rol == rol) {
          this.userRol = result.rol;
          return true;
        } else {
          this.alertaError();
          return false;
        }
      }),
      catchError(() => {
        this.alertaError();
        return of(false);
      })
    );
  }

  getRol(): string | null {
    return this.userRol;
  }

  verificarLogin(token: string): Observable<ResponseI> {
    let direccion = this.url + 'usuarios/verificar';
    return this.http.post<ResponseI>(direccion, { token: token });
  }

  //Metodo para registrarse
  registrar(form: RegistroI): Observable<ResponseI> {
    let direccion = this.url + 'usuarios/registro';
    return this.http.post<ResponseI>(direccion, form);
  }

  //metood para listar centros, en este caso nos devolvera un array de estos
  listarCentros(): Observable<ListarCentrosI[]> {
    let direccion = this.url + 'centros';
    return this.http.get<ListarCentrosI[]>(direccion);
  }
  listarPlantas(centro: string): Observable<ListarPlantasI> {
    let direccion = this.url + 'camas/plantas/' + centro;
    return this.http.get<ListarPlantasI>(direccion);
  }
  //listar camas por planta y cnetro
  listarCamasCentro(
    centro: string,
    planta: string
  ): Observable<ListarCamasI[]> {
    let direccion = this.url + 'camas/centro/' + centro + '/' + planta;
    return this.http.get<ListarCamasI[]>(direccion);
  }

  //listar perfiles
  listarPerfiles(): Observable<ListarPerfilesI[]> {
    let direccion = this.url + 'perfil';
    return this.http.get<ListarPerfilesI[]>(direccion);
  }
  listarAreas(): Observable<ListarAreasI[]> {
    let direccion = this.url + 'areaSalud';
    return this.http.get<ListarAreasI[]>(direccion);
  }
  listarZonas(area: string): Observable<ListarZonasI[]> {
    let direccion = this.url + 'zonas/' + area;
    return this.http.get<ListarZonasI[]>(direccion);
  }
  //listar comunidades autonomas
  listarComunidades(): Observable<ListarcomunidadesI[]> {
    let direccion = this.url + 'comunidades';
    return this.http.get<ListarcomunidadesI[]>(direccion);
  }
  //listar provincias
  listarProvincias(comunidad: string): Observable<ListarProvinciasI[]> {
    let direccion = this.url + 'provincias/' + comunidad;
    return this.http.get<ListarProvinciasI[]>(direccion);
  }
  //listar Ambitos
  listarAmbitos(): Observable<any[]> {
    let direccion = this.url + 'ambitos';
    return this.http.get<any[]>(direccion);
  }

  //BUSCAR PACIENTES
  /*
buscarPacientes(tipo:string,valor: string):Observable<ListarPacientesI[]>{
  let direccion = this.url + `pacientes/${tipo}/${valor}`
  return this.http.get<ListarPacientesI[]>(direccion);
}*/
  //BUSCAR PACIENTES CON POSIBILIAD DE ERROR O DE RESPUESTA
  buscarPacientes(
    tipo: string,
    valor: string
  ): Observable<ListarPacientesI[] | ResponseI> {
    let direccion = this.url + `pacientes/${tipo}/${valor}`;
    return this.http.get<ListarPacientesI[] | ResponseI>(direccion);
  }
  //modificar Paciente
  modificarPacientes(form: ListarPacientesI): Observable<ResponseI> {
    let direccion = this.url + `pacientes`;
    let formCompleto = this.addToken(form);

    return this.http.put<ResponseI>(direccion, formCompleto);
  }
  altaPacientes(form: ListarPacientesI): Observable<ResponseI> {
    let direccion = this.url + `pacientes`;
    let formCompleto = this.addToken(form);
    return this.http.post<ResponseI>(direccion, formCompleto);
  }

  //CAMS
  bloquearCama(idCama: string, estado: string): Observable<ResponseI> {
    let direccion = `${this.url}camas`;
    let formCompleto = this.addToken({ id_cama: idCama, bloqueada: estado });

    return this.http.put<ResponseI>(direccion, formCompleto);
  }
  //buscar paciente por id de cama
  pacienteCama(idCama: string): Observable<infoPacienteI> {
    let direccion = `${this.url}pacientes/cama/${idCama}`;
    return this.http.get<infoPacienteI>(direccion);
  }
  //ingresar un paciente
  ingresarPaciente(form: ingresoPacienteI): Observable<ResponseI> {
    let direccion = this.url + `ingresos`;
    let formCompleto = this.addToken(form);
    return this.http.post<ResponseI>(direccion, formCompleto);
  }
  //dar de alta a un paciente, este se hara con httprequest, ay que httcliente no permite body como envio
  //httrequest permite crear solicitudes perosnalizdas
  altaPaciente(form: ListarPacientesI): Observable<ResponseI> {
    let direccion = this.url + `alta`;
    let formCompleto = this.addToken(form);
    return this.http.post<ResponseI>(direccion, formCompleto);
  }
  altaPacienteOLD(form: any): Observable<ResponseI> {
    const direccion = this.url + `ingresos`;
    const token = this.ls.obtener('token');

    const body = {
      nhc: form.nhc,
      token: token,
      fecha: form.fecha,
      hora: form.hora,
      tipo: form.tipo,
      motivo: form.motivo,
      cama: form.cama
    };

    const headers = new HttpHeaders({
      'Content-Type': 'application/json'
    });

    return this.http.request<ResponseI>('DELETE', direccion, {
      body: body,
      headers: headers
    }).pipe(
      map(event => {
        // Directamente retornamos el evento, dado que la respuesta de DELETE
        // es ResponseI
        return event as ResponseI;
      }),
      catchError(error => {
        console.error('Error en la solicitud:', error);
        return of({ status: 'error', result: 'Error en la solicitud' } as ResponseI);
      })
    );
  }
  //alertas por nhc
  listarAlertaNhc(nhc: string): Observable<any> {
    let direccion = this.url + 'alertas/' + nhc;
    return this.http.get<any>(direccion);
  }

  //insertar una alerta
  ingresarAlerta(form: any): Observable<ResponseI> {
    let direccion = this.url + 'alertas';
    let formCompleto = this.addToken(form);
    return this.http.post<ResponseI>(direccion, formCompleto);
  }
  //modificar alerta
  modificarAlerta(form: any): Observable<ResponseI> {
    let direccion = this.url + 'alertas';
    let formCompleto = this.addToken(form);
    return this.http.put<ResponseI>(direccion, formCompleto);
  }
  //eliminar alerta
  eliminarAlerta(form: any): Observable<ResponseI> {
    const direccion = this.url + `alertas`;
    const token = this.ls.obtener('token');
    //construmos el cuerpo
    const body = {
      nhc: form.nhc,
      token: token
    };
    const headers = new HttpHeaders({
      'Content-Type': 'application/json'
    });

    return this.http.request<ResponseI>('DELETE', direccion, {
      body: body,
      headers: headers
    }).pipe(
      map(event => {
        // Directamente retornamos el evento, dado que la respuesta de DELETE
        // es ResponseI
        return event as ResponseI;
      }),
      catchError(error => {
        console.error('Error en la solicitud:', error);
        return of({ status: 'error', result: 'Error en la solicitud' } as ResponseI);
      })
    );
  }
  //notas de enfermeri
  ingresarNotaEnfermeria(form: any): Observable<ResponseI> {
    let direccion = this.url + 'notasEnfermeria';
    let formCompleto = this.addToken(form);
    return this.http.post<ResponseI>(direccion, formCompleto);
  }
  listarHojasPre(nhc: string): Observable<any> {
    let direccion = this.url + 'hojasPrescripcion/' + nhc;
    return this.http.get<any>(direccion);
  }
  listarPatologias(nhc: string): Observable<any> {
    let direccion = this.url + 'patologia/' + nhc;
    return this.http.get<any>(direccion);
  }
  ingresarPatologia(form: any): Observable<ResponseI> {
    let direccion = this.url + 'patologia';
    let formCompleto = this.addToken(form);
    return this.http.post<ResponseI>(direccion, formCompleto);
  }
  //ingresar hoja pres
  ingresarHoja(form: any): Observable<ResponseI> {
    let direccion = this.url + 'hojasPrescripcion';
    let formCompleto = this.addToken(form);
    return this.http.post<ResponseI>(direccion, formCompleto);
  }

  //infoemes
  listarNotasEnfermeria(nhc: string): Observable<any> {
    let direccion = this.url + 'notasEnfermeria/' + nhc;
    return this.http.get<any>(direccion);
  }
  listarIngresos(nhc: string): Observable<any> {
    let direccion = this.url + 'ingresos/nhc/' + nhc;
    return this.http.get<any>(direccion);
  }
  listarALtas(nhc: string): Observable<any> {
    let direccion = this.url + 'alta/' + nhc;
    return this.http.get<any>(direccion);
  }
  //las petciones para recuperar contraseñas
  recuperarPassword(form: any): Observable<ResponseI> {
    let direccion = this.url + 'recuperarPassword';
    form = { ...form, accion: "recuperarContrasena" };

    return this.http.post<ResponseI>(direccion, form);
  }

  verificarCodigo(form: any): Observable<ResponseI> {
    let direccion = this.url + 'recuperarPassword';
    let formCompleto = { ...form, accion: "verificarCodigo" };
    return this.http.post<ResponseI>(direccion, formCompleto);
  }
  cambiarPassword(form: any): Observable<ResponseI> {
    let direccion = this.url + 'recuperarPassword';
    let formCompleto = { ...form, accion: "resetContrasena" };
    return this.http.post<ResponseI>(direccion, formCompleto);
  }

  listarUsuarios(): Observable<any> {
    let direccion = this.url + 'usuarios/'
    return this.http.get<any>(direccion);
  }
  modificarUsuario(form: any): Observable<any> {
    let direccion = this.url + 'usuarios/'
    let formCompleto = this.addToken(form);
    return this.http.put<ResponseI>(direccion, formCompleto);
  }
  eliminarUsuario0(form: any): Observable<any> {
    let direccion = this.url + 'usuarios/'
    let formCompleto = this.addToken(form);
    return this.http.put<ResponseI>(direccion, formCompleto);
  }


  eliminarUsuario(form: any): Observable<ResponseI> {
    const direccion = this.url + `usuarios/`;
    const token = this.ls.obtener('token');

    // Construimos el cuerpo de la solicitud
    const body = {
      usuario: form.usuario,
      token: token
    };

    const headers = new HttpHeaders({
      'Content-Type': 'application/json'
    });

    return this.http.request<ResponseI>('DELETE', direccion, {
      body: JSON.stringify(body),
      headers: headers
    }).pipe(
      map(event => {
        return event as ResponseI;
      }),
      catchError(error => {
        console.error('Error en la solicitud:', error);
        return of({ status: 'error', result: 'Error en la solicitud' } as ResponseI);
      })
    );
  }
  //esatdosticas

  listarVisitasPorDia(): Observable<any> {
    let direccion = this.url + 'visitas/dia';
    return this.http.get<any>(direccion);
  }

  listarVisitasPorMes(): Observable<any> {
    let direccion = this.url + 'visitas/mes';
    return this.http.get<any>(direccion);
  }

  listarVisitasPorAno(): Observable<any> {
    let direccion = this.url + 'visitas/ano';
    return this.http.get<any>(direccion);
  }

  listarTotalVisitas(): Observable<any> {
    let direccion = this.url + 'visitas/total';
    return this.http.get<any>(direccion);
  }

  listarVisitasPorPagina(): Observable<any> {
    let direccion = this.url + 'visitas/paginas';
    return this.http.get<any>(direccion);
  }


  //para añdir token a form en caso de necesitarlo
  addToken(form: any): any {
    //pedimso tken desde el localstorage
    const tokenGuardado = this.ls.obtener('token');
    return {
      //... hace uan copia del objeto form, es un operador de propagcion, crea un objeto con las prpiedades anteriores
      //aañdiendole las nuevas
      ...form,
      token: tokenGuardado,
    };
  }

  private alertaError() {
    //borra el token gaurdado
    this.ls.borrar('token');
    this.ls.borrar('rol');
    //nos redirige a login
    this.router.navigateByUrl('/login');
    //lanza alerta
    this.notificar.notificar(
      'error',
      'Error al verificar el token. Vuelva a iniciar sesión.'
    );
    /* Swal.fire({
      position: 'bottom-end',
      icon: 'error',
      title: 'Error al verificar el token. Vuelva a iniciar sesión.',
      showConfirmButton: false,
      timer: 1500,
    });*/
  }
}
