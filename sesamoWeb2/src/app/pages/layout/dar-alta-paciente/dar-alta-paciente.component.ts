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
} from '@angular/core';
import { ListarPacientesI } from '../../../modelos/listarPacientes.interface';
import {
  FormBuilder,
  FormGroup,
  FormsModule,
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
  validarDni,
  validarMovil,
} from '../../../validators/validaciones.validator';
import { MAT_DATE_LOCALE } from '@angular/material/core';
@Component({
  selector: 'app-dar-alta-paciente',
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
  templateUrl: './dar-alta-paciente.component.html',
  styleUrl: './dar-alta-paciente.component.css',
  providers: [DatePipe, { provide: MAT_DATE_LOCALE, useValue: 'es-ES' }],
})
export class DarAltaPacienteComponent implements OnInit, OnChanges {
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
  @Input() set fechaActualizada(fecha: any) {
    if (fecha) {
      this.actualizarFechaHora();
    }
  }

  private readonly _formBuilder = inject(FormBuilder);
  constructor(
    private datePipe: DatePipe,
    private api: ApiService,
    private notificar: NotificarService
  ) {}
  //form
  ngOnInit(): void {}
  ngOnChanges(changes: SimpleChanges): void {
    if (changes['infoPa'] && this.infoPa) {
      this.altaPacienteForm.patchValue({
        nhc: this.infoPa.nhc,
        cama: this.infoPa.Cama,
      });
    }
  }
  altaPacienteForm: FormGroup = this._formBuilder.group({
    nhc: [''],
    //nif: ['', [Validators.required, validarDni]],
    fecha: ['', [Validators.required]],
    hora: ['', [Validators.required]],
    cama: ['', [Validators.required]],
    tipo: ['', [Validators.required]],
    motivo: ['', [Validators.required]],
  });
  darDeAltaPaciente(form: any) {
    //si el fomulario es valido
    if (this.altaPacienteForm.valid) {
      //damos de alta
      this.api.altaPaciente(form).subscribe(
        (response: ResponseI) => {
          const result =
            typeof response.result === 'string'
              ? JSON.parse(response.result)
              : response.result;
//si se ah ehcho bien notificamos
          if (response.status === 'ok') {
            //notifcamos
            this.notificar.notificar(
              'success',
              "Paciente dado de alta correctamente."
            );//enviamos la cama al padre para ctualizarla y cerramos modal
        
            this.camaS.estado="Disponible";
            this.camaSeleccionadaEvent.emit(this.camaS);
            //lanzamoa evento para quitar cama
            this.deseleccionarCama.emit()
            this.btnClose.nativeElement.click();
          
            //hacemos un reset del form
          this.resetForm();
          } else {
            //en caso de error, lo notificamos tmb
            this.notificar.notificar(
              'error',
              'Error al ingresar el paciente: ' + result.error_msg
            );
          }
        },
        (error) => {
          //en caso de error en a red igual
          this.notificar.notificar('error', 'Error de red: ' + error.message);
        }
      );
    } else {
      this.notificar.notificar(
        'warning',
        'Por favor, completa todos los campos requeridos.'
      );
    }
  }
  //
  camaS: any = null;
  camaName: any = null;
  //horas

  //actualizar fechas
  actualizarFechaHora(): void {
    this.altaPacienteForm.patchValue({
      fecha: this.getFechaActual(),
      hora: this.getHoraActual(),
    });
  }
  //
  resetForm(){
    this.altaPacienteForm.reset({
      nhc: '',
      fecha: "",
      hora: "",
      motivo: '',
      tipo: '',
      cama: '',
      estado_nhc: '',
      id_ambito: ''
    });
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
