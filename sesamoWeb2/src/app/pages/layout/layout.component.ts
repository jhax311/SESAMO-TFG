import {
  Component,
  EventEmitter,
  OnInit,
  Output,
  inject,
  ViewChild,
  ElementRef,
  OnChanges,
} from '@angular/core';
import { Router, RouterOutlet } from '@angular/router';

import { MatMenuModule } from '@angular/material/menu';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatTooltipModule } from '@angular/material/tooltip';
import {
  MatFormField,
  MatFormFieldControl,
  MatLabel,
} from '@angular/material/form-field';
import { MatOption, MatSelect } from '@angular/material/select';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import {
  FormBuilder,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import {
  sinEspacios,
  validarCorreo,
  validarPassword,
} from '../../validators/validaciones.validator';
import { CommonModule, DatePipe } from '@angular/common';
import { ListarAreasI } from '../../modelos/listarAreas.interface';
import { ListarZonasI } from '../../modelos/listarZonas.interface';
import { ApiService } from '../../servicios/api.service';
import { NotificarService } from '../../servicios/notificar.service';
import { HttpErrorResponse } from '@angular/common/http';
import { ListarPacientesI } from '../../modelos/listarPacientes.interface';
import { ResponseI } from '../../modelos/response.interface';
import { BuscadorComponent } from './buscador/buscador.component';
import { DashboardComponent } from '../dashboard/dashboard.component';
import { FormPacientesComponent } from './info-pacientes/form-pacientes.component';
import { AltaPacienteComponent } from './alta-paciente/alta-paciente.component';
import { ListarCamasI } from '../../modelos/listarCamas.interface';
import { infoPacienteI } from '../../modelos/infoPaciente.interface';
import { IngresarPacienteComponent } from './ingresar-paciente/ingresar-paciente.component';
import { DarAltaPacienteComponent } from './dar-alta-paciente/dar-alta-paciente.component';
import { AlertaPacienteComponent } from './alerta-paciente/alerta-paciente.component';
import { isArray } from 'lodash';
import { MatTabsModule } from '@angular/material/tabs';
import { NotasEnfermeriaComponent } from './notas-enfermeria/notas-enfermeria.component';
import { IngresarPatologiaComponent } from './ingresar-patologia/ingresar-patologia.component';
import { IngresarHojaPreComponent } from './ingresar-hoja-pre/ingresar-hoja-pre.component';
import { LocalSService } from '../../servicios/local-s.service';

declare var $: any;
@Component({
  selector: 'app-layout',
  standalone: true,
  imports: [
    RouterOutlet,
    CommonModule,
    BuscadorComponent,
    DashboardComponent,
    FormPacientesComponent,
    AltaPacienteComponent,
    MatTooltipModule,
    IngresarPacienteComponent,
    DarAltaPacienteComponent,
    AlertaPacienteComponent,
    MatTabsModule,
    MatIconModule,
    NotasEnfermeriaComponent,
    IngresarPatologiaComponent,
    IngresarHojaPreComponent,
  ],
  templateUrl: './layout.component.html',
  styleUrl: './layout.component.css',
  providers: [DatePipe],
})
export class LayoutComponent {
  private readonly _formBuilder = inject(FormBuilder);
  @ViewChild('buscarPaciente') buscarPaciente!: ElementRef;
  @ViewChild('btnClose') btnClose!: ElementRef;
  @ViewChild('infoPaciente') infoPaciente!: ElementRef;
  @ViewChild(DashboardComponent) dashboardComponent!: DashboardComponent;
  @ViewChild('ingresarPaciente') modalIngresar!: ElementRef;
  @ViewChild('darAltaPaciente') darAltaPaciente!: ElementRef;
  @ViewChild('alertaPaciente') alertaPaciente!: ElementRef;
  @ViewChild('modalEnfermeria') modalEnfermeria!: ElementRef;
  @ViewChild('generarInforme') gInforme!: ElementRef;
  @ViewChild('altaPacienteForm') altaPacienteForm: any;
  constructor(
    private api: ApiService,
    private notificar: NotificarService,
    private datePipe: DatePipe,
    private ls: LocalSService,
    private router: Router
  ) { }
  paciente: ListarPacientesI = {} as ListarPacientesI;

  camaS: any = null;
  camaM: any = null;
  infoPa: any = null;
  alerta: any = null;
  hayAlerta: boolean = false;
  select = true;
  fechaFlag = new Date();
  camaVista: any = null;
  //la tblas de hoja y patolo
  mostrarTablaPrescripcion = false;
  mostrarTablaPatologia = false;
  datosHojaPre: any = [];
  datosPatologias: any = [];
  datosIngresos: any = [];
  datosAltas: any = [];
  datosEnfermeria: any = [];
  datosBalanceHidrico: any = [];

  pacienteSeleccionado(paciente: ListarPacientesI) {
    this.paciente = paciente;
    this.btnClose.nativeElement.click();
    $(this.infoPaciente.nativeElement).modal('show');
  }
  camaSeleccionada(cama: any) {
    this.hayAlerta = false;
    this.camaS = cama;
    //si esta ocupada sacamos datos del paciente
    if (cama?.estado == 'Ocupada') {
      this.api.pacienteCama(cama.id_cama).subscribe((data) => {
        //guardamos datos
        this.infoPa = data;
        this.infoPa = this.infoPa[0];
        //guardamos la cama
        this.cargarCama(this.infoPa.Cama)
        //si hay datos en info pa, vamos a buscar las alertas
        this.api.listarAlertaNhc(this.infoPa.nhc).subscribe(
          (response: any) => {
            //si hay un alerta, es decir es un array el contenido la guardamos
            if (isArray(response)) {
              this.alerta = response[0];
              this.hayAlerta = true;
              //la vamos a guardar en info pa tmbb
              this.infoPa = { ...this.infoPa, alerta: this.alerta };
            } else {
              //si no nula
              this.alerta = null;
              this.hayAlerta = false;
            }
          },
          (error) => {
            //si hay errore snula tmb
            this.hayAlerta = false;
            this.alerta = null;
          }
        );

        // Cargar las tablas de prescripción y patología
        this.cargarTablas(this.infoPa.nhc);
      });
    } else {
      //si no null
      this.hayAlerta = false;
      this.infoPa = null;
      this.mostrarTablaPatologia = false;
      this.mostrarTablaPrescripcion = false;
    }
  }

  cargarCama(cama: string) {
    //separamos letars de numero, los parentesis captura esos segmnetops donde el primer segmento eran los numero y el segundo la letra
    const patron= /^(\d+)([A-Z]+)$/;
    //guardmaos la separacion, si cumple ese patron, si no lo cumple null
    const separados = cama.match(patron);
    //si no es null
    if (separados) {
      //guardamos numero y letra
      //numeros seria la primer parte, el 10 para que sea decimal
      let numero=parseInt(separados[1],10);
      //la letra la segund aprte
      let letra=separados[2]
      //comprobbamos que el numeroes amyor que 100, por añadirle o no el 0
      if (numero < 100) {
        numero = parseInt(numero.toString()[0] + '0' + numero.toString().slice(1), 10);
      }
      //la guardamos, se veran como 104A etc, e sla que vamos a mostrar
      this.camaVista=`${numero}${letra}`
    }
  }

  resetForm() {
    this.altaPacienteForm?.resetForm();
  }


  //bloquear u desbloquear
  bloquearCama(event: Event) {
    event.preventDefault();
    if (this.camaS) {
      //comprobamos que esta dispnible
      if (this.camaS.estado === 'Ocupada') {
        this.notificar.notificar('warning', 'Cama no disponible');
      } else if (this.camaS.bloqueada === 'S') {
        this.notificar.notificar('warning', 'La cama ya está bloqueada.');
      } else {
        //si no esta bloaquead ala blqoeuamos
        this.api.bloquearCama(this.camaS.id_cama, 'S').subscribe(
          (response: ResponseI) => {
            if (response.status === 'ok') {
              this.camaS.bloqueada = 'S';
              //  this.actualizarCama(this.camaS);
              this.dashboardComponent.deseleccionarCama();
              this.notificar.notificar(
                'success',
                'Cama bloqueada correctamente.'
              );
            } else {
              this.notificar.notificar('error', 'Error al bloquear la cama.');
            }
          },
          (error) => {
            this.notificar.notificar('error', 'Error al bloquear la cama.');
          }
        );
      }
    } else {
      this.notificar.notificar('warning', 'Seleccione al menos una cama.');
    }
  }
  closeNavbar() {
    const navbarCollapse = document.getElementById('navbarNav');
    if (navbarCollapse && navbarCollapse.classList.contains('show')) {
      navbarCollapse.classList.remove('show');
    }
  }
  desbloquearCama(event: Event) {
    event.preventDefault();
    if (this.camaS) {
      //miramos que no este ocupada
      if (this.camaS.estado === 'Ocupada') {
        this.notificar.notificar('warning', 'Cama no disponible');
      } else if (this.camaS.bloqueada === 'N') {
        //si intenam,os desbloquear error
        this.notificar.notificar('warning', 'La cama ya está desbloqueada.');
      } else {
        this.api.bloquearCama(this.camaS.id_cama, 'N').subscribe(
          (response: ResponseI) => {
            if (response.status === 'ok') {
              this.camaS.bloqueada = 'N';
              //  this.actualizarCama(this.camaS);
              this.dashboardComponent.deseleccionarCama();
              this.notificar.notificar(
                'success',
                'Cama desbloqueada correctamente.'
              );
            } else {
              this.notificar.notificar(
                'error',
                'Error al desbloquear la cama.'
              );
            }
          },
          (error) => {
            this.notificar.notificar('error', 'Error al desbloquear la cama.');
          }
        );
      }
    } else {
      this.notificar.notificar('warning', 'Seleccione al menos una cama.');
    }
  }

  darAlta(event: any) {
    event.preventDefault();
    if (this.camaS) {
      //miramos que no este ocupada
      if (this.camaS.bloqueada === 'S') {
        //si intenam,os desbloquear error
        this.notificar.notificar('warning', 'La cama está bloqueada.');
      } else if (this.camaS.estado === 'Disponible') {
        this.notificar.notificar('warning', 'No hay paciente ingresado.');
      } else {
        $(this.darAltaPaciente.nativeElement).modal('show');
      }
    } else {
      this.notificar.notificar('warning', 'Seleccione al menos una cama.');
    }
  }
  crearAlerta(event: any) {
    //detenemos el vento clikc
    event.preventDefault();
    //
    if (this.camaS) {
      //miramos que no este ocupada
      if (this.camaS.bloqueada === 'S') {
        //si intenam,os desbloquear error
        this.notificar.notificar('warning', 'La cama está bloqueada.');
      } else if (this.camaS.estado === 'Disponible') {
        this.notificar.notificar('warning', 'No hay paciente ingresado.');
      } else {
        $(this.alertaPaciente.nativeElement).modal('show');
      }
    } else {
      this.notificar.notificar('warning', 'Seleccione al menos una cama.');
    }
  }

  //actualizar cama
  actualizarCama(cama: any) {
    this.camaM = cama;
  }
  //para mostrar modal de ingrear
  comprobarSeleccion(event: any) {
    event.preventDefault();
    if (this.camaS) {
      //miramos que no este ocupada
      if (this.camaS.estado === 'Ocupada') {
        this.notificar.notificar('warning', 'Cama no disponible');
      } else if (this.camaS.bloqueada === 'S') {
        //si intenam,os desbloquear error
        this.notificar.notificar('warning', 'La cama está bloqueada.');
      } else {
        $(this.modalIngresar.nativeElement).modal('show');
      }
    } else {
      this.notificar.notificar('warning', 'Seleccione al menos una cama.');
    }
  }
  generarInformee(event: any) {
    event.preventDefault();
    if (this.camaS) {
      //miramos que no este ocupada
      if (this.camaS.bloqueada === 'S') {
        //si intenam,os desbloquear error
        this.notificar.notificar('warning', 'La cama está bloqueada.');
      } else if (this.camaS.estado === 'Disponible') {
        this.notificar.notificar('warning', 'No hay paciente ingresado.');
      } else {
        $(this.gInforme.nativeElement).modal('show');
      }
    } else {
      this.notificar.notificar('warning', 'Seleccione al menos una cama.');
    }
  }
  //para las notase
  notaEnfermeria(event: any) {
    //detenemos el vento clikc
    event.preventDefault();
    //
    if (this.camaS) {
      //miramos que no este ocupada
      if (this.camaS.bloqueada === 'S') {
        //si intenam,os desbloquear error
        this.notificar.notificar('warning', 'La cama está bloqueada.');
      } else if (this.camaS.estado === 'Disponible') {
        this.notificar.notificar('warning', 'No hay paciente ingresado.');
      } else {
        $(this.modalEnfermeria.nativeElement).modal('show');
      }
    } else {
      this.notificar.notificar('warning', 'Seleccione al menos una cama.');
    }
  }

  mostrarTabla(table: string): void {
    if (this.camaS) {
      //miramos que no este ocupada
      if (this.camaS.bloqueada === 'S') {
        //si intenam,os desbloquear error
        this.notificar.notificar('warning', 'La cama está bloqueada.');
      } else if (this.camaS.estado === 'Disponible') {
        this.notificar.notificar('warning', 'No hay paciente ingresado.');
      } else {
        if (table === 'prescripcion') {
          this.mostrarTablaPrescripcion = true;
          this.mostrarTablaPatologia = false;
        } else if (table === 'patologia') {
          this.mostrarTablaPatologia = true;
          this.mostrarTablaPrescripcion = false;
        }
      }
    } else {
      this.notificar.notificar('warning', 'Seleccione al menos una cama.');
    }
  }
  cargarTablas(nhc: number): void {
    this.api.listarHojasPre(this.infoPa.nhc).subscribe(
      (response: any) => {
        //si hay notas
        if (isArray(response)) {
          this.datosHojaPre = response;
        } else {
          this.datosHojaPre = [];
        }
      },
      (error) => {
        this.datosHojaPre = [];
      }
    );

    this.api.listarPatologias(this.infoPa.nhc).subscribe(
      (response: any) => {
        //si hay notas
        if (isArray(response)) {
          this.datosPatologias = response;
        } else {
          this.datosPatologias = [];
        }
      },
      (error) => {
        this.datosPatologias = [];
      }
    );
    //cargaremos los datos de notas de enfermeria y de alrtas e ingresos
    this.api.listarIngresos(this.infoPa.nhc).subscribe({
      next: (response: any) => {
        if (Array.isArray(response) && response.length > 0) {
          this.datosIngresos = response;
        } else {
          this.datosIngresos = [];
        }
      },
      error: (error: any) => {
        console.error('Error al listar ingresos:', error);
      },
    });
    //altas
    this.api.listarALtas(this.infoPa.nhc).subscribe({
      next: (response: any) => {
        if (Array.isArray(response) && response.length > 0) {
          this.datosAltas = response;
        } else {
          this.datosAltas = [];
        }
      },
      error: (error: any) => {
        console.error('Error al listar altas:', error);
      },
    });
    this.api.listarNotasEnfermeria(this.infoPa.nhc).subscribe({
      next: (response: any) => {
        if (Array.isArray(response) && response.length > 0) {
          this.datosEnfermeria = response;
        } else {
          this.datosEnfermeria = [];

        }
      },
      error: (error: any) => {
        console.error('Error al listar notas de enfermeria:', error);
      },
    });
  }

  cerrarReset(evento: any) {
    evento.preventDefault();


  }




  ensenarModal() {
    this.fechaFlag = new Date();
  }

  //trasformar la fecha y verla en hdia mes año
  tranformarFecha(fecha: string): string {
    return this.datePipe.transform(fecha, 'dd/MM/yyyy') || '';
  }

  cerrarSesion(evento: any) {
    evento.preventDefault();
    this.ls.borrar('token');
    this.ls.borrar('rol');
    this.router.navigate(['/login']);
  }
  //separa los datos de enfermeri y bances
}
