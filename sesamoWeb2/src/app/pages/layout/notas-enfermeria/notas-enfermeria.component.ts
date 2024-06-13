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
  selector: 'app-notas-enfermeria',
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
  templateUrl: './notas-enfermeria.component.html',
  styleUrl: './notas-enfermeria.component.css',
  providers: [DatePipe, { provide: MAT_DATE_LOCALE, useValue: 'es-ES' }],
})
export class NotasEnfermeriaComponent implements OnInit, OnChanges {
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
  formNotasEnfermeria: FormGroup;
  private readonly _formBuilder = inject(FormBuilder);

  camaS: any = null;
  camaName: any = null;
  actualizarBalance = false;
  ngOnInit(): void {}
  ngOnChanges(changes: SimpleChanges): void {
    if (changes['infoPa'] && this.infoPa) {
     
    }
  }
  constructor(
    private datePipe: DatePipe,
    private api: ApiService,
    private notificar: NotificarService
  ) {
    this.formNotasEnfermeria = this._formBuilder.group({
      fecha_toma: ['', Validators.required],
      hora_toma: ['', Validators.required],
      temperatura: [
        '',
        [Validators.required, Validators.pattern(/^\d+(\.\d{1,2})?$/)], //valida numero con un deciamal
      ],
      tension_arterial_sistolica: ['', [Validators.required]],
      tension_arterial_diastolica: ['', [Validators.required]],
      frecuencia_cardiaca: ['', [Validators.required]],
      frecuencia_respiratoria: ['', [Validators.required]],
      peso: [
        '',
        [Validators.required, Validators.pattern(/^\d+(\.\d{1,2})?$/)],
      ],
      talla: [
        '',
        [Validators.required, Validators.pattern(/^\d+(\.\d{1,2})?$/)],
      ],
      indice_masa_corporal: ['', [Validators.required]],
      glucemia_capilar: [
        '',
        [Validators.required, Validators.pattern(/^\d+(\.\d{1,2})?$/)],
      ],
      ingesta_alimentos: ['', Validators.required],
      tipo_deposicion: ['', Validators.required],
      nauseas: ['', Validators.required],
      prurito: ['', Validators.required],
      observaciones: ['', Validators.required],
      balance_hidrico_entrada_alimentos: ['', Validators.required],
      balance_hidrico_entrada_liquidos: ['', Validators.required],
      balance_hidrico_fluidoterapia: ['', Validators.required],
      balance_hidrico_hemoderivados: ['', Validators.required],
      balance_hidrico_otros_entrada: ['', Validators.required],
      balance_hidrico_diuresis: ['', Validators.required],
      balance_hidrico_vomitos: ['', Validators.required],
      balance_hidrico_heces: ['', Validators.required],
      balance_hidrico_sonda_nasogastrica: ['', Validators.required],
      balance_hidrico_drenajes: ['', Validators.required],
      balance_hidrico_otras_perdidas: ['', Validators.required],
      total_balance_hidrico: ['', Validators.required],
    });
    //para imc
    this.formNotasEnfermeria.get('peso')?.valueChanges.subscribe(() => {
      this.calcularIMC();
    });

    this.formNotasEnfermeria.get('talla')?.valueChanges.subscribe(() => {
      this.calcularIMC();
    });
    //ara los balances
    this.formNotasEnfermeria.valueChanges.subscribe(() => {
      if (!this.actualizarBalance) {
        this.calcularTotalBalanceHidrico();
      }
    });
  }

  calcularIMC() {
    const peso = parseFloat(this.formNotasEnfermeria.get('peso')?.value); //sacmos peso
    const talla =
      parseFloat(this.formNotasEnfermeria.get('talla')?.value) / 100; // pasamos ametros

    if (peso && talla) {
      //calculamos imc
      const imc = (peso / (talla * talla)).toFixed(2);
      this.formNotasEnfermeria.patchValue({
        indice_masa_corporal: imc,
      });
    } else {
      this.formNotasEnfermeria.patchValue({
        indice_masa_corporal: 'error',
      });
    }
  }
  calcularTotalBalanceHidrico() {
    this.actualizarBalance = true;
    const entrada_alimentos =
      parseFloat(
        this.formNotasEnfermeria.get('balance_hidrico_entrada_alimentos')?.value
      ) || 0;
    const entrada_liquidos =
      parseFloat(
        this.formNotasEnfermeria.get('balance_hidrico_entrada_liquidos')?.value
      ) || 0;
    const fluidoterapia =
      parseFloat(
        this.formNotasEnfermeria.get('balance_hidrico_fluidoterapia')?.value
      ) || 0;
    const hemoderivados =
      parseFloat(
        this.formNotasEnfermeria.get('balance_hidrico_hemoderivados')?.value
      ) || 0;
    const otros_entrada =
      parseFloat(
        this.formNotasEnfermeria.get('balance_hidrico_otros_entrada')?.value
      ) || 0;
    const diuresis =
      parseFloat(
        this.formNotasEnfermeria.get('balance_hidrico_diuresis')?.value
      ) || 0;
    const vomitos =
      parseFloat(
        this.formNotasEnfermeria.get('balance_hidrico_vomitos')?.value
      ) || 0;
    const heces =
      parseFloat(
        this.formNotasEnfermeria.get('balance_hidrico_heces')?.value
      ) || 0;
    const sonda_nasogastrica =
      parseFloat(
        this.formNotasEnfermeria.get('balance_hidrico_sonda_nasogastrica')
          ?.value
      ) || 0;
    const drenajes =
      parseFloat(
        this.formNotasEnfermeria.get('balance_hidrico_drenajes')?.value
      ) || 0;
    const otras_perdidas =
      parseFloat(
        this.formNotasEnfermeria.get('balance_hidrico_otras_perdidas')?.value
      ) || 0;

    const total =
      entrada_alimentos +
      entrada_liquidos +
      fluidoterapia +
      hemoderivados +
      otros_entrada -
      diuresis -
      vomitos -
      heces -
      sonda_nasogastrica -
      drenajes -
      otras_perdidas;

    this.formNotasEnfermeria.patchValue({
      total_balance_hidrico: total.toFixed(2),
    });
    this.actualizarBalance = false;
  }

  crearNota(form: any) {
    //si el fomulario es valido, realizamos la insercion
    if (this.formNotasEnfermeria.valid) {
      //aañdimos el nhc antes
      form = { ...form, nhc: this.infoPa.nhc };
      this.api.ingresarNotaEnfermeria(form).subscribe(
        (response: ResponseI) => {
         
          const result =
            typeof response.result === 'string'
              ? JSON.parse(response.result)
              : response.result;
          //si se ah ehcho bien notificamos
          if (response.status === 'ok') {
            //notifcamos
            this.notificar.notificar('success', 'Nota de enfermeria creada.'); //enviamos la cama al padre para ctualizarla y cerramos modal

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
              'Error al crear nota de enfermeria: ' + result.error_msg
            );
          }
        },
        (error) => {
          //en caso de error en a red igual
          this.notificar.notificar('error', 'Error de red: ' + error.message);
        }
      );
    } else {
      this.notificar.notificar('error', "Ingrese los campos requeridos");
    }
  }

  actualizarFechaHora(): void {
    this.formNotasEnfermeria.patchValue({
      fecha_toma: this.getFechaActual(),
      hora_toma: this.getHoraActual(),
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
  //reset del fom
  resetForm() {
    this.formNotasEnfermeria.patchValue({
      fecha_toma: '',
      hora_toma: '',
      temperatura: '',
      tension_arterial_sistolica: '',
      tension_arterial_diastolica: '',
      frecuencia_cardiaca: '',
      frecuencia_respiratoria: '',
      peso: '',
      talla: '',
      indice_masa_corporal: '',
      glucemia_capilar: '',
      ingesta_alimentos: '',
      tipo_deposicion: '',
      nauseas: '',
      prurito: '',
      observaciones: '',
      balance_hidrico_entrada_alimentos: '',
      balance_hidrico_entrada_liquidos: '',
      balance_hidrico_fluidoterapia: '',
      balance_hidrico_hemoderivados: '',
      balance_hidrico_otros_entrada: '',
      balance_hidrico_diuresis: '',
      balance_hidrico_vomitos: '',
      balance_hidrico_heces: '',
      balance_hidrico_sonda_nasogastrica: '',
      balance_hidrico_drenajes: '',
      balance_hidrico_otras_perdidas: '',
      total_balance_hidrico: '',
    });
  }
}
