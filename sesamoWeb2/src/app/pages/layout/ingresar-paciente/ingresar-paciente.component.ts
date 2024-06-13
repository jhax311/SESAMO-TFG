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
  selector: 'app-ingresar-paciente',
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
  templateUrl: './ingresar-paciente.component.html',
  styleUrl: './ingresar-paciente.component.css',
  providers: [DatePipe, { provide: MAT_DATE_LOCALE, useValue: 'es-ES' }],
})
export class IngresarPacienteComponent implements OnInit, OnChanges {
  @ViewChild('btnClose') btnClose!: ElementRef;
  @Output() camaSeleccionadaEvent = new EventEmitter<any>();
  @Output() deseleccionarCama = new EventEmitter<any>();
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
  //ambitos
  ambitos: any = [];
  camaS: any = null;
  camaName: any = null;
  //form
  ingresarPacienteForm: FormGroup = this._formBuilder.group({
    nhc: [''],
    //nif: ['', [Validators.required, validarDni]],
    fecha: ['', [Validators.required]],
    hora: ['', [Validators.required]],
    id_cama: [this.camaName, [Validators.required]],
    estado_nhc: ['', [Validators.required]],
    id_ambito: ['', [Validators.required]],
  });

  ngOnInit(): void {
    this.api.listarAmbitos().subscribe((data) => {
      this.ambitos = data;
    });
    //parseamso las fechas cuando entremos al modal
    this.ingresarPacienteForm.patchValue({
      fecha: this.getFechaActual(),
      hora: this.getHoraActual(),
    });
    this.actualizarFechaHora();
    //para desabilitar u hbailitar
  }
  ngOnChanges(changes: SimpleChanges): void {
    //cualquier cambio que haya en cama selecioanda oq eue ntre uan nueva cambiara la fecha
    if (changes['camaS'] && !changes['camaS'].isFirstChange()) {
      this.actualizarFechaHora();
    
    }
  }

  ingresarPaciente(form: any) {
    this.ingresarPacienteForm.patchValue({
      id_cama: this.camaS.id_cama,
    });
    if (this.ingresarPacienteForm.valid) {
      //metemos el id de la cama en el form principal y el de los values

      form = { ...form, id_cama: this.camaS.id_cama };

      this.api.ingresarPaciente(form).subscribe(
        (response: any) => {
          // si fue exitosa
          if (response.status === 'ok') {
            this.notificar.notificar(
              'success',
              'Paciente ingresado correctamente.'
            );
            // restablecemos y cerramos modal
            // this.ingresarPacienteForm.reset();
            this.btnClose.nativeElement.click();
            this.camaS.estado = 'Ocupada';
            if (this.camaS.estado === 'Ocupada') {
              this.api.pacienteCama(this.camaS.id_cama).subscribe((paciente) => {
                this.camaS.paciente = paciente;
                this.camaS.paciente[0].ingresado=1;
       
              });
           
            }

            this.camaSeleccionadaEvent.emit(this.camaS);
            this.deseleccionarCama.emit();
          } else {
            // error si viene de la api
            this.notificar.notificar('error', response.result.error_msg);
          }
        },
        (error) => {
          // en caso de erro http
          this.notificar.notificar('error', 'Error de red: ' + error.message);
        }
      );
    } else {
      this.ingresarPacienteForm.patchValue({
        id_cama: this.camaName,
      });
    }
  }
  //actualizar fechas
  actualizarFechaHora(): void {
    this.ingresarPacienteForm.patchValue({
      fecha: this.getFechaActual(),
      hora: this.getHoraActual(),
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
