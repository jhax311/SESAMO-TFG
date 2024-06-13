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
  selector: 'app-ingresar-patologia',
  standalone: true,
  imports: [MatLabel,
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
    DatePipe,],
  templateUrl: './ingresar-patologia.component.html',
  styleUrl: './ingresar-patologia.component.css', providers: [DatePipe, { provide: MAT_DATE_LOCALE, useValue: 'es-ES' }],
})
export class IngresarPatologiaComponent implements OnInit, OnChanges {
  @ViewChild('btnClose') btnClose!: ElementRef;
  @Output() camaSeleccionadaEvent = new EventEmitter<any>();
  @Output() deseleccionarCama = new EventEmitter<any>();
  @Input() infoPa: any;
  @Input() set camaActualizada(cama: any) {
    //si hya valor
    if (cama) {
      this.camaS = cama;
      this.camaName = `${this.camaS.id_planta}${this.camaS.id_habitacion}${this.camaS.letra}`;
      // this.actualizarFechaHora();
    } else {
      this.camaS = null;
    }
  }
  @Input() set fechaActualizada(fecha: any) {
    if (fecha) {
      this.actualizarFechaHora();
    }
  }

  fomIngresarPato!: FormGroup;
  private readonly _formBuilder = inject(FormBuilder);

  camaS: any = null;
  camaName: any = null;
  actualizarBalance = false;


  ngOnChanges(changes: SimpleChanges): void {

  }
  constructor(
    private datePipe: DatePipe,
    private api: ApiService,
    private notificar: NotificarService
  ) {
  }

  ngOnInit(): void {
    this.fomIngresarPato = this._formBuilder.group({
      fecha_inicio: ['', Validators.required],
      fecha_diagnostico: ['', Validators.required],
      sintomas: ['', Validators.required],
      diagnostico: ['', Validators.required],
      especialidad: ['', Validators.required],
      codificacion: ['', Validators.required]
    });
    this.actualizarFechaHora();
  }

  crearPato(form: any) {
    if (this.fomIngresarPato.valid) {
      //si e svalido añadimos el nhc y hacemos la peticion
      form = { ...form, nhc: this.infoPa.nhc };
      this.api.ingresarPatologia(form).subscribe(
        (response: ResponseI) => {
          const result =
            typeof response.result === 'string'
              ? JSON.parse(response.result)
              : response.result;
          //si se ah ehcho bien notificamos
          if (response.status === 'ok') {
            //notifcamos
            this.notificar.notificar('success', 'Patología registrada..'); //enviamos la cama al padre para ctualizarla y cerramos modal

            this.camaSeleccionadaEvent.emit(this.camaS);
            //lanzamoa evento para quitar cama
            this.deseleccionarCama.emit();
            this.btnClose.nativeElement.click();

            //hacemos un reset del form
           // this.resetForm();
          } else {
            //en caso de error, lo notificamos tmb
            this.notificar.notificar(
              'error',
              'Error al crear patologia: ' + result.error_msg
            );
          }
        },
        (error) => {
          //en caso de error en a red igual
          this.notificar.notificar('error', 'Error de red: ' + error.message);
        }
      );

    }else{
      this.notificar.notificar('error', "Inserte todos los campos requeridos.");
    }


  }


  //fecha sy hora 

  actualizarFechaHora(): void {
    this.fomIngresarPato?.patchValue({
      fecha_diagnostico: this.getFechaActual()
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
  resetForm(): void {
    this.fomIngresarPato.reset({
      fecha_inicio: '',
      fecha_diagnostico: '',
      sintomas: '',
      diagnostico: '',
      especialidad: '',
      codificacion: ''
    });
  }

}
