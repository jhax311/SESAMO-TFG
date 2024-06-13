import {
  Component,
  ElementRef,
  EventEmitter,
  Input,
  OnChanges,
  OnInit,
  Output,
  SimpleChanges,
  ViewChild,
  inject,
  input,
} from '@angular/core';
import {
  FormBuilder,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import {
  MatFormField,
  MatFormFieldModule,
  MatLabel,
} from '@angular/material/form-field';
import { MatButtonModule } from '@angular/material/button';
import { MatMenuModule } from '@angular/material/menu';
import { MatIconModule } from '@angular/material/icon';
import { MatTooltipModule } from '@angular/material/tooltip';
import { MatOption, MatSelect } from '@angular/material/select';
import { MatInputModule } from '@angular/material/input';
import { CommonModule, DatePipe } from '@angular/common';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatNativeDateModule } from '@angular/material/core';
import { ApiService } from '../../../servicios/api.service';
import { ResponseI } from '../../../modelos/response.interface';
import { NotificarService } from '../../../servicios/notificar.service';
import {
  sinEspacios,
} from '../../../validators/validaciones.validator';
import { MAT_DATE_LOCALE } from '@angular/material/core';
import Swal from 'sweetalert2';
@Component({
  selector: 'app-alerta-paciente',
  standalone: true,
  imports: [
    MatLabel,
    MatFormField,
    ReactiveFormsModule,
    MatButtonModule,
    MatMenuModule,
    MatIconModule,
    MatTooltipModule,
    MatLabel,
    MatFormField,
    MatSelect,
    MatIconModule,
    MatOption,
    MatFormFieldModule,
    MatInputModule,
    MatDatepickerModule,
    MatNativeDateModule,
    ReactiveFormsModule,
    CommonModule,
    DatePipe,
  ],
  templateUrl: './alerta-paciente.component.html',
  styleUrl: './alerta-paciente.component.css',
})
export class AlertaPacienteComponent implements OnInit, OnChanges {
  @ViewChild('btnClose') btnClose!: ElementRef;
  @Output() camaSeleccionadaEvent = new EventEmitter<any>();
  @Output() deseleccionarCama = new EventEmitter<any>();
  @Input() infoPa: any;
  @Input() set camaActualizada(cama: any) {
    //si hya valor
    if (cama) {
      this.camaS = cama;
      this.camaName = `${this.camaS.id_planta}${this.camaS.id_habitacion}${this.camaS.letra}`;
      this.actualizarFechaHora();
    } else {
      this.camaS = null;
    }
  }
  private readonly _formBuilder = inject(FormBuilder);
  constructor(
    private api: ApiService,
    private notificar: NotificarService
  ) {}
  alertaPacienteForm: FormGroup = this._formBuilder.group({
    fecha: ['', [Validators.required]],
    descripcion: ['', [Validators.required, sinEspacios()]],
    observaciones: ['', []]
    //observaciones: ['', [sinEspacios()]],
  });

  //form
  ngOnInit(): void {}
  ngOnChanges(changes: SimpleChanges): void {
    if (changes['infoPa']) {
      if (this.infoPa) {
        this.alertaPacienteForm.patchValue({
          fecha: this.actualizarFechaHora(),
        });
        if (this.infoPa.alerta) {
          this.alertaPacienteForm.patchValue({
            descripcion: this.infoPa.alerta.descripcion,
            observaciones: this.infoPa.alerta.observaciones,
            fecha: this.infoPa.alerta.fecha,
          });
         //comprobamos que ahy un valor en observacionesç
         //
        //  this.actualizarValidadoresObservaciones(this.infoPa.alerta.observaciones);
         
        } else {
          this.resetForm();
        }
      }
    }
  }
  //cams y nombre
  camaS: any = null;
  camaName: any = null;

  actualizarValidadoresObservaciones(observaciones: string) {
    //hacemos un get del input
    const observacionesControl = this.alertaPacienteForm.get('observaciones');
    if (observacionesControl) {
      if (observaciones) {
        //si hya datos borramos las validaciones, por si hemos puesto ago y lo quermeos dejar vacio
        observacionesControl.clearValidators();
      } else {
        //en caso de que no haya anada, le ponemos avalidacion de qu eno se metan solo esapcios
        observacionesControl.setValidators(sinEspacios());
      }
      //actualizamos las validaciones
      observacionesControl.updateValueAndValidity();
    }
  }
  //actualizar fechas
  actualizarFechaHora(): void {
    this.alertaPacienteForm.patchValue({
      fecha: this.getFechaActual(),
    });
  }
  //
  resetForm() {
    this.alertaPacienteForm.patchValue({
      fecha: this.getFechaActual(),
      descripcion: '',
      observaciones: '',
    });
  }
  //post para crear alerta
  crearALerta(form: any) {
    if (this.alertaPacienteForm.valid) {
      form = { ...form, nhc: this.infoPa.nhc };
      this.api.ingresarAlerta(form).subscribe({
        next: (response) => {
          if (response.status === 'ok') {
            this.notificar.notificar('success', 'Alerta creada con exito.');
            this.camaSeleccionadaEvent.emit(this.camaS);
            //lanzamoa evento para quitar cama
            //  this.deseleccionarCama.emit()
            this.btnClose.nativeElement.click();
          } else {
            console.error('Error en la respuesta del servidor:', response);
          }
        },
        error: (error) => {
          console.error('Error al ingresar alerta:', error);
        },
      });
    } else {
      this.notificar.notificar('error', 'Inserte todos los campos requeridos');
    }
  }
  //put para modificar alerta
  modificarAlerta(form: any) {
    if (this.alertaPacienteForm.valid) {
      //agregamos el nhc
      form = { ...form, nhc: this.infoPa.nhc };
      this.api.modificarAlerta(form).subscribe({
        next: (response) => {
          //guardamos resultado
          const result =
            typeof response.result === 'string'
              ? JSON.parse(response.result)
              : response.result;
          //si es correcta mensaje ya ctualizamos, si no lo contraio
          if (response.status === 'ok') {
            this.notificar.notificar('success', 'Alerta modificada con exito.');
            this.camaSeleccionadaEvent.emit(this.camaS);
            //lanzamoa evento para quitar cama
            //  this.deseleccionarCama.emit()
            this.btnClose.nativeElement.click();
          } else {
            this.notificar.notificar('error', result.error_msg);
          }
        },
        error: (error) => {
          console.error('Error al ingresar alerta:', error);
        },
      });
    } else {
      this.notificar.notificar('error', 'Inserte todos los campos requeridos');
    }
  }

  //eliminarla
  eliminarAlerta(form: any) {
  //  if (this.alertaPacienteForm.valid) {
      Swal.fire({
        title: '¿Estás seguro?',
        text: '¡No podrás revertir esto!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
      }).then((result) => {
        if (result.isConfirmed) {
          //añadimos el nhc
          form = { ...form, nhc: this.infoPa.nhc };


          this.api.eliminarAlerta(form).subscribe({
            next: (response: ResponseI) => {
              //guardamos resultado
              const result =
                typeof response.result === 'string'
                  ? JSON.parse(response.result)
                  : response.result;

              if (response.status === 'ok') {
                /*  Swal.fire({
                  title: "¡Eliminado!",
                text: "Alerta eliminada",
                icon: "success"
                });*/
                this.notificar.notificar(
                  'success',
                  'Alerta eliminada con exito.'
                );
                this.camaSeleccionadaEvent.emit(this.camaS);
                //lanzamoa evento para quitar cama
                //this.deseleccionarCama.emit()
                this.btnClose.nativeElement.click();
              } else {
                this.notificar.notificar('error', result.error_msg);
              }
            },
            error: (error) => {
              console.error('Error al eliminar alerta:', error);
            },
          });
        }
      });
    /*} else {
      this.notificar.notificar('error', 'Inserte todos los campos requeridos');
    }*/
  }

  cerrarM() {
    this.resetForm();
    this.btnClose.nativeElement.click();
    this.deseleccionarCama.emit();
  }

  //para lso formatos de hora y fecha
  getFechaActual(): string {
    //sacamos fecha
    const fecha = new Date();
    //sacamos año
    const anio = fecha.getFullYear();
    //sacamos mes, ponemos 0 hasta llegar a 2 digitos
    const mes = (fecha.getMonth() + 1).toString().padStart(2, '0');
    //sacamos dia
    const day = fecha.getDate().toString().padStart(2, '0');

    return `${anio}-${mes}-${day}`;
  }

  getHoraActual(): string {
    const fecha = new Date();
    //lo mismo co horas minutos ys egundos
    const hora = fecha.getHours().toString().padStart(2, '0');
    const min = fecha.getMinutes().toString().padStart(2, '0');
    const sec = fecha.getSeconds().toString().padStart(2, '0');
    return `${hora}:${min}:${sec}`;
  }
  //fin
}
