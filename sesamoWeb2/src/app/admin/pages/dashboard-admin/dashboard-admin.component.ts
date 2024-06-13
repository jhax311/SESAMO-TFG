import {
  CUSTOM_ELEMENTS_SCHEMA,
  Component,
  NO_ERRORS_SCHEMA,
  inject,
} from '@angular/core';
import { Breakpoints, BreakpointObserver } from '@angular/cdk/layout';
import { map } from 'rxjs/operators';
import { AsyncPipe, CommonModule } from '@angular/common';
import { MatGridListModule } from '@angular/material/grid-list';
import { MatMenuModule } from '@angular/material/menu';
import { MatIconModule } from '@angular/material/icon';
import { MatButtonModule } from '@angular/material/button';
import { MatCardModule } from '@angular/material/card';
import { ApiService } from '../../../servicios/api.service';
import { NgApexchartsModule } from 'ng-apexcharts';

export type ChartOptions = {
  series: ApexAxisChartSeries;
  chart: ApexChart;
  xaxis: ApexXAxis;
  dataLabels: ApexDataLabels;
  title: ApexTitleSubtitle;
};
//chart
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

import {
  ApexAxisChartSeries,
  ApexChart,
  ApexXAxis,
  ApexDataLabels,
  ApexTitleSubtitle,
  ChartComponent,
} from 'ng-apexcharts';

@Component({
  selector: 'app-dashboard-admin',
  templateUrl: './dashboard-admin.component.html',
  styleUrl: './dashboard-admin.component.css',
  standalone: true,
  imports: [
    AsyncPipe,
    MatGridListModule,
    MatMenuModule,
    MatIconModule,
    MatButtonModule,
    MatCardModule,
    CommonModule,
    NgApexchartsModule,
  ],
  schemas: [CUSTOM_ELEMENTS_SCHEMA, NO_ERRORS_SCHEMA],
})
export class DashboardAdminComponent {
  public chartOptions: Partial<ChartOptions> | any;
  public vDiaG: Partial<ChartOptions> | any;
  public vMesG: Partial<ChartOptions> | any;
  public vAnioG: Partial<ChartOptions> | any;
  private breakpointObserver = inject(BreakpointObserver);
  private visitasService = inject(ApiService);
  private mesAnio: string[] = [
    'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
    'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'
  ];
  visitasPorDia: any[] = [];
  visitasPorMes: any[] = [];
  visitasPorAno: any[] = [];
  totalVisitas: number = 0;
  visitasPorPagina: any[] = [];


  cards = this.breakpointObserver.observe(Breakpoints.Handset).pipe(
    map(({ matches }) => {
      if (matches) {
        return [
          {
            title: 'Visitas por Página',
            cols: 6,
            rows: 1,
            data: this.visitasPorPagina,
          },
          {
            title: 'Total de Visitas',
            cols: 6,
            rows: 1,
            data: this.totalVisitas,
          },
          {
            title: 'Visitas por Día',
            cols: 6,
            rows: 1,
            data: this.visitasPorDia,
          },
          {
            title: 'Visitas por Mes',
            cols: 6,
            rows: 1,
            data: this.visitasPorMes,
          },
          {
            title: 'Visitas por Año',
            cols: 6,
            rows: 1,
            data: this.visitasPorAno,
          }
        ];
      }

      return [
        {
          title: 'Visitas por Página',
          cols: 5,
          rows: 1,
          data: this.visitasPorPagina,
        },
        {
          title: 'Total de Visitas',
          cols: 1,
          rows: 1,
          data: this.totalVisitas,
        },
        {
          title: 'Visitas por Día',
          cols: 2,
          rows: 1,
          data: this.visitasPorDia,
        },
        {
          title: 'Visitas por Mes',
          cols: 2,
          rows: 1,
          data: this.visitasPorMes,
        },
        {
          title: 'Visitas por Año',
          cols: 2,
          rows: 1,
          data: this.visitasPorAno,
        }
      ];
    })
  );


  ngOnInit(): void {
    this.cargarVisitasPorDia();
    this.cargarVisitasPorMes();
    this.cargarVisitasPorAno();
    this.cargarTotalVisitas();
    this.cargarVisitasPorPagina();
  }

  cargarVisitasPorDia(): void {
    let labeldata: any[] = [];
    let realdata: any[] = [];
    this.visitasService.listarVisitasPorDia().subscribe((data) => {
      this.visitasPorDia = data;
      if (this.visitasPorDia != null) {
        this.visitasPorDia.map((v) => {
          //vamos a decontruir la fecha
          const date = new Date(v.fecha);
          labeldata.push(`${date.getDate()}-${date.getMonth() + 1}`)
          realdata.push(v.visitas)
        });

        //cargamos el chart

        this.vDiaG = {
          series: [
            {
              name: 'Visitas',
              data: realdata,
            },
          ],
          chart: {
            type: 'line',
            height: 300,
          },
          xaxis: {
            categories: labeldata,
          },
          dataLabels: {
            enabled: true,
          }
        };
      }
    });

  }

  cargarVisitasPorMes(): void {
    let labeldata: any[] = [];
    let realdata: any[] = [];
    this.visitasService.listarVisitasPorMes().subscribe((data) => {
      this.visitasPorMes = data;
      if (this.visitasPorMes != null) {
        this.visitasPorMes.map((v) => {
          //vamos a decontruir la fech
          let [anio, mes] = v.mes.split("-")
          mes = parseInt(mes, 10)
          labeldata.push(this.mesAnio[mes - 1])
          realdata.push(parseInt(v.visitas, 10))
        });
        this.vMesG = {
          series: realdata,
          chart: {
            type: 'donut',
            height: 300,
          },
          xaxis: {
            categories: labeldata,
          },
          dataLabels: {
            enabled: true,
          },
        };
      }
    });
    
  }

  cargarVisitasPorAno(): void {
    let labeldata: any[] = [];
    let realdata: any[] = [];
    this.visitasService.listarVisitasPorAno().subscribe((data) => {
      this.visitasPorAno = data;
      if (this.visitasPorAno != null) {
        this.visitasPorAno.map((v) => {
          labeldata.push(v.ano)
          realdata.push(v.visitas)
        });
        this.vAnioG = {
          series: [
            {
              type: 'area',
              data: realdata,
            },
          ],
          chart: {
            type: 'bar',
            height: 300,
          },
          xaxis: {
            categories: labeldata,
          },
          dataLabels: {
            enabled: true,
          }
        };
      }
    });
  
  }

  cargarTotalVisitas(): void {
    this.visitasService.listarTotalVisitas().subscribe((data) => {
      this.totalVisitas = data.total_visitas;
    });
  }

  cargarVisitasPorPagina(): void {
    let labeldata: any[] = [];
    let realdata: any[] = [];
    this.visitasService.listarVisitasPorPagina().subscribe((data) => {
      this.visitasPorPagina = data;
      if (this.visitasPorPagina != null) {
        this.visitasPorPagina.map((v) => {
          labeldata.push(v.pagina);
          realdata.push(v.visitas);
        });
        this.chartOptions = {
          series: [
            {
              name: 'Visitas',
              data: realdata,
            },
          ],
          chart: {
            type: 'bar',
            height: 300,
          }, plotOptions: {
            bar: {
              borderRadius: 10,
              dataLabels: {
                position: 'top', // top, center, bottom
              },
            }
          },
          xaxis: {
            categories: labeldata,
    
          },
          dataLabels: {
            enabled: true,
          }
        };
      }
    });

  
  }

  constructor() { }
}
