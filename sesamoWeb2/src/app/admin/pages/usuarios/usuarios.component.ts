import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { MatTableModule, MatTableDataSource } from '@angular/material/table';
import { ApiService } from '../../../servicios/api.service';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { MatFormField, MatInputModule } from '@angular/material/input';
import { MatDialog, MatDialogModule } from '@angular/material/dialog';
import { MatButtonModule } from '@angular/material/button';
import {
  FormBuilder,
  FormGroup,
  ReactiveFormsModule,
  RequiredValidator,
  Validators,
} from '@angular/forms';
import { MatOption, MatOptionModule } from '@angular/material/core';
import { MatFormFieldControl } from '@angular/material/form-field';
import { MatSelect } from '@angular/material/select';
import { MatIcon } from '@angular/material/icon';
import { NotificarService } from '../../../servicios/notificar.service';
import {
  sinEspacios,
  sinEspacios2,
} from '../../../validators/validaciones.validator';
import Swal from 'sweetalert2';
import { ResponseI } from '../../../modelos/response.interface';
declare var $: any;

@Component({
  selector: 'app-usuarios',
  standalone: true,
  imports: [
    CommonModule,
    MatTableModule,
    HttpClientModule,
    MatInputModule,
    MatFormField,
    MatDialogModule,
    ReactiveFormsModule,
    MatOptionModule,
    MatFormField,
    MatSelect,
    MatIcon,
  ],
  templateUrl: './usuarios.component.html',
  styleUrls: ['./usuarios.component.css'],
})
export class UsuariosComponent implements OnInit {
  @ViewChild('editUserModal') editUserModal!: ElementRef;
  @ViewChild('customFade') customFade!: ElementRef;
  @ViewChild('addUserModal') addUserModal!: ElementRef;

  nombreColumnas: string[] = [
    'id_usuario',
    'username',
    'nombre',
    'apellidos',
    'email',
    'id_rol',
    'nombre_centro',
    'actions',
  ];

  usuarios = new MatTableDataSource<any>();
  formModificar: FormGroup;
  formAgregar: FormGroup;
  usuarioElejido: any;
  centros: any;
  roles: any;

  constructor(
    private api: ApiService,
    private fb: FormBuilder,
    private notificar: NotificarService
  ) {
    this.formModificar = this.fb.group({
      id_usuario: ['', [Validators.required]],
      userName: ['', [Validators.required, sinEspacios()]],
      nombre: ['', [Validators.required, sinEspacios()]],
      apellidos: ['', [Validators.required, sinEspacios()]],
      email: ['', [Validators.required, sinEspacios(), Validators.email]],
      id_rol: ['', [Validators.required]],
      id_centro: ['', [Validators.required]],
      password: [''],
    });
    this.formAgregar = this.fb.group({
      usuario: ['', [Validators.required, sinEspacios()]],
      nombre: ['', [Validators.required, sinEspacios()]],
      apellidos: ['', [Validators.required, sinEspacios()]],
      email: ['', [Validators.required, sinEspacios(), Validators.email]],
      id_rol: ['', [Validators.required]],
      id_centro: ['', [Validators.required]],
      password: ['', [Validators.required]],
    });
  }

  ngOnInit() {
    //al inciar llamamos al afucnion, par agaurdar los usuarios
    this.listarUsuarios();
    this.api.listarPerfiles().subscribe((response) => {
      this.roles = response;
    });
    this.api.listarCentros().subscribe((response) => {
      this.centros = response;
    });
  }

  listarUsuarios() {
    this.api.listarUsuarios().subscribe(
      (usuarios: any[]) =>
        //guardmaos los usuarios
        (this.usuarios.data = usuarios),
      (error) =>
        //si no error
        console.error('Error al obtener usuarios', error)
    );
  }
  //para filtarr por datos
  aplicarFiltro(event: Event) {
    //aqui vamos a capturar cada evento de tecl pulsada
    const filterValue = (event.target as HTMLInputElement).value;
    this.usuarios.filter = filterValue.trim().toLowerCase();
  }

  //fomulario para editar usuario
  addUser() {
    // Crear y mostrar backdrop manualmente
    // Mostrar el fade personalizado
    $(this.customFade.nativeElement).fadeIn();
    //quitamso el backdrop
    $(this.addUserModal.nativeElement).css('z-index', 1050).modal({
      backdrop: false,
    });
    //mostramos el modal
    $(this.addUserModal.nativeElement).css('z-index', 1050).modal('show');

    // cuando se acabe la vida del modal se quita el fade
    $(this.addUserModal.nativeElement).on('hidden.bs.modal', () => {
      $(this.customFade.nativeElement).fadeOut();
    });
    // Cierra el modal
    //$(this.editUserModal.nativeElement).modal('hide');
  }
  agregarUsuario(form: any) {

    if (this.formAgregar.valid) {
      this.api.registrar(form).subscribe((respuesta: ResponseI) => {
        //como congifuramos result como string en la inetrfaz, aqui debemos parsearlo, por nuestro
        //tipo de respuesta
        const result =
          typeof respuesta.result === 'string'
            ? JSON.parse(respuesta.result)
            : respuesta.result;
        if (respuesta.status === 'ok') {
          this.listarUsuarios();
          $(this.addUserModal.nativeElement).on('hidden.bs.modal', () => {
            $(this.customFade.nativeElement).fadeOut();
          });
          $(this.addUserModal.nativeElement).modal('hide');
          this.notificar.notificar('success', result.mensaje);
        } else {
          this.notificar.notificar('error', result.error_msg);
        }
      });
    }
  }

  editUser(user: any) {
    this.usuarioElejido = user;
    this.formModificar.setValue({
      id_usuario: user.id_usuario,
      userName: user.userName,
      nombre: user.nombre,
      apellidos: user.apellidos,
      email: user.email,
      id_rol: user.id_rol,
      id_centro: user.id_centro,
      password: '',
    });

    // Crear y mostrar backdrop manualmente
    // Mostrar el fade personalizado
    $(this.customFade.nativeElement).fadeIn();
    //quitamso el backdrop
    $(this.editUserModal.nativeElement).css('z-index', 1050).modal({
      backdrop: false,
    });
    //mostramos el modal
    $(this.editUserModal.nativeElement).css('z-index', 1050).modal('show');

    // cuando se acabe la vida del modal se quita el fade
    $(this.editUserModal.nativeElement).on('hidden.bs.modal', () => {
      $(this.customFade.nativeElement).fadeOut();
    });
    // Cierra el modal
    //$(this.editUserModal.nativeElement).modal('hide');
  }

  modificar(form: any) {
    //validamos el formulario
    if (this.formModificar.valid) {
      this.api.modificarUsuario(form).subscribe(
        (response: any) => {
          // si fue exitosa
          if (response.status === 'ok') {
            this.listarUsuarios();
            this.notificar.notificar(
              'success',
              'Usuario modificado correctamente.'
            );

            $(this.editUserModal.nativeElement).modal('hide');
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
      this.notificar.notificar('error', 'Inserte los campos requerdios.');
    }
  }

  deleteUser(user: any) {
    Swal.fire({
      title: '¿Estás seguro de eliminar al usuario ' + user.userName + '?',
      text: '¡No podrás revertir esto!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        if (user.id_rol == 7) {
          Swal.fire({
            title: '¡Estas a punto de eliminar un usuario administrador!',
            text: '¡No podrás revertir esto!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
          }).then((resultado) => {
            if (resultado.isConfirmed) {
              let form: any = { usuario: user.userName };
              this.api.eliminarUsuario(form).subscribe({
                next: (response: ResponseI) => {
                  const result =
                    typeof response.result === 'string'
                      ? JSON.parse(response.result)
                      : response.result;

                  if (response.status === 'ok') {
                    Swal.fire({
                      title: '¡Eliminado!',
                      text: 'Usuario eliminado',
                      icon: 'success',
                    });
                    this.listarUsuarios();
                    this.notificar.notificar(
                      'success',
                      'Usuario eliminado con éxito.'
                    );
                  } else {
                    this.notificar.notificar('error', result.error_msg);
                  }
                },
                error: (error) => {
                  console.error('Error al eliminar al usuario:', error);
                },
              });
            }
          });
        } else {
          let form: any = { usuario: user.userName };
          this.api.eliminarUsuario(form).subscribe({
            next: (response: ResponseI) => {
              if (response.status === 'ok') {
                Swal.fire({
                  title: '¡Eliminado!',
                  text: 'Usuario eliminado',
                  icon: 'success',
                });
                this.listarUsuarios();
                this.notificar.notificar(
                  'success',
                  'Usuario eliminado con éxito.'
                );
              } else {
                this.notificar.notificar('error', response.result);
              }
            },
            error: (error) => {
              console.error('Error al eliminar al usuario:', error);
            },
          });
        }
      }
    });
  }
}
