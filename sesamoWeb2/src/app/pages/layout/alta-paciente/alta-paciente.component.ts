import {
  Component,
  ElementRef,
  Input,
  OnChanges,
  OnInit,
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
  sinEspacios,
  validarDni,
  validarMovil,
} from '../../../validators/validaciones.validator';
import { MatRadioModule } from '@angular/material/radio';
import { ListarAreasI } from '../../../modelos/listarAreas.interface';
import { ListarZonasI } from '../../../modelos/listarZonas.interface';
import { ListarcomunidadesI } from '../../../modelos/listarComunidades.interface';
import { ListarProvinciasI } from '../../../modelos/listarProvincias.interface';
import { MAT_DATE_LOCALE } from '@angular/material/core';
@Component({
  selector: 'app-alta-paciente',
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
  templateUrl: './alta-paciente.component.html',
  styleUrl: './alta-paciente.component.css',
  providers: [DatePipe, { provide: MAT_DATE_LOCALE, useValue: 'es-ES' }],
})
export class AltaPacienteComponent implements OnInit {
  private readonly _formBuilder = inject(FormBuilder);
  @ViewChild('cerrarModal') cerrarModal!: ElementRef;
  constructor(
    private datePipe: DatePipe,
    private api: ApiService,
    private notificar: NotificarService
  ) {}
 
  //para areas, zonas , comunidade autom y
  areas: ListarAreasI[] = [];
  zonas: ListarZonasI[] = [];
  comunidades: ListarcomunidadesI[] = [];
  provincias: ListarProvinciasI[] = [];
  comunidadesNa: ListarcomunidadesI[] = [];
  provinciasNa: ListarProvinciasI[] = [];
  esNacional: boolean = true;

  altaPacienteForm: FormGroup = this._formBuilder.group({
    nhc: [''],
    nif: ['', [Validators.required, validarDni,sinEspacios()]],
    numeroSS: ['', [Validators.required]],
    nombre: ['', [Validators.required,sinEspacios()]],
    apellido1: ['', [Validators.required,sinEspacios()]],
    apellido2: ['', [Validators.required,sinEspacios()]],
    sexo: ['', [Validators.required]],
    fechaNacimiento: ['', [Validators.required]],
    telefono1: ['', [Validators.required, validarMovil]],
    telefono2: [''],
    movil: [''],
    estadoCivil: ['', [Validators.required]],
    estudios: [''],
    fallecido: ['', [Validators.required]],
    nacionalidad: ['', [Validators.required]],
    cAutonoma: ['', [Validators.required]],
    provincia: ['', [Validators.required]],
    poblacion: [''],
    cp: ['',[Validators.required,Validators.min(1000), Validators.max(52999)]],
    direccion: [''],
    titular: [''],
    regimen: [''],
    tis: ['', [Validators.required]],
    medico: [''],
    cap: [''],
    zona: ['', [Validators.required]],
    area: ['', [Validators.required]],
    nacimiento: ['', [Validators.required]],
    cAutonomaNacimiento: ['', [Validators.required]],
    provinciaNacimiento: ['', [Validators.required]],
    poblacionNacimiento: [''],
  });

  nComunidad: string = '';
  nProvincia: string = '';
  nComunidadNacimiento: string = '';
  nProvinciaNacimiento: string = '';
  //cada vez que haya un cambioa ctualiamos el fomrualrio
  ngOnChanges(changes: SimpleChanges): void {}

  ngOnInit(): void {
    //cargamos las areas
    this.api.listarAreas().subscribe((data) => {
      this.areas = data;
    });
    this.api.listarComunidades().subscribe((data) => {
      this.comunidades = data;
    });
    this.api.listarComunidades().subscribe((data) => {
      this.comunidadesNa = data;
    });
    //cada vez que slecione un comunidad saldran sus provincias
    this.altaPacienteForm
      .get('cAutonoma')
      ?.valueChanges.subscribe((idComunidad) => {
        //como realmente en el valur capturamoe l id,y queremos el nombre al enviar el pacete, vamos a buascra rpor el
        //id en la lisat de comunidades, usamos find, cogemos el nombre del objeto cmundiad donde l id sea el sekecioando
        //si no lo encuentra era vacio
        this.nComunidad =
          this.comunidades.find((com) => com.id_cautonoma === idComunidad)
            ?.nombre || '';

        //si hay unaa area selcioanda, habilitamos la bsuqueda de zonas, si no, sigue desabiliatda
        if (idComunidad) {
          this.api.listarProvincias(idComunidad).subscribe((data) => {
            this.provincias = data;
            //añadimos el nombre de la provincia al envio del formualrio
          });
        }
      });
    this.altaPacienteForm
      .get('cAutonomaNacimiento')
      ?.valueChanges.subscribe((idComunidad) => {
        //como realmente en el valur capturamoe l id,y queremos el nombre al enviar el pacete, vamos a buascra rpor el
        //id en la lisat de comunidades, usamos find, cogemos el nombre del objeto cmundiad donde l id sea el sekecioando
        //si no lo encuentra era vacio
        this.nComunidadNacimiento =
          this.comunidadesNa.find((com) => com.id_cautonoma === idComunidad)
            ?.nombre || '';

        //si hay unaa area selcioanda, habilitamos la bsuqueda de zonas, si no, sigue desabiliatda
        if (idComunidad) {
          this.api.listarProvincias(idComunidad).subscribe((data) => {
            this.provinciasNa = data;
            //añadimos el nombre de la provincia al envio del formualrio
          });
        }
      });

    this.altaPacienteForm.get('area')?.valueChanges.subscribe((idArea) => {
      //si hay unaa area selcioanda, habilitamos la bsuqueda de zonas, si no, sigue desabiliatda
      if (idArea) {
        this.api.listarZonas(idArea).subscribe((data) => {
          this.zonas = data;
          console.log(this.zonas);
          this.altaPacienteForm.get('cap')?.setValue('');
        });
      } else {
        this.altaPacienteForm.get('zona')?.setValue('');
        this.altaPacienteForm.get('cap')?.setValue('');
        // this.altaPacienteForm.get('zona')?.disable({ emitEvent: false });
      }
    });
    //para wue se ponga lo del cap
    this.altaPacienteForm.get('zona')?.valueChanges.subscribe((idZona) => {
      if (idZona) {
        //buscamos la zona en el arraz
        const selectedZona = this.zonas.find(zona => zona.nombre=== idZona);
        if (selectedZona) {
          //seteamso el cap
          console.log(selectedZona)
          this.altaPacienteForm.get('cap')?.setValue(selectedZona.cap);
        }
      } else {
        this.altaPacienteForm.get('cap')?.setValue('');
      }
    });

    //comprobar si ha ancido o no ene spaña

    this.altaPacienteForm.get('nacimiento')?.valueChanges.subscribe((valor) => {
      //si hay unaa area selcioanda, habilitamos la bsuqueda de zonas, si no, sigue desabiliatda
      if (valor == 'Nacional') {
        this.esNacional = true;
      } else {
        //modifcar luego esta e sla verdadera
        // this.esNacional=false;
        //esta para continuar
        this.esNacional = true;
        this.altaPacienteForm.get('cAutonomaNacimiento')?.setValue('');
      }
    });
  }
  resetForm() {
    this.altaPacienteForm.reset();
  }

  crearPaciente(form: any) {

    
    //comprobaremos si es valido
    //para enviar la fecha en fomrtoa año mes dia con -
    //usamosa date pipe: clase para tranformar fechas en un formato specifico, ene ste caso el que adminis mysql
    const fecha = this.datePipe.transform(
      this.altaPacienteForm.get('fechaNacimiento')?.value,
      'yyyy-MM-dd'
    );
    form = {
      ...form,
      cAutonoma: this.nComunidad,
      fechaNacimiento: fecha,
      cAutonomaNacimiento: this.nComunidadNacimiento,
    };

    if (this.altaPacienteForm.valid) {
      this.api.altaPacientes(form).subscribe(
        (respuesta: ResponseI) => {
          const result =
            typeof respuesta.result === 'string'
              ? JSON.parse(respuesta.result)
              : respuesta.result;
          if (respuesta.status === 'ok') {
            this.notificar.notificar('success', result.msg);
            this.cerrarModal.nativeElement.click();

            this.altaPacienteForm.reset();
          } else {
            const errorMensaje = result.error_msg;
            this.notificar.notificar('error', errorMensaje);
          }
        },
        (error) => {
          console.error('Error del servidor:', error);
          this.notificar.notificar('error', 'Error al procesar la solicitud.');
        }
      );
  
      
    } else {
      this.notificar.notificar('error', 'Rellenar los campos requeridos');
    }
  }

  //gettter
  // Obtener el control de un campo específico
  get getNif() {
    return this.altaPacienteForm.get('nif');
  }
  get getFecha(): any {
    return this.altaPacienteForm.get('fechaNacimiento')?.value;
  }

  //  mostrar mensajes de error de nif
  getErroresNif() {
    if (this.getNif?.hasError('required')) {
      return 'Campo requerido';
    }
    if (this.getNif?.hasError('validarDni')) {
      return 'Formato de DNI incorrecto';
    }
    return null;
  }
}
