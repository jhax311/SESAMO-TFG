import { AbstractControl, ValidationErrors, ValidatorFn } from '@angular/forms';
//validacion para que no metan campos en blanco u esapcios
export function sinEspacios(): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    //haremos un trim del valor, si esa longitud es 0  sera un cmapo invalido
    const esEspacio = (control.value || '').trim().length === 0;
    const isValid = !esEspacio;
    return isValid ? null : { esEspacio: true };
  };
}
export const sinEspacios2 = (
  control: AbstractControl
): ValidationErrors | null => {
  //cogemos el valor
  const esEspacio = (control.value || '').trim().length === 0;
  const isValid = !esEspacio;
  //verificamos si coincidde la contraseña insertad con nuestro match
  if (isValid) {
    return { esEspacio: true };
  }
  return null;
};
//para verificar contraseñas seguras de 8 caracteres minimo, mayus min y carcter especial
//?= se usa para que cumpla eso, si cumple sigue evaluando
//*\\d  tenga al menos un digito
//?=.*[a-z]  tenga una minisucla
//       una mayusucla
//\\w que contenga un caracter _ letra etc

const password = new RegExp(
  '(?=.*\\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.*\\w).{8,}'
);

export const validarPassword = (
  control: AbstractControl
): ValidationErrors | null => {
  //cogemos el valor
  const valor = control.value;
  //verificamos si coincidde la contraseña insertad con nuestro match
  if (!password.test(valor)) {
    return { validarPassword: true };
  }
  return null;
};

//para los correos, que sea algo@algo.tld
const valCorreo = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
export const validarCorreo = (
  control: AbstractControl
): ValidationErrors | null => {
  //cogemos el valor
  const valor = control.value;
  //verificamos si coincidde el match del correo
  if (!valCorreo.test(valor)) {
    return { validarEmail: true };
  }
  return null;
};

//para teelfonos
const movil = /^[0-9]{9}$/;

export const validarMovil = (
  control: AbstractControl
): ValidationErrors | null => {
  const valor = control.value;
  // Verificar si el valor coincide con el patrón de móvil
  if (!movil.test(valor)) {
    return { validarMovil: true };
  }
  return null;
};

//para el dni u nif

const validarDniNie = /^(?:[XYZxyz]?\d{7,8}[A-Za-z])$/;
//xyz puede apaecer o no un avez para nie  7 u 8 digitos segun , y una letra de la a a la z
//sepuede validar la letra, ene ste caso no lo haremos, para no ser tan rigurosos
export const validarDni = (
  control: AbstractControl
): ValidationErrors | null => {
  const valor = control.value;
  // Verificar si el valor coincide con el patrón de móvil
  if (!validarDniNie.test(valor)) {
    return { validarDni: true };
  }
  return null;
};
