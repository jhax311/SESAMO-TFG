import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { ApiService } from '../servicios/api.service';
import { map, take } from 'rxjs/operators';

//funcion authguard para proteger rutas en angular, la usaremos para que no se acceda sin inciar sesion.
export const authGuard: CanActivateFn = (route, state) => {
  //debemos insertar las dependencias, ene ste caso la de routes y el servicio api
  const apiService = inject(ApiService);
  const router = inject(Router);
  //rolesperado para la srryta
  const rolEsperado = route.data['rolEsperado'];

  // llamamos a checklogin que nos devuelve un observable booleano, true si esta auteticado u false en caso de que no
  //tmb comprueba que se inserte un token manuelamente.
  //Observable es un objeto que representa una colección de futuros valores o eventos,  datos asincrónicos
  //.pipe() permite encadenar múltiples operadores para transformar, filtrar o combinar los valores emitidos por el Observable
  return apiService.checkLogin().pipe(
    take(1), // Toma el primer valor y lo filtra
    map(isLoggedIn => {
      if (isLoggedIn) {
        const userRole = apiService.getRol();
        if ((userRole == '7' && rolEsperado == '7') || (userRole != '7' && rolEsperado == 'user')) {
          return true;
        } else {
          // Redirigir según el rol del usuario
          if (userRole =='7') {
            return router.createUrlTree(['/admin/dashboard']);
          } else {
            return router.createUrlTree(['/user/dashboard']);
          }
        }
      } else {
        return router.createUrlTree(['/login']);
      }
    })
  );
};

