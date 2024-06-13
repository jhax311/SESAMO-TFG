import {
  Component,
  EventEmitter,
  Input,
  OnInit,
  Output,
  inject,
  output,
} from '@angular/core';
import { LocalSService } from '../../servicios/local-s.service';
import { Router } from '@angular/router';
import { ApiService } from '../../servicios/api.service';
import { ResponseI } from '../../modelos/response.interface';
import Swal from 'sweetalert2';
import {
  MatFormField,
  MatFormFieldModule,
  MatLabel,
} from '@angular/material/form-field';
import { MatOption, MatSelect } from '@angular/material/select';
import { FormBuilder, FormControl, ReactiveFormsModule } from '@angular/forms';
import { ListarCentrosI } from '../../modelos/listarCentros.interface';
import { MatAutocompleteModule } from '@angular/material/autocomplete';
import { MatInputModule } from '@angular/material/input';
import { MatCardModule } from '@angular/material/card';
import { CommonModule } from '@angular/common';
import { NotificarService } from '../../servicios/notificar.service';
import { forIn } from 'lodash';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [
    MatFormField,
    MatLabel,
    MatSelect,
    MatOption,
    MatAutocompleteModule,
    MatInputModule,
    MatCardModule,
    CommonModule,
    ReactiveFormsModule,
    MatFormFieldModule,
  ],
  templateUrl: './dashboard.component.html',
  styleUrl: './dashboard.component.css',
})
export class DashboardComponent implements OnInit {
  @Output() camaSeleccionadaEvent = new EventEmitter<any>();
  @Input() set camaActualizada(cama: any) {
    //si hya valor
    if (cama) {
      //buscamos por id, y sustituimos
      const index = this.camas.findIndex((c) => c.id_cama === cama.id_cama);
      if (index !== -1) {
        //cone sto cmabaira el color
        this.camas[index] = cama;
      }
 
      //ademas liberamos la seleccion
      this.camaSeleccionada = cama;
    } else {
      this.camaSeleccionada = null;
    }
  }
  constructor(
    private router: Router,
    private ls: LocalSService,
    private api: ApiService,
    private notificar: NotificarService
  ) { }
  private readonly _formBuilder = inject(FormBuilder);
  hospitales: ListarCentrosI[] = [];
  idHospital: any = '';
  plantas: any = [];
  camas: any[] = [];
  camaSeleccionada: any = null;
  plantaReset = new FormControl({ value: '', disabled: true });
  camasOcupadas: any = [];

  ngOnInit(): void {
    //this.api.checkLogin();
    this.api.listarCentros().subscribe((data) => {
      this.hospitales = data;
    });
  }
  selectHospital(event: any): void {
    this.plantas = [];
    this.camas = [];
    this.plantaReset.reset();
    this.idHospital = event.value;
    //sacamos las plantas
    this.api.listarPlantas(this.idHospital).subscribe((data) => {
      this.plantas = this.crearPlantas(data.plantas);
      this.plantaReset.enable();
    });
  }
  selectPlanta(event: any): void {
    //cargamos las camas
    this.api
      .listarCamasCentro(this.idHospital, event.value)
      .subscribe((data) => {
        this.camas = data;
        //guardamos las cams ocupadas
        this.camas.forEach(cama => {
          if (cama.estado === 'Ocupada') {
            this.api.pacienteCama(cama.id_cama).subscribe((paciente) => {
              cama.paciente = paciente;
    
            });
         
          }
        });
      });



  }

  //pras elecionar la cama
  camaSelecionada(cama: any): void {
    //enviamos la cama al padre para egstionar el click del bootn
    this.camaSeleccionada = cama;
    this.camaSeleccionadaEvent.emit(cama);
  }

  //cear el array con las plantas
  crearPlantas(num: number): number[] {
    const plantas = [];
    for (let i = 1; i <= num; i++) {
      plantas.push(i);
    }
    return plantas;
  }
  deseleccionarCama() {
    this.camaSeleccionada = null;
  }
  //funcion quea ctualiza el estado de la cama, viene del padre
}
