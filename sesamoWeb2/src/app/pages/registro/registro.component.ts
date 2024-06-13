import { Component, EventEmitter, OnInit, Output, inject } from '@angular/core';
import {
  FormBuilder,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { CommonModule } from '@angular/common';
import { ApiService } from '../../servicios/api.service';
import { ListarCentrosI } from '../../modelos/listarCentros.interface';
import { MatAutocompleteModule } from '@angular/material/autocomplete';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatIconModule } from '@angular/material/icon';
import { MatSelectModule } from '@angular/material/select';
import {
  sinEspacios,
  validarCorreo,
  validarPassword,
} from '../../validators/validaciones.validator';
import { RegistroI } from '../../modelos/registro.interface';
import { ResponseI } from '../../modelos/response.interface';
import { NotificarService } from '../../servicios/notificar.service';
import { LocalSService } from '../../servicios/local-s.service';
import { Router } from '@angular/router';
import { ListarPerfilesI } from '../../modelos/listarPerfiles.interface';

@Component({
  selector: 'app-registro',
  standalone: true,
  imports: [
    MatSelectModule,
    MatInputModule,
    MatIconModule,
    ReactiveFormsModule,
    CommonModule,
    MatAutocompleteModule,
    MatFormFieldModule,
    MatInputModule,
    RegistroComponent,
  ],
  templateUrl: './registro.component.html',
  styleUrl: './registro.component.css',
})
export class RegistroComponent implements OnInit {
  @Output() cerrarModal = new EventEmitter<void>();

  constructor(
    private api: ApiService,
    private notificar: NotificarService
  ) {}
  //para formularios reactivos
  private readonly _formBuilder = inject(FormBuilder);
  centros: ListarCentrosI[] = [];
  perfiles:ListarPerfilesI[]=[];
  hide=true;
  clickEvent(event: MouseEvent) {
    this.hide = !this.hide;
    event.stopPropagation();
  }
  //nonNullable, hace que sean obligatorios todos los campos
  registroDos = this._formBuilder.nonNullable.group({
    usuario: ['', [Validators.required, sinEspacios()]],
    password: ['', [Validators.required, validarPassword, sinEspacios()]],
    nombre: ['', [Validators.required, sinEspacios()]],
    apellidos: ['', [Validators.required, sinEspacios()]],
    email: ['', [Validators.required, validarCorreo]],
    centro: ['', [Validators.required]],
    perfil: ['', [Validators.required]],
  });

  ngOnInit(): void {
    //nada mas inicarse el componente, debemos llmar a la funcion de listar centros
    this.api.listarCentros().subscribe((data) => {
      this.centros = data;
    });
    this.api.listarPerfiles().subscribe((data) => {
      this.perfiles = data;
    });
    

  }
  registrarse() {
    //cogemos los datos del formualrio
    if (this.registroDos.valid) {
      const form: RegistroI = {
        //el simbolo ! es como decirle que confie qu es evalor no ser anulo, en este caso lo sabemos por las validaciones anteriores
        usuario: this.registroDos.get('usuario')!.value,
        password: this.registroDos.get('password')!.value,
        nombre: this.registroDos.get('nombre')!.value,
        apellidos: this.registroDos.get('apellidos')!.value,
        email: this.registroDos.get('email')!.value,
        centro: this.registroDos.get('centro')!.value,
        perfil: this.registroDos.get('perfil')!.value,
      };

      this.api.registrar(form).subscribe((respuesta: ResponseI) => {
        //como congifuramos result como string en la inetrfaz, aqui debemos parsearlo, por nuestro
        //tipo de respuesta
        const result =
          typeof respuesta.result === 'string'
            ? JSON.parse(respuesta.result)
            : respuesta.result;
        if (respuesta.status === 'ok') {
          //guardamos
          //   this.dialogRef.close();
          //  this.modalService. cerrarModal();
          
          this.notificar.notificar('success', result.mensaje);
          this.cerrarModal.emit();
        } else {
          this.notificar.notificar('error', result.error_msg);
        }
      });
    } else {
    }
  }

  hola(){
    this.cerrarModal.emit();
  }

  //geters para controld e errores o coge rvalores
  get usuario() {
    return this.registroDos.get('usuario');
  }
  get password() {
    return this.registroDos.get('password');
  }
  get email() {
    return this.registroDos.get('email');
  }
  get nombre() {
    return this.registroDos.get('nombre');
  }
  get apellidos() {
    return this.registroDos.get('apellidos');
  }
  get centro() {
    return this.registroDos.get('centro');
  }
}
//this.formgrup.email-haserrro("required")

//https://stackoverflow.com/questions/48352238/why-mat-select-not-working-inside-the-modal-onclick-it-shows-options-behind-th
