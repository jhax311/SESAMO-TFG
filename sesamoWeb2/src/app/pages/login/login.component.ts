import { HttpClient, HttpClientModule } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import {
  FormBuilder,
  FormControl,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { LocalSService } from '../../servicios/local-s.service';
import { ApiService } from '../../servicios/api.service';
import { LoginI } from '../../modelos/login.interface';
import { ResponseI } from '../../modelos/response.interface';
import { ToastrService } from 'ngx-toastr';
import Swal from 'sweetalert2';
import { map, take } from 'rxjs/operators';
import { RegistroComponent } from '../registro/registro.component';
import { ListarCentrosI } from '../../modelos/listarCentros.interface';
import { MatInputModule } from '@angular/material/input';
import { MatIconModule } from '@angular/material/icon';
import { MatSelectModule } from '@angular/material/select';
import {
  sinEspacios,
  validarPassword,
} from '../../validators/validaciones.validator';
import { NotificarService } from '../../servicios/notificar.service';
declare var $: any;
import { ViewChild, ElementRef } from '@angular/core';
@Component({
  selector: 'app-login',
  standalone: true,
  imports: [
    MatSelectModule,
    MatInputModule,
    MatIconModule,
    FormsModule,
    HttpClientModule,
    ReactiveFormsModule,
    CommonModule,
    RegistroComponent,
  ],
  templateUrl: './login.component.html',
  styleUrl: './login.component.css',
})
export class LoginComponent implements OnInit {
  //elemento de cerra el boton
  hide = true;
  clickEvent(event: MouseEvent) {
    this.hide = !this.hide;
    event.stopPropagation();
  }
  @ViewChild('closeButton') closeButton!: ElementRef;
  @ViewChild('closeButtonRC') rcBtn!: ElementRef;
  @ViewChild('codeModal') codeModal!: ElementRef;
  @ViewChild(' closeButtonMc') mcBtn!: ElementRef;
  @ViewChild(' closeButtonNC') ncBtn!: ElementRef;
  @ViewChild(' nuevaPasswordModal') nuevaPasswordModal!: ElementRef;
  loginForm: FormGroup;
  errorEstado: boolean = false;
  errorMensaje: string = '';
  resetPasswordForm: FormGroup;
  codigoRecuperacionForm: FormGroup;
  nuevaContrasenaForm: FormGroup;
  ocultarPass = true;
  ocultarPassCon = true;
  private nombreUser = null;
  private codigoUser = null;
  //su construtor

  //creammos un grupo de fomualrio con sus elementos, seran para controlar que son rwqueridos

  //creamos un objeto de tipo login
  ngOnInit(): void {
    //esto se ejeucta la incio, comprobara que no haya una sesion valida, si la hay nos lleva al dashboard
    this.api.checkLogin().pipe(
      take(1), // toma el primer valor y lo filtra
      map((isLoggedIn) => {
        if (isLoggedIn) {
          const rol = this.ls.obtener('rol');
          if (rol == '7') {
            this.router.navigate(['/admin/dashboard']);
          } else {
            this.router.navigate(['/user/dashboard']);
          }
        }
      })
    ).subscribe();
  }

  constructor(
    private http: HttpClient,
    private router: Router,
    private ls: LocalSService,
    private api: ApiService,
    private notificar: NotificarService,
    private fb: FormBuilder
  ) {
    this.loginForm = new FormGroup({
      usuario: new FormControl('', [Validators.required, sinEspacios()]),
      password: new FormControl('', [Validators.required, sinEspacios()]),
    });
    this.resetPasswordForm = this.fb.group({
      usuario: ['', [Validators.required]],
    });
    this.codigoRecuperacionForm = this.fb.group({
      codigo: ['', Validators.required],
      usuario: ['', Validators.required],
    });
    this.nuevaContrasenaForm = this.fb.group(
      {
        usuario: ['', Validators.required],
        codigo: ['', Validators.required],
        nuevaContrasena: ['', Validators.required],
        confirmarContrasena: ['', Validators.required],
      },
      { validator: this.passwordIguales }
    );

    //para que sena iguales
    this.nuevaContrasenaForm
      .get('confirmarContrasena')
      ?.valueChanges.subscribe(() => {
        this.nuevaContrasenaForm.updateValueAndValidity();
      });

    this.nuevaContrasenaForm
      .get('nuevaContrasena')
      ?.valueChanges.subscribe(() => {
        this.nuevaContrasenaForm.updateValueAndValidity();
      });
  }

  cerrarModal() {
    if (this.closeButton) {
      this.closeButton.nativeElement.click();
    }
  }

  //funcion de inicio de sesion
  iniciarSesion(form: LoginI) {
    this.api.login(form).subscribe((respuesta: ResponseI) => {
      const result =
        typeof respuesta.result === 'string'
          ? JSON.parse(respuesta.result)
          : respuesta.result;
  
      if (respuesta.status === 'ok') {
        const token = result.token;
        const rol = result.rol;
        this.ls.guardar('token', token);
        this.ls.guardar('rol', rol);
  
        if (rol === 7) {
          this.router.navigate(['/admin/dashboard']);
        } else {
          this.router.navigate(['/user/dashboard']);
        }
  
        this.notificar.notificar('success', 'Has iniciado sesión correctamente.');
      } else {
        this.errorEstado = true;
        this.errorMensaje = result.error_msg;
        this.notificar.notificar('error', this.errorMensaje);
        this.loginForm.reset();
      }
    });
  }
  

  recuperarContrasena(form: any) {
    if (this.resetPasswordForm.valid) {
      this.api.recuperarPassword(form).subscribe({
        next: (response: any) => {
          //guardmao jnombre de user
          this.nombreUser = form.usuario;
          //seetmao el valor
          this.codigoRecuperacionForm.get('usuario')?.setValue(form.usuario);
          this.nuevaContrasenaForm.get('usuario')?.setValue(form.usuario);

          this.rcBtn.nativeElement.click();
          $(this.codeModal.nativeElement).modal('show');
        },
        error: (error: any) => {
          
          this.notificar.notificar("error",'Error al enviar el código de recuperación.');
        },
      });
    }
  }
  verificarCodigo(form: any) {
    //si form es valido, verifcamos el codigo
    if (this.codigoRecuperacionForm.valid) {
      this.api.verificarCodigo(form).subscribe(
        (response) => {
          if (response.status == 'ok') {
            this.notificar.notificar('success', 'Código correcto.');
            //setemos el codigo en el formulario de contarseña
            this.nuevaContrasenaForm.get('codigo')?.setValue(form.codigo);
            //cerramos y abrimos nuevo modal
            this.mcBtn.nativeElement.click();
            $(this.nuevaPasswordModal.nativeElement).modal('show');
          } else {
            this.notificar.notificar('error', 'Código incorrecto.');
          }
        },
        (error) => {
          this.notificar.notificar("error",'Error al verificar el código.');
        }
      );
    }
  }
  cambiarContrasena() {
    if (this.nuevaContrasenaForm.valid) {
      const nuevaC = this.nuevaContrasenaForm.get('nuevaContrasena')?.value;
      const confirmarC = this.nuevaContrasenaForm.get(
        'confirmarContrasena'
      )?.value;
      if (nuevaC === confirmarC) {
        this.api
          .cambiarPassword({
            nuevaContrasena: nuevaC,
            usuario: this.nuevaContrasenaForm.get('usuario')?.value,
            codigo: this.nuevaContrasenaForm.get('codigo')?.value,
          })
          .subscribe(
            (response: any) => {
              if (response.status === 'ok') {
                this.notificar.notificar(
                  'success',
                  'Contraseña actualizada correctamente.'
                );
                this.ncBtn.nativeElement.click();

              } else {
                this.notificar.notificar(
                  'error',
                  'Error al cambiar la contraseña. Por favor, comuníquese con el administrador o inténtelo de nuevo.'
                );
                this.ncBtn.nativeElement.click();
              }
            },
            (error: any) => {
              this.notificar.notificar(
                'error',
                'Error al cambiar la contraseña. Por favor, comuníquese con el administrador o inténtelo de nuevo.'
              );
              this.ncBtn.nativeElement.click();
            }
          );
      } else {
        this.notificar.notificar('error', 'Las contraseñas no coinciden.');
      }
    }
  }

  //validar que sena iguales
  passwordIguales(form: FormGroup) {
    const password = form.get('nuevaContrasena')?.value;
    const confirmPassword = form.get('confirmarContrasena')?.value;
    return password === confirmPassword ? null : { passwordMismatch: true };
  }
}
