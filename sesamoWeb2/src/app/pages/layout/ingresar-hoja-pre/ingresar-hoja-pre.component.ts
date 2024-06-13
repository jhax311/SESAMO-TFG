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
  selector: 'app-ingresar-hoja-pre',
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
    DatePipe],
  templateUrl: './ingresar-hoja-pre.component.html',
  styleUrl: './ingresar-hoja-pre.component.css',
  providers: [DatePipe, { provide: MAT_DATE_LOCALE, useValue: 'es-ES' }]
})
export class IngresarHojaPreComponent implements OnInit {
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
      //this.actualizarFechaHora();
    }
  }

  hojaPrescripcionForm !: FormGroup;
  private readonly _formBuilder = inject(FormBuilder);

  camaS: any = null;
  camaName: any = null;
  actualizarBalance = false;

  
  constructor(
    private datePipe: DatePipe,
    private api: ApiService,
    private notificar: NotificarService
  ) {
  }

  ngOnInit(): void {
    this.hojaPrescripcionForm = this._formBuilder.group({
      especialidad: ['', Validators.required],
      principio_activo: ['', Validators.required],
      dosis: ['', Validators.required],
      via_administracion: ['', Validators.required],
      frecuencia: ['', Validators.required],
      fecha_fin_tratamiento: ['', Validators.required]
    });
  }

  crearHoja(form:any){
    if (this.hojaPrescripcionForm.valid) {
      //aañdimos el nhc antes
      form = { ...form, nhc: this.infoPa.nhc };
      this.api.ingresarHoja(form).subscribe(
        (response: ResponseI) => {
          console.log(response);
          //guardamos el resultado
          console.log(response);
          const result =
            typeof response.result === 'string'
              ? JSON.parse(response.result)
              : response.result;
          //si se ah ehcho bien notificamos
          if (response.status === 'ok') {
            //notifcamos
            this.notificar.notificar('success', 'Hoja de prescripción creada.');
             //enviamos la cama al padre para ctualizarla y cerramos modal

            this.camaSeleccionadaEvent.emit(this.camaS);
            //lanzamoa evento para quitar cama
            this.deseleccionarCama.emit();
            this.btnClose.nativeElement.click();

            //hacemos un reset del form
            this.resetForm();
          } else {
            //en caso de error, lo notificamos tmb
            this.notificar.notificar(
              'error',
              'Error al crear hoja de prescripción: ' + result.error_msg
            );
          }
        },
        (error) => {
          //en caso de error en a red igua
          this.notificar.notificar('error', 'Error de red: ' + error.message);
        }
      );
    } else {
      this.notificar.notificar('error', "Ingrese los campos requeridos");
    }

  }


  resetForm() {
    this.hojaPrescripcionForm.patchValue({
      especialidad: '',
      principio_activo: '',
      dosis: '',
      via_administracion: '',
      frecuencia: '',
      fecha_fin_tratamiento: '',
      nhc: ''
    });
  }

}
