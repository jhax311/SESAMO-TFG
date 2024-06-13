import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AlertaPacienteComponent } from './alerta-paciente.component';

describe('AlertaPacienteComponent', () => {
  let component: AlertaPacienteComponent;
  let fixture: ComponentFixture<AlertaPacienteComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AlertaPacienteComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(AlertaPacienteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
