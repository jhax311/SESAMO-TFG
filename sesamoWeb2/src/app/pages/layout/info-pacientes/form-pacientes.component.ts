import {
  Component,
  Input,
  OnChanges,
  OnInit,
  SimpleChanges,
  inject,
} from '@angular/core';
import { ListarPacientesI } from '../../../modelos/listarPacientes.interface';
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
@Component({
  selector: 'app-form-pacientes',
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
  templateUrl: './form-pacientes.component.html',
  styleUrl: './form-pacientes.component.css',
  providers: [DatePipe],
})
export class FormPacientesComponent implements OnChanges, OnInit {
  @Input() paciente!: ListarPacientesI;
  pacienteForm!: FormGroup;
  private readonly _formBuilder = inject(FormBuilder);

  constructor(
    private datePipe: DatePipe,
    private api: ApiService,
    private notificar: NotificarService
  ) {}
  //cada vez que haya un cambioa ctualiamos el fomrualrio
  ngOnChanges(changes: SimpleChanges): void {
    //inicalizamos
    this.inicializarFormulario();

    if (changes['paciente'] && this.paciente) {
      this.actualizarFormulario();
    }
  }

  ngOnInit(): void {}

  private inicializarFormulario(): void {
    this.pacienteForm = this._formBuilder.group({
      nhc: [''],
      nif: [''],
      numeroSS: [''],
      nombre: [''],
      apellido1: [''],
      apellido2: [''],
      sexo: [''],
      fechaNacimiento: [''],
      telefono1: [''],
      telefono2: [''],
      movil: [''],
      estadoCivil: [''],
      estudios: [''],
      fallecido: [''],
      nacionalidad: [''],
      cAutonoma: [''],
      provincia: [''],
      poblacion: [''],
      cp: [''],
      direccion: [''],
      titular: [''],
      regimen: [''],
      tis: [''],
      medico: [''],
      cap: [''],
      zona: [''],
      area: [''],
      nacimiento: [''],
      cAutonomaNacimiento: [''],
      provinciaNacimiento: [''],
      poblacionNacimiento: [''],
    });
  }

  private actualizarFormulario(): void {
    if (this.pacienteForm) {
      this.pacienteForm.patchValue({
        nhc: this.paciente.nhc,
        nif: this.paciente.nif,
        numeroSS: this.paciente.numeroSS,
        nombre: this.paciente.nombre,
        apellido1: this.paciente.apellido1,
        apellido2: this.paciente.apellido2,
        sexo: this.paciente.sexo,
        fechaNacimiento: this.formatoFecha(this.paciente.fechaNacimiento),
        telefono1: this.paciente.telefono1,
        telefono2: this.paciente.telefono2,
        movil: this.paciente.movil,
        estadoCivil: this.paciente.estadoCivil,
        estudios: this.paciente.estudios,
        fallecido: this.paciente.fallecido,
        nacionalidad: this.paciente.nacionalidad,
        cAutonoma: this.paciente.cAutonoma,
        provincia: this.paciente.provincia,
        Poblacion: this.paciente.poblacion,
        cp: this.paciente.cp,
        direccion: this.paciente.direccion,
        titular: this.paciente.titular,
        regimen: this.paciente.regimen,
        tis: this.paciente.tis,
        medico: this.paciente.medico,
        cap: this.paciente.cap,
        zona: this.paciente.zona,
        area: this.paciente.area,
        nacimiento: this.paciente.nacimiento,
        cAutonomaNacimiento: this.paciente.cAutonomaNacimiento,
        provinciaNacimiento: this.paciente.provinciaNacimiento,
        poblacionNacimiento: this.paciente.poblacionNacimiento,
      });
    }
  }

  modificar(form: ListarPacientesI): void {
    if (this.pacienteForm.valid) {
      //debemos hacer uan comprobacion de los numeo de telefono,en caso de ser vacios que ponga un 0 por defecto
      const t1 = this.pacienteForm.get('telefono1')!.value
        ? this.pacienteForm.get('telefono1')!.value
        : 0;
      const t2 = this.pacienteForm.get('telefono2')!.value
        ? this.pacienteForm.get('telefono2')!.value
        : 0;
      const movi = this.pacienteForm.get('movil')!.value
        ? this.pacienteForm.get('movil')!.value
        : 0;
      form = { ...form, telefono1: t1, telefono2: t2, movil: movi };
      this.api.modificarPacientes(form).subscribe(
        (response: ResponseI) => {
          //guardamos result
          const result =
            typeof response.result === 'string'
              ? JSON.parse(response.result)
              : response.result;
          //si se ah realizado la operacion correctamente
          if (response.status == 'ok') {
            this.notificar.notificar('success', result.msg);
          } else {
            this.notificar.notificar('error', result.error_msg);
          }
        },
        (error) => {
          console.error('Error al enviar el formulario', error);
        }
      );
    }
  }

  //formto de la fecha
  private formatoFecha(fecha: string | Date): string {
    return this.datePipe.transform(fecha, 'yyyy-MM-dd') ?? '';
  }
}
