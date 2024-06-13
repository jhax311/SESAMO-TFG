import {
  Component,
  EventEmitter,
  OnInit,
  Output,
  inject,
  ViewChild,
  ElementRef,
} from '@angular/core';
import { RouterOutlet } from '@angular/router';
declare var $: any;
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
} from '../../../validators/validaciones.validator';
import { CommonModule } from '@angular/common';
import { ListarAreasI } from '../../../modelos/listarAreas.interface';
import { ListarZonasI } from '../../../modelos/listarZonas.interface';
import { ApiService } from '../../../servicios/api.service';
import { NotificarService } from '../../../servicios/notificar.service';
import { ListarPacientesI } from '../../../modelos/listarPacientes.interface';
import { ResponseI } from '../../../modelos/response.interface';

@Component({
  selector: 'app-buscador',
  standalone: true,
  imports: [
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
    ReactiveFormsModule,
    CommonModule,
    BuscadorComponent,
  ],
  templateUrl: './buscador.component.html',
  styleUrl: './buscador.component.css',
})
export class BuscadorComponent implements OnInit {
  //para los form reactivos
  private readonly _formBuilder = inject(FormBuilder);
  //para abrir els egundo modal
  @ViewChild('seleccionarPacienteModal') seleccionarPacienteModal!: ElementRef;
  constructor(private api: ApiService, private notificar: NotificarService) {}
  //para enviar el paciente selecioando
  @Output() pacienteSeleccionado: EventEmitter<ListarPacientesI> =
    new EventEmitter<any>();

  buscar = this._formBuilder.nonNullable.group({
    nhc: ['', []],
    nif: ['', []],
    area: ['', []],
    zona: [{ value: '', disabled: true }],
  });
  buscar2 = this._formBuilder.nonNullable.group({
    paciente: ['', []],
  });
  //las listas de areas y zonas
  areas: ListarAreasI[] = [];
  zonas: ListarZonasI[] = [];
  //para controlar que se selecione un area, si se leceiona se mhabilita las zonas
  selecionada: boolean = true;
  desactivado: boolean = true;

  listaPacientes: ListarPacientesI[] = [];

  ngOnInit(): void {
    //cargamos las areas
    this.api.listarAreas().subscribe((data) => {
      this.areas = data;
    });

    //ahora tendremos que poner un tipo de listener a cada cmaboio de las claves del fomurlario

    Object.keys(this.buscar.controls).forEach((clave) => {
      this.buscar.get(clave)?.valueChanges.subscribe((valor) => {
        //miraremos si e sun valor valido
        if (valor) {
          //si lo es desactivamos el resto, exepto la que le pasemos
          this.desactivar(clave);
          //si hay valor se marca como requerido, para que no se pued aenviar
          this.buscar.get(clave)?.setValidators([Validators.required]);
          //
          if (clave != 'area') {
            this.desactivado = false;
          } else {
            this.desactivado = true;
          }
        } else {
          //si no activamos todo
          this.activarTodo();
          //quitamos lo de requerido, todos los validarores
          this.buscar.get(clave)?.clearValidators();
          this.desactivado = true;
        }
      });
      //aqui ponemos los obligatorios, en el caso del nif etc
      this.buscar.get(clave)?.updateValueAndValidity();
    });

    //ahora debemso evaluar los cambios en los valores de areas, para hacer la bsuqueda
    this.buscar.get('area')?.valueChanges.subscribe((idArea) => {
      //si hay unaa area selcioanda, habilitamos la bsuqueda de zonas, si no, sigue desabiliatda
      if (idArea) {
        this.desactivar('zona');
        this.api.listarZonas(idArea).subscribe((data) => {
          this.zonas = data;
        });
      } else {
        this.buscar.get('zona')?.setValue('');
        this.buscar.get('zona')?.disable({ emitEvent: false });
      }
    });
  }

  //fin init
  desactivar(exepcion: string) {
    //.controls, nos da un pack clave valor,de loq eu hayamos definido en el fomulario, vamos aiterar esas cves
    //desativando los campo exepto el que le pasemos, usamos object keys para iterar solo las claves
    Object.keys(this.buscar.controls).forEach((clave) => {
      //verificamos que no se la clave que le pasamos por param
      if (exepcion == 'zona') {
        if (clave == exepcion) {
          this.buscar.get(clave)?.enable({ emitEvent: false });
        }
        if (clave != exepcion && clave != 'area') {
          this.buscar.get(clave)?.disable({ emitEvent: false });
        }
      } else {
        if (clave != exepcion) {
          this.buscar.get(clave)?.disable({ emitEvent: false });
        }
      }
    });
  }
  //ahora uan funcion que haga lo contraio, active todas, ene ste caso exeptuando la de zonas
  activarTodo() {
    // this.buscar.setValue({nhc: '',nif: '',area: '',zona: ''});
    Object.keys(this.buscar.controls).forEach((clave) => {
      //verificamos que no se la clave que le pasamos por param
      //cada vez que se actic aud esactiva el control, emite un evento, debemos decirle que no lo haga

      this.buscar.get(clave)?.enable({ emitEvent: false });
    });
    //desacticamos zona
    this.buscar.get('zona')?.disable({ emitEvent: false });
  }
  activarTodoo() {
    //desacticamos zona
    this.buscar.setValue({ nhc: '', nif: '', area: '', zona: '' });
  }

  buscars(form: FormGroup) {
    let anyValidField = false;

    Object.keys(form.controls).forEach((key) => {
      // que lvalor no sea un esapcio en blancom, o muchos de lso mismos
      const valor = (form.controls[key].value || '')
        .toString()
        .replace(/\s+/g, '');
      //si no e vacio
      if (valor !== '') {
        //comprobamos que la peticions e a zona, nhc u nif
        if (key === 'nhc' || key === 'nif' || key === 'zona') {
          //hacemos la peticion y suscribimos el resultados
          this.api.buscarPacientes(key, valor).subscribe({
            next: (response: ListarPacientesI[] | ResponseI) => {
              //si hay una repsuesta y es un aray seran los pacientes
              if (Array.isArray(response)) {
                // La respuesta es una lista de pacientes
                if (response.length === 1) {
                  // Si hay solo un paciente, enviarlo al componente padre
                  $(this.seleccionarPacienteModal.nativeElement).modal('hide');
                  this.pacienteSeleccionado.emit(response[0]);
                  this.activarTodoo();
                } else {
                  // Si hay más de un paciente, abrir el segundo modal
                  this.listaPacientes = response;
                  this.abrirSegundoModal();
                }
              } else {
                // si no es un array sera un objeto con la respuesta, en estecaso erro 200
                //debemos parsear el resultado
                const result =
                  typeof response.result === 'string'
                    ? JSON.parse(response.result)
                    : response.result;
                //notificamos
                this.notificar.notificar('error', result.error_msg);
              }
            },
            error: (error: any) => {
              this.notificar.notificar('error', 'Error al buscar pacientes');
            },
          });
        }
      }
    });
  }

  //abrir el segundomodal
  abrirSegundoModal() {
    // Abre el segundo modal utilizando jQuery
    this.buscar.get('nif')?.enable({ emitEvent: false });
    this.buscar.setValue({ nhc: '', nif: '', area: '', zona: '' });
    $(this.seleccionarPacienteModal.nativeElement).modal('show');
  }
}
