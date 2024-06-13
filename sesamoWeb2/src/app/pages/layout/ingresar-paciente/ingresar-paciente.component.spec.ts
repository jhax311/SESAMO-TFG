import { ComponentFixture, TestBed } from '@angular/core/testing';

import { IngresarPacienteComponent } from './ingresar-paciente.component';

describe('IngresarPacienteComponent', () => {
  let component: IngresarPacienteComponent;
  let fixture: ComponentFixture<IngresarPacienteComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [IngresarPacienteComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(IngresarPacienteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
